<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction #{{ $transaction->transaction_id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header { 
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 20px;
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
        .office-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0 5px;
        }
        .office-subtitle {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .transaction-number {
            font-size: 18px;
            color: #333;
            font-weight: bold;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 5px;
            display: inline-block;
        }
        .section { 
            margin-bottom: 30px;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .section-title { 
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .grid { 
            width: 100%;
            border-collapse: collapse;
        }
        .grid-row { 
            border-bottom: 1px solid #f3f4f6;
        }
        .grid-cell { 
            padding: 12px 8px;
            vertical-align: top;
        }
        .label { 
            font-weight: bold;
            color: #333;
            min-width: 120px;
            display: inline-block;
        }
        .value {
            color: #333;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
 
        <div class="transaction-number">Transaction #{{ $transaction->transaction_id }}</div>
    </div>

    <div class="section">
        <div class="section-title">Transaction Overview</div>
        <table class="grid">
            <tr class="grid-row">
                <td class="grid-cell">
                    <span class="label">Status:</span>
                    <span class="status-badge status-{{ $transaction->status == 0 ? 'pending' : ($transaction->status == 1 ? 'completed' : 'cancelled') }}">
                        {{ $transaction->status == 0 ? 'Pending' : ($transaction->status == 1 ? 'Completed' : 'Cancelled') }}
                    </span>
                </td>
                <td class="grid-cell">
                    <span class="label">Date:</span>
                    <span class="value">{{ $transaction->created_at->format('F j, Y \a\t g:i A') }}</span>
                </td>
            </tr>
            <tr class="grid-row">
                <td class="grid-cell" colspan="2">
                    <span class="label">Transaction Type:</span>
                    <span class="value">
                        {{ $transaction->transactionType->type_name ?? 'N/A' }}
                        @if($transaction->transactionSubtype)
                            - {{ $transaction->transactionSubtype->subtype_name }}
                        @endif
                    </span>
                </td>
            </tr>
            @if($transaction->transactionSubtype && $transaction->transactionSubtype->id == 8)
            <tr class="grid-row">
                <td class="grid-cell" colspan="2">
                    <span class="label">Vaccine Information:</span>
                    <div class="value" style="margin-top: 8px;">
                        <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 4px;">
                            @if($transaction->vaccine)
                                <tr>
                                    <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-weight: 600;">Vaccine Name:</span>
                                        <span style="margin-left: 8px;">{{ $transaction->vaccine->vaccine_name }}</span>
                                    </td>
                                </tr>
                               

                            @else
                                <tr>
                                    <td style="padding: 8px;">
                                        <span style="color: #dc2626;">No vaccine information available</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Animal Information</div>
        <table class="grid">
            <tr class="grid-row">
                <td class="grid-cell">
                    <span class="label">Name:</span>
                    <span class="value">{{ $transaction->animal->name }}</span>
                </td>
                <td class="grid-cell">
                    <span class="label">Species/Breed:</span>
                    <span class="value">
                        {{ $transaction->animal->species->name ?? 'Unknown species' }} 
                        ({{ $transaction->animal->breed->name ?? 'Unknown breed' }})
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Owner Information</div>
        <table class="grid">
            <tr class="grid-row">
                <td class="grid-cell">
                    <span class="label">Name:</span>
                    <span class="value">{{ optional($transaction->owner->user)->complete_name ?? 'N/A' }}</span>
                </td>
                <td class="grid-cell">
                    <span class="label">Contact:</span>
                    <span class="value">{{ optional($transaction->owner->user)->contact_no ?? 'No contact number' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Staff Information</div>
        <table class="grid">
            <tr class="grid-row">
                <td class="grid-cell">
                    <span class="label">Veterinarian:</span>
                    <span class="value">{{ optional($transaction->vet)->complete_name ?? 'Not assigned' }}</span>
                </td>
                <td class="grid-cell">
                    <span class="label">Technician:</span>
                    <span class="value">{{ optional($transaction->technician)->full_name ?? 'Not assigned' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This is an official document from the City Veterinarians Office of Valencia.</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>