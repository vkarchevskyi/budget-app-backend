<?php

declare(strict_types=1);

namespace App\ViewModels\Transactions;

use App\DTO\Transactions\IndexTransactionDTO;
use App\Models\Transaction;
use App\Resources\Transactions\TransactionResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Optional;

readonly class PaginateTransactionsViewModel
{
    /**
     * @param IndexTransactionDTO $paginateDTO
     * @return LengthAwarePaginator<TransactionResource>
     */
    public function get(IndexTransactionDTO $paginateDTO): LengthAwarePaginator
    {
        $query = Transaction::query();

        if (!($paginateDTO->search instanceof Optional)) {
            $query->where('name', 'ilike', "%$paginateDTO->search%")
                ->orWhere('description', 'ilike', "%$paginateDTO->search%");
        }

        $query->where("user_id", $paginateDTO->userId)
            ->orderBy($paginateDTO->sortBy, $paginateDTO->sortOrder);

        $paginatedData = $query->paginate($paginateDTO->perPage, ["*"], "page", $paginateDTO->page);

        /** @var LengthAwarePaginator<TransactionResource> $paginatedResource */
        $paginatedResource = TransactionResource::collect($paginatedData, LengthAwarePaginator::class);

        return $paginatedResource;
    }
}
