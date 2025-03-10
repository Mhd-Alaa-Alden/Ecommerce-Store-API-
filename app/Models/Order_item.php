<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    use HasFactory;
    protected $fillable = [

        'order_id',
        'product_id',
        'quantity',
        'price',


    ];

    public function Orders()
    {
        return $this->hasMany(Order::class);
    }


    public function products()
    {
        return $this->hasMany(product::class);
    }
}
