<?php

namespace App\Services\Impl;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\PaginationService;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationServiceImpl implements PaginationService
{
    public function getAllProducts(string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator
    {
        $query = Product::orderBy($orderBy, $direction);

        return $query->paginate($pageSize, ['*'], 'page', $currentPage);
    }


    public function getAllTransactions(int $customerId, string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator
    {
        $query = Transaction::where('customer_id', $customerId)->orderBy($orderBy, $direction);

        return $query->paginate($pageSize, ['*'], 'page', $currentPage);
    }


    public function getMerchantHistoryTransaction(int $merchantId, string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator
    {
        // ambil transaksi yang memiliki transaksiDetails.product yang mana productnya (product didalam transaksiDetails) itu memiliki merchat_id == $merchatId
        $query = Transaction::whereHas('transactionDetails.product', function ($q) use ($merchantId) {
            $q->where('merchant_id', $merchantId);
        })->orderBy($orderBy, $direction);

        return $query->paginate($pageSize, ['*'], 'page', $currentPage);
    }
}
