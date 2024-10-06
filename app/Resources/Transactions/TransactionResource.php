<?php

declare(strict_types=1);

namespace App\Resources\Transactions;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class TransactionResource extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $categoryId,
        public readonly int $userId,
        public readonly int $accountId,
        public readonly int $price,
        #[WithCast(DateTimeInterfaceCast::class, format: CarbonInterface::DEFAULT_TO_STRING_FORMAT)]
        public readonly Carbon $date,
        #[WithCast(DateTimeInterfaceCast::class, format: CarbonInterface::DEFAULT_TO_STRING_FORMAT)]
        public readonly Carbon $createdAt,
        #[WithCast(DateTimeInterfaceCast::class, format: CarbonInterface::DEFAULT_TO_STRING_FORMAT)]
        public readonly Carbon $updatedAt,
        #[WithCast(DateTimeInterfaceCast::class, format: CarbonInterface::DEFAULT_TO_STRING_FORMAT)]
        public readonly ?Carbon $deletedAt
    ) {
    }
}
