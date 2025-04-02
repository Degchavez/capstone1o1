<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Report</h1>
        <p>{{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
    </div>

    <div>
        <h2>Summary</h2>
        <p>Total Transactions: {{ $summary['total'] }}</p>
        <p>Status Breakdown:</p>
        <ul>
            <li>Pending: {{ $summary['byStatus']['pending'] }}</li>
            <li>Completed: {{ $summary['byStatus']['completed'] }}</li>
            <li>Cancelled: {{ $summary['byStatus']['cancelled'] }}</li>
        </ul>
    </div>

    @if(count($transactions) > 0)
        <h2>Transaction Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Animal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td>{{ $transaction->transactionType->type_name }}</td>
                        <td>{{ $transaction->owner->user->complete_name }}</td>
                        <td>{{ $transaction->animal->name }} ({{ $transaction->animal->species->name }})</td>
                        <td>
                            @if($transaction->status === 0)
                                Pending
                            @elseif($transaction->status === 1)
                                Completed
                            @else
                                Cancelled
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No transactions found for this period.</p>
    @endif
</body>
</html>