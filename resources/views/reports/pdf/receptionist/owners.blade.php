<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Report</title>
    <style>
        @page {
                    margin: 0.5cm 1cm;
                    margin-bottom: 1.5cm; /* Add space for footer */
                }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #2d3748;
            background-color: #fff;
            margin-bottom: 60px; /* Space for footer */
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
            page-break-inside: auto;
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
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            background-color: white;
            border-top: 1px solid #ecf0f1;
            padding: 10px 20px;
            height: auto;
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

        .page-break {
            page-break-after: always;
        }

        /* Update table container styles */
        .table-container {
            margin-bottom: 40px;
            page-break-inside: auto;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            page-break-inside: auto;
        }

        /* Keep header with content */
        thead {
            display: table-header-group;
        }

        /* Prevent row splitting */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Summary section styles */
        .summary-section {
            page-break-before: always; /* Start on new page */
            margin-bottom: 60px; /* Space for footer */
        }

    </style>
</head>
<body>
    <!-- Main content section -->
    <div class="content-wrapper">
        <!-- Header -->
        <div class="header" style="display: block; text-align: center;">
            <table style="width: 100%; border: none; border-collapse: collapse; margin: 0 auto;">
                <tr>
                    <td style="width: 25%; text-align: right; vertical-align: middle; padding-right: 15px; border: none;">
                        <img src="{{ public_path('assets/1.jpg') }}" alt="Logo" style="width: 100px; height: auto;">
                    </td>
                    <td style="width: 75%; text-align: left; vertical-align: middle; border: none;">
                        <h1 style="margin: 0; font-size: 20px;">Owners Report</h1>
                        <div style="font-size: 14px;">City Veterinarians Office of Valencia</div>
                        <div style="font-size: 12px;">Official Owners Record</div>
                        <p style="font-size: 10px; color: #718096;">Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Owner List Section -->
        <div class="section table-container">
            <div class="section-title" style="margin-bottom: 10px;">Owner List</div>
            <div class="report-info">
                <p>Location: <b>{{ isset($barangay_name) ? $barangay_name : 'All Barangays' }}</b></p>
            </div>
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
                            <td>{{ $owner->user->complete_name }}</td>
                            <td>
                                <span class="badge">
                                    {{ optional($owner->user->address)->barangay->barangay_name ?? 'No Barangay' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="count-circle">{{ $owner->animals->count() }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="count-circle">{{ $owner->transactions->count() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="section-title">Summary Statistics</div>
            <div class="stat-box">
                <div class="stat-label">Total Owners</div>
                <div class="stat-value">{{ $summary['total'] }}</div>
            </div>
            
            <div class="section-title" style="margin-top: 10px;">Barangay Distribution</div>
            <div class="barangay-summary">
                <table>
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th style="text-align: center;">Owners</th>
                            <th style="text-align: center;">Animals</th>
                            <th style="text-align: center;">Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['byBarangay'] as $barangay => $data)
                            <tr>
                                <td>{{ $barangay }}</td>
                                <td style="text-align: center;">{{ $data['count'] }}</td>
                                <td style="text-align: center;">{{ $data['animalCount'] }}</td>
                                <td style="text-align: center;">{{ $data['transactionCount'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
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