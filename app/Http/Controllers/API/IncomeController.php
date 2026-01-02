<?php

namespace App\Http\Controllers\API;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $incomes = Auth::user()->incomes()->with('source')->get();

            return response()->json([
                'success' => true,
                'message' => 'Incomes retrieved successfully',
                'data' => $incomes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve incomes',
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
                'income_source_id' => 'required|exists:income_sources,id',
                'category' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'date' => 'required|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            $data['user_id'] = Auth::id();

            $income = Income::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Income created successfully',
                'data' => $income->load('source')
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
                'message' => 'Failed to create income',
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
            $income = Income::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('source')
                ->first();

            if (!$income) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Income retrieved successfully',
                'data' => $income
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve income',
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
            $income = Income::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$income) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income not found'
                ], 404);
            }

            $data = $request->validate([
                'income_source_id' => 'sometimes|required|exists:income_sources,id',
                'category' => 'sometimes|required|string|max:255',
                'amount' => 'sometimes|required|numeric|min:0',
                'date' => 'sometimes|required|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            $income->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Income updated successfully',
                'data' => $income->fresh()->load('source')
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
                'message' => 'Failed to update income',
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
            $income = Income::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$income) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income not found'
                ], 404);
            }

            $income->delete();

            return response()->json([
                'success' => true,
                'message' => 'Income deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete income',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
