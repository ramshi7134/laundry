<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Branch;
use App\Models\Service;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add a primary branch
        $branch = Branch::create([
            'name' => 'Main Branch',
            'address' => '123 Laundry St, Downtown',
            'phone' => '111-222-3333'
        ]);

        // Add an admin user
        User::factory()->create([
            'branch_id' => $branch->id,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '999-888-7777',
            'role' => 'admin',
        ]);

        // Add base services
        $services = [
            ['name' => 'Wash & Fold', 'price' => 15.00],
            ['name' => 'Dry Cleaning', 'price' => 25.00],
            ['name' => 'Ironing', 'price' => 8.00],
            ['name' => 'Heavy Blankets', 'price' => 30.00],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['branch_id' => $branch->id]));
        }

        // Add a demo customer
        Customer::create([
            'branch_id' => $branch->id,
            'name' => 'John Doe',
            'phone' => '123-456-7890',
            'email' => 'john@example.com',
            'address' => '456 Client Rd'
        ]);
    }
}
