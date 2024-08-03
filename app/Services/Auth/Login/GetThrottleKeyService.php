<?php

declare(strict_types=1);

namespace App\Services\Auth\Login;

use Illuminate\Support\Str;

readonly class GetThrottleKeyService
{
    public function run(string $email, ?string $ip): string
    {
        return Str::transliterate(Str::lower($email) . '|' . ($ip ?? ''));
    }
}
