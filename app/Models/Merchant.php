<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'phone',
        'address'
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
