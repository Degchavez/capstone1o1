<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use App\Models\Animal;
use App\Models\Species;
use App\Models\TransactionType;
use App\Models\TransactionSubtype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use Storage;

class ReportController extends Controller
{
    public function index()
    {
        $recentReports = Report::where('user_id', auth()->user()->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $transactionTypes = TransactionType::with('subtypes')->get();

        return view('vet.reportEngine', compact('recentReports', 'transactionTypes'));
    }

    public function generateTransactionReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'transaction_type_id' => 'nullable|exists:transaction_types,id',
                'transaction_subtype_id' => 'nullable|exists:transaction_subtypes,id',
                'status' => 'nullable|in:0,1,2',
                'format' => 'required|in:pdf,excel'
            ]);

            // Get transactions for the logged-in vet only
            $query = Transaction::query()
                ->where('vet_id', auth()->user()->user_id)
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ])
                ->with([
                    'owner.user',
                    'animal.species',
                    'animal.breed',
                    'transactionType',
                    'transactionSubtype'
                ]);

            // Filter by transaction type if selected
            if (!empty($validated['transaction_type_id'])) {
                $query->where('transaction_type_id', $validated['transaction_type_id']);
            }

            // Filter by transaction subtype if selected
            if (!empty($validated['transaction_subtype_id'])) {
                $query->where('transaction_subtype_id', $validated['transaction_subtype_id']);
            }

            // Filter by status if selected
            if (isset($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            $transactions = $query->get();

            $data = [
                'transactions' => $transactions,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $transactions->count(),
                    'byStatus' => [
                        'pending' => $transactions->where('status', 0)->count(),
                        'completed' => $transactions->where('status', 1)->count(),
                        'cancelled' => $transactions->where('status', 2)->count(),
                    ],
                    'byType' => $transactions->groupBy('transactionType.type_name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'completed' => $group->where('status', 1)->count(),
                                'pending' => $group->where('status', 0)->count(),
                                'cancelled' => $group->where('status', 2)->count(),
                                'bySubtype' => $group->groupBy('transactionSubtype.subtype_name')
                                    ->map(function ($subgroup) {
                                        return [
                                            'count' => $subgroup->count(),
                                            'completed' => $subgroup->where('status', 1)->count(),
                                            'pending' => $subgroup->where('status', 0)->count(),
                                            'cancelled' => $subgroup->where('status', 2)->count(),
                                        ];
                                    })
                            ];
                        }),
                ],
                'filters' => [
                    'type' => $validated['transaction_type_id'] ? 
                        TransactionType::find($validated['transaction_type_id'])->type_name : 'All Types',
                    'subtype' => $validated['transaction_subtype_id'] ?? null ? 
                        TransactionSubtype::find($validated['transaction_subtype_id'])->subtype_name : 'All Subtypes',
                    'status' => isset($validated['status']) ? 
                        ['Pending', 'Completed', 'Cancelled'][$validated['status']] : 'All Statuses'
                ],
                'veterinarian' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.transactions', $data);
            
            // Create filename
            $fileName = "transaction-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            // Save report record first
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'transactions',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath // Save file path immediately
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route that we know works
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function download(Report $report)
    {
        try {
            // Security check
            if ($report->user_id !== auth()->user()->user_id) {
                abort(403, 'Unauthorized access to report');
            }

            if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
                return back()->with('error', 'Report file not found');
            }

            return response()->file(storage_path('app/public/' . $report->file_path), [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . basename($report->file_path) . '"'
            ]);

        } catch (\Exception $e) {
            \Log::error('Download failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to download report: ' . $e->getMessage());
        }
    }

    public function delete(Report $report)
    {
        try {
            // Security check
            if ($report->user_id !== auth()->user()->user_id) {
                abort(403);
            }

            // Delete the file if it exists
            if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }

            // Delete the report record
            $report->delete();

            return back()->with('success', 'Report deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Report deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete report.');
        }
    }
}