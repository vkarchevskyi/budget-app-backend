<?php

declare(strict_types=1);

namespace App\DTO\Categories;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateCategoryDTO extends Data
{
    public string|Optional $name;
    public bool|Optional $isIncome;
}
