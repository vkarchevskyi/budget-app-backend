<?php

use App\Actions\Categories\CreateCategoryAction;
use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('can create a new category', function () {
    $user = User::factory()->create();

    $createCategoryDTO = CreateCategoryDTO::from([
        'name' => 'Groceries',
        'user_id' => $user->id
    ]);

    $action = new CreateCategoryAction();
    $category = $action->run($createCategoryDTO);

    expect($category)
        ->toBeInstanceOf(Category::class)
        ->and($category->name)
        ->toBe('Groceries')
        ->and($category->user_id)
        ->toBe($user->id);

    $this->assertDatabaseHas('categories', [
        'name' => 'Groceries',
        'user_id' => $user->id,
    ]);
});

test('can create a new category with a maximum name length', function () {
    $user = User::factory()->create();

    $createCategoryDTO = CreateCategoryDTO::from([
        'name' => str_repeat('a', 255),
        'user_id' => $user->id,
    ]);

    $category = (new CreateCategoryAction())->run($createCategoryDTO);

    expect($category->name)->toBe(str_repeat('a', 255));
});

test('cannot create a new category for non existed user', function () {
    $createCategoryDTO = CreateCategoryDTO::from([
        'name' => 'Groceries',
        'user_id' => 1
    ]);

    (new CreateCategoryAction())->run($createCategoryDTO);
})->throws(ValidationException::class);

test('cannot create a new category without a user_id', function () {
    $createCategoryDTO = CreateCategoryDTO::from([
        'name' => 'Groceries',
    ]);

    (new CreateCategoryAction())->run($createCategoryDTO);
})->throws(ValidationException::class, 'The user id field is required.');

test('cannot create a new category without a name', function () {
    $createCategoryDTO = CreateCategoryDTO::from([
        'user_id' => 1,
    ]);

    (new CreateCategoryAction())->run($createCategoryDTO);
})->throws(ValidationException::class, 'The name field is required.');

test('cannot create a new categor with very long name', function () {
    $user = User::factory()->create();

    $createCategoryDTO = CreateCategoryDTO::from([
        'name' => str_repeat('a', 255 + 1),
        'user_id' => $user->id,
    ]);

    (new CreateCategoryAction())->run($createCategoryDTO);
})->throws(ValidationException::class, 'The name field must not be greater than 255 characters.');
