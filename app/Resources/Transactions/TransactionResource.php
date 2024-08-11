<?php

declare(strict_types=1);

namespace App\Resources\Transactions;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class TransactionResource extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $categoryId,
        public readonly int $userId,
        public readonly int $accountId,
        public readonly int $price,
        public readonly Carbon $date,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
        public readonly ?Carbon $deletedAt
    ) {
    }
}
