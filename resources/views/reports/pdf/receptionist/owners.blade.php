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
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 24px;
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
            color: #2d3748;
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
            color: #2d3748;
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
            color: #2d3748;
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
            color: #2d3748;
            text-align: center;
        }
        .metric-value.animals {
            color: #2d3748;
        }
        .metric-value.transactions {
            color: #2d3748;
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
        <div class="logo-container">
            <img src="{{ public_path('assets/1.jpg') }}" alt="Logo" class="logo">
        </div>
        <div class="office-title">City Veterinarians Office of Valencia</div>
        <div class="office-subtitle">Official Onwers Record</div>
        <p>Barangay: {{ $filters['barangay'] }}</p>
    </div>

    
        
        <div class="section">
            <div class="section-title" style="margin-bottom: 10px;">Owner List</div>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background-color: #f7fafc;">
                    <tr>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left;">Owner Name</th>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left;">Barangay</th>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: center;">Animals</th>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: center;">Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($owners as $owner)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #edf2f7; font-weight: 500;">
                                {{ $owner->user->complete_name }}
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #edf2f7;">
                                <span class="badge" style="background-color: #edf2f7; padding: 4px 8px; border-radius: 6px;">
                                    {{ optional($owner->user->address)->barangay->barangay_name ?? 'No Barangay' }}
                                </span>
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #edf2f7; text-align: center;">
                                <span class="count-circle" style="background-color: #e0f2fe; color: #2d3748; padding: 5px 10px; border-radius: 999px;">
                                    {{ $owner->animals->count() }}
                                </span>
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #edf2f7; text-align: center;">
                                <span class="count-circle" style="background-color: #dcfce7; color: #2d3748; padding: 5px 10px; border-radius: 999px;">
                                    {{ $owner->transactions->count() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Summary Statistics</div>
            <div class="stat-box">
                <div class="stat-label">Total Owners</div>
                <div class="stat-value" style="color: #2d3748;">
                    {{ $summary['total'] }}
                </div>
            </div>
            
            <div class="section-title" style="margin-top: 10px;">Barangay Distribution</div>
            <div class="barangay-summary">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead style="background-color: #f7fafc; text-align: left;">
                        <tr>
                            <th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Barangay</th>
                            <th style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center;">Owners</th>
                            <th style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center;">Animals</th>
                            <th style="padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: center;">Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['byBarangay'] as $barangay => $data)
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #edf2f7;">{{ $barangay }}</td>
                                <td style="padding: 8px; text-align: center; border-bottom: 1px solid #edf2f7;">{{ $data['count'] }}</td>
                                <td style="padding: 8px; text-align: center; border-bottom: 1px solid #edf2f7;">{{ $data['animalCount'] }}</td>
                                <td style="padding: 8px; text-align: center; border-bottom: 1px solid #edf2f7;">{{ $data['transactionCount'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        

    <div class="footer">
        <p><strong>Generated by:</strong> {{ $receptionist->complete_name }}</p>
        <p>Period: {{ $dateFrom->toFormattedDateString() }} - {{ $dateTo->toFormattedDateString() }}</p>
        <p style="font-size: 10px; color: #718096;">Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        Page 1 of 1 | Valencia Veterinary Clinic | {{ now()->format('M d, Y') }}
    </div>
</body>
</html>