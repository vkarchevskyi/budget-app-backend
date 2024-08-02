<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class CreateAccountAction
{
    /**
     * @throws \Throwable
     */
    public function run(CreateAccountDTO $createAccountDTO): void
    {
        DB::transaction(function () use ($createAccountDTO): void {
            Account::query()
                ->create([
                    'name' => $createAccountDTO->name,
                    'balance' => $createAccountDTO->balance,
                ]);
        });
    }
}
