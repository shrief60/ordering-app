<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductIngredient extends Model
{
    use HasFactory;

    protected $fillable =['order_id', 'product_id', 'ingredient_id', 'ingredient_amount'];
}
