<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist_Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'Wishlist_id',
    ];
}
