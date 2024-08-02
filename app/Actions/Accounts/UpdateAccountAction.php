<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class UpdateAccountAction
{
    /**
     * @throws \Throwable
     */
    public function run(int $id, UpdateAccountDTO $updateAccountDTO): void
    {
        DB::transaction(function () use ($id, $updateAccountDTO): void {
            Account::query()
                ->where('id', '=', $id)
                ->firstOrFail(['id'])
                ->update([
                    'name' => $updateAccountDTO->name,
                ]);
        });
    }
}
