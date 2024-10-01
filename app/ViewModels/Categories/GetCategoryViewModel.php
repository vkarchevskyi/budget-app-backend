<?php

declare(strict_types=1);

namespace App\ViewModels\Categories;

use App\Models\Category;
use App\Resources\Categories\CategoryResource;

readonly class GetCategoryViewModel
{
    public function get(Category $category): CategoryResource
    {
        return CategoryResource::from($category);
    }
}
