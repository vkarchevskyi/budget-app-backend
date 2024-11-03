<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->account = Account::factory()->create(['user_id' => $this->user->id]);
    $this->category = Category::factory()->create(['user_id' => $this->user->id]);
    $this->transaction = Transaction::factory()->create([
        'user_id' => $this->user->id,
        'account_id' => $this->account->id,
        'category_id' => $this->category->id,
    ]);
});

test('can update a transaction', function () {
    $response = $this->actingAs($this->user)->patchJson("/api/transactions/{$this->transaction->id}", [
        'name' => 'Updated name',
        'account_id' => $this->account->id,
        'category_id' => $this->category->id,
        'price' => 2000,
        'date' => '2024-08-12T18:00:00+02:00',
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('name', 'Updated name')
            ->where('user_id', $this->user->id)
            ->where('account_id', $this->account->id)
            ->where('category_id', $this->category->id)
            ->where('price', 2000)
            ->where('date', '2024-08-12T16:00:00+00:00')
            ->hasAll(['id', 'created_at', 'updated_at'])
            ->etc()
        );
});

test('cannot update a transaction for non unauthenticated user', function () {
    $response = $this->patchJson("/api/transactions/{$this->transaction->id}", [
        'name' => 'Updated name',
        'account_id' => $this->account->id,
        'category_id' => $this->category->id,
        'price' => 2000,
        'date' => '2024-08-12T18:00:00+02:00',
    ]);

    $response->assertUnauthorized();
});

test('cannot update a transaction with category of other user', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->patchJson("/api/transactions/{$this->transaction->id}", [
        'name' => 'Updated name',
        'account_id' => $this->account->id,
        'category_id' => $category->id,
        'price' => 2000,
        'date' => '2024-08-12T18:00:00+02:00',
    ]);

    $response->assertForbidden();
});

test('cannot update a transaction with account of other user', function () {
    $account = Account::factory()->create();

    $response = $this->actingAs($this->user)->patchJson("/api/transactions/{$this->transaction->id}", [
        'name' => 'Updated name',
        'account_id' => $account->id,
        'category_id' => $this->category->id,
        'price' => 2000,
        'date' => '2024-08-12T18:00:00+02:00',
    ]);

    $response->assertForbidden();
});

test('cannot update a transaction with empty required fields', function () {
    $response = $this->actingAs($this->user)->patchJson("/api/transactions/{$this->transaction->id}", [
        'name' => '',
        'category_id' => '',
        'account_id' => '',
        'price' => '',
        'date' => '',
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field is required.',
            'category_id' => 'The category id field is required.',
            'account_id' => 'The account id field is required.',
            'price' => 'The price field is required.',
            'date' => 'The date field is required.',
        ]);
});
