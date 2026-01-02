<?php

namespace App\Http\Controllers\API;

use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class IncomeSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $incomeSources = Auth::user()->incomeSources;

            return response()->json([
                'success' => true,
                'message' => 'Income sources retrieved successfully',
                'data' => $incomeSources
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve income sources',
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
                'type' => 'required|string|in:bank,mno,cash',
                'balance' => 'nullable|numeric|min:0'
            ]);

            $data['user_id'] = Auth::id();

            $incomeSource = IncomeSource::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Income source created successfully',
                'data' => $incomeSource
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
                'message' => 'Failed to create income source',
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
            $incomeSource = IncomeSource::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$incomeSource) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income source not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Income source retrieved successfully',
                'data' => $incomeSource
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve income source',
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
            $incomeSource = IncomeSource::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$incomeSource) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income source not found'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|required|string|in:bank,mno,cash',
                'balance' => 'nullable|numeric|min:0'
            ]);

            $incomeSource->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Income source updated successfully',
                'data' => $incomeSource->fresh()
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
                'message' => 'Failed to update income source',
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
            $incomeSource = IncomeSource::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$incomeSource) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income source not found'
                ], 404);
            }

            $incomeSource->delete();

            return response()->json([
                'success' => true,
                'message' => 'Income source deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete income source',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
