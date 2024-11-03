<?php

use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can soft delete an category', function () {
    $category = Category::factory()->state(['user_id' => $this->user->id])->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/categories/$category->id");

    $response->assertSuccessful();

    $this->assertSoftDeleted('categories', [
        'id' => $category->id,
    ]);
});

test('cannot soft delete a non-existent category', function () {
    $response = $this->actingAs($this->user)->deleteJson('/api/categories/999');

    $response->assertNotFound();
});

test('cannot soft delete an category of other user', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->deleteJson("/api/categories/$category->id");

    $response->assertForbidden();
});

test('unauthenticated user cannot soft delete an category', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson("/api/categories/$category->id");

    $response->assertUnauthorized();
});
