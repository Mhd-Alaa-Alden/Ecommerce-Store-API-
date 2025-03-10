<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'response',
    ];

    public function Users()
    {
        return $this->hasMany(User::class);
    }
}
