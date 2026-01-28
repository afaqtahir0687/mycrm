<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: white;
            padding: 20px;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            border-bottom: 3px solid #0078d4;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .company-info h1 {
            font-size: 24px;
            color: #0078d4;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 11px;
            margin: 2px 0;
        }
        
        .quotation-title {
            text-align: right;
        }
        
        .quotation-title h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .quotation-title .quotation-number {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-left: 3px solid #0078d4;
        }
        
        .info-box h3 {
            font-size: 12px;
            color: #0078d4;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .info-box p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background: #0078d4;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .totals-section {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        
        .totals-table {
            width: 100%;
        }
        
        .totals-table td {
            padding: 8px 10px;
            font-size: 11px;
        }
        
        .totals-table td:first-child {
            text-align: right;
            color: #666;
        }
        
        .totals-table td:last-child {
            text-align: right;
            font-weight: 600;
        }
        
        .totals-table .total-row {
            background: #f3f2f1;
            font-size: 14px;
            font-weight: 700;
            border-top: 2px solid #0078d4;
        }
        
        .terms-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .terms-section h3 {
            font-size: 12px;
            color: #0078d4;
            margin-bottom: 10px;
        }
        
        .terms-section p {
            font-size: 11px;
            color: #666;
            line-height: 1.8;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border-left: 3px solid #ffaa44;
        }
        
        .notes-section h3 {
            font-size: 12px;
            color: #ffaa44;
            margin-bottom: 8px;
        }
        
        .notes-section p {
            font-size: 11px;
            color: #666;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            .print-container {
                max-width: 100%;
            }
            
            @page {
                margin: 1cm;
            }
        }
        
        .print-actions {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f3f2f1;
            border-radius: 2px;
        }
        
        .print-actions button {
            padding: 10px 20px;
            background: #0078d4;
            color: white;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .print-actions button:hover {
            background: #106ebe;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print print-actions">
            <button onclick="window.print()">Print Quotation</button>
            <button onclick="window.close()">Close</button>
        </div>
        
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <h1>Your Company Name</h1>
                    <p>Company Address Line 1</p>
                    <p>City, State, Postal Code</p>
                    <p>Phone: +1-555-0100 | Email: info@company.com</p>
                    <p>Website: www.company.com</p>
                </div>
                <div class="quotation-title">
                    <h2>QUOTATION</h2>
                    <p class="quotation-number">Quotation #: {{ $quotation->quotation_number }}</p>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-box">
                <h3>Bill To</h3>
                @if($quotation->account)
                    <p><strong>{{ $quotation->account->account_name }}</strong></p>
                    @if($quotation->account->address)
                        <p>{{ $quotation->account->address }}</p>
                    @endif
                    @if($quotation->account->city || $quotation->account->state || $quotation->account->postal_code)
                        <p>
                            @if($quotation->account->city){{ $quotation->account->city }}, @endif
                            @if($quotation->account->state){{ $quotation->account->state }} @endif
                            @if($quotation->account->postal_code){{ $quotation->account->postal_code }}@endif
                        </p>
                    @endif
                    @if($quotation->account->country)
                        <p>{{ $quotation->account->country }}</p>
                    @endif
                @elseif($quotation->contact)
                    <p><strong>{{ $quotation->contact->first_name }} {{ $quotation->contact->last_name }}</strong></p>
                    @if($quotation->contact->email)
                        <p>Email: {{ $quotation->contact->email }}</p>
                    @endif
                    @if($quotation->contact->phone)
                        <p>Phone: {{ $quotation->contact->phone }}</p>
                    @endif
                @else
                    <p>N/A</p>
                @endif
            </div>
            
            <div class="info-box">
                <h3>Quotation Details</h3>
                <p><strong>Date:</strong> {{ $quotation->quotation_date ? $quotation->quotation_date->format('F d, Y') : 'N/A' }}</p>
                @if($quotation->valid_until)
                    <p><strong>Valid Until:</strong> {{ $quotation->valid_until->format('F d, Y') }}</p>
                @endif
                <p><strong>Status:</strong> {{ ucfirst($quotation->status) }}</p>
                @if($quotation->contact)
                    <p><strong>Contact:</strong> {{ $quotation->contact->first_name }} {{ $quotation->contact->last_name }}</p>
                @endif
                @if($quotation->deal)
                    <p><strong>Deal:</strong> {{ $quotation->deal->deal_name }}</p>
                @endif
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 15%;" class="text-right">Quantity</th>
                    <th style="width: 15%;" class="text-right">Unit Price</th>
                    <th style="width: 20%;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Quotation Services</td>
                    <td class="text-right">1</td>
                    <td class="text-right">{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                    <td class="text-right">{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                </tr>
                @if($quotation->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td>- {{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($quotation->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total Amount:</td>
                    <td>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->total_amount ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>
        
        @if($quotation->terms_conditions)
        <div class="terms-section">
            <h3>Terms & Conditions</h3>
            <p>{{ nl2br(e($quotation->terms_conditions)) }}</p>
        </div>
        @endif
        
        @if($quotation->notes)
        <div class="notes-section">
            <h3>Notes</h3>
            <p>{{ nl2br(e($quotation->notes)) }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p>This quotation is valid until {{ $quotation->valid_until ? $quotation->valid_until->format('F d, Y') : 'further notice' }}.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }} by {{ $quotation->creator->name ?? 'System' }}</p>
        </div>
    </div>
</body>
</html>
