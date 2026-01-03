<?php

namespace App\Http\Controllers\API;

use App\Models\BudgetItem;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BudgetItemController extends Controller
{
    /**
     * Display a listing of all budget items for the authenticated user
     */
    public function index()
    {
        try {
            $budgetItems = BudgetItem::where('user_id', Auth::id())
                ->with('budget')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Budget items retrieved successfully',
                'data' => $budgetItems->map(function ($item) {
                    return $item->loadCalculatedAttributes();
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budget items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get budget items for a specific budget
     */
    public function itemsForBudget($budgetId)
    {
        try {
            // Verify the budget belongs to the authenticated user
            $budget = Budget::where('user_id', Auth::id())
                ->where('id', $budgetId)
                ->first();

            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget not found'
                ], 404);
            }

            $budgetItems = BudgetItem::where('budget_id', $budgetId)
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Budget items retrieved successfully',
                'data' => [
                    'budget' => $budget->loadCalculatedAttributes(),
                    'budget_items' => $budgetItems->map(function ($item) {
                        return $item->loadCalculatedAttributes();
                    }),
                    'summary' => [
                        'total_items' => $budgetItems->count(),
                        'total_planned' => $budgetItems->sum('planned_amount'),
                        'total_spent' => $budgetItems->sum('spent_amount'),
                        'total_remaining' => $budgetItems->sum('planned_amount') - $budgetItems->sum('spent_amount')
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budget items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created budget item in storage
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'budget_id' => 'required|exists:budgets,id',
                'name' => 'required|string|max:255',
                'planned_amount' => 'required|numeric|min:0.01',
                'spent_amount' => 'sometimes|numeric|min:0',
                'category_type' => 'required|string|in:income,investment,expense,asset,debt',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000'
            ]);

            // Verify the budget belongs to the authenticated user
            $budget = Budget::where('user_id', Auth::id())
                ->where('id', $data['budget_id'])
                ->first();

            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget not found'
                ], 404);
            }

            $data['user_id'] = Auth::id();
            $data['spent_amount'] = $data['spent_amount'] ?? 0;
            $data['status'] = 'active';

            $budgetItem = BudgetItem::create($data);

            // Load the budget relationship for the response
            $budgetItem->load('budget');

            return response()->json([
                'success' => true,
                'message' => 'Budget item created successfully',
                'data' => $budgetItem->loadCalculatedAttributes()
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create budget item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified budget item
     */
    public function show($id)
    {
        try {
            $budgetItem = BudgetItem::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('budget')
                ->first();

            if (!$budgetItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget item not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Budget item retrieved successfully',
                'data' => $budgetItem->loadCalculatedAttributes()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budget item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified budget item in storage
     */
    public function update(Request $request, $id)
    {
        try {
            $budgetItem = BudgetItem::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$budgetItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget item not found'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'planned_amount' => 'sometimes|required|numeric|min:0.01',
                'spent_amount' => 'sometimes|required|numeric|min:0',
                'category_type' => 'sometimes|required|string|in:income,investment,expense,asset,debt',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'sometimes|required|string|in:active,completed,cancelled'
            ]);

            $budgetItem->update($data);
            $budgetItem->load('budget');

            return response()->json([
                'success' => true,
                'message' => 'Budget item updated successfully',
                'data' => $budgetItem->fresh()->loadCalculatedAttributes()
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update budget item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified budget item from storage
     */
    public function destroy($id)
    {
        try {
            $budgetItem = BudgetItem::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$budgetItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget item not found'
                ], 404);
            }

            $budgetItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Budget item deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete budget item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update spent amount for a budget item (quick update)
     */
    public function updateSpentAmount(Request $request, $id)
    {
        try {
            $budgetItem = BudgetItem::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$budgetItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget item not found'
                ], 404);
            }

            $data = $request->validate([
                'spent_amount' => 'required|numeric|min:0'
            ]);

            $budgetItem->update($data);
            $budgetItem->load('budget');

            return response()->json([
                'success' => true,
                'message' => 'Spent amount updated successfully',
                'data' => $budgetItem->fresh()->loadCalculatedAttributes()
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update spent amount',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get budget items summary by category type
     */
    public function summaryByCategory()
    {
        try {
            $userId = Auth::id();

            $summary = BudgetItem::where('user_id', $userId)
                ->selectRaw('category_type, COUNT(*) as item_count, SUM(planned_amount) as total_planned, SUM(spent_amount) as total_spent')
                ->groupBy('category_type')
                ->get()
                ->map(function ($item) {
                    $item->total_remaining = $item->total_planned - $item->total_spent;
                    $item->usage_percentage = $item->total_planned > 0 ? min(100, ($item->total_spent / $item->total_planned) * 100) : 0;
                    return $item;
                });

            return response()->json([
                'success' => true,
                'message' => 'Budget items summary retrieved successfully',
                'data' => $summary
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budget items summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}