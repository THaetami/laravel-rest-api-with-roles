<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'point_transaction',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function claimRewards()
    {
        return $this->hasMany(ClaimReward::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
