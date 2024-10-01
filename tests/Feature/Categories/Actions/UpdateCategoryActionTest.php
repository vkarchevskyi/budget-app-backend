<?php

use App\Actions\Categories\UpdateCategoryAction;
use App\DTO\Categories\UpdateCategoryDTO;
use App\Models\Category;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('can update an category name', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'user_id' => $user->id,
    ]);

    $updateCategoryDTO = UpdateCategoryDTO::from([
        'name' => 'New Name',
    ]);

    $action = new UpdateCategoryAction();
    $updatedCategory = $action->run($category, $updateCategoryDTO);

    expect($updatedCategory)
        ->toBeInstanceOf(Category::class)
        ->and($updatedCategory->name)
        ->toBe('New Name');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
    ]);
});

test('cannot update an category with a very long name', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create([
        'name' => 'Valid Name',
        'user_id' => $user->id,
    ]);

    $updateCategoryDTO = UpdateCategoryDTO::from([
        'name' => str_repeat('a', 256), // Name longer than 255 characters
    ]);

    $action = new UpdateCategoryAction();

    $this->expectException(ValidationException::class);
    $action->run($category, $updateCategoryDTO);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Valid Name', // Ensure name wasn't updated
    ]);
});

test('can update an category without changing the name', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create([
        'name' => 'Existing Name',
        'user_id' => $user->id,
    ]);

    // No name provided in the DTO, category name should remain unchanged
    $updateCategoryDTO = UpdateCategoryDTO::from([]);

    $action = new UpdateCategoryAction();
    $updatedCategory = $action->run($category, $updateCategoryDTO);

    expect($updatedCategory->name)->toBe('Existing Name');
});
