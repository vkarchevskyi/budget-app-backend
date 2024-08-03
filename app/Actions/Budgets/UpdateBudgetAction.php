<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\UpdateBudgetDTO;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class UpdateBudgetAction
{
    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateBudgetDTO $updateBudgetDTO): Budget
    {
        return DB::transaction(function () use ($id, $updateBudgetDTO): Budget {
            $budget = Budget::with(['category'])->findOrFail($id);

            Gate::authorize('update', [$budget, $budget->category]);

            $budget->update(['size' => $updateBudgetDTO->size]);
            return $budget;
        });
    }
}
