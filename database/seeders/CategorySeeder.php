<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new \App\Models\Category();

        $category->create([
            'name' => 'Category 1',
        ]);

        $category->create([
            'name' => 'Category 2',
            'parent_id' => 1
        ]);
    }
}
