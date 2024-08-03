<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Categories\CreateCategoryAction;
use App\Actions\Categories\UpdateCategoryAction;
use App\DTO\Categories\CreateCategoryDTO;
use App\DTO\Categories\UpdateCategoryDTO;
use App\Http\Requests\Accounts\CreateAccountRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Resources\Categories\CategoryResource;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(CreateAccountRequest $request, CreateCategoryAction $createCategoryAction): CategoryResource
    {
        return CategoryResource::from($createCategoryAction->run(CreateCategoryDTO::from($request)));
    }

    /**
     * @throws Throwable
     */
    public function update(
        int $id,
        UpdateCategoryRequest $request,
        UpdateCategoryAction $updateCategoryAction
    ): CategoryResource {
        return CategoryResource::from($updateCategoryAction->run($id, UpdateCategoryDTO::from($request)));
    }
}
