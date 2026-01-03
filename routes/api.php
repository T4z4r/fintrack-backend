<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\IncomeSourceController;
use App\Http\Controllers\API\IncomeController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\BudgetController;
use App\Http\Controllers\API\BudgetItemController;
use App\Http\Controllers\API\InvestmentController;
use App\Http\Controllers\API\DebtController;
use App\Http\Controllers\API\DebtPaymentController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API Routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Add more public API routes here as needed
});

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/income-sources', [IncomeSourceController::class, 'index']);
        Route::post('/income-sources', [IncomeSourceController::class, 'store']);
        Route::get('/income-sources/{id}', [IncomeSourceController::class, 'show']);
        Route::put('/income-sources/{id}', [IncomeSourceController::class, 'update']);
        Route::delete('/income-sources/{id}', [IncomeSourceController::class, 'destroy']);

        Route::get('/incomes', [IncomeController::class, 'index']);
        Route::post('/incomes', [IncomeController::class, 'store']);
        Route::get('/incomes/{id}', [IncomeController::class, 'show']);
        Route::put('/incomes/{id}', [IncomeController::class, 'update']);
        Route::delete('/incomes/{id}', [IncomeController::class, 'destroy']);

        Route::get('/expenses', [ExpenseController::class, 'index']);
        Route::post('/expenses', [ExpenseController::class, 'store']);
        Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
        Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

        Route::get('/budgets', [BudgetController::class, 'index']);
        Route::post('/budgets', [BudgetController::class, 'store']);
        Route::get('/budgets/{id}', [BudgetController::class, 'show']);
        Route::put('/budgets/{id}', [BudgetController::class, 'update']);
        Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);

        Route::get('/budget-items', [BudgetItemController::class, 'index']);
        Route::post('/budget-items', [BudgetItemController::class, 'store']);
        Route::get('/budget-items/{id}', [BudgetItemController::class, 'show']);
        Route::put('/budget-items/{id}', [BudgetItemController::class, 'update']);
        Route::delete('/budget-items/{id}', [BudgetItemController::class, 'destroy']);
        Route::get('/budgets/{budgetId}/items', [BudgetItemController::class, 'itemsForBudget']);
        Route::patch('/budget-items/{id}/spent-amount', [BudgetItemController::class, 'updateSpentAmount']);
        Route::get('/budget-items-summary', [BudgetItemController::class, 'summaryByCategory']);

        Route::get('/investments', [InvestmentController::class, 'index']);
        Route::post('/investments', [InvestmentController::class, 'store']);
        Route::get('/investments/{id}', [InvestmentController::class, 'show']);
        Route::put('/investments/{id}', [InvestmentController::class, 'update']);
        Route::delete('/investments/{id}', [InvestmentController::class, 'destroy']);

        Route::get('/debts', [DebtController::class, 'index']);
        Route::post('/debts', [DebtController::class, 'store']);
        Route::get('/debts/{id}', [DebtController::class, 'show']);
        Route::put('/debts/{id}', [DebtController::class, 'update']);
        Route::delete('/debts/{id}', [DebtController::class, 'destroy']);

        Route::get('/debt-payments', [DebtPaymentController::class, 'index']);
        Route::post('/debt-payments', [DebtPaymentController::class, 'store']);
        Route::get('/debt-payments/{id}', [DebtPaymentController::class, 'show']);
        Route::put('/debt-payments/{id}', [DebtPaymentController::class, 'update']);
        Route::delete('/debt-payments/{id}', [DebtPaymentController::class, 'destroy']);
        Route::get('/debts/{debtId}/payments', [DebtPaymentController::class, 'paymentsForDebt']);
        Route::get('/debt-payments-summary', [DebtPaymentController::class, 'summary']);

        Route::get('/assets', [AssetController::class, 'index']);
        Route::post('/assets', [AssetController::class, 'store']);
        Route::get('/assets/{id}', [AssetController::class, 'show']);
        Route::put('/assets/{id}', [AssetController::class, 'update']);
        Route::delete('/assets/{id}', [AssetController::class, 'destroy']);
    });


    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });
});

// Fallback route for undefined API routes
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found'
    ], 404);
});
