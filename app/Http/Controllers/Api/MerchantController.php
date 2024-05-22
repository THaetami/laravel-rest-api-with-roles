<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant;

class MerchantController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $merchants = Merchant::all();
        return JsonResponseHelper::respondSuccess($merchants, 200);
    }
}
