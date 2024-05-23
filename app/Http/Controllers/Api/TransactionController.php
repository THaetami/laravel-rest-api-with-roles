<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TreansactionRequest;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Models\Product;
use App\Services\PaginationService;
use Illuminate\Http\Request;

class TransactionController extends Controller
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

        [$direction, $orderBy, $currentPage, $pageSize] = $this->getDefaultPaginationParams($request);

        $customerId = Auth::user()->customers->id;
        $transactions = $this->paginationService->getAllTransactions($customerId, $direction, $orderBy, $currentPage, $pageSize);

        $response = $transactions->map(function ($transaction) {
            return $this->createTransactionResponse($transaction);
        });

        $transactions->setCollection($response);
        return JsonResponseHelper::respondSuccess($transactions, 200);
    }


    /**
     * @param  \App\Http\Requests\TreansactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TreansactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $user = Auth::user();
            $totalPrice = 0;

            $transaction = Transaction::create([
                'customer_id' => $user->customers->id,
                'trans_date' => now(),
            ]);

            foreach ($validated as $detail) {
                $product = Product::find($detail['productId']);

                $product->stock -= $detail['quantity'];
                $product->save();

                $totalPrice += $detail['quantity'] * $detail['price'];

                $transaction->transactionDetails()->create([
                    'qty' => $detail['quantity'],
                    'price' => $detail['price'],
                    'product_id' => $detail['productId'],
                    'transaction_id' => $transaction->id,
                ]);
            }

            $pointEarned = (int)($totalPrice / 1000);
            $user->customers->point_transaction += $pointEarned;
            $user->customers->save();

            DB::commit();
            return JsonResponseHelper::respondSuccess($this->createTransactionResponse($transaction));

        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponseHelper::respondFail('Transaction failed: ' . $e->getMessage(), 500);
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMerchantHistoryTransaction(Request $request)
    {

        [$direction, $orderBy, $currentPage, $pageSize] = $this->getDefaultPaginationParams($request);

        $merchantId = Auth::user()->merchants->id;

        $transactions = $this->paginationService->getMerchantHistoryTransaction($merchantId, $direction, $orderBy, $currentPage, $pageSize);
        $response = $transactions->map(function ($transaction) {
            return $this->createTransactionResponse($transaction);
        });

        $transactions->setCollection($response);
        return JsonResponseHelper::respondSuccess($transactions, 200);
    }


    /**
     * @param Request $request
     * @return array An array containing the direction, orderBy, currentPage, and pageSize.
     */
    private function getDefaultPaginationParams(Request $request): array
    {
        return [
            $request->input('direction', 'asc'),
            $request->input('orderBy', 'trans_date'),
            $request->input('currentPage', 1),
            $request->input('pageSize', 10),
        ];
    }


    /**
     * @param mixed $transaction The transaction data.
     * @return array The formatted transaction response.
     */
    private function createTransactionResponse($transaction)
    {
        $details = [];
        $totalPrice = 0;

        foreach ($transaction->transactionDetails as $detail) {
            $totalPrice += $detail->qty * $detail->price;
            $details[] = $detail->formatForApiResponse();
        }

        $pointEarned = (int)($totalPrice / 1000);

        return [
            'id' => $transaction->id,
            'transaction_date' => $transaction->trans_date,
            'pointEarned' => $pointEarned,
            'customer_id' => $transaction->customer_id,
            'total_transaction' => $totalPrice,
            'details' => $details,
        ];
    }

}
