<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Accounts\CreateAccountAction;
use App\Actions\Accounts\DeleteAccountAction;
use App\Actions\Accounts\UpdateAccountAction;
use App\DTO\Accounts\CreateAccountDTO;
use App\DTO\Accounts\IndexAccountDTO;
use App\DTO\Accounts\UpdateAccountDTO;
use App\Http\Requests\Accounts\CreateAccountRequest;
use App\Http\Requests\Accounts\IndexAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Models\Account;
use App\Resources\Accounts\AccountResource;
use App\ViewModels\Accounts\GetAccountViewModel;
use App\ViewModels\Accounts\PaginateAccountsViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @param IndexAccountRequest $request
     * @param PaginateAccountsViewModel $paginateAccountsViewModel
     * @return LengthAwarePaginator<AccountResource>
     */
    public function index(
        IndexAccountRequest $request,
        PaginateAccountsViewModel $paginateAccountsViewModel
    ): LengthAwarePaginator {
        Gate::authorize('viewAny', Account::class);

        $data = IndexAccountDTO::from([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return $paginateAccountsViewModel->get($data);
    }

    public function store(
        CreateAccountRequest $request,
        CreateAccountAction $createAccountAction
    ): AccountResource {
        Gate::authorize('create', Account::class);

        $data = CreateAccountDTO::from([
            'name' => $request->str('name')->toString(),
            'userId' => auth()->id(),
        ]);

        return AccountResource::from($createAccountAction->run($data));
    }

    public function show(Account $account, GetAccountViewModel $getAccountViewModel): AccountResource
    {
        Gate::authorize('view', $account);

        return $getAccountViewModel->get($account);
    }

    public function update(
        Account $account,
        UpdateAccountRequest $request,
        UpdateAccountAction $updateAccountAction
    ): AccountResource {
        Gate::authorize('update', $account);

        $data = UpdateAccountDTO::from($request);

        return AccountResource::from(
            $updateAccountAction->run($account, $data)
        );
    }

    public function delete(Account $account, DeleteAccountAction $deleteAccountAction): JsonResponse
    {
        Gate::authorize('delete', $account);

        if ($deleteAccountAction->run($account)) {
            return response()->json(['message' => 'Account deleted successfully.'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Failed to delete account.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
