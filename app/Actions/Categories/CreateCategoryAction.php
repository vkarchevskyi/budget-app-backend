<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class CreateCategoryAction
{
    /**
     * @throws ValidationException
     */
    public function run(CreateCategoryDTO $createCategoryDTO): Category
    {
        $this->validate($createCategoryDTO);

        return DB::transaction(
            fn (): Category => Category::query()->create($createCategoryDTO->all())
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(CreateCategoryDTO $createCategoryDTO): void
    {
        Validator::make($createCategoryDTO->all(), [
            'name' => 'required|string|min:1|max:255',
            'user_id' => 'required|integer|min:1|exists:users,id',
            'is_income' => 'required|boolean',
        ])->validate();
    }
}
