<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateAccountAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(CreateAccountDTO $createAccountDTO): Account
    {
        return DB::transaction(function () use ($createAccountDTO): Account {
            $account = Account::query()->make([
                'name' => $createAccountDTO->name,
                'balance' => 0,
                'user_id' => auth()->user()?->getAuthIdentifier(),
            ]);

            $this->gate->authorize('create', [$account]);

            $account->save();
            return $account;
        });
    }
}
