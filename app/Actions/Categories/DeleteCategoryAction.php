<?php

declare(strict_types=1);

namespace App\Actions\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

readonly class DeleteCategoryAction
{
    public function run(Category $category): bool
    {
        return DB::transaction(fn (): bool => (bool) $category->delete());
    }
}
