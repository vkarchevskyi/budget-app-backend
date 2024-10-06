<?php

declare(strict_types=1);

namespace App\Actions\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

readonly class DeleteTransactionAction
{
    public function run(Transaction $transaction): bool
    {
        return DB::transaction(fn (): bool => (bool) $transaction->delete());
    }
}
