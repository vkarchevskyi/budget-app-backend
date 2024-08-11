<?php

declare(strict_types=1);

namespace App\Resources;

use Spatie\LaravelData\Data;

class PaginatedDTO extends Data
{
    public function __construct(public int $page, public int $perPage)
    {
    }
}
