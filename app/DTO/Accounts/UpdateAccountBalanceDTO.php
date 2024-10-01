<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UpdateAccountBalanceDTO extends Data
{
    public float $balanceChange;
}
