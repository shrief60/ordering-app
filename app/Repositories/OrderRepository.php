<?php

namespace App\Repositories;

use App\Jobs\OrderProductIngredientJob;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderProduct;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{

     /**
     * open transaction and create order and update ingredient amount
     *
     * @param array $products
     * @param array $ingredientsAmount
     * @return array response
     */
    public function CreateOrder ($products, $ingredientsAmount) : array
    {
        try{
            $result = DB::transaction(function () use ($products, $ingredientsAmount){
                $order = Order::create(['status' => Order::ACCEPTED_STATUS, 'total_price' => '10']);
                foreach ($products as $key => $product){
                    $product =OrderProduct::create(['order_id' => $order->id, 'product_id' => $product['product_id'], 'quantity' => $product['quantity']]);
                    //log order, product and used ingredients just for tracking 
                    dispatch((new OrderProductIngredientJob($product->id, $order->id, $products, $product['product_id']))->onQueue('default'));
                }

                foreach ($ingredientsAmount as $key => $ingredient) {
                    $DBingredient = Ingredient::where('id', $key)->decrement('stock',$ingredient);
                }
            });
            return ['status' => 200, 'message' => "order Created successfully"];
        }catch(Exception $e) {
            Log::error(__CLASS__ . __FUNCTION__ . " log error happened while create order transaction", ["message" => $e->getMessage()]);
            return ['status' => 403, 'message' => "DB: Failed to create order"];
        }
    }

}
