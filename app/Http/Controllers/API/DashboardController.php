<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display financial dashboard summary.
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Calculate financial metrics
            $totalIncome = $user->incomes()->sum('amount');
            $totalExpenses = $user->expenses()->sum('amount');
            $totalAssets = $user->assets()->sum('value');
            $totalDebts = $user->debts()->sum('amount');
            $netWorth = $totalAssets - $totalDebts;

            // Additional calculated metrics
            $savingsRate = $totalIncome > 0 ? (($totalIncome - $totalExpenses) / $totalIncome) * 100 : 0;
            $debtToAssetRatio = $totalAssets > 0 ? ($totalDebts / $totalAssets) * 100 : 0;

            return response()->json([
                'success' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => [
                    'financial_summary' => [
                        'total_income' => (float) $totalIncome,
                        'total_expenses' => (float) $totalExpenses,
                        'total_assets' => (float) $totalAssets,
                        'total_debts' => (float) $totalDebts,
                        'net_worth' => (float) $netWorth
                    ],
                    'calculated_metrics' => [
                        'savings_rate' => round($savingsRate, 2),
                        'debt_to_asset_ratio' => round($debtToAssetRatio, 2),
                        'total_savings' => (float) ($totalIncome - $totalExpenses)
                    ],
                    'breakdown' => [
                        'income_sources_count' => $user->incomes()->count(),
                        'expenses_count' => $user->expenses()->count(),
                        'assets_count' => $user->assets()->count(),
                        'debts_count' => $user->debts()->count()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
