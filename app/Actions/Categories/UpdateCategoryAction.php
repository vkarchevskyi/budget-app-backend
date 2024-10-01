<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\UpdateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class UpdateCategoryAction
{
    /**
     * @throws ValidationException
     */
    public function run(Category $category, UpdateCategoryDTO $updateCategoryDTO): Category
    {
        $this->validate($updateCategoryDTO);

        return DB::transaction(
            function () use ($category, $updateCategoryDTO): Category {
                $category->update($updateCategoryDTO->all());

                return $category;
            }
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(UpdateCategoryDTO $updateCategoryDTO): void
    {
        Validator::make($updateCategoryDTO->all(), [
            'name' => 'sometimes|required|string|min:1|max:255',
        ])->validate();
    }
}
