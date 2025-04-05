
public function transactionReportView(Request $request)
    {
        $filters = $request->only([
            'transaction_type_id',
            'transaction_subtype_id',
            'status',
            'date_from',
            'date_to'
        ]);
    
        $dateFrom = \Carbon\Carbon::parse($filters['date_from'] ?? now()->subYear());
        $dateTo = \Carbon\Carbon::parse($filters['date_to'] ?? now());
    
        $transactions = Transaction::with('transactionType', 'transactionSubtype', 'owner', 'animal.species', 'animal.breed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when($request->filled('transaction_type_id'), fn ($query) => $query->where('transaction_type_id', $filters['transaction_type_id']))
            ->when($request->filled('transaction_subtype_id'), fn ($query) => $query->where('transaction_subtype_id', $filters['transaction_subtype_id']))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $filters['status']))
            ->get();
    
        $summary = $this->generateSummaryStatistics($transactions);
    
        $report = Report::create([
            'user_id' => auth()->user()->user_id,
            'report_type' => 'transactions',
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'parameters' => $filters,
            'generated_by' => auth()->user()->user_id,
            'status' => 'completed',
            'file_path' => '',
        ]);
    
        $pdf = PDF::loadView('reports.pdf.receptionist.transactions', [
            'veterinarian' => auth()->user(),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'filters' => $filters,
            'summary' => $summary,
            'transactions' => $transactions,
        ]);
    
        $fileName = 'transaction_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $filePath = 'reports/' . $fileName;
    
        if (!Storage::disk('public')->put($filePath, $pdf->output())) {
            throw new \Exception('Failed to save the PDF file.');
        }
    
        $report->update(['file_path' => $filePath]);
    
        // ✅ Redirect to download route instead of returning the PDF directly
        return redirect()->route('reports.downloadfromRec', $report->id);
    }
    