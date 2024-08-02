<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CreateCategoryAction
{
    /**
     * @throws \Throwable
     */
    public function run(CreateCategoryDTO $createCategoryDTO): void
    {
        DB::transaction(function () use ($createCategoryDTO): void {
            Category::query()
                ->create([
                    'user_id' => $createCategoryDTO->userId,
                    'name' => $createCategoryDTO->name,
                ]);
        });
    }
}
