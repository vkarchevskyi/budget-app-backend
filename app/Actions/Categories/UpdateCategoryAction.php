<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\UpdateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateCategoryAction
{
    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateCategoryDTO $updateCategoryDTO): Category
    {
        return DB::transaction(function () use ($id, $updateCategoryDTO): Category {
            $category = Category::query()->findOrFail($id);

            // TODO: add policy

            $category->update(['name' => $updateCategoryDTO->name]);
            return $category;
        });
    }
}
