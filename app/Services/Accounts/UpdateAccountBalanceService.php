<?php

declare(strict_types=1);

namespace App\Services\Accounts;

use App\DTO\Accounts\UpdateAccountBalanceDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateAccountBalanceService
{
    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateAccountBalanceDTO $accountBalanceDTO): void
    {
        DB::transaction(function () use ($id, $accountBalanceDTO): void {
            Account::query()
                ->where('id', '=', $id)
                ->increment('balance', $accountBalanceDTO->balanceChange);
        });
    }
}
