<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
    <style>

        @page {
            margin: 0.5cm 1cm;
        }
        body { 
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 60px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #060a0f;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #060a0f;
            margin-bottom: 5px;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .summary-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            width: 50%;
            align-self: auto;
        }
        .section-title {
            color: #2d3748;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e0;
        }
        .filter-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #ebf4ff;
            border-radius: 12px;
            font-size: 11px;
            color: #060a0f;
            margin: 2px;
        }
        .stats-grid {
            align-items: center;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
            width: 50%;
        }
        .stat-box {
            width: 50%;
            padding: 8px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .stat-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th {
            background-color: #edf2f7;
            color: #2d3748;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-completed { background-color: #dcfce7; color: #166534; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .subtype-text {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            background-color: white;
            border-top: 1px solid #ecf0f1;
            padding: 10px 20px;
            margin-top: 20px;
        }
        .table-container {
            margin-bottom: 60px;
        }
        .table-wrapper {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
    </style>
</head>
<body>
    <div class="header" style="display: block; text-align: center;">
        <table style="width: 100%; border: none; border-collapse: collapse; margin: 0 auto;">
            <tr>
                <td style="width: 25%; text-align: right; vertical-align: middle; padding-right: 15px; border: none;">
                    <img src="{{ public_path('assets/1.jpg') }}" alt="Logo" style="width: 100px; height: auto;">
                </td>
                <td style="width: 75%; text-align: left; vertical-align: middle; border: none;">
                    <h1 style="margin: 0; font-size: 20px;">Transaction Report</h1>
                    <div style="font-size: 14px;">City Veterinarians Office of Valencia</div>
                    <div style="font-size: 12px;">Official Transaction Record</div>
                    <p style="font-size: 10px; color: #718096;">Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
                </td>
            </tr>
        </table>
    </div>
   
    @if($transactions->count() > 0)
        <div class="section table-wrapper">
            <div class="section-title">Transaction Details</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Transaction Info</th>
                            <th>Client & Animal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 10px; color: #6b7280;">
                                        {{ $transaction->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: bold;">
                                        {{ $transaction->transactionType->type_name }}
                                    </div>
                                    @if($transaction->transactionSubtype)
                                        <div class="subtype-text">
                                            {{ $transaction->transactionSubtype->subtype_name }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: bold;">
                                        {{ $transaction->owner->user->complete_name }}
                                    </div>
                                    <div class="subtype-text">
                                        {{ $transaction->animal->name }} 
                                        ({{ $transaction->animal->species->name }}
                                        @if($transaction->animal->breed)
                                            - {{ $transaction->animal->breed->name }}
                                        @endif)
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower([
                                        0 => 'pending',
                                        1 => 'completed',
                                        2 => 'cancelled'
                                    ][$transaction->status]) }}">
                                        {{ [
                                            0 => 'Pending',
                                            1 => 'Completed',
                                            2 => 'Cancelled'
                                        ][$transaction->status] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="section" style="text-align: center; color: #718096;">
            No transactions found for this period.
        </div>
    @endif

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left;">
                    Generated by: {{ auth()->user()->complete_name }} | {{ now()->format('F d, Y h:i A') }}
                </td>
                <td style="border: none; text-align: right;">
                    Page {PAGE_NUM} of {PAGE_COUNT}
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="summary-section" style="margin-bottom: 60px;">
        <div class="section-title">Summary Statistics</div>
        <div class="stat-box">
            <div class="stat-label">Completed</div>
            <div class="stat-value" style="color: #059669;">
                {{ $summary['byStatus'][1] ?? 0 }}
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color: #d97706;">
                {{ $summary['byStatus'][0] ?? 0 }}
            </div>
        </div>
        
        
        </div>
        @if(isset($summary['byType']))
        @foreach($summary['byType'] as $typeName => $typeStats)
        @endforeach
    @else
        <p>No transaction types found.</p>
    @endif

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left;">
                    Generated by: {{ auth()->user()->complete_name }} | {{ now()->format('F d, Y h:i A') }}
                </td>
                <td style="border: none; text-align: right;">
                    Page {PAGE_NUM} of {PAGE_COUNT}
                </td>
            </tr>
        </table>
    </div>
</body>
</html> 
