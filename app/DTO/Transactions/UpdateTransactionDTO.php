<?php

declare(strict_types=1);

namespace App\DTO\Transactions;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateTransactionDTO extends Data
{
    public function __construct(
        public string|Optional $name,
        public int|Optional $categoryId,
        public int|Optional $accountId,
        public float|Optional $price
    ) {
    }
}
