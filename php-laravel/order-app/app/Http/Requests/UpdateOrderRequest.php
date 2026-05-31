<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // LESSON: 'sometimes' means only validate this field IF it is present in the request.
        // This allows partial updates (PATCH) — you don't have to send every field.
        return [
            'customer_name' => 'sometimes|required|string|max:255',
            'status'        => 'sometimes|required|in:pending,confirmed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter the customer name.',
            'status.in'              => 'Status must be pending, confirmed, or cancelled.',
        ];
    }
}
