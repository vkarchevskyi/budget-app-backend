<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Transactions\CreateTransactionAction;
use App\Actions\Transactions\DeleteTransactionAction;
use App\Actions\Transactions\UpdateTransactionAction;
use App\DTO\Transactions\CreateTransactionDTO;
use App\DTO\Transactions\IndexTransactionDTO;
use App\DTO\Transactions\UpdateTransactionDTO;
use App\Http\Requests\Transactions\CreateTransactionRequest;
use App\Http\Requests\Transactions\IndexTransactionRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Resources\Transactions\TransactionResource;
use App\ViewModels\Transactions\GetTransactionViewModel;
use App\ViewModels\Transactions\PaginateTransactionsViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * @param IndexTransactionRequest $request
     * @param PaginateTransactionsViewModel $paginateTransactionsViewModel
     * @return LengthAwarePaginator<TransactionResource>
     */
    public function index(
        IndexTransactionRequest $request,
        PaginateTransactionsViewModel $paginateTransactionsViewModel
    ): LengthAwarePaginator {
        Gate::authorize('viewAny', Transaction::class);

        $data = IndexTransactionDTO::from($request->all());
        $data->userId = auth()->id();

        return $paginateTransactionsViewModel->get($data);
    }

    public function store(
        CreateTransactionRequest $request,
        CreateTransactionAction $createTransactionAction
    ): TransactionResource {
        $data = CreateTransactionDTO::from($request->all());
        $data->userId = auth()->id();

        Gate::authorize(
            'create',
            [
                Transaction::class,
                Category::query()->findOrFail($data->categoryId),
                Account::query()->findOrFail($data->accountId),
            ]
        );

        return TransactionResource::from($createTransactionAction->run($data));
    }

    public function show(
        Transaction $transaction,
        GetTransactionViewModel $getTransactionViewModel
    ): TransactionResource {
        Gate::authorize('view', $transaction);

        return $getTransactionViewModel->get($transaction);
    }

    public function update(
        int $id,
        UpdateTransactionRequest $request,
        UpdateTransactionAction $updateTransactionAction
    ): TransactionResource {
        return TransactionResource::from($updateTransactionAction->run($id, UpdateTransactionDTO::from($request)));
    }
}
