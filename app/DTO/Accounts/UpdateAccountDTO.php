<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateAccountDTO extends Data
{
    public function __construct(
        public string|Optional $name,
    ) {
    }
}
