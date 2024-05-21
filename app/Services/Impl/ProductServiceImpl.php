<?php

namespace App\Services\Impl;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductServiceImpl implements ProductService
{
    public function getAllProducts(string $direction, string $orderBy, int $currentPage, int $pageSize): LengthAwarePaginator
    {
        $query = Product::orderBy($orderBy, $direction);

        return $query->paginate($pageSize, ['*'], 'page', $currentPage);
    }
}
