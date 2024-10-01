<?php

declare(strict_types=1);

namespace App\ViewModels\Transactions;

use App\Models\Transaction;
use App\Resources\Transactions\TransactionResource;

readonly class GetTransactionViewModel
{
    public function get(Transaction $transaction): TransactionResource
    {
        return TransactionResource::from($transaction);
    }
}
