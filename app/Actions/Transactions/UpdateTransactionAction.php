<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\DTO\Transactions\UpdateTransactionDTO;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class UpdateTransactionAction
{
    /**
     * @throws \Throwable
     */
    public function run(UpdateTransactionDTO $updateTransactionDTO): void
    {
        DB::transaction(function () use ($updateTransactionDTO): void {
            Transaction::query()
                ->create([
                    'category_id' => $updateTransactionDTO->categoryId,
                    'account_id' => $updateTransactionDTO->accountId,
                    'price' => $updateTransactionDTO->price,
                ]);
        });
    }
}
