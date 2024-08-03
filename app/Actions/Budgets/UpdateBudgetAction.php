<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\UpdateBudgetDTO;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateBudgetAction
{
    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateBudgetDTO $updateBudgetDTO): Budget
    {
        return DB::transaction(function () use ($id, $updateBudgetDTO): Budget {
            $budget = Budget::query()->findOrFail($id);

            // TODO: add policy

            $budget->update(['size' => $updateBudgetDTO->size]);
            return $budget;
        });
    }
}
