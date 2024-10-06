<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

test('can create a new account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/accounts', [
        'name' => 'Cash',
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', 1)
            ->where('name', 'Cash')
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );
});

test('can create a new account with a maximum name length', function () {
    $user = User::factory()->create();
    $name = Str::repeat('a', 255);

    $response = $this->actingAs($user)->postJson('/api/accounts', [
        'name' => $name,
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', 1)
            ->where('name', $name)
            ->where('balance', 0)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );
});

test('cannot create a new account for non unauthenticated user', function () {
    $response = $this->postJson('/api/accounts', [
        'name' => 'Cash',
    ]);

    $response->assertUnauthorized();
});

test('cannot create a new account without a name', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->postJson('/api/accounts', []);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field is required.',
        ]);
});

test('cannot create a new account with very long name', function () {
    $user = User::factory()->create();
    $name = Str::repeat('a', 255 + 1);

    $response = $this->actingAs($user)->postJson('/api/accounts', [
        'name' => $name,
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);
});
