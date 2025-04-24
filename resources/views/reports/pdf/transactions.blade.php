<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
            margin-bottom: 1.5cm;
        }

        body { 
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding-bottom: 60px;
        }

        .header-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin: 0 auto 20px auto;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
        }

        .header-table td {
            border: none;
            padding: 5px;
        }

        .logo {
            width: 100px;
            height: auto;
        }

        .header-content h1 {
            margin: 0;
            font-size: 20px;
            color: #010204;
        }

        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .section-title {
            color: #2d3748;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e0;
        }

        .table-container {
            margin-bottom: 20px;
            page-break-inside: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th {
            background-color: #edf2f7;
            color: #2d3748;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #e2e8f0;
        }

        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-box {
            padding: 12px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .stat-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-pending { 
            background-color: #fef3c7; 
            color: #92400e; 
        }

        .status-completed { 
            background-color: #dcfce7; 
            color: #166534; 
        }

        .status-cancelled { 
            background-color: #fee2e2; 
            color: #991b1b; 
        }

        .subtype-text {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #718096;
            background-color: white;
            border-top: 1px solid #e2e8f0;
            padding: 10px 0;
        }
        .page-break {
            page-break-after: always;
        }

        .no-data {
            text-align: center;
            color: #718096;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 25%; text-align: right; vertical-align: middle; padding-right: 15px;">
                <img src="{{ public_path('assets/1.jpg') }}" alt="Logo" class="logo">
            </td>
            <td style="width: 75%; text-align: left; vertical-align: middle;">
                <div class="header-content">
                    <h1>Transaction Report</h1>
                    <div style="font-size: 14px;">City Veterinarians Office of Valencia</div>
                    <div style="font-size: 12px;">Official Transaction Record</div>
                    <p style="font-size: 10px; color: #718096;">Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
                </div>
            </td>
        </tr>
    </table>

    @if($transactions->count() > 0)
        <div class="section">
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

        <div class="page-break"></div>


        <div class="section">
            <div class="section-title">Summary Statistics</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Total Transactions</div>
                    <div class="stat-value">{{ $summary['total'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value" style="color: #059669;">
                        {{ $summary['byStatus']['completed'] }}
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" style="color: #d97706;">
                        {{ $summary['byStatus']['pending'] }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="section no-data">
            No transactions found for this period.
        </div>
    @endif

    <div class="footer">
        <p>Generated by: {{ $veterinarian->complete_name }}</p>
        <p style="font-size: 10px; color: #718096;">Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        Page 1 of 1 | Valencia Veterinary Clinic
    </div>
</body>
</html> 