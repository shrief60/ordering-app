<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;
    public function SetUp(): void
    {
        parent::setUp();
        $this->ingredientsAmount = [
            "1" => 42000.0,
            "2" => 800000.0,
            "3" => 500000.0
        ];
    }

    /**
     * A basic feature test test_create_order_params_was_not_right.
     *
     * @return void
     */
    public function  test_create_order_params_was_not_right()
    {
        $this->seed();
        $response = $this->post('/api/orders', [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 0
                ],
                [
                    "product_id" => 2,
                    "quantity" => 1
                ]
            ]
        ]);
        $response->assertStatus(400);

    }

    public function  test_create_order_not_enough_ingredients()
    {
        $this->seed();
        $response = $this->post('/api/orders', [
            "products" => [
                [
                    "product_id" =>  1,
                    "quantity" => 20000
                ],
                [
                    "product_id" =>  2,
                    "quantity" => 1000
                ]
            ]
        ]);
        $response->assertStatus(403)->assertJson(["Message" => "we are sorry, we could not proceed with order please try again"]);

    }

    public function test_create_order_normal_case_success()
    {
        $this->seed();
        $products = Product::all();
        $response = $this->post('/api/orders', [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 2
                ],
                [
                    "product_id" => 2,
                    "quantity" => 1
                ]
            ]
        ]);
        $response->assertStatus(200)->assertJson(["status" => 200, "message" => "order Created successfully"]);

    }










}
