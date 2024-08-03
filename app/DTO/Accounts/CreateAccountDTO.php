<?php

declare(strict_types=1);

namespace App\DTO\Accounts;

use Spatie\LaravelData\Data;

class CreateAccountDTO extends Data
{
    public function __construct(
        public string $name,
    ) {
    }
}
