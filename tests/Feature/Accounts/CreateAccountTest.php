<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can create a new account', function () {
    $response = $this->actingAs($this->user)->postJson('/api/accounts', [
        'name' => 'Cash',
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('name', 'Cash')
            ->where('balance', 0)
            ->hasAll(['id', 'created_at', 'updated_at'])
            ->etc()
        );
});

test('can create a new account with a maximum name length', function () {
    $name = Str::repeat('a', 255);

    $response = $this->actingAs($this->user)->postJson('/api/accounts', [
        'name' => $name,
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('name', $name)
            ->where('balance', 0)
            ->hasAll(['id', 'created_at', 'updated_at'])
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
    $response = $this->actingAs($this->user)->postJson('/api/accounts', []);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field is required.',
        ]);
});

test('cannot create a new account with very long name', function () {
    $name = Str::repeat('a', 255 + 1);

    $response = $this->actingAs($this->user)->postJson('/api/accounts', [
        'name' => $name,
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);
});
