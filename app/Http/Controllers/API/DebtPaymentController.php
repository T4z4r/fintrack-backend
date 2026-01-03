<?php

namespace App\Http\Controllers\API;

use App\Models\DebtPayment;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DebtPaymentController extends Controller
{
    /**
     * Display a listing of all payments for the authenticated user
     */
    public function index()
    {
        try {
            $payments = DebtPayment::where('user_id', Auth::id())
                ->with('debt')
                ->orderBy('payment_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Debt payments retrieved successfully',
                'data' => $payments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve debt payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payments for a specific debt
     */
    public function paymentsForDebt($debtId)
    {
        try {
            // Verify the debt belongs to the authenticated user
            $debt = Debt::where('user_id', Auth::id())
                ->where('id', $debtId)
                ->first();

            if (!$debt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found'
                ], 404);
            }

            $payments = DebtPayment::where('debt_id', $debtId)
                ->where('user_id', Auth::id())
                ->orderBy('payment_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Debt payments retrieved successfully',
                'data' => [
                    'debt' => $debt,
                    'payments' => $payments,
                    'summary' => [
                        'total_debt' => $debt->amount,
                        'total_paid' => $debt->total_paid,
                        'remaining_balance' => $debt->remaining_balance,
                        'payment_count' => $payments->count()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve debt payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created debt payment in storage
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'debt_id' => 'required|exists:debts,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_date' => 'required|date',
                'payment_method' => 'nullable|string|max:255',
                'notes' => 'nullable|string'
            ]);

            // Verify the debt belongs to the authenticated user
            $debt = Debt::where('user_id', Auth::id())
                ->where('id', $data['debt_id'])
                ->first();

            if (!$debt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found'
                ], 404);
            }

            $data['user_id'] = Auth::id();

            $payment = DebtPayment::create($data);

            // Load the debt relationship for the response
            $payment->load('debt');

            return response()->json([
                'success' => true,
                'message' => 'Debt payment created successfully',
                'data' => $payment
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
                'message' => 'Failed to create debt payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified debt payment
     */
    public function show($id)
    {
        try {
            $payment = DebtPayment::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('debt')
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt payment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Debt payment retrieved successfully',
                'data' => $payment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve debt payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified debt payment in storage
     */
    public function update(Request $request, $id)
    {
        try {
            $payment = DebtPayment::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt payment not found'
                ], 404);
            }

            $data = $request->validate([
                'amount' => 'sometimes|required|numeric|min:0.01',
                'payment_date' => 'sometimes|required|date',
                'payment_method' => 'sometimes|nullable|string|max:255',
                'notes' => 'sometimes|nullable|string'
            ]);

            $payment->update($data);
            $payment->load('debt');

            return response()->json([
                'success' => true,
                'message' => 'Debt payment updated successfully',
                'data' => $payment
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
                'message' => 'Failed to update debt payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified debt payment from storage
     */
    public function destroy($id)
    {
        try {
            $payment = DebtPayment::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt payment not found'
                ], 404);
            }

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Debt payment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete debt payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment summary for all debts
     */
    public function summary()
    {
        try {
            $userId = Auth::id();

            $debts = Debt::where('user_id', $userId)->with('payments')->get();

            $summary = [
                'total_debts' => $debts->count(),
                'total_debt_amount' => $debts->sum('amount'),
                'total_paid' => $debts->sum(function($debt) {
                    return $debt->payments->sum('amount');
                }),
                'remaining_balance' => $debts->sum('amount') - $debts->sum(function($debt) {
                    return $debt->payments->sum('amount');
                }),
                'fully_paid_debts' => $debts->where('remaining_balance', 0)->count(),
                'partially_paid_debts' => $debts->where('remaining_balance', '>', 0)
                    ->filter(function($debt) { return $debt->total_paid > 0; })->count(),
                'unpaid_debts' => $debts->where('total_paid', 0)->count()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Payment summary retrieved successfully',
                'data' => $summary
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}