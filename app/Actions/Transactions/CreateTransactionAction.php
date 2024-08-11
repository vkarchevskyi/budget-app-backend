<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\CreateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateTransactionAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(CreateTransactionDTO $createTransactionDTO): Transaction
    {
        return DB::transaction(function () use ($createTransactionDTO): Transaction {
            /** @var Transaction $transaction */
            $transaction = Transaction::query()->make([
                'category_id' => $createTransactionDTO->categoryId,
                'user_id' => $createTransactionDTO->userId,
                'account_id' => $createTransactionDTO->accountId,
                'price' => $createTransactionDTO->price,
            ])->load(['category', 'account']);

            $this->gate->authorize('create', [$transaction, $transaction->category, $transaction->account]);

            $transaction->save();
            return $transaction;
        });
    }
}
