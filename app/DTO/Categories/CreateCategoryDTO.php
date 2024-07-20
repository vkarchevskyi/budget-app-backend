<?php

declare(strict_types=1);

namespace App\DTO\Categories;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CreateCategoryDTO extends Data
{
    public function __construct(public int $userId, public string $name)
    {
    }
}
