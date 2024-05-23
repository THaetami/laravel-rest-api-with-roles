<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Reward;

class ClaimRewardRequest extends FormRequest
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
            'reward_id' => 'required|integer'
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
            $reward_id = $this->input('reward_id');
            $reward = Reward::find($reward_id);

            if (!$reward) {
                $validator->errors()->add('reward_id', 'Reward not found!');
            } elseif ($reward->stock <= 0) {
                $validator->errors()->add('reward_id', 'The reward is no longer available');
            }
        });
    }
}
