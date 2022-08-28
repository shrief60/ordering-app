<?php

namespace App\Jobs;

use App\Models\OrderProductIngredient;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Type\Integer;

class OrderProductIngredientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;
    private $orderId;
    private $orderProductId; 
    private $DBProductId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderProductId, $orderId, $products, $DBProductId)
    {
        $this->products = $products;
        $this->orderId = $orderId;
        $this->orderProductId = $orderProductId;
        $this->DBProductId = $DBProductId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productIngredients = Product::where('id', $this->DBProductId)->with('ingredients')->first();
        $quantity = $this->getQuantity();
        foreach ($productIngredients->ingredients as $key => $ingredient) {
            OrderProductIngredient::create(["order_id" => $this->orderId, "product_id" => $this->orderProductId, "ingredient_id" => $ingredient->ingredient_id, "ingredient_amount" => $ingredient->amount* $quantity]);
        }
    }

    /**
     * get quantity for each product
     * @return Integer
     */
    public function getQuantity()
    {
        foreach ($this->products as $key => $product) {
            if($product['product_id'] == $this->DBProductId)
                return $product['quantity'];
        }
        return 1;
    }
}
