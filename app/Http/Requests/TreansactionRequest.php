<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Product;

class TreansactionRequest extends FormRequest
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
            '*.quantity' => 'required|integer|min:1',
            '*.price' => 'required|integer|min:0',
            '*.productId' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $details = $this->all();

            foreach ($details as $detail) {
                $product = Product::where('id', $detail['productId'])->first();

                if (!$product) {
                    $validator->errors()->add('productId', 'Product not found!');
                } else {
                    if ($product->price != $detail['price']) {
                        $validator->errors()->add('price', 'Product price mismatch!');
                    }

                    if ($detail['quantity'] <= 0 || $detail['quantity'] > $product->stock) {
                        $validator->errors()->add('quantity', 'Invalid quantity!');
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            '*.quantity.required' => 'Quantity is required',
            '*.quantity.integer' => 'Quantity must be an integer',
            '*.quantity.min' => 'Minimum quantity is 1',
            '*.price.required' => 'Price is required',
            '*.price.integer' => 'Price must be an integer',
            '*.price.min' => 'Minimum price is 0',
            '*.productId.required' => 'Product ID is required',
            '*.rewardId.required' => 'Reward ID is required',
        ];
    }
}
