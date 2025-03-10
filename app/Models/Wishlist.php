<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',


    ];


    // كل منتج يمكن ان يضع في المفضلة  وكل مفضلة يمكن ان تحتوي على العديد من المنتجات
    //  تأكد
    public function products()
    {
        return $this->belongsToMany(Product::class, 'Wishlist_Product', 'product_id', 'wishlist_id');
    }
}
