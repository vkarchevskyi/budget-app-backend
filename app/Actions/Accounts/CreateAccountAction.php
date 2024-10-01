<?php

declare(strict_types=1);

namespace App\Actions\Accounts;

use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class CreateAccountAction
{
    /**
     * @throws ValidationException
     */
    public function run(CreateAccountDTO $createAccountDTO): Account
    {
        $this->validate($createAccountDTO);

        return DB::transaction(
            fn (): Account => Account::query()->create([
                'name' => $createAccountDTO->name,
                'balance' => 0,
                'user_id' => $createAccountDTO->userId,
            ])
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(CreateAccountDTO $createAccountDTO): void
    {
        Validator::make($createAccountDTO->all(), [
            'name' => 'required|string|min:1|max:255',
            'user_id' => 'required|integer|min:1|exists:users,id',
        ])->validate();
    }
}
