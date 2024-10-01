<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

readonly class CreateAccountAction
{
    public function run(CreateAccountDTO $createAccountDTO): Account
    {
        return DB::transaction(
            fn (): Account => Account::query()->create([
                'name' => $createAccountDTO->name,
                'balance' => 0,
                'user_id' => $createAccountDTO->userId,
            ])
        );
    }
}
