<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ingredients')->insert([
            [
                'id' => 1,
                'name' => 'Beef',
                'total_amount' => 20000,
                'stock' => 20000,
                'Achknowleded' => false
            ],
            [
                'id' => 2,
                'name' => 'Cheese',
                'total_amount' => 5000,
                'stock' => 5000,
                'Achknowleded' => false
            ],
            [
                'id' => 3,
                'name' => 'Onion',
                'total_amount' => 1000,
                'stock' => 1000,
                'Achknowleded' => false
            ],

        ]);
    }
}
