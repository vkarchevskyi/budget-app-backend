<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\CreateBudgetDTO;
use App\Models\Budget;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateBudgetAction
{
    public function __construct(protected Gate $gate)
    {
    }

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

            $this->gate->authorize('create', [$budget, $budget->category]);

            $budget->save();
            return $budget;
        });
    }
}
