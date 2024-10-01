<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use App\DTO\BasePaginationDTO;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class IndexAccountDTO extends BasePaginationDTO
{
    public string|Optional $search;
    public int $userId;
}
