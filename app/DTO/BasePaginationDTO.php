<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
abstract class BasePaginationDTO extends Data
{
    public int $page = 1;
    public int $perPage = 15;
    public string $sortBy = 'created_at';
    public string $sortOrder = 'ASC';
}
