<?php

declare(strict_types=1);

namespace App\Resources\Categories;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;

#[MapName(SnakeCaseMapper::class)]
class CategoryResource extends Resource
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $name,
        public readonly bool $isIncome,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
        public readonly Carbon|Optional $deletedAt
    ) {
    }
}
