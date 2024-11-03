<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('Authorized user can see their own category', function () {
    $category = Category::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->getJson("/api/categories/$category->id");

    $response->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $category->id)
            ->where('name', $category->name)
            ->where('is_income', false)
            ->where('user_id', $category->user_id)
            ->where('created_at', $category->created_at->toAtomString())
            ->where('updated_at', $category->updated_at->toAtomString())
        );
});

test('Unauthorized user cannot see category', function () {
    $category = Category::factory()->create();

    $response = $this->getJson("/api/categories/$category->id");

    $response->assertUnauthorized();
});

test('User cannot see category of other user', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->getJson("/api/categories/$category->id");

    $response->assertForbidden();
});

test('User cannot see a non-existed category', function () {
    $response = $this->actingAs($this->user)->getJson('/api/categories/999');

    $response->assertNotFound();
});
