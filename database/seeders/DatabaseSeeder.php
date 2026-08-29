<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        Product::create([
            'name' => 'Samsung Galaxy S24',
            'description' => 'Flagship smartphone with high resolution dynamic AMOLED display',
            'price' => 700000.00,
        ]);

        Product::create([
            'name' => 'MacBook Air M2',
            'description' => 'Lightweight Apple laptop with M2 chip and long battery life',
            'price' => 5000000.00,
        ]);

        Product::create([
            'name' => 'Sony WH-1000XM5',
            'description' => 'Industry-leading noise canceling wireless headphones',
            'price' => 950000.00,
        ]);
    }
}
