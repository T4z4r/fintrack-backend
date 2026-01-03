<?php

namespace App\Http\Controllers\API;

use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $investments = Auth::user()->investments;

            return response()->json([
                'success' => true,
                'message' => 'Investments retrieved successfully',
                'data' => $investments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve investments',
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
                'amount_invested' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'expected_return' => 'nullable|numeric|min:0',
                'status' => 'nullable|string|in:active,closed'
            ]);

            $data['user_id'] = Auth::id();

            $investment = Investment::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Investment created successfully',
                'data' => $investment
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
                'message' => 'Failed to create investment',
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
            $investment = Investment::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$investment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Investment retrieved successfully',
                'data' => $investment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve investment',
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
            $investment = Investment::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$investment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment not found'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'amount_invested' => 'sometimes|required|numeric|min:0',
                'start_date' => 'sometimes|required|date',
                'expected_return' => 'nullable|numeric|min:0',
                'status' => 'sometimes|required|string|in:active,closed'
            ]);

            $investment->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Investment updated successfully',
                'data' => $investment->fresh()
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
                'message' => 'Failed to update investment',
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
            $investment = Investment::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$investment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment not found'
                ], 404);
            }

            $investment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Investment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete investment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

