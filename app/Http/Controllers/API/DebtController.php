<?php

namespace App\Http\Controllers\API;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $debts = Auth::user()->debts;

            return response()->json([
                'success' => true,
                'message' => 'Debts retrieved successfully',
                'data' => $debts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve debts',
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
                'amount' => 'required|numeric|min:0',
                'due_date' => 'required|date',
                'status' => 'nullable|string|in:paid,partial,unpaid'
            ]);

            $data['user_id'] = Auth::id();

            $debt = Debt::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Debt created successfully',
                'data' => $debt
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
                'message' => 'Failed to create debt',
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
            $debt = Debt::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$debt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Debt retrieved successfully',
                'data' => $debt
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve debt',
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
            $debt = Debt::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$debt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'amount' => 'sometimes|required|numeric|min:0',
                'due_date' => 'sometimes|required|date',
                'status' => 'sometimes|required|string|in:paid,partial,unpaid'
            ]);

            $debt->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Debt updated successfully',
                'data' => $debt->fresh()
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
                'message' => 'Failed to update debt',
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
            $debt = Debt::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$debt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found'
                ], 404);
            }

            $debt->delete();

            return response()->json([
                'success' => true,
                'message' => 'Debt deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete debt',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

