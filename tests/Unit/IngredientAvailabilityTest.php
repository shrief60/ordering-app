<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IngredientAvailabilityTest extends TestCase
{
    use RefreshDatabase;
    public $ingredientsAmount =[];
    public function SetUp() :void
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
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_ingredient_is_available()
    {
        $ingredientsAmount = [
            "1" => 42.0,
            "2" => 80.0,
            "3" => 50.0
        ];
        $orderRepository = new OrderRepository();
        $orderService = new OrderService($orderRepository);
        $orderService->checkIngredientsAvailability($ingredientsAmount);
        $this->assertTrue(true);
    }

    public function test_ingredient_not_available()
    {
        $orderRepository = new OrderRepository();
        $orderService = new OrderService($orderRepository);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("we are sorry, we could not proceed with order please try again");
        $orderService->checkIngredientsAvailability($this->ingredientsAmount);
    }

    public function test_ingredient_less_than_fifty_percentage()
    {
        $ingredient = Ingredient::first();
        $orderRepository = new OrderRepository();
        $orderService = new OrderService($orderRepository);
        $result = $orderService->IsIngredientLessThanFiftyPercentage($ingredient, $this->ingredientsAmount);
        $this->assertTrue($result);
    }

    public function test_ingredient_greater_than_fifty_percentage()
    {
        $ingredientsAmount = [
            "1" => 42.0,
            "2" => 80.0,
            "3" => 50.0
        ];
        $ingredient = Ingredient::first();
        $orderRepository = new OrderRepository();
        $orderService = new OrderService($orderRepository);
        $result = $orderService->IsIngredientLessThanFiftyPercentage($ingredient, $ingredientsAmount);
        $this->assertFalse($result);
    }

}
