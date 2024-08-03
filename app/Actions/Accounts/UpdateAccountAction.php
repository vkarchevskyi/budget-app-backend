<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateAccountAction
{
    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateAccountDTO $updateAccountDTO): Account
    {
        return DB::transaction(function () use ($id, $updateAccountDTO): Account {
            $account = Account::query()->findOrFail($id);

            // TODO: add policy

            $account->update(['name' => $updateAccountDTO->name]);
            return $account;
        });
    }
}
