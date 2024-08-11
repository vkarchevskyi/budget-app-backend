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
class CreateTransactionDTO extends Data
{
    public function __construct(
        public string $name,
        public string|Optional $description,
        public int $categoryId,
        public int $accountId,
        public float $price,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public Carbon $date,
    ) {
    }
}
