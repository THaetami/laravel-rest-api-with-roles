<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'price',
        'product_id',
        'transaction_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function formatForApiResponse()
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'qty' => $this->qty,
            'price' => $this->price,
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
