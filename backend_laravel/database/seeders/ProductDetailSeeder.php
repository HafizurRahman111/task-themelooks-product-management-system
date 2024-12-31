<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_details')->insert([
            [
                'name' => 'T-Shirt',
                'sku' => 'TSH123',
                'unit' => 'piece',
                'unit_value' => 5,
                // 'selling_price' => 150,
                // 'purchase_price' => 100,
                'discount' => 10, // 10% discount
                'tax' => 5, // 5% tax
                'image' => 'tshirt.png',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Shoe',
                'sku' => 'SHO456',
                'unit' => 'pair',
                'unit_value' => 3,
                // 'selling_price' => 2000,
                // 'purchase_price' => 1800,
                'discount' => 5, // 5% discount
                'tax' => 8, // 8% tax
                'image' => 'shoe.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sugar',
                'sku' => 'SUG789',
                'unit' => 'kg',
                'unit_value' => 25,
                // 'selling_price' => 150,
                // 'purchase_price' => 135,
                'discount' => 2, // 2% discount
                'tax' => 3, // 3% tax
                'image' => 'sugar.png',
                'created_at' => now(),
                'updated_at' => now(),

            ],
        ]);
    }
}
