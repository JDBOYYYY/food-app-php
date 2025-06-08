<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        if ($user) {
            Address::create([
                'Street' => '123 Main St',
                'Apartment' => 'Apt 1',
                'City' => 'Anytown',
                'PostalCode' => '12345',
                'Country' => 'USA',
                'UserId' => $user->id,
            ]);
            Address::create([
                'Street' => '456 Oak Ave',
                'Apartment' => 'Suite 200',
                'City' => 'Otherville',
                'PostalCode' => '67890',
                'Country' => 'USA',
                'UserId' => $user->id,
            ]);
        } else {
            $this->command->warn('Default user for AddressSeeder not found. Skipping address seeding.');
        }
    }
}
