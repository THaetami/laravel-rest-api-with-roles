<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:25|regex:/^[a-zA-Z ]*$/',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:5|regex:/^[a-zA-Z0-9]*$/',
        ];
    }
}
