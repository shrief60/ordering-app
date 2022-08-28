<?php

namespace App\Services;

use App\Mail\IngredientShortage;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Repositories\OrderRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderService
{

    protected $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * check  products availablility & create  order
     *
     * @param array $orderData
     * @return array
     */
    public function CreateOrder ($orderData = null) : array
    {
        try{
            $products = $orderData['products'];

            // get request product ingredients exists or not
            $productIds = array_column($products , 'product_id');
            $productIngredients = Product::whereIn('id', $productIds)->with('ingredients')->get();

            //check validity of  ingredients exists or not
            $ingredientsAmount = $this->calculateAllIngredients($productIngredients, $products );
            $this->checkIngredientsAvailability($ingredientsAmount);

            //create order
            return $this->orderRepository->CreateOrder($products, $ingredientsAmount);
        } catch(Exception $e){
            Log::error(__CLASS__.__FUNCTION__." create order error happened", ['message' => $e->getMessage(), 'code'=> $e->getCode()]);
            return ['status' => 403, 'Message' => $e->getMessage()];
        }

    }

    /**
     * calculate product ingredients for order
     *
     * @param Product[] $productIngredients
     * @param array $products
     * 
     * @return array
     */
    public function calculateAllIngredients($productIngredients, $products ) :array
    {
        $ingredientsAmount = array();
        foreach ($productIngredients as $key => $product) {
            foreach ($product->ingredients as $key => $ingredient) {
                $productQuantatiy =$this->getProductQuantity($product->id, $products);
                $ingredientsAmount[$ingredient->ingredient_id] = !array_key_exists($ingredient->ingredient_id, $ingredientsAmount) ? ($ingredient->amount * $productQuantatiy) :  $ingredientsAmount[$ingredient->ingredient_id] + ($ingredient->amount *$productQuantatiy);
            }
        }
        return  $ingredientsAmount;
    }

    /**
     * check Ingredients Availability
     *
     * @param array $ingredientsAmount
     * 
     * @return void
     */
    public function checkIngredientsAvailability($ingredientsAmount) : void
    {
        $ingredientsIds = array_keys($ingredientsAmount);
        $ingredients = Ingredient::whereIn('id', $ingredientsIds)->get();
        Log::info(__CLASS__.__FUNCTION__."Check  ingredient availability log product ingredients", ['ingredients' => $ingredients]);
        
        foreach ($ingredients as $key => $ingredient)
        {
            if( $this->IsIngredientLessThanFiftyPercentage($ingredient, $ingredientsAmount))
            {
                //send email in background
                $this->sendShortageMail($ingredient);
                $ingredient->Achknowleded = true;
                $ingredient->save();
            }

            if( $ingredient->stock < $ingredientsAmount[$ingredient->id])
            {
                Log::emergency($ingredient->name." is not available now, please Contact stock manager", ['stock' => $ingredient->stock, 'amount' => $ingredientsAmount[$ingredient->id]]);
                throw new Exception("we are sorry, we could not proceed with order please try again", 403);
            }
        }
    }

    /**
     * Is Ingredient Less Than Fifty Percentage
     * @param Ingredient $ingredient
     * @param array $ingredientsAmount
     * 
     * @return bool
     */
    public function IsIngredientLessThanFiftyPercentage($ingredient, $ingredientsAmount) :bool
    {
        Log::info(__CLASS__.__FUNCTION__."Check  ingredient for mail", ['stock' => $ingredient->stock, 'ingredient_amount' => $ingredientsAmount[$ingredient->id], 'acknowleded' => $ingredient->Achknowleded ]);
        return ($ingredient->stock - $ingredientsAmount[$ingredient->id]) * 100 / $ingredient->total_amount < 50 && $ingredient->Achknowleded == false;
    }

    /**
     * Is Ingredient Less Than Fifty Percentage
     * @param integer $productId
     * @param array $products
     * 
     * @return bool
     */
    public function getProductQuantity($productId, $products)
    {
        foreach ($products as $key => $product) {
            if($product['product_id'] == $productId) return $product['quantity'];
        }
        return 1;
    }

    /**
     * send Shortage Mail
     * @param Ingredient $ingredient
     * 
     * @return void
     */
    public function sendShortageMail($ingredient)
    {
        try {
            Mail::to(config('app.reciever_mail'))->send(new IngredientShortage($ingredient));
        } catch (\Throwable $th) {
            Log::emergency($ingredient->name." shortage mail  doesn't sent to the store",['ingredient' => $ingredient]);
        }
    }
}
