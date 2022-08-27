<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =['status', 'total_price'];
    const SENT_STATUS = 'sent';
    const ACCEPTED_STATUS = 'accepted';
    const COMPLETED_STATUS = 'completed';

}
