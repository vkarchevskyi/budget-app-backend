<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class UpdateAccountAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateAccountDTO $updateAccountDTO): Account
    {
        return DB::transaction(function () use ($id, $updateAccountDTO): Account {
            /** @var Account $account */
            $account = Account::query()->findOrFail($id);

            $this->gate->authorize('update', [$account]);

            $account->update(['name' => $updateAccountDTO->name]);
            return $account;
        });
    }
}
