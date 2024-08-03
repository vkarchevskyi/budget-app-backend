<?php

declare(strict_types=1);

namespace App\Http\Requests\Budgets;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBudgetRequest extends FormRequest
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
            'size' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'min:0', 'exists:categories,id'],
            'date' => ['required', 'date:Y-m-d'],
        ];
    }
}
