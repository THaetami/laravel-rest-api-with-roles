<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginationService
{
    public function getAllProducts(string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator;
    public function getAllTransactions(int $customerId, string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator;
    public function getMerchantHistoryTransaction(int $merchantId, string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator;
}
