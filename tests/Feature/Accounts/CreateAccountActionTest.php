<?php

use App\Actions\Accounts\CreateAccountAction;
use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use App\Models\User;

test('can create an new account', function () {
    $user = User::factory()->create();

    $createAccountDTO = CreateAccountDTO::from([
        'name' => 'Cash',
        'user_id' => $user->id
    ]);

    $action = new CreateAccountAction();
    $account = $action->run($createAccountDTO);

    expect($account)
        ->toBeInstanceOf(Account::class)
        ->and($account->name)
        ->toBe('Cash')
        ->and($account->balance)
        ->toBe(0)
        ->and($account->user_id)
        ->toBe(1);

    $this->assertDatabaseHas('accounts', [
        'name' => 'Cash',
        'balance' => 0,
        'user_id' => $user->id,
    ]);
});
