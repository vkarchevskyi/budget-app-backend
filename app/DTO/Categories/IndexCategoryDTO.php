<?php

declare(strict_types=1);

namespace App\DTO\Categories;

use App\DTO\BasePaginationDTO;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class IndexCategoryDTO extends BasePaginationDTO
{
    public string|Optional $search;
    public int $userId;
}
