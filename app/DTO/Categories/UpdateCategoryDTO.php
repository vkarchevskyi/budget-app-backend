<?php

declare(strict_types=1);

namespace App\DTO\Categories;

use Spatie\LaravelData\Data;

class UpdateCategoryDTO extends Data
{
    public function __construct(public string $name)
    {
    }
}
