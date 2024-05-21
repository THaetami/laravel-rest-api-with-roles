<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reward_id',
        'claim_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function reward()
    {
        return $this->hasOne(Reward::class);
    }
}
