<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class UpdateAccountAction
{
    /**
     * @throws ValidationException
     */
    public function run(Account $account, UpdateAccountDTO $updateAccountDTO): Account
    {
        $this->validate($updateAccountDTO);

        return DB::transaction(
            function () use ($account, $updateAccountDTO): Account {
                $account->update($updateAccountDTO->all());

                return $account;
            }
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(UpdateAccountDTO $updateAccountDTO): void
    {
        Validator::make($updateAccountDTO->all(), [
            'name' => 'sometimes|required|string|min:1|max:255',
        ])->validate();
    }
}
