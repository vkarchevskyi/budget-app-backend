<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Accounts\CreateAccountAction;
use App\Actions\Accounts\UpdateAccountAction;
use App\DTO\Accounts\CreateAccountDTO;
use App\DTO\Accounts\UpdateAccountDTO;
use App\Http\Requests\Accounts\CreateAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Models\Account;
use App\Resources\Accounts\AccountResource;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AccountController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(CreateAccountRequest $request, CreateAccountAction $createAccountAction): AccountResource
    {
        Gate::authorize('create', Account::class);

        $data = CreateAccountDTO::from([
            'name' => $request->str('name')->toString(),
            'userId' => auth()->id(),
        ]);

        return AccountResource::from($createAccountAction->run($data));
    }

    /**
     * @throws Throwable
     */
    public function update(
        Account $account,
        UpdateAccountRequest $request,
        UpdateAccountAction $updateAccountAction
    ): AccountResource {
        Gate::authorize('update', $account);

        $data = UpdateAccountDTO::from($request);

        return AccountResource::from($updateAccountAction->run($account, $data));
    }
}
