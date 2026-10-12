<?php

namespace App\Http\Requests\Admin\Catalog\Vehicle;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_model_id' => ['required', 'integer', 'exists:vehicle_models,id'],
            'stock_code' => ['required', 'string', 'max:255', 'unique:vehicles,stock_code'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . now()->year + 1],
            'color' => ['required', 'string', 'max:100'],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:available,reserved,sold'],
            'condition' => ['required', 'string', 'in:new,used'],
            'description' => ['nullable', 'string'],
        ];
    }
}
