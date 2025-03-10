<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'total_price',
        'status',
        'email',
        'phone_number',
        'country',
        'address',
        'note',

    ];


    //  يتمي الى طلب واحدorder_item كل 

    public function order_items()
    {
        return $this->belongsTo(Order_item::class);
    }

    public function Users()
    {
        return $this->hasMany(User::class);
    }
}
