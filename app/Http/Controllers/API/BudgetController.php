<?php

namespace App\Http\Controllers\API;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $budgets = Auth::user()->budgets()->with('budgetItems')->get();

            return response()->json([
                'success' => true,
                'message' => 'Budgets retrieved successfully',
                'data' => $budgets->map(function ($budget) {
                    return $budget->loadCalculatedAttributes();
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budgets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'planned_amount' => 'required|numeric|min:0',
                'time_period' => 'required|string|in:daily,weekly,monthly,yearly',
                'category_type' => 'required|string|in:income,investment,expense,asset,debt',
                'description' => 'nullable|string|max:1000',
                // Backward compatibility fields
                'category' => 'nullable|string|max:255',
                'monthly_limit' => 'nullable|numeric|min:0',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2000|max:' . (date('Y') + 10)
            ]);

            $data['user_id'] = Auth::id();
            $data['status'] = 'active';
            $data['spent_amount'] = 0;

            // For backward compatibility, set monthly_limit if not provided
            if (empty($data['monthly_limit']) && !empty($data['planned_amount'])) {
                $data['monthly_limit'] = $data['planned_amount'];
            }

            $budget = Budget::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Budget created successfully',
                'data' => $budget->loadCalculatedAttributes()
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
                'message' => 'Failed to create budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $budget = Budget::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('budgetItems')
                ->first();

            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Budget retrieved successfully',
                'data' => $budget->loadWithItems()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $budget = Budget::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget not found'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'planned_amount' => 'sometimes|required|numeric|min:0',
                'spent_amount' => 'sometimes|required|numeric|min:0',
                'time_period' => 'sometimes|required|string|in:daily,weekly,monthly,yearly',
                'category_type' => 'sometimes|required|string|in:income,investment,expense,asset,debt',
                'status' => 'sometimes|required|string|in:active,completed,cancelled',
                'description' => 'nullable|string|max:1000',
                // Backward compatibility fields
                'category' => 'nullable|string|max:255',
                'monthly_limit' => 'nullable|numeric|min:0',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2000|max:' . (date('Y') + 10)
            ]);

            $budget->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully',
                'data' => $budget->fresh()->loadCalculatedAttributes()
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
                'message' => 'Failed to update budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $budget = Budget::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget not found'
                ], 404);
            }

            $budget->delete();

            return response()->json([
                'success' => true,
                'message' => 'Budget deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

