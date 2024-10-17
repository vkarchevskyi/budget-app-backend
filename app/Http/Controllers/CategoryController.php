<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Categories\CreateCategoryAction;
use App\Actions\Categories\DeleteCategoryAction;
use App\Actions\Categories\UpdateCategoryAction;
use App\DTO\Categories\CreateCategoryDTO;
use App\DTO\Categories\IndexCategoryDTO;
use App\DTO\Categories\UpdateCategoryDTO;
use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Http\Requests\Categories\IndexCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Resources\Categories\CategoryResource;
use App\ViewModels\Categories\GetCategoryViewModel;
use App\ViewModels\Categories\PaginateCategoriesViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param IndexCategoryRequest $request
     * @param PaginateCategoriesViewModel $paginateCategoriesViewModel
     * @return LengthAwarePaginator<CategoryResource>
     */
    public function index(
        IndexCategoryRequest $request,
        PaginateCategoriesViewModel $paginateCategoriesViewModel
    ): LengthAwarePaginator {
        Gate::authorize('viewAny', Category::class);

        $data = IndexCategoryDTO::from([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return $paginateCategoriesViewModel->get($data);
    }

    public function store(
        CreateCategoryRequest $request,
        CreateCategoryAction $createCategoryAction
    ): CategoryResource {
        Gate::authorize('create', Category::class);

        $data = CreateCategoryDTO::from([
            'name' => $request->str('name')->toString(),
            'userId' => auth()->id(),
            'isIncome' => $request->boolean('is_income'),
        ]);

        return CategoryResource::from($createCategoryAction->run($data));
    }

    public function show(Category $category, GetCategoryViewModel $getCategoryViewModel): CategoryResource
    {
        Gate::authorize('view', $category);

        return $getCategoryViewModel->get($category);
    }

    public function update(
        Category $category,
        UpdateCategoryRequest $request,
        UpdateCategoryAction $updateCategoryAction
    ): CategoryResource {
        Gate::authorize('update', $category);

        $data = UpdateCategoryDTO::from($request);

        return CategoryResource::from(
            $updateCategoryAction->run($category, $data)
        );
    }

    public function destroy(Category $category, DeleteCategoryAction $deleteCategoryAction): JsonResponse
    {
        Gate::authorize('delete', $category);

        if ($deleteCategoryAction->run($category)) {
            return response()->json(['message' => 'Category deleted successfully.'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Failed to delete category.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
