<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();
        $product1 = Product::first(); // Get first available product
        $product2 = Product::skip(1)->first(); // Get second
        $shippingAddress = $user ? $user->addresses()->first() : null;

        if (!$user || !$product1 || !$shippingAddress) {
            $this->command->warn('User, Product, or Address not found for OrderSeeder. Skipping.');
            return;
        }

        try {
            DB::beginTransaction();

            $order = $user->orders()->create([
                'OrderDate' => now()->subDays(rand(1, 30)), // Random order date in last month
                'TotalAmount' => 0, // Will be calculated
                'Status' => Order::STATUS_COMPLETED,
                'ShippingAddressId' => $shippingAddress->Id,
                'BillingAddressId' => $shippingAddress->Id, // Use same for simplicity
            ]);

            $totalAmount = 0;
            $itemsToSave = [];

            // Item 1
            if ($product1) {
                $quantity1 = rand(1, 3);
                $unitPrice1 = $product1->Price;
                $totalAmount += ($unitPrice1 * $quantity1);
                $itemsToSave[] = new OrderItem([
                    'ProductId' => $product1->Id,
                    'Quantity' => $quantity1,
                    'UnitPrice' => $unitPrice1,
                ]);
            }

            // Item 2
            if ($product2) {
                $quantity2 = rand(1, 2);
                $unitPrice2 = $product2->Price;
                $totalAmount += ($unitPrice2 * $quantity2);
                $itemsToSave[] = new OrderItem([
                    'ProductId' => $product2->Id,
                    'Quantity' => $quantity2,
                    'UnitPrice' => $unitPrice2,
                ]);
            }

            if (!empty($itemsToSave)) {
                $order->orderItems()->saveMany($itemsToSave);
                $order->TotalAmount = $totalAmount;
                $order->save();
            }

            DB::commit();
            $this->command->info('Sample order created for user: ' . $user->email);


            if (!empty($itemsToSave)) {
                $order->orderItems()->saveMany($itemsToSave);
                $order->TotalAmount = $totalAmount;
                $order->save();
                        
                // Create a payment for the order
                $order->payment()->create([
                    'PaymentDate' => $order->OrderDate, // Or now()
                    'Amount' => $order->TotalAmount,
                    'PaymentMethod' => 'Seeded Card',
                    'TransactionId' => 'seed_txn_' . $order->Id,
                    'Status' => \App\Models\Payment::STATUS_SUCCEEDED,
                ]);
            }

DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed order: ' . $e->getMessage());
        }
    }
}