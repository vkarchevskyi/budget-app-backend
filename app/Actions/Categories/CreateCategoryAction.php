<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CreateCategoryAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateCategoryDTO $createCategoryDTO): Category
    {
        return DB::transaction(function () use ($createCategoryDTO): Category {
            $category = Category::query()->make([
                'user_id' => auth()->user()?->getAuthIdentifier(),
                'name' => $createCategoryDTO->name,
            ]);

            Gate::authorize('create', [$category]);

            $category->save();
            return $category;
        });
    }
}
