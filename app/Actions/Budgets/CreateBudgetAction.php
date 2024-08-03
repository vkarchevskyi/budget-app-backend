<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\CreateBudgetDTO;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CreateBudgetAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateBudgetDTO $createBudgetDTO): Budget
    {
        return DB::transaction(function () use ($createBudgetDTO): Budget {
            $category = Category::query()->findOrFail($createBudgetDTO->categoryId);
            $budget = Budget::query()->make([
                'size' => $createBudgetDTO->size,
                'category_id' => $createBudgetDTO->categoryId,
                'user_id' => auth()->user()?->getAuthIdentifier(),
                'date' => $createBudgetDTO->date,
            ]);

            Gate::authorize('create', [$budget, $category]);

            $budget->save();
            return $budget;
        });
    }
}
