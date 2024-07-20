<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountBalanceDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class UpdateAccountBalanceAction
{
    /**
     * @throws \Throwable
     */
    public function run(int $id, UpdateAccountBalanceDTO $accountBalanceDTO): void
    {
        DB::transaction(function () use ($id, $accountBalanceDTO) {
            /** @var Account $account */
            $account = Account::where('id', '=', $id)
                ->firstOrFail(['id', 'balance']);

            $account->balance += $accountBalanceDTO->balanceChange;

            $account->save();
        });
    }
}
