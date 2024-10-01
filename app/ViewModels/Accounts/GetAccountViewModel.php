<?php

declare(strict_types=1);

namespace App\ViewModels\Accounts;

use App\Models\Account;
use App\Resources\Accounts\AccountResource;

class GetAccountViewModel
{
    public function get(Account $account): AccountResource
    {
        return AccountResource::from($account);
    }
}
