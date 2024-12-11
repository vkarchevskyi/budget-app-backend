<?php

declare(strict_types=1);

namespace App\Resources\Charts;

use Spatie\LaravelData\Resource;

class ExpenseResource extends Resource
{
    public function __construct(
        public readonly string $month,
        public readonly int $income,
        public readonly int $outcome,
    ) {
    }
}
