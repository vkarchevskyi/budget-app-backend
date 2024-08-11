<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Transactions\CreateTransactionAction;
use App\Actions\Transactions\UpdateTransactionAction;
use App\DTO\Transactions\CreateTransactionDTO;
use App\DTO\Transactions\UpdateTransactionDTO;
use App\Http\Requests\Transactions\CreateTransactionRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use App\Resources\Transactions\TransactionResource;
use Throwable;

class TransactionController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(
        CreateTransactionRequest $request,
        CreateTransactionAction $createTransactionAction
    ): TransactionResource {
        return TransactionResource::from($createTransactionAction->run(CreateTransactionDTO::from($request)));
    }

    /**
     * @throws Throwable
     */
    public function update(
        int $id,
        UpdateTransactionRequest $request,
        UpdateTransactionAction $updateTransactionAction
    ): TransactionResource {
        return TransactionResource::from($updateTransactionAction->run($id, UpdateTransactionDTO::from($request)));
    }
}
