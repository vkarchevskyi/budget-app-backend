<?php

declare(strict_types=1);

namespace App\DTO\Charts;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ExpensesDTO extends Data
{
    public int $monthQuantity = 3;
    public int $userId;
}
