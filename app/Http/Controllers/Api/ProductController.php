<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\PaginationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $paginationService;

    /**
     * @param PaginationService $paginationService
     */
    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $direction = $request->input('direction', 'asc');
        $orderBy = $request->input('orderBy', 'name');
        $currentPage = $request->input('currentPage', 1);
        $pageSize = $request->input('pageSize', 10);

        $products = $this->paginationService->getAllProducts($direction, $orderBy, $currentPage, $pageSize);

        $formattedProducts = $products->map(function ($product) {
            return $product->formatForApiResponse();
        });

        $products->setCollection($formattedProducts);

        return JsonResponseHelper::respondSuccess($products, 200);
    }


    /**
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $user = Auth::user();

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'merchant_id' => $user->merchants->id,
            ]);

            DB::commit();
            return JsonResponseHelper::respondSuccess($product->formatForApiResponse(), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponseHelper::respondFail('Add product failed: ' . $e->getMessage(), 500);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $product = Product::find($request->id);
        if(!$product) {
            return JsonResponseHelper::respondErrorNotFound('Product not found!');
        }
        return JsonResponseHelper::respondSuccess($product->formatForApiResponse(), 200);
    }


    /**
     * @param ProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, $id)
    {
        $validated = $request->validated();
        $product = Product::find($id);
        if(!$product) {
            return JsonResponseHelper::respondErrorNotFound('Product not found!');
        }

        $product->update($validated);
        return JsonResponseHelper::respondSuccess($product->formatForApiResponse(), 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        if(!$product) {
            return JsonResponseHelper::respondErrorNotFound('Product not found!');
        }
        $product->delete();
        return JsonResponseHelper::respondSuccess("Product successfully deleted", 200);
    }

}
