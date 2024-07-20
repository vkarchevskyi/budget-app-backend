<?php

declare(strict_types=1);

namespace App\DTO\Budgets;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CreateBudgetDTO extends Data
{
    public function __construct(
        public float $size,
        public int $categoryId,
        public int $userId,
        public Carbon $month,
    ) {
    }
}
