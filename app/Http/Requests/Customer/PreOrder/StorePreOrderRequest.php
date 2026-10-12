<?php

namespace App\Http\Requests\Customer\PreOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePreOrderRequest extends FormRequest
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
            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id',
            ],

            'address_id' => [
                'nullable',
                'integer',
                'exists:addresses,id',
            ],

            'deposit' => [
                'required',
                'numeric',
                'min:0',
            ],

            'user_note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
