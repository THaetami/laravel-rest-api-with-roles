<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'point_reward',
        'stock'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
