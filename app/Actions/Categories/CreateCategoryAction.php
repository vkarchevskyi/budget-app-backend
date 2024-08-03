<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateCategoryAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateCategoryDTO $createCategoryDTO): Category
    {
        return DB::transaction(function () use ($createCategoryDTO): Category {
            return Category::query()
                ->create([
                    'user_id' => auth()->user()?->getAuthIdentifier(),
                    'name' => $createCategoryDTO->name,
                ]);
        });
    }
}
