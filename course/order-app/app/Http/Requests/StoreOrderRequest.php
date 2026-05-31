<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// LESSON: A Form Request is a dedicated class for validation.
// It keeps controllers clean — instead of $request->validate([...]) inline,
// you type-hint this class in the controller method and Laravel runs it automatically.
// If validation fails → API returns 422, Web redirects back with errors.

class StoreOrderRequest extends FormRequest
{
    // LESSON: authorize() controls who can make this request.
    // Return true = everyone allowed. Later you'd check auth/permissions here.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'status'        => 'sometimes|in:pending,confirmed,cancelled',
        ];
    }

    // LESSON: messages() lets you customize the error text per rule
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter the customer name.',
            'status.in'              => 'Status must be pending, confirmed, or cancelled.',
        ];
    }
}
