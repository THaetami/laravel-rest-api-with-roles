<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
        /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customers = Customer::all();
        return JsonResponseHelper::respondSuccess($customers, 200);
    }
}
