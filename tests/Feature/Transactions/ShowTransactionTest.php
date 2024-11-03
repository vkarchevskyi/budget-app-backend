<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('Authorized user can see their own transaction', function () {
    $transaction = Transaction::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->getJson("/api/transactions/$transaction->id");

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $transaction->id)
            ->where('name', $transaction->name)
            ->where('description', $transaction->description)
            ->where('account_id', $transaction->account_id)
            ->where('category_id', $transaction->category_id)
            ->where('user_id', $transaction->user_id)
            ->where('price', $transaction->price)
            ->where('date', $transaction->date->toAtomString())
            ->where('created_at', $transaction->created_at->toAtomString())
            ->where('updated_at', $transaction->updated_at->toAtomString())
        );
});

test('Unauthorized user cannot see transaction', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->getJson("/api/transactions/$transaction->id");

    $response->assertUnauthorized();
});

test('User cannot see transaction of other user', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->actingAs($this->user)->getJson("/api/transactions/$transaction->id");

    $response->assertForbidden();
});

test('User cannot see a non-existed transaction', function () {
    $response = $this->actingAs($this->user)->getJson('/api/transactions/999');

    $response->assertNotFound();
});
