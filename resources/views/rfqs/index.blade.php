<x-app-layout>
    <x-slot name="header">
        <div>
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                PROCUREMENT SYSTEM — RFQs
            </p>
            <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px">
                Request for <em style="font-style:italic;color:#38bdf8">Quotation</em>
            </h1>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:4px">
                @if (auth()->user()->role === 'requester')
                    RFQs linked to your Purchase Requests.
                @else
                    All RFQs across the organisation.
                @endif
            </p>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- TOPBAR --}}
        <div style="display:flex;align-items:center;justify-content:space-between">
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em">
                SHOWING LATEST RFQs AVAILABLE TO YOUR ROLE
            </p>
        </div>

        {{-- TABLE --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:12px;overflow:hidden">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:0.5px solid #f0ece6">
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">RFQ NUMBER</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">SOURCE PR</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">MATERIAL</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">CURRENCY</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">STATUS</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rfqs as $rfq)
                        <tr style="border-bottom:0.5px solid #f8f5f0;transition:background 0.1s"
                            onmouseover="this.style.background='#faf8f5'"
                            onmouseout="this.style.background='transparent'">

                            {{-- RFQ NUMBER --}}
                            <td style="padding:14px 18px">
                                <a href="{{ route('rfqs.show', $rfq->rfq_id) }}"
                                   style="font-family:'DM Mono',monospace;font-size:12px;font-weight:500;color:#1a1a18;text-decoration:none">
                                    {{ $rfq->rfq_number }}
                                </a>
                            </td>

                            {{-- SOURCE PR --}}
                            <td style="padding:14px 18px">
                                <a href="{{ route('purchase-requests.show', $rfq->purchaseRequestLine->purchaseRequest->pr_id) }}"
                                   style="font-family:'DM Mono',monospace;font-size:12px;font-weight:500;color:#1a1a18;text-decoration:none">
                                    {{ $rfq->purchaseRequestLine->purchaseRequest->pr_number }}
                                </a>
                            </td>

                            {{-- MATERIAL --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151">
                                    {{ $rfq->rawMaterial->name }}
                                </span>
                            </td>

                            {{-- CURRENCY --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Mono',monospace;font-size:12px;color:#374151">
                                    {{ $rfq->currency?->code ?? '—' }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td style="padding:14px 18px">
                                @php
                                    $statusStyles = [
                                        'draft'     => 'background:#f1efe8;color:#5f5e5a',
                                        'issued'    => 'background:#eff6ff;color:#1d4ed8',
                                        'awarded'   => 'background:#f0fdf4;color:#166534',
                                        'closed'    => 'background:#f1efe8;color:#5f5e5a',
                                        'cancelled' => 'background:#fef2f2;color:#991b1b',
                                    ];
                                @endphp
                                <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 9px;border-radius:5px;letter-spacing:0.04em;{{ $statusStyles[$rfq->status] ?? 'background:#f1efe8;color:#5f5e5a' }}">
                                    {{ strtoupper($rfq->status) }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td style="padding:14px 18px">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                                    <a href="{{ route('rfqs.show', $rfq->rfq_id) }}"
                                       style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;color:#374151;text-decoration:none;padding:5px 12px;border:0.5px solid #e8e3da;border-radius:6px;background:#fff;transition:all 0.15s"
                                       onmouseover="this.style.background='#f8f7f4';this.style.borderColor='#c9c4ba'"
                                       onmouseout="this.style.background='#fff';this.style.borderColor='#e8e3da'">
                                        View
                                    </a>

                                    @if ($rfq->status === 'draft' && in_array(auth()->user()->role, ['purchasing_officer', 'admin']))
                                        <form method="POST" action="{{ route('rfqs.issue', $rfq->rfq_id) }}" style="margin:0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;color:#f0f9ff;padding:5px 12px;border:none;border-radius:6px;background:#0d1117;cursor:pointer;transition:all 0.15s"
                                                    onmouseover="this.style.background='#1e293b'"
                                                    onmouseout="this.style.background='#0d1117'"
                                                    onclick="return confirm('Issue this RFQ to vendors?')">
                                                Issue
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:48px;text-align:center;font-family:'DM Sans',sans-serif;font-size:13px;color:#9ca3af">
                                No RFQs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div>
            {{ $rfqs->links() }}
        </div>

    </div>
</x-app-layout>
