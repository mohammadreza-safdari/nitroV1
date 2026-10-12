<?php

namespace App\Http\Requests\Customer\buy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id',
            ],

            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')
                    ->where('user_id', $this->user()->id),
            ],

            'type' => [
                'required',
                Rule::in(['purchase']),
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
        ];
    }
}
