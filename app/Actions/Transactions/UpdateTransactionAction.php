<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\UpdateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class UpdateTransactionAction
{
    /**
     * @throws ValidationException
     */
    public function run(Transaction $transaction, UpdateTransactionDTO $updateTransactionDTO): Transaction
    {
        $this->validate($updateTransactionDTO);

        return DB::transaction(
            function () use ($transaction, $updateTransactionDTO): Transaction {
                $updateTransactionDTO->date = $updateTransactionDTO->date->utc();

                $transaction->update($updateTransactionDTO->all());

                return $transaction;
            }
        );
    }

    /**
     * @throws ValidationException
     */
    private function validate(UpdateTransactionDTO $updateTransactionDTO): void
    {
        Validator::make($updateTransactionDTO->toArray(), [
            'name' => 'sometimes|required|string|min:1|max:255',
            'description' => 'sometimes|nullable|string|max:255',
            'category_id' => 'sometimes|required|integer|min:0|exists:categories,id',
            'account_id' => 'sometimes|required|integer|min:0|exists:accounts,id',
            'price' => 'sometimes|required|integer|min:0',
            'date' => 'sometimes|required|date_format:' . DATE_ATOM,
        ])->validate();
    }
}
