<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div>
                <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;margin:0;letter-spacing:-0.3px">{{ $rfq->rfq_number }}</h1>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:4px 0 0 0">Vendor-facing RFQ snapshot generated from an approved PR line.</p>
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:flex-end">
                @if ($rfq->status === 'draft' && in_array(auth()->user()->role, ['purchasing_officer', 'admin']))
                    <a href="{{ route('rfqs.edit', $rfq->rfq_id) }}"
                       style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#374151;padding:9px 18px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff;text-decoration:none;display:inline-block">
                        Edit RFQ
                    </a>
                @endif

                {{-- Draft: Issue — purchasing_officer, admin only --}}
                @if ($rfq->status === 'draft' && in_array(auth()->user()->role, ['purchasing_officer', 'admin']))
                    <form method="POST" action="{{ route('rfqs.issue', $rfq->rfq_id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#f0f9ff;padding:9px 18px;border:none;border-radius:7px;background:#0d1117;cursor:pointer"
                                onclick="return confirm('Issue this RFQ to vendors?')">
                            Issue RFQ
                        </button>
                    </form>
                @endif

                {{-- Draft: Cancel — admin only --}}
                @if ($rfq->status === 'draft' && auth()->user()->role === 'admin')
                    <form method="POST" action="{{ route('rfqs.cancel', $rfq->rfq_id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#991b1b;padding:9px 18px;border:0.5px solid #fecaca;border-radius:7px;background:#fef2f2;cursor:pointer"
                                onclick="return confirm('Cancel this RFQ? This action cannot be undone.')">
                            Cancel RFQ
                        </button>
                    </form>
                @endif

                {{-- Issued: Download PDF — all roles --}}
                @if (in_array($rfq->status, ['issued', 'awarded', 'closed']))
                    <a href="{{ route('rfqs.pdf', $rfq->rfq_id) }}"
                       style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#f0f9ff;padding:9px 18px;border:none;border-radius:7px;background:#0d1117;text-decoration:none;display:inline-block">
                        Download PDF
                    </a>
                    @if (in_array(auth()->user()->role, ['procurement_manager', 'admin']))
                        <button type="button" disabled
                                style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;color:#9ca3af;padding:9px 14px;border:0.5px solid #e5e7eb;border-radius:7px;background:#f9fafb;cursor:not-allowed">
                            Mark as Awarded
                        </button>
                        <button type="button" disabled
                                style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;color:#9ca3af;padding:9px 14px;border:0.5px solid #e5e7eb;border-radius:7px;background:#f9fafb;cursor:not-allowed">
                            Close RFQ
                        </button>
                    @endif
                @endif

                <a href="{{ route('purchase-requests.show', $rfq->purchaseRequestLine->purchaseRequest->pr_id) }}"
                   style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#374151;padding:9px 18px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff;text-decoration:none;display:inline-block">
                    View Source PR
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div style="margin-bottom:18px;padding:11px 16px;border:0.5px solid #bbf7d0;background:#f0fdf4;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;color:#166534">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- 1. RFQ header card --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">RFQ HEADER</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">Request for Quotation</h2>
            </div>
            <div style="padding:18px 20px;display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(180px,1fr))">
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">RFQ NUMBER</p>
                    <p style="font-family:'DM Mono',monospace;font-size:13px;font-weight:500;color:#1a1a18;margin:0">{{ $rfq->rfq_number }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">STATUS</p>
                    @php
                        $statusStyles = [
                            'draft'     => 'background:#fffbeb;color:#92400e;border:0.5px solid #fde68a',
                            'issued'    => 'background:#eff6ff;color:#1d4ed8;border:0.5px solid #93c5fd',
                            'awarded'   => 'background:#f0fdf4;color:#166534;border:0.5px solid #86efac',
                            'closed'    => 'background:#f3f4f6;color:#4b5563;border:0.5px solid #d1d5db',
                            'cancelled' => 'background:#fef2f2;color:#991b1b;border:0.5px solid #fecaca',
                        ];
                    @endphp
                    <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;font-weight:500;letter-spacing:0.06em;padding:4px 10px;border-radius:6px;{{ $statusStyles[$rfq->status] ?? 'background:#f3f4f6;color:#4b5563' }}">
                        {{ strtoupper($rfq->status) }}
                    </span>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">RFQ DATE</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->rfq_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">QUOTATION DUE</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->quotation_due_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">CURRENCY</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">
                        @if ($rfq->currency)
                            {{ $rfq->currency->code }} — {{ $rfq->currency->name }} ({{ $rfq->currency->symbol }})
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">PAYMENT TERMS</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->payment_terms ?: '—' }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">DELIVERY LOCATION</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->delivery_location ?: '—' }}</p>
                </div>
            </div>
        </div>

        {{-- 2. Material snapshot card --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">MATERIAL SNAPSHOT</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">{{ $rfq->rawMaterial->name }}</h2>
            </div>
            <div style="padding:18px 20px;display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(180px,1fr))">
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">QUANTITY</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ number_format((float) $rfq->quantity, 4) }} {{ $rfq->unit->symbol }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">REQUIRED DELIVERY</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->required_delivery_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">SOURCE PR</p>
                    <a href="{{ route('purchase-requests.show', $rfq->purchaseRequestLine->purchaseRequest->pr_id) }}"
                       style="font-family:'DM Mono',monospace;font-size:12px;font-weight:500;color:#1a1a18;text-decoration:underline">
                        {{ $rfq->purchaseRequestLine->purchaseRequest->pr_number }}
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. Issuer card (issued/awarded/closed only) --}}
        @if (in_array($rfq->status, ['issued', 'awarded', 'closed']) && $rfq->issued_by)
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="padding:18px 20px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">ISSUED BY</p>
                    <p style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0 0 4px 0">{{ $rfq->issuer?->name ?? 'System' }}</p>
                    <p style="font-family:'DM Mono',monospace;font-size:12px;color:#6b7280;margin:0">{{ $rfq->issued_at?->format('d M Y \a\t H:i') ?? '—' }}</p>
                </div>
            </div>
        @endif

        {{-- Recipients --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">VENDORS CONTACTED</p>
                    <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">Recipients</h2>
                </div>
                @if (in_array($rfq->status, ['issued', 'awarded', 'closed']))
                    <a href="{{ route('rfqs.pdf', $rfq->rfq_id) }}"
                       style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;color:#f0f9ff;padding:8px 16px;border:none;border-radius:6px;background:#0d1117;text-decoration:none;display:inline-block">
                        Download PDF
                    </a>
                @endif
            </div>
            <div style="padding:18px 20px;display:flex;flex-direction:column;gap:12px">
                @forelse ($rfq->recipients as $recipient)
                    <div style="background:#f8f7f4;border:0.5px solid #e8e3da;border-radius:8px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                        <div>
                            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;color:#1a1a18;margin:0">{{ $recipient->vendor->name }}</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#6b7280;margin:4px 0 0 0">
                                {{ $recipient->vendor->country }}
                                @if ($recipient->vendor->contact_email)
                                    · {{ $recipient->vendor->contact_email }}
                                @endif
                            </p>
                            <span style="display:inline-block;margin-top:8px;font-family:'DM Mono',monospace;font-size:10px;padding:3px 8px;border-radius:4px;background:#fff;border:0.5px solid #e8e3da;color:#6b7280">
                                {{ strtoupper($recipient->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9ca3af;margin:0">No vendors linked to this RFQ.</p>
                @endforelse
            </div>
        </div>

        {{-- 5. Status decision card (bottom) --}}
        @if ($rfq->status === 'draft')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#f59e0b"></div>
                <div style="padding:14px 16px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#92400e;margin:0 0 6px 0">PENDING REVIEW</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:0">Awaiting procurement manager</p>
                </div>
            </div>
        @endif

        @if ($rfq->status === 'issued')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#3b82f6"></div>
                <div style="padding:14px 16px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#1d4ed8;margin:0 0 6px 0">ISSUED</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:0">Distributed to vendors</p>
                </div>
            </div>
        @endif

        @if ($rfq->status === 'awarded')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#22c55e"></div>
                <div style="padding:14px 16px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#166534;margin:0 0 6px 0">AWARDED</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:0">Vendor selected</p>
                </div>
            </div>
        @endif

        @if ($rfq->status === 'closed')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#9ca3af"></div>
                <div style="padding:14px 16px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#4b5563;margin:0 0 6px 0">CLOSED</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:0">No vendor selected</p>
                </div>
            </div>
        @endif

        @if ($rfq->status === 'cancelled')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#ef4444"></div>
                <div style="padding:14px 16px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#991b1b;margin:0">CANCELLED</p>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
