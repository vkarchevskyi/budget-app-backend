<?php

declare(strict_types=1);

namespace App\Http\Requests\Transactions;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|integer|min:0|exists:categories,id',
            'account_id' => 'required|integer|min:0|exists:accounts,id',
            'price' => 'required|integer|min:0',
            'date' => 'required|date_format:' . DATE_ATOM,
        ];
    }
}
