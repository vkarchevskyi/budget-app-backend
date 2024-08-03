<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Accounts\CreateAccountAction;
use App\Actions\Accounts\UpdateAccountAction;
use App\DTO\Accounts\CreateAccountDTO;
use App\DTO\Accounts\UpdateAccountDTO;
use App\Http\Requests\Accounts\CreateAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Resources\Accounts\AccountResource;
use Throwable;

class AccountController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(CreateAccountRequest $request, CreateAccountAction $createAccountAction): AccountResource
    {
        return AccountResource::from($createAccountAction->run(CreateAccountDTO::from($request)));
    }

    /**
     * @throws Throwable
     */
    public function update(
        int $id,
        UpdateAccountRequest $request,
        UpdateAccountAction $updateAccountAction
    ): AccountResource {
        return AccountResource::from($updateAccountAction->run($id, UpdateAccountDTO::from($request)));
    }
}
