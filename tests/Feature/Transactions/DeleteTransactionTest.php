<?php

use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can soft delete an transaction', function () {
    $transaction = Transaction::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/transactions/$transaction->id");

    $response->assertSuccessful();

    $this->assertSoftDeleted('transactions', [
        'id' => $transaction->id,
    ]);
});

test('cannot soft delete a non-existent transaction', function () {
    $response = $this->actingAs($this->user)->deleteJson('/api/transactions/999');

    $response->assertNotFound();
});

test('cannot soft delete an transaction of other user', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/transactions/$transaction->id");

    $response->assertForbidden();
});

test('unauthenticated user cannot soft delete an transaction', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->deleteJson("/api/transactions/$transaction->id");

    $response->assertUnauthorized();
});
