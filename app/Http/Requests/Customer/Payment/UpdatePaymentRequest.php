<?php

namespace App\Http\Requests\Customer\Payment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'method' => [
                'required',
                'string',
                'max:50',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'installment_ids' => [
                'nullable',
                'array',
            ],

            'installment_ids.*' => [
                'integer',
                'distinct',
                'exists:installments,id',
            ],
        ];
    }
}
