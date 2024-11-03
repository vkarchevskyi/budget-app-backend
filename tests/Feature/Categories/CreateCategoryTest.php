<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can create a new category', function () {
    $response = $this->actingAs($this->user)->postJson('/api/categories', [
        'name' => 'Groceries',
        'is_income' => true,
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('name', 'Groceries')
            ->where('is_income', true)
            ->hasAll(['id', 'created_at', 'updated_at'])
            ->etc()
        );
});

test('can create a new category with a maximum name length', function () {
    $name = Str::repeat('a', 255);

    $response = $this->actingAs($this->user)->postJson('/api/categories', [
        'name' => $name,
        'is_income' => true,
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('name', $name)
            ->where('is_income', true)
            ->hasAll(['id', 'created_at', 'updated_at'])
            ->etc()
        );
});

test('cannot create a new category for non unauthenticated user', function () {
    $response = $this->postJson('/api/categories', [
        'name' => 'Cash',
    ]);

    $response->assertUnauthorized();
});

test('cannot create a new category without a name', function () {
    $response = $this->actingAs($this->user)->postJson('/api/categories', []);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field is required.',
        ]);
});

test('cannot create a new category with very long name', function () {
    $name = Str::repeat('a', 255 + 1);

    $response = $this->actingAs($this->user)->postJson('/api/categories', [
        'name' => $name,
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);
});

test('cannot create a new category without an is_income flag', function () {
    $response = $this->actingAs($this->user)->postJson('/api/categories', [
        'name' => Str::random(),
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'is_income' => 'The is income field is required.',
        ]);
});

test('cannot create a new category with an is_income flag with non-boolean value', function () {
    $response = $this->actingAs($this->user)->postJson('/api/categories', [
        'name' => Str::random(),
        'is_income' => 123,
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'is_income' => 'The is income field must be true or false.',
        ]);
});
