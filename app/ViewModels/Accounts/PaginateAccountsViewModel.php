<?php

declare(strict_types=1);

namespace App\ViewModels\Accounts;

use App\DTO\Accounts\IndexAccountDTO;
use App\Models\Account;
use App\Resources\Accounts\AccountResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Optional;

readonly class PaginateAccountsViewModel
{
    /**
     * @param IndexAccountDTO $paginateDTO
     * @return LengthAwarePaginator<AccountResource>
     */
    public function get(IndexAccountDTO $paginateDTO): LengthAwarePaginator
    {
        $query = Account::query();

        if (!($paginateDTO->search instanceof Optional)) {
            $query->where('name', 'ilike', "%$paginateDTO->search%");
        }

        $query->where("user_id", $paginateDTO->userId)
            ->orderBy($paginateDTO->sortBy, $paginateDTO->sortOrder);

        $paginatedData = $query->paginate($paginateDTO->perPage, ["*"], "page", $paginateDTO->page);

        /** @var LengthAwarePaginator<AccountResource> $paginatedResource */
        $paginatedResource = AccountResource::collect($paginatedData, LengthAwarePaginator::class);

        return $paginatedResource;
    }
}
