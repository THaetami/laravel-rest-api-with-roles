<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductService
{
    public function getAllProducts(string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator;
}
