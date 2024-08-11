<?php

declare(strict_types=1);

namespace App\Http\Requests\Transactions;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
     * @return array<string, ValidationRule|mixed[]|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'integer', 'min:0', 'exists:categories,id'],
            'account_id' => ['sometimes', 'required', 'integer', 'min:0', 'exists:accounts,id'],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'date' => ['sometimes', 'required', 'date'],
        ];
    }
}
