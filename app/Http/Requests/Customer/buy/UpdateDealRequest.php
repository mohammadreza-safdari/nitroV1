<?php

namespace App\Http\Requests\Customer\buy;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDealRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')
                    ->where('user_id', $this->user()->id),
            ],

            'payment_method' => [
                'required',
                Rule::in(['full', 'installment']),
            ],

            'installment_months' => [
                'nullable',
                'integer',
                Rule::in([12, 24, 36]),
            ],

            'discount_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'user_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}

