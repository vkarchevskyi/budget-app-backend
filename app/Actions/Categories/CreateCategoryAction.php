<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateCategoryAction
{
    public function __construct(protected Gate $gate)
    {
    }

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

            $this->gate->authorize('create', [$category]);

            $category->save();
            return $category;
        });
    }
}
