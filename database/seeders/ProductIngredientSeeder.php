<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_ingredients')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'ingredient_id' => 1,
                'amount' => 150,
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'ingredient_id' => 2,
                'amount' => 30,
            ],
            [
                'id' => 3,
                'product_id' => 1,
                'ingredient_id' => 3,
                'amount' => 20,
            ],
            [
                'id' => 4,
                'product_id' => 2,
                'ingredient_id' => 1,
                'amount' => 120,
            ],
            [
                'id' => 5,
                'product_id' => 2,
                'ingredient_id' => 2,
                'amount' => 20,
            ],
            [
                'id' => 6,
                'product_id' => 2,
                'ingredient_id' => 3,
                'amount' => 10,
            ],
        ]);
    }
}
