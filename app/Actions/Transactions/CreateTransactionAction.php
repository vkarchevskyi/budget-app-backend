<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\CreateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class CreateTransactionAction
{
    /**
     * @throws ValidationException
     */
    public function run(CreateTransactionDTO $createTransactionDTO): Transaction
    {
        $this->validate($createTransactionDTO);

        return DB::transaction(
            function () use ($createTransactionDTO): Transaction {
                $createTransactionDTO->date = $createTransactionDTO->date->utc();

                return Transaction::query()->create($createTransactionDTO->all());
            }
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(CreateTransactionDTO $createTransactionDTO): void
    {
        Validator::make($createTransactionDTO->toArray(), [
            'name' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|integer|min:1|exists:categories,id',
            'account_id' => 'required|integer|min:1|exists:accounts,id',
            'user_id' => 'required|integer|min:1|exists:users,id',
            'price' => 'required|integer|min:0',
            'date' => 'required|date_format:' . DATE_ATOM,
        ])->validate();
    }
}
