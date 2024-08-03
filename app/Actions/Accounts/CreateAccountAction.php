<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateAccountAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateAccountDTO $createAccountDTO): Account
    {
        return DB::transaction(function () use ($createAccountDTO): Account {
            return Account::query()
                ->create([
                    'name' => $createAccountDTO->name,
                    'balance' => 0,
                    'user_id' => auth()->user()?->getAuthIdentifier(),
                ]);
        });
    }
}
