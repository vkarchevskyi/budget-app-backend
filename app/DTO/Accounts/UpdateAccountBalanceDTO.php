<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateAccountBalanceDTO extends Data
{
    public float $balanceChange;
}
