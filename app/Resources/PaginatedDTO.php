<?php

declare(strict_types=1);

namespace App\Resources;

use Spatie\LaravelData\Resource;

class PaginatedDTO extends Resource
{
    public function __construct(public int $page, public int $perPage)
    {
    }
}
