<?php

declare(strict_types=1);

namespace App\DTO\Transactions;

use Spatie\LaravelData\Data;

class CreateTransactionDTO extends Data
{
    public function __construct(
        public int $categoryId,
        public int $userId,
        public int $accountId,
        public float $price
    ) {
    }
}
