<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => 'sometimes|required|string|max:255',
            'quantity'     => 'sometimes|required|integer|min:1',
            'unit_price'   => 'sometimes|required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.min'    => 'Quantity must be at least 1.',
            'unit_price.min'  => 'Unit price cannot be negative.',
        ];
    }
}
