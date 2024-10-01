<?php

use App\Actions\Categories\DeleteCategoryAction;
use App\Models\Category;
use App\Models\User;

test('can soft delete an category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create([
        'name' => 'Category to Soft Delete',
        'user_id' => $user->id,
    ]);

    $action = new DeleteCategoryAction();
    $result = $action->run($category);

    // Check that the action returned true
    expect($result)->toBeTrue();

    // Ensure the category is soft deleted (deleted_at is not null)
    $this->assertSoftDeleted('categories', [
        'id' => $category->id,
    ]);
});

test('cannot soft delete a non-existent category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->make([
        'id' => 999, // Non-existent ID
        'user_id' => $user->id,
    ]);

    $action = new DeleteCategoryAction();
    $result = $action->run($category);

    // Since soft deletes won't remove the category entirely, check for a false result
    expect($result)->toBeFalse();
});
