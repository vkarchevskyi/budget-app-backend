<?php

use App\Models\Account;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can soft delete an account', function () {
    $account = Account::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/accounts/$account->id");

    $response->assertSuccessful();

    $this->assertSoftDeleted('accounts', [
        'id' => $account->id,
    ]);
});

test('cannot soft delete a non-existent account', function () {
    $response = $this->actingAs($this->user)->deleteJson('/api/accounts/999');

    $response->assertNotFound();
});

test('cannot soft delete an account of other user', function () {
    $account = Account::factory()->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/accounts/$account->id");

    $response->assertForbidden();
});

test('unauthenticated user cannot soft delete an account', function () {
    $account = Account::factory()->create();

    $response = $this->deleteJson("/api/accounts/$account->id");

    $response->assertUnauthorized();
});
