<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Budgets\CreateBudgetAction;
use App\Actions\Budgets\UpdateBudgetAction;
use App\DTO\Budgets\CreateBudgetDTO;
use App\DTO\Budgets\UpdateBudgetDTO;
use App\Http\Requests\Budgets\CreateBudgetRequest;
use App\Http\Requests\Budgets\UpdateBudgetRequest;
use App\Resources\Budgets\BudgetResource;
use Throwable;

class BudgetController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(CreateBudgetRequest $request, CreateBudgetAction $createBudgetAction): BudgetResource
    {
        return BudgetResource::from($createBudgetAction->run(CreateBudgetDTO::from($request)));
    }

    /**
     * @throws Throwable
     */
    public function update(
        int $id,
        UpdateBudgetRequest $request,
        UpdateBudgetAction $updateBudgetAction
    ): BudgetResource {
        return BudgetResource::from($updateBudgetAction->run($id, UpdateBudgetDTO::from($request)));
    }
}
