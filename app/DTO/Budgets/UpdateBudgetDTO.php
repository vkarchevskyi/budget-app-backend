<?php

declare(strict_types=1);

namespace App\DTO\Budgets;

use Spatie\LaravelData\Data;

class UpdateBudgetDTO extends Data
{
    public function __construct(public float $size)
    {
    }
}
