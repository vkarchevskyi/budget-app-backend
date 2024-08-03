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
    public function run(int $id, UpdateCategoryDTO $updateCategoryDTO): void
    {
        DB::transaction(function () use ($id, $updateCategoryDTO): void {
            Category::query()
                ->where('id', '=', $id)
                ->update([
                    'name' => $updateCategoryDTO->name,
                ]);
        });
    }

}
