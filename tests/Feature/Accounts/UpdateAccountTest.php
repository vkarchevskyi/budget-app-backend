<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can update an account name', function () {
    $account = Account::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/accounts/$account->id", [
        'name' => 'Card'
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $account->id)
            ->where('name', 'Card')
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->etc()
        );

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => 'Card',
    ]);
});

test('cannot update a non-existed account', function () {
    $response = $this->actingAs($this->user)->patchJson("/api/accounts/999");

    $response->assertNotFound();
});

test('cannot update an account of other user', function () {
    $account = Account::factory()->create();

    $response = $this->actingAs($this->user)->patchJson("/api/accounts/$account->id", [
        'name' => 'Card'
    ]);

    $response->assertForbidden();
});

test('unauthenticated user cannot update and account', function () {
    $account = Account::factory()->create();

    $response = $this->patchJson("/api/accounts/$account->id", [
        'name' => 'Card'
    ]);

    $response->assertUnauthorized();
});

test('cannot update an account with a very long name', function () {
    $account = Account::factory()->state([
        'user_id' => $this->user->id,
        'name' => 'Valid Name'
    ])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/accounts/$account->id", [
        'name' => Str::repeat('a', 255 + 1),
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => 'Valid Name', // Ensure name wasn't updated
    ]);
});

test('can update an account without changing the name', function () {
    $account = Account::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/accounts/$account->id", []);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $account->id)
            ->where('name', $account->name)
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->etc()
        );

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => $account->name,
    ]);
});
