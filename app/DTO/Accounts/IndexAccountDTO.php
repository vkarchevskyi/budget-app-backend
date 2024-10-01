<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use App\DTO\BasePaginationDTO;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class IndexAccountDTO extends BasePaginationDTO
{
    public string|Optional $search;
    public int $userId;
}
