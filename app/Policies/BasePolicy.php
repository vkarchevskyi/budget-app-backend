<?php

declare(strict_types=1);

namespace App\Policies;

class BasePolicy
{
    protected function isUniqueIds(int ...$ids): bool
    {
        return count(array_unique($ids)) <= 1;
    }
}
