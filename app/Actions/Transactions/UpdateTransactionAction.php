<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\UpdateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class UpdateTransactionAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateTransactionDTO $updateTransactionDTO): Transaction
    {
        return DB::transaction(function () use ($id, $updateTransactionDTO): Transaction {
            /** @var Transaction $transaction */
            $transaction = Transaction::query()->findOrFail($id);

            $this->gate->authorize('update', [$transaction]);

            $transaction->update([
                'category_id' => $updateTransactionDTO->categoryId,
                'account_id' => $updateTransactionDTO->accountId,
                'price' => $updateTransactionDTO->price,
            ]);

            return $transaction;
        });
    }
}
