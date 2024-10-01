<?php

use App\Actions\Accounts\CreateAccountAction;
use App\DTO\Accounts\CreateAccountDTO;
use App\Models\Account;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('can create a new account', function () {
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

test('cannot create a new account for non existed user', function () {
    $createAccountDTO = CreateAccountDTO::from([
        'name' => 'Cash',
        'user_id' => 1
    ]);

    (new CreateAccountAction())->run($createAccountDTO);
})->throws(ValidationException::class);

test('cannot create a new account without a user_id', function () {
    $createAccountDTO = CreateAccountDTO::from([
        'name' => 'Cash',
    ]);

    (new CreateAccountAction())->run($createAccountDTO);
})->throws(ValidationException::class);

test('cannot create a new account without a name', function () {
    $createAccountDTO = CreateAccountDTO::from([
        'user_id' => 1,
    ]);

    (new CreateAccountAction())->run($createAccountDTO);
})->throws(ValidationException::class);

test('cannot create a new account with very long name', function () {
    $user = User::factory()->create();

    $createAccountDTO = CreateAccountDTO::from([
        'name' => str_repeat('a', 255 + 1),
        'user_id' => $user->id,
    ]);

    (new CreateAccountAction())->run($createAccountDTO);
})->throws(ValidationException::class);
