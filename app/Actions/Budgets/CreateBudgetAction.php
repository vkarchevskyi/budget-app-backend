<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\CreateBudgetDTO;
use App\Models\Budget;
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
            /** @var Budget $budget */
            $budget = Budget::query()->make([
                'size' => $createBudgetDTO->size,
                'category_id' => $createBudgetDTO->categoryId,
                'user_id' => auth()->user()?->getAuthIdentifier(),
                'date' => $createBudgetDTO->date,
            ])->load(['category']);

            Gate::authorize('create', [$budget, $budget->category]);

            $budget->save();
            return $budget;
        });
    }
}
