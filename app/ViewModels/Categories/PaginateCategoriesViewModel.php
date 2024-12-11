<?php

declare(strict_types=1);

namespace App\ViewModels\Categories;

use App\DTO\Categories\IndexCategoryDTO;
use App\Models\Category;
use App\Resources\Categories\CategoryResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Optional;

readonly class PaginateCategoriesViewModel
{
    public function get(IndexCategoryDTO $paginateDTO): LengthAwarePaginator
    {
        $query = Category::query();

        if (!($paginateDTO->search instanceof Optional)) {
            $query->where('name', 'ilike', "%$paginateDTO->search%");
        }

        $query->where('user_id', $paginateDTO->userId)
            ->orderBy($paginateDTO->sortBy, $paginateDTO->sortOrder);

        $paginatedData = $query->paginate($paginateDTO->perPage, ['*'], 'page', $paginateDTO->page);

        /** @var LengthAwarePaginator<CategoryResource> $paginatedResource */
        $paginatedResource = CategoryResource::collect($paginatedData, LengthAwarePaginator::class);

        return $paginatedResource;
    }
}
