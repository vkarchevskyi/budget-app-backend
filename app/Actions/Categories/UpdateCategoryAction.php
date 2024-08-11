<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\UpdateCategoryDTO;
use App\Models\Category;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class UpdateCategoryAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateCategoryDTO $updateCategoryDTO): Category
    {
        return DB::transaction(function () use ($id, $updateCategoryDTO): Category {
            /** @var Category $category */
            $category = Category::query()->findOrFail($id);

            $this->gate->authorize('update', [$category]);

            $category->update(['name' => $updateCategoryDTO->name]);
            return $category;
        });
    }
}
