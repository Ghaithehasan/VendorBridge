<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $rfq->rfq_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1a1a18;
            font-size: 11px;
            line-height: 1.6;
            background: #fff;
        }

        /* ── PAGE WRAPPER ── */
        .page {
            padding: 40px 44px;
        }

        /* ── HEADER ── */
        .header {
            border-bottom: 2px solid #0a0f1a;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: bottom;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: bottom;
            text-align: right;
            width: 40%;
        }
        .doc-type {
            font-size: 9px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .doc-number {
            font-size: 22px;
            font-weight: bold;
            color: #0a0f1a;
            letter-spacing: -0.5px;
        }
        .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #0a0f1a;
            margin-bottom: 2px;
        }
        .company-sub {
            font-size: 10px;
            color: #6b7280;
        }
        .pr-ref {
            margin-top: 8px;
            font-size: 10px;
            color: #6b7280;
        }
        .pr-ref span {
            color: #0a0f1a;
            font-weight: bold;
        }

        /* ── STATUS BADGE ── */
        .status-bar {
            margin-bottom: 24px;
            padding: 8px 14px;
            background: #f8f7f4;
            border-left: 3px solid #0a0f1a;
        }
        .status-bar-inner {
            display: table;
            width: 100%;
        }
        .status-item {
            display: table-cell;
            width: 25%;
        }
        .status-label {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 3px;
        }
        .status-value {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a18;
        }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 8px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 0.5px solid #e8e3da;
        }

        /* ── INFO GRID ── */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 22px;
        }
        .info-cell {
            display: table-cell;
            width: 50%;
            padding-right: 20px;
            vertical-align: top;
        }
        .info-cell:last-child { padding-right: 0; }
        .info-label {
            font-size: 8px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 11px;
            color: #1a1a18;
            font-weight: bold;
        }
        .info-sub {
            font-size: 10px;
            color: #6b7280;
        }

        /* ── TABLES ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .data-table thead tr {
            background: #0a0f1a;
        }
        .data-table thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #f0f9ff;
            font-weight: normal;
        }
        .data-table tbody tr {
            border-bottom: 0.5px solid #f0ece6;
        }
        .data-table tbody tr:last-child {
            border-bottom: none;
        }
        .data-table tbody td {
            padding: 10px 12px;
            font-size: 11px;
            color: #374151;
        }
        .data-table tbody tr:nth-child(even) td {
            background: #faf8f5;
        }
        .highlight-row td {
            font-weight: bold;
            color: #1a1a18;
        }

        /* ── COMMERCIAL TERMS ── */
        .terms-grid {
            display: table;
            width: 100%;
            margin-bottom: 22px;
            border: 0.5px solid #e8e3da;
        }
        .terms-row {
            display: table-row;
        }
        .terms-cell {
            display: table-cell;
            width: 33.33%;
            padding: 12px 14px;
            border-right: 0.5px solid #e8e3da;
            vertical-align: top;
        }
        .terms-cell:last-child { border-right: none; }
        .terms-label {
            font-size: 8px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .terms-value {
            font-size: 11px;
            color: #1a1a18;
            font-weight: bold;
        }

        /* ── INSTRUCTIONS BOX ── */
        .instructions {
            border: 0.5px solid #e8e3da;
            margin-bottom: 28px;
        }
        .instructions-header {
            background: #f8f7f4;
            padding: 8px 14px;
            border-bottom: 0.5px solid #e8e3da;
        }
        .instructions-label {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #6b7280;
        }
        .instructions-body {
            padding: 12px 14px;
            font-size: 11px;
            color: #374151;
            line-height: 1.7;
        }

        /* ── FOOTER ── */
        .footer {
            border-top: 0.5px solid #e8e3da;
            padding-top: 14px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 60%;
            font-size: 9px;
            color: #9ca3af;
        }
        .footer-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            font-size: 9px;
            color: #9ca3af;
        }

        /* ── DIVIDER ── */
        .section-gap { margin-bottom: 22px; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-top">
            <div class="header-left">
                <div class="doc-type">Request for Quotation</div>
                <div class="doc-number">{{ $rfq->rfq_number }}</div>
                <div class="pr-ref">
                    Generated from PR: <span>{{ $rfq->purchaseRequestLine->purchaseRequest->pr_number }}</span>
                </div>
            </div>
            <div class="header-right">
                <div class="company-name">ProcureFlow</div>
                <div class="company-sub">Manufacturing Procurement System</div>
                <div class="company-sub" style="margin-top:6px;color:#9ca3af">
                    Issued: {{ $rfq->issued_at ? $rfq->issued_at->format('d M Y') : now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── STATUS BAR ── --}}
    <div class="status-bar">
        <div class="status-bar-inner">
            <div class="status-item">
                <div class="status-label">RFQ Date</div>
                <div class="status-value">{{ $rfq->rfq_date->format('d M Y') }}</div>
            </div>
            <div class="status-item">
                <div class="status-label">Quotation Due</div>
                <div class="status-value">{{ $rfq->quotation_due_date->format('d M Y') }}</div>
            </div>
            <div class="status-item">
                <div class="status-label">Currency</div>
                <div class="status-value">
                    {{ $rfq->currency ? $rfq->currency->code : '—' }}
                    @if($rfq->currency)
                        ({{ $rfq->currency->symbol }})
                    @endif
                </div>
            </div>
            <div class="status-item">
                <div class="status-label">Status</div>
                <div class="status-value">{{ strtoupper($rfq->status) }}</div>
            </div>
        </div>
    </div>

    {{-- ── REQUESTER INFO ── --}}
    <div class="section-title">Request Origin</div>
    <div class="info-grid section-gap">
        <div class="info-cell">
            <div class="info-label">Requested By</div>
            <div class="info-value">{{ $rfq->purchaseRequestLine->purchaseRequest->requester->name }}</div>
            <div class="info-sub">{{ $rfq->purchaseRequestLine->purchaseRequest->department->name }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Issued By</div>
            <div class="info-value">{{ $rfq->issuer?->name ?? '—' }}</div>
            <div class="info-sub">Purchasing Officer</div>
        </div>
    </div>

    {{-- ── MATERIAL DETAILS ── --}}
    <div class="section-title">Material Request Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:40%">Material</th>
                <th style="width:20%">Quantity Required</th>
                <th style="width:20%">Unit of Measure</th>
                <th style="width:20%">Required Delivery</th>
            </tr>
        </thead>
        <tbody>
            <tr class="highlight-row">
                <td>{{ $rfq->rawMaterial->name }}</td>
                <td>{{ number_format((float) $rfq->quantity, 4) }}</td>
                <td>{{ $rfq->unit->name }} ({{ $rfq->unit->symbol }})</td>
                <td>{{ $rfq->required_delivery_date->format('d M Y') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ── COMMERCIAL TERMS ── --}}
    <div class="section-title">Commercial Terms</div>
    <div class="terms-grid section-gap">
        <div class="terms-row">
            <div class="terms-cell">
                <div class="terms-label">Currency</div>
                <div class="terms-value">
                    {{ $rfq->currency ? $rfq->currency->code . ' — ' . $rfq->currency->name : '—' }}
                </div>
            </div>
            <div class="terms-cell">
                <div class="terms-label">Payment Terms</div>
                <div class="terms-value">{{ $rfq->payment_terms ?: 'To be confirmed' }}</div>
            </div>
            <div class="terms-cell">
                <div class="terms-label">Delivery Location</div>
                <div class="terms-value">{{ $rfq->delivery_location ?: 'To be confirmed' }}</div>
            </div>
        </div>
    </div>

    {{-- ── VENDORS ── --}}
    <div class="section-title">Vendors Contacted</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:35%">Vendor Name</th>
                <th style="width:20%">Country</th>
                <th style="width:35%">Email</th>
                <th style="width:10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rfq->recipients as $recipient)
                <tr>
                    <td style="font-weight:bold;color:#1a1a18">{{ $recipient->vendor->name }}</td>
                    <td>{{ $recipient->vendor->country ?: '—' }}</td>
                    <td>{{ $recipient->vendor->email }}</td>
                    <td style="text-transform:uppercase;font-size:9px;letter-spacing:1px;color:#6b7280">
                        {{ $recipient->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── INSTRUCTIONS ── --}}
    <div class="instructions">
        <div class="instructions-header">
            <div class="instructions-label">Instructions for Vendor</div>
        </div>
        <div class="instructions-body">
            Please provide your official quotation before the due date indicated above. Your response must include:
            <br><br>
            &bull; Unit price in {{ $rfq->currency ? $rfq->currency->code . ' (' . $rfq->currency->symbol . ')' : 'the specified currency' }}<br>
            &bull; Estimated lead time in calendar days<br>
            &bull; Minimum order quantity (if applicable)<br>
            &bull; Payment terms and conditions<br>
            &bull; Validity period of the quoted price<br>
            <br>
            Quotations received after the due date may not be considered.
            Please direct all correspondence to the issuing officer listed above.
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            {{ $rfq->rfq_number }} &nbsp;·&nbsp;
            {{ $rfq->purchaseRequestLine->purchaseRequest->pr_number }} &nbsp;·&nbsp;
            ProcureFlow Manufacturing System
        </div>
        <div class="footer-right">
            Generated {{ now()->format('d M Y \a\t H:i') }}
        </div>
    </div>

</div>
</body>
</html>