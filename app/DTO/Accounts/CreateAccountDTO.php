<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CreateAccountDTO extends Data
{
    public string $name;
    public int $userId;
}
