<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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

        $products = $this->productService->getAllProducts($direction, $orderBy, $currentPage, $pageSize);

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
            return JsonResponseHelper::respondFail('Registration failed: ' . $e->getMessage(), 500);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
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

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);
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
