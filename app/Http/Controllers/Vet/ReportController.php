<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function preview(Request $request)
    {
        try {
            $query = Transaction::query()
                ->where('vet_id', auth()->id())
                ->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);

            // Apply filters
            if ($request->transaction_type_id) {
                $query->where('transaction_type_id', $request->transaction_type_id);
            }
            
            if ($request->transaction_subtype_id) {
                $query->where('transaction_subtype_id', $request->transaction_subtype_id);
            }
            
            if ($request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            // Clone query for counts to avoid modifying the original query
            $totalQuery = clone $query;
            $completedQuery = clone $query;
            $pendingQuery = clone $query;

            // Get summary
            $summary = [
                'total' => $totalQuery->count(),
                'completed' => $completedQuery->where('status', 1)->count(),
                'pending' => $pendingQuery->where('status', 0)->count()
            ];

            // Get sample data (latest 5 transactions)
            $samples = $query->latest()
                ->take(5)
                ->with(['transactionType', 'transactionSubtype'])
                ->get()
                ->map(function ($transaction) {
                    return [
                        'created_at' => $transaction->created_at,
                        'type' => $transaction->transactionType->type_name . 
                            ($transaction->transactionSubtype ? ' - ' . $transaction->transactionSubtype->subtype_name : ''),
                        'status' => $transaction->status === 0 ? 'Pending' : 
                                   ($transaction->status === 1 ? 'Completed' : 'Cancelled')
                    ];
                });

            return response()->json([
                'summary' => $summary,
                'samples' => $samples
            ]);
        } catch (\Exception $e) {
            \Log::error('Report preview error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate preview'
            ], 500);
        }
    }
} 