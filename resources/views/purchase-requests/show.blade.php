<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div>
                <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                    PROCUREMENT SYSTEM — PURCHASE REQUEST
                </p>
                <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px">
                    {{ $purchaseRequest->pr_number }}
                </h1>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:4px">
                    Purchase Request details, line items, and workflow actions.
                </p>
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">

                {{-- Edit: requester (own PR) or admin — draft only --}}
                @if (
                    $purchaseRequest->status === 'draft' &&
                    (
                        (auth()->user()->role === 'requester' && $purchaseRequest->requester_id === auth()->user()->user_id) ||
                        auth()->user()->role === 'admin'
                    )
                )
                    <a href="{{ route('purchase-requests.edit', $purchaseRequest->pr_id) }}"
                       style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:#374151;text-decoration:none;padding:8px 16px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff">
                        Edit PR
                    </a>
                @endif

                {{-- Submit: requester (own PR) or admin — draft only --}}
                @if (
                    $purchaseRequest->status === 'draft' &&
                    (
                        (auth()->user()->role === 'requester' && $purchaseRequest->requester_id === auth()->user()->user_id) ||
                        auth()->user()->role === 'admin'
                    )
                )
                    <form method="POST" action="{{ route('purchase-requests.submit', $purchaseRequest->pr_id) }}" style="margin:0">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#f0f9ff;padding:8px 16px;border:none;border-radius:7px;background:#0d1117;cursor:pointer"
                                onclick="return confirm('Submit this Purchase Request for approval? You will no longer be able to edit it.')">
                            Submit for Approval
                        </button>
                    </form>
                @endif

                {{-- Approve: procurement_manager or admin — submitted only --}}
                @if (
                    $purchaseRequest->status === 'submitted' &&
                    in_array(auth()->user()->role, ['procurement_manager', 'admin']) &&
                    !(auth()->user()->role === 'procurement_manager' && $purchaseRequest->requester_id === auth()->user()->user_id)
                )
                    <form method="POST" action="{{ route('purchase-requests.approve', $purchaseRequest->pr_id) }}" style="margin:0">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#fff;padding:8px 16px;border:none;border-radius:7px;background:#166534;cursor:pointer"
                                onclick="return confirm('Approve this Purchase Request?')">
                            Approve
                        </button>
                    </form>
                @endif

                {{-- Cancel: procurement_manager or admin — submitted only --}}
                @if (
                    $purchaseRequest->status === 'submitted' &&
                    in_array(auth()->user()->role, ['procurement_manager', 'admin'])
                )
                    <button type="button"
                            onclick="openCancelModal()"
                            style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:#991b1b;padding:8px 16px;border:0.5px solid #fecaca;border-radius:7px;background:#fef2f2;cursor:pointer">
                        Cancel PR
                    </button>
                @endif

                {{-- Back --}}
                <a href="{{ route('purchase-requests.index') }}"
                   style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:#6b7280;text-decoration:none;padding:8px 16px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- ── INFO CARDS ── --}}
        <div style="display:grid;gap:12px;grid-template-columns:repeat(5,minmax(0,1fr))">

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:16px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">REQUESTER</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;color:#1a1a18">{{ $purchaseRequest->requester->name }}</p>
            </div>

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:16px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">DEPARTMENT</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;color:#1a1a18">{{ $purchaseRequest->department->name }}</p>
            </div>

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:16px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">REQUEST DATE</p>
                <p style="font-family:'DM Mono',monospace;font-size:13px;font-weight:500;color:#1a1a18">{{ $purchaseRequest->request_date->format('d M Y') }}</p>
            </div>

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:16px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">STATUS</p>
                @if($purchaseRequest->status === 'draft')
                    <span style="font-family:'DM Mono',monospace;font-size:11px;padding:4px 10px;border-radius:5px;background:#f1efe8;color:#5f5e5a;letter-spacing:0.04em">DRAFT</span>
                @elseif($purchaseRequest->status === 'submitted')
                    <span style="font-family:'DM Mono',monospace;font-size:11px;padding:4px 10px;border-radius:5px;background:#fffbeb;color:#92400e;letter-spacing:0.04em">SUBMITTED</span>
                @elseif($purchaseRequest->status === 'approved')
                    <span style="font-family:'DM Mono',monospace;font-size:11px;padding:4px 10px;border-radius:5px;background:#f0fdf4;color:#166534;letter-spacing:0.04em">APPROVED</span>
                @elseif($purchaseRequest->status === 'cancelled')
                    <span style="font-family:'DM Mono',monospace;font-size:11px;padding:4px 10px;border-radius:5px;background:#fef2f2;color:#991b1b;letter-spacing:0.04em">CANCELLED</span>
                @endif
            </div>

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:16px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">LINES</p>
                <p style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px">{{ $purchaseRequest->lines->count() }}</p>
            </div>

        </div>

        {{-- ── NOTES ── --}}
        @if ($purchaseRequest->notes)
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;padding:18px">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:8px">NOTES</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;line-height:1.6">{{ $purchaseRequest->notes }}</p>
            </div>
        @endif

        {{-- ── DECISION AUDIT TRAIL ── --}}
        @if ($purchaseRequest->status === 'approved')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#22c55e"></div>
                <div style="display:flex;gap:14px;align-items:flex-start;padding:18px">
                    <div style="width:32px;height:32px;border-radius:50%;background:#f0fdf4;border:1px solid #22c55e;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:'DM Mono',monospace;font-size:14px;color:#166534;font-weight:600">✓</div>
                    <div>
                        <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#166534;margin:0 0 6px 0">APPROVED</p>
                        <p style="font-family:'DM Serif Display',serif;font-size:20px;line-height:1.2;color:#1a1a18;margin:0 0 5px 0">{{ $purchaseRequest->approver?->name ?? 'System' }}</p>
                        <p style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;margin:0">{{ $purchaseRequest->approved_at?->format('d M Y \a\t H:i') ?? '—' }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($purchaseRequest->status === 'cancelled')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#ef4444"></div>
                <div style="display:flex;gap:14px;align-items:flex-start;padding:18px 18px 12px 18px">
                    <div style="width:32px;height:32px;border-radius:50%;background:#fef2f2;border:1px solid #ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:'DM Mono',monospace;font-size:14px;color:#991b1b;font-weight:600">×</div>
                    <div>
                        <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#991b1b;margin:0 0 6px 0">CANCELLED</p>
                        <p style="font-family:'DM Serif Display',serif;font-size:20px;line-height:1.2;color:#1a1a18;margin:0 0 5px 0">{{ $purchaseRequest->canceller?->name ?? 'System' }}</p>
                        <p style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;margin:0">{{ $purchaseRequest->cancelled_at?->format('d M Y \a\t H:i') ?? '—' }}</p>
                    </div>
                </div>
                @if($purchaseRequest->cancellation_reason)
                    <div style="padding:0 18px 18px 18px">
                        <p style="font-family:'DM Mono',monospace;font-size:9px;letter-spacing:0.1em;color:#9ca3af;margin:0 0 6px 0">REASON</p>
                        <div style="background:#fef2f2;border:0.5px solid #fecaca;border-radius:8px;padding:12px 14px">
                            <p style="margin:0;font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;line-height:1.6">{{ $purchaseRequest->cancellation_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($purchaseRequest->status === 'submitted')
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#f59e0b"></div>
                <div style="padding:16px 18px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;letter-spacing:0.12em;color:#92400e;margin:0 0 5px 0">AWAITING DECISION</p>
                    <p style="margin:0;font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280">This request is pending procurement manager review.</p>
                </div>
            </div>
        @endif

        {{-- ── PR LINES TABLE ── --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">MATERIAL LINES</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">PR Lines</h2>
            </div>
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:0.5px solid #f0ece6">
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">LINE</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">MATERIAL</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">QUANTITY</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">REQUIRED DATE</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">NOTES</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:12px 20px">RFQ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseRequest->lines as $line)
                        <tr style="border-bottom:0.5px solid #f8f5f0"
                            onmouseover="this.style.background='#faf8f5'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:13px 20px;font-family:'DM Mono',monospace;font-size:12px;color:#1a1a18;font-weight:500">
                                {{ $line->line_no }}
                            </td>
                            <td style="padding:13px 20px;font-family:'DM Sans',sans-serif;font-size:13px;color:#374151">
                                {{ $line->rawMaterial->name }}
                            </td>
                            <td style="padding:13px 20px;font-family:'DM Mono',monospace;font-size:12px;color:#374151">
                                {{ number_format((float) $line->quantity, 4) }} {{ $line->unit->symbol }}
                            </td>
                            <td style="padding:13px 20px;font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af">
                                {{ $line->required_delivery_date->format('d M Y') }}
                            </td>
                            <td style="padding:13px 20px;font-family:'DM Sans',sans-serif;font-size:13px;color:#9ca3af">
                                {{ $line->notes ?? '—' }}
                            </td>
                            <td style="padding:13px 20px">
                                @if ($line->rfq)
                                    <a href="{{ route('rfqs.show', $line->rfq->rfq_id) }}"
                                       style="font-family:'DM Mono',monospace;font-size:11px;font-weight:500;color:#1a1a18;text-decoration:none;padding:3px 8px;background:#f8f7f4;border:0.5px solid #e8e3da;border-radius:5px">
                                        {{ $line->rfq->rfq_number }}
                                    </a>
                                @else
                                    <span style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af">Not created</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── CREATE RFQ SECTION ── --}}
        @if (
    in_array(auth()->user()->role, ['purchasing_officer', 'admin']) &&
    $purchaseRequest->status === 'approved'
)
    @php
        $linesWithoutRfq = $purchaseRequest->lines->filter(fn($line) => is_null($line->rfq));
        $defaultCurrencyId = $currencies->firstWhere('code', 'USD')?->id ?? $currencies->first()?->id;
    @endphp

    @if ($linesWithoutRfq->isNotEmpty())
        @foreach ($linesWithoutRfq as $line)
   

            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
                <div style="height:2px;background:#38bdf8"></div>
                <div style="padding:20px 22px">

                    <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:5px">
                        CREATE RFQ — will be saved as draft for review · LINE {{ $line->line_no }}
                    </p>
                    <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0 0 4px 0">
                        {{ $line->rawMaterial->name }}
                    </h2>
                    <p style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;margin:0 0 20px 0">
                        {{ number_format((float) $line->quantity, 4) }} {{ $line->unit->symbol }}
                    </p>

                    <form method="POST" action="{{ route('rfqs.store') }}">
                        @csrf
                        <input type="hidden" name="pr_line_id" value="{{ $line->pr_line_id }}">

                        <div style="display:grid;gap:14px;grid-template-columns:repeat(4,minmax(0,1fr))">

                            {{-- RFQ DATE --}}
                            <div>
                                <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">RFQ DATE</label>
                                <input type="date" name="rfq_date"
                                       value="{{ old('rfq_date', now()->toDateString()) }}"
                                       style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                            </div>

                            {{-- QUOTATION DUE DATE --}}
                            <div>
                                <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">QUOTATION DUE DATE</label>
                                <input type="date" name="quotation_due_date"
                                       value="{{ old('quotation_due_date', now()->addDays(7)->toDateString()) }}"
                                       style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                            </div>

                            {{-- CURRENCY --}}
                            <div>
                                <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">CURRENCY</label>
                                <select name="currency_id" required
                                        style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                                    @foreach ($currencies->sortBy(fn($c) => match($c->code) { 'USD' => 0, 'EUR' => 1, default => 2 }) as $currency)
                                        <option value="{{ $currency->id }}"
                                            @selected((string) old('currency_id', $defaultCurrencyId) === (string) $currency->id)>
                                            {{ $currency->code }} — {{ $currency->name }} ({{ $currency->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- PAYMENT TERMS --}}
                            <div>
                                <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">PAYMENT TERMS</label>
                                <input type="text" name="payment_terms"
                                       value="{{ old('payment_terms', '30 days credit') }}"
                                       style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
                            </div>

                            {{-- DELIVERY LOCATION --}}
                            <div style="grid-column:span 2">
                                <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">DELIVERY LOCATION</label>
                                <input type="text" name="delivery_location"
                                       value="{{ old('delivery_location', 'Main Factory Warehouse') }}"
                                       style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
                            </div>
                            {{-- VENDORS — only vendors who supply this material --}}
<div style="grid-column:span 2">
    <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">
        VENDORS
        <span style="color:#9ca3af;font-size:9px;margin-left:6px">(hold Ctrl/Cmd to select multiple)</span>
    </label>

    @php $lineVendorMaterials = $vendorsByLine[$line->pr_line_id] ?? collect(); @endphp

    @if($lineVendorMaterials->isNotEmpty())
        <select name="vendor_ids[]" multiple size="{{ min($lineVendorMaterials->count(), 5) }}"
                style="width:100%;padding:4px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
            @foreach($lineVendorMaterials as $vm)
                <option value="{{ $vm->vendor->vendor_id }}" selected style="padding:6px;color:#1a1a18">
                    {{ $vm->vendor->name }} ({{ $vm->vendor->country }})
                    @if($vm->last_price)
                        — {{ $vm->currency?->symbol }}{{ number_format((float)$vm->last_price, 2) }} / {{ $line->unit->symbol }}
                        @if($vm->lead_time_days) · {{ $vm->lead_time_days }}d @endif
                    @endif
                    @if($vm->preferred_vendor) ★ @endif
                </option>
            @endforeach
        </select>
    @else
        <div style="margin-bottom:8px;padding:8px 12px;background:#fffbeb;border:0.5px solid #fde68a;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:12px;color:#92400e">
            No sourcing data for {{ $line->rawMaterial->name }} — showing all vendors. Verify before sending.
        </div>
        <select name="vendor_ids[]" multiple size="5"
                style="width:100%;padding:4px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
            @foreach(\App\Models\Vendor::orderBy('name')->get() as $vendor)
                <option value="{{ $vendor->vendor_id }}" style="padding:6px;color:#6b7280">
                    {{ $vendor->name }} ({{ $vendor->country }})
                </option>
            @endforeach
        </select>
    @endif

    @error('vendor_ids')
        <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
    @enderror
</div>

                            {{-- SUBMIT --}}
                            <div style="grid-column:span 4;display:flex;justify-content:flex-end">
                                <button type="submit"
                                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#f0f9ff;padding:10px 22px;border:none;border-radius:7px;background:#0d1117;cursor:pointer">
                                    Save RFQ as Draft →
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    @else
        <div style="background:#f0fdf4;border:0.5px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-family:'DM Sans',sans-serif;font-size:13px;color:#166534">
            All lines on this Purchase Request have an associated RFQ.
        </div>
    @endif
@endif

    </div>

    {{-- ── CANCEL MODAL ── --}}
    @if (
        $purchaseRequest->status === 'submitted' &&
        in_array(auth()->user()->role, ['procurement_manager', 'admin'])
    )
        {{-- Do not use inline display:none here: it overrides Tailwind .hidden removal and blocks the modal from showing. --}}
        <div id="cancel-modal" class="hidden"
             style="position:fixed;inset:0;z-index:50;align-items:center;justify-content:center;background:rgba(0,0,0,0.4);padding:16px"
             onclick="if (event.target === this) closeCancelModal()">
            <div style="width:100%;max-width:440px;background:#fff;border-radius:12px;border:0.5px solid #e8e3da;overflow:hidden">
                <div style="height:2px;background:#ef4444"></div>
                <div style="padding:24px">
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#991b1b;letter-spacing:0.12em;margin:0 0 6px 0">CANCEL PURCHASE REQUEST</p>
                    <h3 style="font-family:'DM Serif Display',serif;font-size:20px;color:#1a1a18;margin:0 0 8px 0">Confirm Cancellation</h3>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin:0 0 20px 0">
                        Please provide a reason for cancellation. This will be stored as part of the audit trail.
                    </p>

                    <form method="POST" action="{{ route('purchase-requests.cancel', $purchaseRequest->pr_id) }}">
                        @csrf @method('PATCH')
                        <textarea name="cancellation_reason" rows="4" required minlength="10" maxlength="500"
                                  placeholder="e.g. Material is now available from internal stock. PR no longer required."
                                  style="width:100%;padding:10px 14px;border:0.5px solid #e8e3da;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;resize:vertical;outline:none;box-sizing:border-box"></textarea>
                        @error('cancellation_reason')
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b;margin-top:6px">{{ $message }}</p>
                        @enderror

                        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px">
                            <button type="button"
                                    onclick="closeCancelModal()"
                                    style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:#374151;padding:8px 16px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff;cursor:pointer">
                                Go Back
                            </button>
                            <button type="submit"
                                    style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#fff;padding:8px 16px;border:none;border-radius:7px;background:#dc2626;cursor:pointer">
                                Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openCancelModal() {
                const m = document.getElementById('cancel-modal');
                if (!m) return;
                m.classList.remove('hidden');
                m.style.display = 'flex';
            }
            function closeCancelModal() {
                const m = document.getElementById('cancel-modal');
                if (!m) return;
                m.classList.add('hidden');
                m.style.display = 'none';
            }
        </script>
    @endif

</x-app-layout>