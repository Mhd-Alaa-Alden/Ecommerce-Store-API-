<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;



// Definition Global Scopes

// class AvailableScope implements Scope
// {
//     public function apply(Builder $builder, Model $model)
//     {
//         $builder->where('stock_quantity', '>', 0);
//     }
// }


class Product extends Model

{
    //  local Scopes
    public function scopeQuantity(Builder $query)
    {
        return $query->where('stock_quantity', '>', 0);
    }



    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'Categorie_id',
        'image_url',
        'stock_quantity',
    ];


    // Apply Global Scopes

    // protected static function booted()
    // {
    //     static::addGlobalScope(new AvailableScope);
    // }
    // ---------------------------------------------------------

    public function Order_items()
    {
        return $this->hasMany(Order_item::class);
    }

    // كل  categ
    // يحتوي على العديد من المنتجات
    public function Categories()
    {
        return $this->hasMany(Categorie::class);
    }

    // كل منتج يمكن ان يضع في المفضلة  وكل مفضلة يمكن ان تحتوي على العديد من المنتجات
    //تأكد
    public function Wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'Wishlist_Product', 'product_id', 'wishlist_id');
    }
}
