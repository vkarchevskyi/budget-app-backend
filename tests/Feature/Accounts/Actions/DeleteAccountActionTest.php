<?php

use App\Actions\Accounts\DeleteAccountAction;
use App\Models\Account;
use App\Models\User;

test('can soft delete an account', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'name' => 'Account to Soft Delete',
        'user_id' => $user->id,
    ]);

    $action = new DeleteAccountAction();
    $result = $action->run($account);

    // Check that the action returned true
    expect($result)->toBeTrue();

    // Ensure the account is soft deleted (deleted_at is not null)
    $this->assertSoftDeleted('accounts', [
        'id' => $account->id,
    ]);
});

test('cannot soft delete a non-existent account', function () {
    $user = User::factory()->create();
    $account = Account::factory()->make([
        'id' => 999, // Non-existent ID
        'user_id' => $user->id,
    ]);

    $action = new DeleteAccountAction();
    $result = $action->run($account);

    // Since soft deletes won't remove the account entirely, check for a false result
    expect($result)->toBeFalse();
});
