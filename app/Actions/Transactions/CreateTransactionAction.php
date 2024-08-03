<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\CreateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateTransactionAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateTransactionDTO $createTransactionDTO): void
    {
        DB::transaction(function () use ($createTransactionDTO): void {
            Transaction::query()
                ->create([
                    'category_id' => $createTransactionDTO->categoryId,
                    'user_id' => $createTransactionDTO->userId,
                    'account_id' => $createTransactionDTO->accountId,
                    'price' => $createTransactionDTO->price,
                ]);
        });
    }
}
