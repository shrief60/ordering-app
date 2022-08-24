<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Burger',
                'Description' => "clice Beef with Onion and cheese",
                'price' => 15,
            ],
            [
                'name' => 'chicken',
                'Description' => "slice chicken with Onion and cheese",
                'price' => 20,
            ]

        ]);
    }
}
