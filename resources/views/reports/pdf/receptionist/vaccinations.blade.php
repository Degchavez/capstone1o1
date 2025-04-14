<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vaccination Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
        }

        .header {
            background-color: #006d77;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .sub-header {
            background-color: #83c5be;
            padding: 10px;
            margin-bottom: 20px;
            color: #000;
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
        }

        .summary-table th, .data-table th {
            background-color: #edf6f9;
        }

        .section-title {
            background-color: #006d77;
            color: #fff;
            padding: 8px;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 40px;
            color: #777;
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
        <strong>Pending:</strong> {{ $summary['pending'] }} |
        <strong>Cancelled:</strong> {{ $summary['cancelled'] }}
    </div>

    {{-- By Vaccine --}}
    <div class="section-title">Summary by Vaccine</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Vaccine</th>
                <th>Total</th>
                <th>Completed</th>
                <th>Pending</th>
                <th>Cancelled</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['byVaccine'] as $vaccine => $data)
                <tr>
                    <td>{{ $vaccine }}</td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ $data['completed'] }}</td>
                    <td>{{ $data['pending'] }}</td>
                    <td>{{ $data['cancelled'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- By Species --}}
    <div class="section-title">Summary by Species</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Species</th>
                <th>Total</th>
                <th>Completed</th>
                <th>Pending</th>
                <th>Cancelled</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['bySpecies'] as $species => $data)
                <tr>
                    <td>{{ $species }}</td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ $data['completed'] }}</td>
                    <td>{{ $data['pending'] }}</td>
                    <td>{{ $data['cancelled'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Detailed Vaccination Records --}}
    <div class="section-title">Detailed Records</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Owner</th>
                <th>Animal</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Vaccine</th>
                <th>Status</th>
                <th>Vet</th>
                <th>Receptionist</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vaccinations as $record)
                <tr>
                    <td>{{ $record->created_at->format('M d, Y') }}</td>
                    <td>{{ $record->owner->user->name ?? 'N/A' }}</td>
                    <td>{{ $record->animal->name }}</td>
                    <td>{{ $record->animal->species->name ?? '—' }}</td>
                    <td>{{ $record->animal->breed->name ?? '—' }}</td>
                    <td>{{ $record->vaccine->vaccine_name ?? '—' }}</td>
                    <td>
                        @php
                            $statuses = ['Pending', 'Completed', 'Cancelled'];
                        @endphp
                        {{ $statuses[$record->status] ?? 'Unknown' }}
                    </td>
                    <td>{{ $record->vet->name ?? '—' }}</td>
                    <td>{{ $record->receptionist->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y - h:i A') }}</p>
    </div>

</body>
</html>
