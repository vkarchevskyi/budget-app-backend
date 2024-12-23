<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->appUrl = env('APP_URL');
});

test('Unauthorized user cannot receive accounts', function () {
    $response = $this->getJson('/api/accounts');

    $response->assertUnauthorized();
});

test('Test default pagination settings', function () {
    Account::factory()->state(['user_id' => $this->user->id])->count(20)->create();
    $response = $this->actingAs($this->user)->getJson('/api/accounts');

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('current_page', 1)
            ->where('first_page_url', $this->appUrl . '/api/accounts?page=1')
            ->where('from', 1)
            ->where('last_page', 2)
            ->where('last_page_url', $this->appUrl . '/api/accounts?page=2')
            ->where('next_page_url', $this->appUrl . '/api/accounts?page=2')
            ->where('path', $this->appUrl . '/api/accounts')
            ->where('per_page', 15)
            ->where('prev_page_url', null)
            ->where('to', 15)
            ->where('total', 20)
            ->has('links', 4)
            ->has('data', 15, fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'balance', 'created_at', 'updated_at'])
                ->etc()
            )
        );
});

test('If page is provided, it must be an integer', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?page=abc');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['page']);
});

test('If page is provided, it must be an integer greater than or equal to 1', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?page=0');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['page']);
});

test('If page is less than 1, an error should be returned', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?page=0');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['page']);
});

test('If per_page is provided, it must be an integer', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?per_page=abc');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['per_page']);
});

test('If per_page is provided, it must be an integer greater than or equal to 1', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?per_page=0');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['per_page']);
});

test('If per_page exceeds 100, an error should be returned', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?per_page=101');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['per_page']);
});

test('If per_page is less than 1, an error should be returned', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?per_page=0');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['per_page']);
});

test('If no search term is provided, all accounts should be returned', function () {
    Account::factory()->state(['user_id' => $this->user->id])->count(20)->create();
    $response = $this->actingAs($this->user)->getJson('/api/accounts');

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 15)
            ->has('links')
            ->etc()
        );
});

test('If search exceeds 255 characters, an error should be returned', function () {
    $longString = str_repeat('a', 256);
    $response = $this->actingAs($this->user)->getJson("/api/accounts?search={$longString}");
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['search']);
});

test('If sort_by is provided, it must be one of id, name, created_at, or balance', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?sort_by=invalid_field');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_by']);
});

test('If an invalid sort_by value is provided, an error should be returned', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?sort_by=invalid_field');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_by']);
});

test('If sort_order is provided, it must be either asc or desc', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?sort_order=invalid_order');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_order']);
});

test('If an invalid sort_order is provided, an error should be returned', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts?sort_order=invalid_order');
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_order']);
});

test('If sort_by is provided without sort_order, it should default to ascending order', function () {
    Account::factory()->state(['user_id' => $this->user->id])->count(20)->create();
    $response = $this->actingAs($this->user)->getJson('/api/accounts?sort_by=name');

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 15)
            ->has('links')
            ->etc()
        );
});

test(
    'If all parameters are provided with valid values, the request should return the filtered and sorted list of accounts',
    function () {
        Account::factory()->state(['user_id' => $this->user->id, 'name' => 'test'])->count(20)->create();
        $response = $this->actingAs($this->user)->getJson(
            '/api/accounts?page=1&per_page=10&search=test&sort_by=name&sort_order=asc'
        );

        $response->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('current_page', 1)
                ->where('per_page', 10)
                ->has('data', 10)
                ->has('links')
                ->etc()
            );
    }
);

test(
    'If all parameters are invalid, the request should return a validation error for each invalid field.',
    function () {
        $name = Str::repeat('a', 256);
        $response = $this->actingAs($this->user)->getJson(
            "/api/accounts?page=abc&per_page=0&search=$name&sort_by=invalid_field&sort_order=invalid_order"
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page', 'per_page', 'search', 'sort_by', 'sort_order']);
    }
);
