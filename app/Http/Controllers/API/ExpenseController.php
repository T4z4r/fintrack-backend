<?php

namespace App\Http\Controllers\API;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $expenses = Auth::user()->expenses;

            return response()->json([
                'success' => true,
                'message' => 'Expenses retrieved successfully',
                'data' => $expenses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expenses',
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
                'category' => 'required|string|max:255',
                'amount' => 'required|min:0',
                'payment_source' => 'required|string|max:255',
                'date' => 'required|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            $data['user_id'] = Auth::id();

            $expense = Expense::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Expense created successfully',
                'data' => $expense
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
                'message' => 'Failed to create expense',
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
            $expense = Expense::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Expense retrieved successfully',
                'data' => $expense
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expense',
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
            $expense = Expense::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found'
                ], 404);
            }

            $data = $request->validate([
                'category' => 'sometimes|required|string|max:255',
                'amount' => 'sometimes|required|min:0',
                'payment_source' => 'sometimes|required|string|max:255',
                'date' => 'sometimes|required|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            $expense->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully',
                'data' => $expense->fresh()
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
                'message' => 'Failed to update expense',
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
            $expense = Expense::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found'
                ], 404);
            }

            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
