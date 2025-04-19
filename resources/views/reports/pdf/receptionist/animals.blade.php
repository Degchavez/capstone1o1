<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Animal Report</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333333;
            margin: 0;
            padding: 20px;
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
        
        .vaccination-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-yes {
            background-color: #27ae60;
            color: #ffffff;
        }
        
        .status-no {
            background-color: #e74c3c;
            color: #ffffff;
        }
        
        .status-not-required {
            background-color: #f1c40f;
            color: #ffffff;
        }
        
        .summary {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-box {
            display: inline-block;
            width: 22%;
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
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('assets/1.jpg') }}" alt="Logo" class="logo">
        </div>
        <h1>Animal Report</h1>
        <div class="office-title">City Veterinarians Office of Valencia</div>
        <div class="office-subtitle">Official Animals Record</div>
        <div class="report-info">
            <p>Report Period: {{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }}</p>
            <p>Location: {{ isset($barangay_name) ? $barangay_name : 'All Barangays' }}</p>
        </div>
    </div>

    <div class="section-title">Animal List</div>
    <table>
        <thead>
            <tr>
                <th>Animal Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Owner</th>
                <th>Barangay</th>
                <th>Vaccination Status</th>
                <th>Date Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($animals as $animal)
                <tr>
                    <td><strong>{{ $animal->name }}</strong></td>
                    <td>{{ $animal->species->name ?? 'N/A' }}</td>
                    <td>{{ $animal->breed->name ?? 'N/A' }}</td>
                    <td>{{ $animal->owner->user->complete_name ?? 'N/A' }}</td>
                    <td>{{ $animal->owner->user->address->barangay->barangay_name ?? 'N/A' }}</td>
                    <td>
                        @if($animal->is_vaccinated === 1)
                            <span class="vaccination-status status-yes">Vaccinated</span>
                        @elseif($animal->is_vaccinated === 0)
                            <span class="vaccination-status status-no">Not Vaccinated</span>
                        @else
                            <span class="vaccination-status status-not-required">Not Required</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($animal->created_at)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by: {{ auth()->user()->complete_name }} | {{ now()->format('F d, Y h:i A') }}</p>
        <p>Page {PAGE_NUM} of {PAGE_COUNT}</p>
    </div>

    <!-- Force page break before summary -->
    <div class="page-break"></div>

    <!-- Summary Statistics Page -->
    <div class="stats-header">
        <h2>Summary Statistics</h2>
        <div class="stats-date">
            {{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }}
        </div>
        <div class="report-info">
            <p>Location: {{ isset($barangay_name) ? $barangay_name : 'All Barangays' }}</p>
        </div>
    </div>

    <div class="summary">
        <div style="margin-top: 20px;">
            <div class="stat-box">
                <strong>Total Animals</strong>
                <span>{{ $summary['total'] }}</span>
            </div>
            <div class="stat-box">
                <strong>Vaccinated</strong>
                <span>{{ $summary['vaccinated'] }}</span>
            </div>
            <div class="stat-box">
                <strong>Not Vaccinated</strong>
                <span>{{ $summary['nonVaccinated'] }}</span>
            </div>
            <div class="stat-box">
                <strong>Not Required</strong>
                <span>{{ $summary['notRequired'] }}</span>
            </div>
        </div>
    </div>

    <!-- Add species breakdown -->
    <div class="section-title">Species Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Species</th>
                <th>Total</th>
                <th>Vaccinated</th>
                <th>Not Vaccinated</th>
                <th>Not Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['bySpecies'] as $species => $data)
                <tr>
                    <td><strong>{{ $species }}</strong></td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ $data['vaccinated'] }}</td>
                    <td>{{ $data['nonVaccinated'] }}</td>
                    <td>{{ $data['notRequired'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add breed breakdown -->
    <div class="section-title">Breed Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Breed</th>
                <th>Total</th>
                <th>Vaccinated</th>
                <th>Not Vaccinated</th>
                <th>Not Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['byBreed'] as $breed => $data)
                <tr>
                    <td><strong>{{ $breed }}</strong></td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ $data['vaccinated'] }}</td>
                    <td>{{ $data['nonVaccinated'] }}</td>
                    <td>{{ $data['notRequired'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by: {{ auth()->user()->complete_name }} | {{ now()->format('F d, Y h:i A') }}</p>
        <p>Page {PAGE_NUM} of {PAGE_COUNT}</p>
    </div>
</body>
</html>
