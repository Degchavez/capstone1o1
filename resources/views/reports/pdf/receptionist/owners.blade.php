<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Report</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #2d3748;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c5282;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
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
            color: #2c5282;
            margin: 2px;
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
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .stat-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
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
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f1f8ff;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            background-color: #e5edff;
            color: #3b82f6;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #718096;
            padding: 10px 0;
            border-top: 1px solid #e2e8f0;
        }
        .count-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #ebf4ff;
            color: #3b82f6;
            font-weight: bold;
            font-size: 11px;
        }
        .barangay-summary {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 15px;
        }
        .barangay-summary-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            background-color: #edf2f7;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            color: #4a5568;
        }
        .barangay-summary-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            padding: 10px 12px;
            border-top: 1px solid #e2e8f0;
            align-items: center;
        }
        .barangay-summary-item:first-child {
            border-top: none;
        }
        .barangay-name {
            font-weight: 500;
            color: #2d3748;
        }
        .metric-value {
            font-weight: bold;
            color: #3b82f6;
            text-align: center;
        }
        .metric-value.animals {
            color: #0284c7;
        }
        .metric-value.transactions {
            color: #16a34a;
        }
        .stat-icon {
            font-size: 22px;
            margin-bottom: 5px;
            opacity: 0.8;
        }
        .generated-by {
            font-style: italic;
            color: #4a5568;
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dotted #cbd5e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Owner Report</h1>
        <p><strong>Generated by:</strong> {{ $receptionist->complete_name }}</p>
        <p>Barangay: {{ $filters['barangay'] }}</p>
        <p>Period: {{ $dateFrom->toFormattedDateString() }} - {{ $dateTo->toFormattedDateString() }}</p>
        <p style="font-size: 10px; color: #718096;">Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Summary Statistics</div>
        <div class="stat-box">
            <div class="stat-icon">👤</div>
            <div class="stat-label">Total Owners</div>
            <div class="stat-value" style="color: #3b82f6;">
                {{ $summary['total'] }}
            </div>
        </div>
        
        <div class="section-title" style="margin-top: 10px;">Barangay Distribution</div>
        <div class="barangay-summary">
            <div class="barangay-summary-header">
                <div>Barangay</div>
                <div style="text-align: center;">Owners</div>
                <div style="text-align: center;">Animals</div>
                <div style="text-align: center;">Transactions</div>
            </div>
            
            @foreach($summary['byBarangay'] as $barangay => $data)
                <div class="barangay-summary-item">
                    <div class="barangay-name">{{ $barangay }}</div>
                    <div class="metric-value">{{ $data['count'] }}</div>
                    <div class="metric-value animals">{{ $data['animalCount'] }}</div>
                    <div class="metric-value transactions">{{ $data['transactionCount'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="section">
        <div class="section-title">Owner List</div>
        <table>
            <thead>
                <tr>
                    <th>Owner Name</th>
                    <th>Barangay</th>
                    <th>Animals</th>
                    <th>Transactions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($owners as $owner)
                    <tr>
                        <td style="font-weight: 500;">
                            {{ $owner->user->complete_name }}
                        </td>
                        <td>
                            <span class="badge">
                                {{ optional($owner->user->address)->barangay->barangay_name ?? 'No Barangay' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="count-circle" style="background-color: #e0f2fe; color: #0284c7;">
                                {{ $owner->animals->count() }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="count-circle" style="background-color: #dcfce7; color: #16a34a;">
                                {{ $owner->transactions->count() }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Page 1 of 1 | Valencia Veterinary Clinic | {{ now()->format('M d, Y') }}
    </div>
</body>
</html>