<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Helpers\JsonResponseHelper;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\CreateMerchantRequest;
use App\Models\User;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = Auth::attempt($credentials)) {
            return JsonResponseHelper::respondFail('Provided email address or password is incorrect', 422);
        }

        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        return JsonResponseHelper::respondSuccess([
            'id' => $user->id,
            'email' => $user->email,
            'roles' => $roles,
            'token' => $token,
        ], 200);
    }

    /**
     * @param CreateCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerCustomer(CreateCustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $user = $this->createUser($validated['email'], $validated['password']);
            $this->attachRole($user, 'customer');

            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'point_transaction' => 0,
                'user_id' => $user->id,
            ]);

            DB::commit();

            return JsonResponseHelper::respondSuccess([
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponseHelper::respondFail('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @param CreateMerchantRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerMerchant(CreateMerchantRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $user = $this->createUser($validated['email'], $validated['password']);
            $this->attachRole($user, 'merchant');

            Merchant::create([
                'shop_name' => $validated['shop_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'user_id' => $user->id,
            ]);

            DB::commit();

            return JsonResponseHelper::respondSuccess([
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponseHelper::respondFail('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    private function createUser(string $email, string $password): User
    {
        return User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    /**
     * @param User $user
     * @param string $roleName
     * @return void
     */
    private function attachRole(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $user->roles()->attach($role);
        }
    }
}
