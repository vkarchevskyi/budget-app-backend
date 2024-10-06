<?php

declare(strict_types=1);

namespace App\DTO\Transactions;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateTransactionDTO extends Data
{
    public string|Optional $name;
    public string|Optional $description;
    public int|Optional $categoryId;
    public int|Optional $accountId;
    public float|Optional $price;
    #[WithCast(DateTimeInterfaceCast::class, format: DATE_ATOM)]
    public Carbon $date;
}
