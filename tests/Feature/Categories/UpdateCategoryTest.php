<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can update an category name', function () {
    $category = Category::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/categories/$category->id", [
        'name' => 'Groceries'
    ]);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $category->id)
            ->where('name', 'Groceries')
            ->where('is_income', false)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Groceries',
    ]);
});

test('cannot update a non-existed category', function () {
    $response = $this->actingAs($this->user)->patchJson("/api/categories/999");

    $response->assertNotFound();
});

test('cannot update an category of other user', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->patchJson("/api/categories/$category->id", [
        'name' => 'Groceries'
    ]);

    $response->assertForbidden();
});

test('unauthenticated user cannot update and category', function () {
    $category = Category::factory()->create();

    $response = $this->patchJson("/api/categories/$category->id", [
        'name' => 'Groceries'
    ]);

    $response->assertUnauthorized();
});

test('cannot update an category with a very long name', function () {
    $category = Category::factory()->state([
        'user_id' => $this->user->id,
        'name' => 'Valid Name'
    ])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/categories/$category->id", [
        'name' => Str::repeat('a', 255 + 1),
    ]);

    $response
        ->assertStatus(422)
        ->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Valid Name', // Ensure name wasn't updated
    ]);
});

test('can update an category without changing the name', function () {
    $category = Category::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->patchJson("/api/categories/$category->id", []);

    $response
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $category->id)
            ->where('name', $category->name)
            ->where('is_income', false)
            ->hasAll(['created_at', 'updated_at'])
            ->where('deleted_at', null)
            ->etc()
        );

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => $category->name,
    ]);
});
