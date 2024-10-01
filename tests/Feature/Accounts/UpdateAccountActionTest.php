<?php

use App\Actions\Accounts\UpdateAccountAction;
use App\DTO\Accounts\UpdateAccountDTO;
use App\Models\Account;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('can update an account name', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'name' => 'Old Name',
        'user_id' => $user->id,
    ]);

    $updateAccountDTO = UpdateAccountDTO::from([
        'name' => 'New Name',
    ]);

    $action = new UpdateAccountAction();
    $updatedAccount = $action->run($account, $updateAccountDTO);

    expect($updatedAccount)
        ->toBeInstanceOf(Account::class)
        ->and($updatedAccount->name)
        ->toBe('New Name');

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => 'New Name',
    ]);
});

test('cannot update an account with a very long name', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'name' => 'Valid Name',
        'user_id' => $user->id,
    ]);

    $updateAccountDTO = UpdateAccountDTO::from([
        'name' => str_repeat('a', 256), // Name longer than 255 characters
    ]);

    $action = new UpdateAccountAction();

    $this->expectException(ValidationException::class);
    $action->run($account, $updateAccountDTO);

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => 'Valid Name', // Ensure name wasn't updated
    ]);
});

test('can update an account without changing the name', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'name' => 'Existing Name',
        'user_id' => $user->id,
    ]);

    // No name provided in the DTO, account name should remain unchanged
    $updateAccountDTO = UpdateAccountDTO::from([]);

    $action = new UpdateAccountAction();
    $updatedAccount = $action->run($account, $updateAccountDTO);

    expect($updatedAccount->name)->toBe('Existing Name');
});
