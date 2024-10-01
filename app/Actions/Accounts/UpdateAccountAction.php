<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

readonly class UpdateAccountAction
{
    public function run(Account $account, UpdateAccountDTO $updateAccountDTO): Account
    {
        return DB::transaction(
            function () use ($account, $updateAccountDTO): Account {
                $account->update($updateAccountDTO->all());

                return $account;
            }
        );
    }
}
