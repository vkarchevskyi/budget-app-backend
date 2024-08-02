<?php

declare(strict_types=1);

namespace App\Actions\Budget;

use App\DTO\Budgets\UpdateBudgetDTO;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;

class UpdateBudgetAction
{
    /**
     * @throws \Throwable
     */
    public function run(int $id, UpdateBudgetDTO $updateBudgetDTO): void
    {
        DB::transaction(function () use ($id, $updateBudgetDTO): void {
            Budget::query()
                ->where('id', '=', $id)
                ->update([
                    'size' => $updateBudgetDTO->size,
                ]);
        });
    }
}
