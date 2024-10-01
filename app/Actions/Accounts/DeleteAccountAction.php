<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

readonly class DeleteAccountAction
{
    public function run(Account $account): bool
    {
        return DB::transaction(fn (): bool => (bool) $account->delete());
    }
}
