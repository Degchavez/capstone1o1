<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vaccination Report</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
            margin-bottom: 1.5cm; /* Add space for footer */
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333333;
            margin: 0;
            padding: 20px;
            padding-bottom: 60px; /* Space for footer */
            background-color: #ffffff;
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
        }
        
        .header h1 {
            color: #2c3e50;
            margin: 0;
            padding: 0;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .logo-container {
            margin: 15px 0;
        }
        
        .logo {
            width: 80px;
            height: auto;
        }
        
        .office-title {
            font-size: 20px;
            color: #2c3e50;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .office-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .report-info {
            font-size: 14px;
            color: #34495e;
            margin: 10px 0;
        }
        
        .section-title {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 12px 20px;
            margin: 25px 0 15px 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
            page-break-inside: auto;
        }
        
        th {
            background-color: #34495e;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #2c3e50;
        }
        
        td {
            padding: 10px;
            border: 1px solid #bdc3c7;
            font-size: 11px;
            color: #2c3e50;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            color: #ffffff;
        }
        
        .status-0 { background-color: #f39c12; } /* Pending */
        .status-1 { background-color: #27ae60; } /* Completed */
        .status-2 { background-color: #c0392b; } /* Cancelled */
        
        .summary {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-box {
            display: inline-block;
            width: 30%;
            margin: 1%;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 6px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .stat-box strong {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 13px;
        }
        
        .stat-box span {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
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
        
        .page-break {
            page-break-after: always;
        }

        .stats-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .stats-header h2 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }

        .stats-date {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Table container styles */
        .table-container {
            margin-bottom: 40px;
            page-break-inside: auto;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
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
                        <h1 style="margin: 0; font-size: 20px;">Vaccination Report</h1>
                        <div style="font-size: 14px;">City Veterinarians Office of Valencia</div>
                        <div style="font-size: 12px;">Official Vaccination Record</div>
                        <p style="font-size: 10px; color: #718096;">Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Vaccination Records Section -->
        <div class="table-container">
            <div class="section-title">Vaccination Records</div>
            <div class="report-info">
                <p>Location: <b>{{ isset($barangay_name) ? $barangay_name : 'All Barangays' }}</b></p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Animal</th>
                        <th>Species</th>
                        <th>Owner</th>
                        <th>Barangay</th>
                        <th>Vaccine</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vaccinations as $record)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($record['created_at'])->format('M d, Y') }}</td>
                            <td><strong>{{ $record['animal'] }}</strong></td>
                            <td>{{ $record['species'] }}</td>
                            <td>{{ $record['owner'] }}</td>
                            <td>{{ $record['barangay'] }}</td>
                            <td>{{ $record['vaccine'] }}</td>
                            <td>
                                <span class="status-badge status-{{ $record['status'] }}">
                                    @if($record['status'] === 0)
                                        Pending
                                    @elseif($record['status'] === 1)
                                        Completed
                                    @else
                                        Cancelled
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">No vaccination records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Statistics Section -->
        <div class="summary-section">
            <div class="stats-header">
                <h2>Summary Statistics</h2>
                <div class="stats-date">
                    {{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }}
                </div>
                <div class="report-info">
                    <p>Location: {{ isset($barangay_name) ? $barangay_name : 'All Barangays' }}</p>
                </div>
            </div>

            <!-- Vaccine Type Breakdown -->
            <div class="table-container">
                <div class="section-title">Vaccine Type Breakdown</div>
                <table>
                    <thead>
                        <tr>
                            <th>Vaccine Type</th>
                            <th>Total</th>
                            <th>Completed</th>
                            <th>Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['byVaccine'] as $vaccine => $data)
                            <tr>
                                <td><strong>{{ $vaccine }}</strong></td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ $data['completed'] }}</td>
                                <td>{{ $data['pending'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">No vaccine data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Species Breakdown -->
            <div class="table-container">
                <div class="section-title">Species Breakdown</div>
                <table>
                    <thead>
                        <tr>
                            <th>Species</th>
                            <th>Total</th>
                            <th>Completed</th>
                            <th>Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['bySpecies'] as $species => $data)
                            <tr>
                                <td><strong>{{ $species }}</strong></td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ $data['completed'] }}</td>
                                <td>{{ $data['pending'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">No species data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Barangay Breakdown -->
            <div class="table-container">
                <div class="section-title">Barangay Breakdown</div>
                <table>
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th>Total</th>
                            <th>Completed</th>
                            <th>Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['byBarangay'] as $barangay => $data)
                            <tr>
                                <td><strong>{{ $barangay }}</strong></td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ $data['completed'] }}</td>
                                <td>{{ $data['pending'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">No barangay data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
