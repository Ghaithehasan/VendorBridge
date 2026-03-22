<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div>
                <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                    RFQ DRAFT EDITOR
                </p>
                <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px;margin:0">
                    Edit {{ $rfq->rfq_number }}
                </h1>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:4px">
                    Update draft RFQ commercial details before procurement manager issues it.
                </p>
            </div>
            <a href="{{ route('rfqs.show', $rfq->rfq_id) }}"
               style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#374151;padding:9px 18px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff;text-decoration:none;display:inline-block">
                Back to RFQ
            </a>
        </div>
    </x-slot>

    @php
        $selectedVendorIds = $rfq->recipients->pluck('vendor_id')->all();
    @endphp

    <form method="POST" action="{{ route('rfqs.update', $rfq->rfq_id) }}" style="display:flex;flex-direction:column;gap:18px">
        @csrf
        @method('PUT')

        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">SNAPSHOT (READ ONLY)</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">Material Snapshot</h2>
            </div>
            <div style="padding:18px 20px;display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))">
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">MATERIAL</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->rawMaterial->name }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">QUANTITY</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ number_format((float) $rfq->quantity, 4) }} {{ $rfq->unit->symbol }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">REQUIRED DELIVERY DATE</p>
                    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;margin:0">{{ $rfq->required_delivery_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em;margin:0 0 4px 0">SOURCE PR</p>
                    <a href="{{ route('purchase-requests.show', $rfq->purchaseRequestLine->purchaseRequest->pr_id) }}"
                       style="font-family:'DM Mono',monospace;font-size:12px;color:#1a1a18;text-decoration:underline">
                        {{ $rfq->purchaseRequestLine->purchaseRequest->pr_number }}
                    </a>
                </div>
            </div>
        </div>

        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:10px;overflow:hidden">
            <div style="padding:18px 20px;border-bottom:0.5px solid #f0ece6">
                <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:4px">EDITABLE FIELDS (DRAFT ONLY)</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:18px;color:#1a1a18;margin:0">RFQ Commercial Details</h2>
            </div>
            <div style="padding:20px 22px">
                <div style="display:grid;gap:14px;grid-template-columns:repeat(4,minmax(0,1fr))">
                    <div>
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">RFQ DATE</label>
                        <input type="date" name="rfq_date" value="{{ old('rfq_date', $rfq->rfq_date->format('Y-m-d')) }}"
                               style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                        @error('rfq_date')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">QUOTATION DUE DATE</label>
                        <input type="date" name="quotation_due_date" value="{{ old('quotation_due_date', $rfq->quotation_due_date->format('Y-m-d')) }}"
                               style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                        @error('quotation_due_date')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">CURRENCY</label>
                        <select name="currency_id" required
                                style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Mono',monospace;font-size:12px;background:#fff;outline:none">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" @selected((string) old('currency_id', $rfq->currency_id) === (string) $currency->id)>
                                    {{ $currency->code }} — {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">PAYMENT TERMS</label>
                        <input type="text" name="payment_terms" value="{{ old('payment_terms', $rfq->payment_terms) }}"
                               style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
                        @error('payment_terms')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div style="grid-column:span 2">
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">DELIVERY LOCATION</label>
                        <input type="text" name="delivery_location" value="{{ old('delivery_location', $rfq->delivery_location) }}"
                               style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
                        @error('delivery_location')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div style="grid-column:span 2">
                        <label style="font-family:'DM Mono',monospace;font-size:10px;color:#6b7280;letter-spacing:0.06em;display:block;margin-bottom:6px">
                            VENDORS <span style="color:#9ca3af;font-size:9px">(hold Ctrl/Cmd to select multiple)</span>
                        </label>
                        <select name="vendor_ids[]" multiple size="5"
                                style="width:100%;padding:9px 12px;border:0.5px solid #e8e3da;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;outline:none">
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->vendor_id }}" {{ in_array($vendor->vendor_id, old('vendor_ids', $selectedVendorIds)) ? 'selected' : '' }}>
                                    {{ $vendor->name }} ({{ $vendor->country }})
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_ids')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                        @error('vendor_ids.*')
                            <p style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:12px;color:#991b1b">{{ $message }}</p>
                        @enderror
                    </div>
                    <div style="grid-column:span 4;display:flex;justify-content:flex-end;gap:10px">
                        <a href="{{ route('rfqs.show', $rfq->rfq_id) }}"
                           style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:#374151;padding:10px 18px;border:0.5px solid #e8e3da;border-radius:7px;background:#fff;text-decoration:none">
                            Cancel
                        </a>
                        <button type="submit"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#f0f9ff;padding:10px 22px;border:none;border-radius:7px;background:#0d1117;cursor:pointer">
                            Update RFQ Draft →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
