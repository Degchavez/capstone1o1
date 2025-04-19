<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vaccination Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            background-color: #006d77;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .sub-header {
            background-color: #83c5be;
            padding: 10px;
            margin-bottom: 20px;
            color: #000;
            text-align: center;
        }

        .summary-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .summary-table th, .summary-table td,
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        .summary-table th, .data-table th {
            background-color: #edf6f9;
            font-weight: bold;
        }

        .section-title {
            background-color: #006d77;
            color: #fff;
            padding: 8px;
            margin-top: 30px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 40px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
            display: inline-block;
        }

        .status-0 { background-color: #f39c12; } /* Pending */
        .status-1 { background-color: #27ae60; } /* Completed */
        .status-2 { background-color: #c0392b; } /* Cancelled */

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Vaccination Report</h2>
        <p>{{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }}</p>
    </div>

    <div class="sub-header">
        <strong>Total Records:</strong> {{ $summary['total'] }} |
        <strong>Completed:</strong> {{ $summary['completed'] }} |
        <strong>Pending:</strong> {{ $summary['pending'] }}
    </div>

    <div class="sub-header">
        <strong>Barangay:</strong> {{ $barangay_name }}
    </div>

    <!-- By Vaccine Summary -->
    <div class="section-title">Summary by Vaccine</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Vaccine</th>
                <th>Total</th>
                <th>Completed</th>
                <th>Pending</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary['byVaccine'] as $vaccine => $data)
                <tr>
                    <td>{{ $vaccine }}</td>
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

    <!-- By Species Summary -->
    <div class="section-title">Summary by Species</div>
    <table class="summary-table">
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
                    <td>{{ $species }}</td>
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

    <!-- Add this after the By Species Summary -->
    <div class="section-title">Summary by Barangay</div>
    <table class="summary-table">
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
                    <td>{{ $barangay }}</td>
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

    <!-- Detailed Records -->
    <div class="section-title">Detailed Records</div>
    <table class="data-table">
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
                    <td>{{ $record['animal'] }}</td>
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

    <div class="footer">
        <p>Generated by: {{ auth()->user()->complete_name }} | {{ now()->format('F d, Y h:i A') }}</p>
        <p>Page {PAGE_NUM} of {PAGE_COUNT}</p>
    </div>
</body>
</html>
