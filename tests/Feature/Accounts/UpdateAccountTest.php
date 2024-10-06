<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

test('can update an account name', function () {
    $user = User::factory()->create();
    $account = Account::factory()->state(['user_id' => $user->id])->create();

    $response = $this->actingAs($user)->patchJson("/api/accounts/$account->id", [
        'name' => 'Card'
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $account->id)
            ->where('name', 'Card')
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => 'Card',
    ]);
});

test('cannot update an account of other user', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $response = $this->actingAs($user)->patchJson("/api/accounts/$account->id", [
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
    $user = User::factory()->create();
    $account = Account::factory()->state([
        'user_id' => $user->id,
        'name' => 'Valid Name'
    ])->create();

    $response = $this->actingAs($user)->patchJson("/api/accounts/$account->id", [
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
    $user = User::factory()->create();
    $account = Account::factory()->state(['user_id' => $user->id])->create();

    $response = $this->actingAs($user)->patchJson("/api/accounts/$account->id", []);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $account->id)
            ->where('name', $account->name)
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'name' => $account->name,
    ]);
});
