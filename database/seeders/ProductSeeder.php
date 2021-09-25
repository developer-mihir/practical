<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Models\Product();

        $product->create([
            'name' => 'Product 1',
            'price' => 150.15,
        ]);

        $product->create([
            'name' => 'Product 2',
            'price' => 200,
        ]);

        $product->where('id', 1)->first()->categories()->attach([1, 2]);
    }
}
