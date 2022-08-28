<?php

namespace Tests\Unit;

use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcceptOrderTest extends TestCase
{

    use RefreshDatabase;
    public $ingredientsAmount = [];
    public function SetUp(): void
    {
        parent::setUp();
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

        $this->ingredientsAmount = [
            "1" => 42000.0,
            "2" => 800000.0,
            "3" => 500000.0
        ];
    }

    /**
     * A basic unit test create order fails incase not enough ingredient.
     *
     * @return void
     */
    public function test_create_order_successfully()
    {
        $products = [
            [
                "product_id" => 1,
                "quantity" => 2
            ],
            [
                "product_id" => 2,
                "quantity" => 1
            ]
        ];

        $ingredientsAmount =  [
            "1" => 20.0,
            "2" => 50.0,
            "3" => 56.0
        ];
        $orderRepository = new OrderRepository();
        $response = $orderRepository->CreateOrder($products, $ingredientsAmount);
        $expectedResponse = json_encode(['status' => 200, 'message' => "order Created successfully"]);
        $actualResponse = json_encode($response);
        $this->assertJsonStringEqualsJsonString($expectedResponse, $actualResponse);

    }

    /**
     * A basic unit test create order fails incase not enough ingredient.
     * URGENT: happened in case of concurrency only
     *
     * @return void
     */
    public function test_create_order_fails_incase_not_enough_ingredient()
    {
        $products = [
            [
                "product_id" => 1,
                "quantity" => 20000
            ],
            [
                "product_id" => 2,
                "quantity" => 100
            ]
        ];
        $orderRepository = new OrderRepository();
        $response = $orderRepository->CreateOrder($products, $this->ingredientsAmount);
        $expectedResponse = json_encode(['status' => 403, 'message' => "DB: Failed to create order"]);
        $actualResponse = json_encode($response);
        $this->assertJsonStringEqualsJsonString($expectedResponse, $actualResponse);

    }


}
