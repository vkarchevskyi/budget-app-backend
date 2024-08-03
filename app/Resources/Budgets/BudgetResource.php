<?php

declare(strict_types=1);

namespace App\Resources\Budgets;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class BudgetResource extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $size,
        public readonly int $categoryId,
        public readonly int $userId,
        public readonly Carbon $date,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
        public readonly ?Carbon $deletedAt,
    ) {
    }
}
