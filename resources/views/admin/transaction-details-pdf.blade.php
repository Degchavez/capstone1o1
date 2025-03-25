<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction #{{ $transaction->transaction_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-bottom: 10px; }
        .grid { display: table; width: 100%; }
        .grid-row { display: table-row; }
        .grid-cell { display: table-cell; padding: 5px; }
        .label { font-weight: bold; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Details</h1>
        <p>Transaction #{{ $transaction->transaction_id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Transaction Overview</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <span class="label">Status:</span>
                    {{ $transaction->status == 0 ? 'Pending' : ($transaction->status == 1 ? 'Completed' : 'Cancelled') }}
                </div>
                <div class="grid-cell">
                    <span class="label">Date:</span>
                    {{ $transaction->created_at->format('M j, Y \a\t g:i A') }}
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <span class="label">Type:</span>
                    {{ $transaction->transactionType->type_name ?? 'N/A' }}
                    @if($transaction->transactionSubtype)
                        - {{ $transaction->transactionSubtype->subtype_name }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Animal Information</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <span class="label">Name:</span>
                    {{ $transaction->animal->name }}
                </div>
                <div class="grid-cell">
                    <span class="label">Species/Breed:</span>
                    {{ $transaction->animal->species->name ?? 'Unknown species' }} 
                    ({{ $transaction->animal->breed->name ?? 'Unknown breed' }})
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Owner Information</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <span class="label">Name:</span>
                    {{ optional($transaction->owner->user)->complete_name ?? 'N/A' }}
                </div>
                <div class="grid-cell">
                    <span class="label">Contact:</span>
                    {{ optional($transaction->owner->user)->contact_no ?? 'No contact number' }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Staff Information</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <span class="label">Veterinarian:</span>
                    {{ optional($transaction->vet)->complete_name ?? 'Not assigned' }}
                </div>
                <div class="grid-cell">
                    <span class="label">Technician:</span>
                    {{ optional($transaction->technician)->full_name ?? 'Not assigned' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>