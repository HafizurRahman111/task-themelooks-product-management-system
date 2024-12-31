<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = [
            1 => 'T-shirt', // ID 1
            2 => 'Shoe',    // ID 2
            3 => 'Sugar',   // ID 3
        ];

        // Product Variations
        $variations = [
            // T-shirt variations
            [
                'product_id' => 1,
                'variation_attributes' => json_encode(['color' => 'Red', 'size' => 'L']),
                'purchase_price' => 100,
                'selling_price' => 120,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 1,
                'variation_attributes' => json_encode(['color' => 'Blue', 'size' => 'XL']),
                'purchase_price' => 130,
                'selling_price' => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Shoe variations
            [
                'product_id' => 2,
                'variation_attributes' => json_encode(['size' => 38]),
                'purchase_price' => 900,
                'selling_price' => 1200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 2,
                'variation_attributes' => json_encode(['size' => 41]),
                'purchase_price' => 1800,
                'selling_price' => 2000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Sugar variations
            [
                'product_id' => 3,
                'variation_attributes' => json_encode(['weight' => '1KG']),
                'purchase_price' => 135,
                'selling_price' => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 3,
                'variation_attributes' => json_encode(['weight' => '2KG']),
                'purchase_price' => 170,
                'selling_price' => 180,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('product_variations')->insert($variations);
    }
}
