<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\{User, Category, Restaurant, Product, Address, Order, OrderItem, Payment, Review};
use Carbon\Carbon;

class AllEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('key:generate');

        $this->user = User::factory()->create();
        $this->category = Category::create(['Name' => 'Category']);
        $this->restaurant = Restaurant::create([
            'Name' => 'Restaurant',
            'ImageUrl' => null,
            'AverageRating' => 4.5,
            'DeliveryTime' => '30m',
            'Distance' => '5km',
            'PriceRange' => '$$'
        ]);
        $this->restaurant->categories()->attach($this->category->Id);
        $this->product = Product::create([
            'Name' => 'Product',
            'Description' => 'Desc',
            'Price' => 9.99,
            'ImageUrl' => null,
            'RestaurantId' => $this->restaurant->Id,
            'CategoryId' => $this->category->Id
        ]);
        $this->address = Address::create([
            'Street' => 'Street 1',
            'Apartment' => '1',
            'City' => 'City',
            'PostalCode' => '12345',
            'Country' => 'Country',
            'UserId' => $this->user->id,
        ]);
    }

    public function test_public_endpoints(): void
    {
        $this->getJson('/api/ping')->assertStatus(200);
        $this->getJson('/api/categories')->assertStatus(200);
        $this->getJson('/api/categories/'.$this->category->Id)->assertStatus(200);
        $this->getJson('/api/products')->assertStatus(200);
        $this->getJson('/api/products/'.$this->product->Id)->assertStatus(200);
        $this->getJson('/api/restaurants')->assertStatus(200);
        $this->getJson('/api/restaurants/'.$this->restaurant->Id)->assertStatus(200);
        $this->getJson('/api/restaurants/'.$this->restaurant->Id.'/categories')->assertStatus(200);
        $this->getJson('/api/restaurants/'.$this->restaurant->Id.'/products')->assertStatus(200);
        $this->getJson('/api/products/'.$this->product->Id.'/reviews')->assertStatus(200);
    }

    public function test_authenticated_endpoints(): void
    {
        Sanctum::actingAs($this->user, ['*']);

        $this->getJson('/api/user')->assertStatus(200);
        $this->getJson('/api/addresses')->assertStatus(200);
        $addrShow = $this->getJson('/api/addresses/'.$this->address->Id);
        $addrShow->assertStatus(200);
        $this->postJson('/api/products/'.$this->product->Id.'/favorite')->assertStatus(201);

        $this->getJson('/api/favorites')->assertStatus(200)
            ->assertJsonCount(1, 'data');
        $this->deleteJson('/api/products/'.$this->product->Id.'/unfavorite')->assertStatus(204);
        $this->getJson('/api/favorites')->assertStatus(200)
            ->assertJsonCount(0, 'data');
      
        $orderPayload = [
            'ShippingAddressId' => $this->address->Id,
            'BillingAddressId' => $this->address->Id,
            'items' => [
                ['ProductId' => $this->product->Id, 'Quantity' => 1]
            ]
        ];
        $orderResp = $this->postJson('/api/orders', $orderPayload);
        $orderResp->assertStatus(201);
        $orderId = $orderResp->json('data.Id');
        $this->getJson('/api/orders')->assertStatus(200);
        $this->getJson('/api/orders/'.$orderId)->assertStatus(200);
        $this->putJson('/api/orders/'.$orderId, ['Status' => Order::STATUS_CANCELLED])->assertStatus(200);
        $this->deleteJson('/api/orders/'.$orderId)->assertStatus(204);
        $resp = $this->postJson('/api/addresses', [
            'Street' => 'New',
            'Apartment' => '2',
            'City' => 'Town',
            'PostalCode' => '54321',
            'Country' => 'Country',
        ]);
        $resp->assertStatus(201);
        $addrId = $resp->json('data.Id');
        $this->putJson('/api/addresses/'.$addrId, ['City' => 'NewCity'])->assertStatus(200);
        $this->deleteJson('/api/addresses/'.$addrId)->assertStatus(204);
    }
}
