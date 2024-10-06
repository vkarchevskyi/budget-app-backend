<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('Authorized user can see their own account', function () {
    $account = Account::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->getJson("/api/accounts/$account->id");

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $account->id)
            ->where('name', $account->name)
            ->where('balance', 0)
            ->where('created_at', $account->created_at->toAtomString())
            ->where('updated_at', $account->updated_at->toAtomString())
            ->where('deleted_at', null)
        );
});

test('Unauthorized user cannot see account', function () {
    $account = Account::factory()->create();

    $response = $this->getJson("/api/accounts/$account->id");

    $response->assertUnauthorized();
});

test('User cannot see account of other user', function () {
    $account = Account::factory()->create();

    $response = $this->actingAs($this->user)->getJson("/api/accounts/$account->id");

    $response->assertForbidden();
});

test('User cannot see a non-existed account', function () {
    $response = $this->actingAs($this->user)->getJson('/api/accounts/999');

    $response->assertNotFound();
});
