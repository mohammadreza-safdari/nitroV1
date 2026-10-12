<?php

namespace App\Http\Requests\Admin\Catalog\MotorcycleDetail;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleDetailRequest extends FormRequest
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
            'engine' => ['required', 'string', 'max:255'],
            'horsepower' => ['required', 'numeric', 'min:0'],
            'torque' => ['required', 'numeric', 'min:0'],
            'transmission' => ['required', 'string', 'max:100'],
            'fuel_type' => ['required', 'string', 'max:100'],
            'cooling_system' => ['required', 'string', 'max:100'],
            'seat_height' => ['required', 'numeric', 'min:0'],
            'wheelbase' => ['required', 'numeric', 'min:0'],
            'fuel_capacity' => ['required', 'numeric', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'top_speed' => ['required', 'numeric', 'min:0'],
            'additional_specs' => ['nullable', 'array'],
        ];
    }
}
