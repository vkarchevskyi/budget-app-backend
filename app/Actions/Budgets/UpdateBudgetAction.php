<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\UpdateBudgetDTO;
use App\Models\Budget;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class UpdateBudgetAction
{
    public function __construct(protected Gate $gate)
    {
    }

    /**
     * @throws Throwable
     */
    public function run(int $id, UpdateBudgetDTO $updateBudgetDTO): Budget
    {
        return DB::transaction(function () use ($id, $updateBudgetDTO): Budget {
            /** @var Budget $budget */
            $budget = Budget::with(['category'])->findOrFail($id);

            $this->gate->authorize('update', [$budget, $budget->category]);

            $budget->update(['size' => $updateBudgetDTO->size]);
            return $budget;
        });
    }
}
