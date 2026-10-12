<?php

namespace App\Http\Requests\Admin\Catalog\VehicleModel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleModel = $this->route('vehicle_model');

        return [
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:vehicle_models,slug,' . $vehicleModel->id,
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
