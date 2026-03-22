<x-app-layout>
<style>
@keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:0.4}}
</style>

    <x-slot name="header">
        <div>
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                PROCUREMENT SYSTEM — PURCHASE REQUESTS
            </p>
            <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px">
                Purchase <em style="font-style:italic;color:#38bdf8">Requests</em>
            </h1>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:4px">
                @if (auth()->user()->role === 'purchasing_officer')
                    Approved Purchase Requests ready for RFQ creation.
                @elseif (auth()->user()->role === 'requester')
                    Your Purchase Requests — track status and submit for approval.
                @else
                    All Purchase Requests across the organisation.
                @endif
            </p>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- TOPBAR --}}
        <div style="display:flex;align-items:center;justify-content:space-between">
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.06em">
                @if (auth()->user()->role === 'purchasing_officer')
                    SHOWING APPROVED PRs ONLY — ELIGIBLE FOR RFQ CREATION
                @else
                    SHOWING LATEST PURCHASE REQUESTS AVAILABLE TO YOUR ROLE
                @endif
            </p>
            @if (in_array(auth()->user()->role, ['requester', 'admin']))
                <a href="{{ route('purchase-requests.create') }}"
                   style="background:#0d1117;color:#f0f9ff;border:none;padding:9px 20px;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;letter-spacing:0.02em;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
                    + New Purchase Request
                </a>
            @endif
        </div>

        {{-- TABLE --}}
        <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:12px;overflow:hidden">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:0.5px solid #f0ece6">
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">PR NUMBER</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">REQUESTER</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">DEPARTMENT</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">DATE</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">STATUS</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">LINES</th>
                        <th style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:14px 18px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseRequests as $purchaseRequest)
                        <tr style="border-bottom:0.5px solid #f8f5f0;transition:background 0.1s"
                            onmouseover="this.style.background='#faf8f5'"
                            onmouseout="this.style.background='transparent'">

                            {{-- PR NUMBER --}}
                            <td style="padding:14px 18px">
                                <a href="{{ route('purchase-requests.show', $purchaseRequest->pr_id) }}"
                                   style="font-family:'DM Mono',monospace;font-size:12px;font-weight:500;color:#1a1a18;text-decoration:none">
                                    {{ $purchaseRequest->pr_number }}
                                </a>
                            </td>

                            {{-- REQUESTER --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151">
                                    {{ $purchaseRequest->requester->name }}
                                </span>
                            </td>

                            {{-- DEPARTMENT --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#374151">
                                    {{ $purchaseRequest->department->name }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af">
                                    {{ $purchaseRequest->request_date->format('d M Y') }}
                                </span>
                            </td>

                            {{-- STATUS + AUDIT TRAIL --}}
                            <td style="padding:14px 18px">
                                @if($purchaseRequest->status === 'draft')
                                    <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 9px;border-radius:5px;letter-spacing:0.04em;background:#f1efe8;color:#5f5e5a">
                                        DRAFT
                                    </span>

                                @elseif($purchaseRequest->status === 'submitted')
                                    <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 9px;border-radius:5px;letter-spacing:0.04em;background:#fffbeb;color:#92400e">
                                        SUBMITTED
                                    </span>
                                    <div style="display:flex;align-items:center;gap:5px;margin-top:6px">
                                        <div style="width:5px;height:5px;border-radius:50%;background:#f59e0b;flex-shrink:0;animation:pulse-dot 2s infinite"></div>
                                        <span style="font-family:'DM Mono',monospace;font-size:10px;color:#92400e;letter-spacing:0.03em">
                                            awaiting review
                                        </span>
                                    </div>

                                @elseif($purchaseRequest->status === 'approved')
                                    <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 9px;border-radius:5px;letter-spacing:0.04em;background:#f0fdf4;color:#166534">
                                        APPROVED
                                    </span>
                                    <div style="display:flex;align-items:center;gap:5px;margin-top:6px">
                                        <div style="width:5px;height:5px;border-radius:50%;background:#22c55e;flex-shrink:0"></div>
                                        <span style="font-family:'DM Mono',monospace;font-size:10px;color:#166534;letter-spacing:0.03em">
                                            {{ $purchaseRequest->approver?->name ?? 'System' }}
                                        </span>
                                    </div>
                                    @if($purchaseRequest->approved_at)
                                        <div style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;margin-top:2px;padding-left:10px">
                                            {{ $purchaseRequest->approved_at->format('d M Y') }}
                                        </div>
                                    @endif

                                @elseif($purchaseRequest->status === 'cancelled')
                                    <span style="display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 9px;border-radius:5px;letter-spacing:0.04em;background:#fef2f2;color:#991b1b">
                                        CANCELLED
                                    </span>
                                    <div style="display:flex;align-items:center;gap:5px;margin-top:6px">
                                        <div style="width:5px;height:5px;border-radius:50%;background:#ef4444;flex-shrink:0"></div>
                                        <span style="font-family:'DM Mono',monospace;font-size:10px;color:#991b1b;letter-spacing:0.03em">
                                            {{ $purchaseRequest->canceller?->name ?? 'System' }}
                                        </span>
                                    </div>
                                    @if($purchaseRequest->cancelled_at)
                                        <div style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;margin-top:2px;padding-left:10px">
                                            {{ $purchaseRequest->cancelled_at->format('d M Y') }}
                                        </div>
                                    @endif
                                    @if($purchaseRequest->cancellation_reason)
                                        <div style="margin-top:5px;font-family:'DM Sans',sans-serif;font-size:11px;color:#991b1b;background:#fef2f2;border:0.5px solid #fecaca;border-radius:5px;padding:4px 8px;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                             title="{{ $purchaseRequest->cancellation_reason }}">
                                            {{ Str::limit($purchaseRequest->cancellation_reason, 25) }}
                                        </div>
                                    @endif
                                @endif
                            </td>

                            {{-- LINES COUNT --}}
                            <td style="padding:14px 18px">
                                <span style="font-family:'DM Mono',monospace;font-size:12px;color:#374151">
                                    {{ $purchaseRequest->lines->count() }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td style="padding:14px 18px">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                                    <a href="{{ route('purchase-requests.show', $purchaseRequest->pr_id) }}"
                                       style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;color:#374151;text-decoration:none;padding:5px 12px;border:0.5px solid #e8e3da;border-radius:6px;background:#fff;transition:all 0.15s"
                                       onmouseover="this.style.background='#f8f7f4';this.style.borderColor='#c9c4ba'"
                                       onmouseout="this.style.background='#fff';this.style.borderColor='#e8e3da'">
                                        View
                                    </a>

                                    @if (
                                        $purchaseRequest->status === 'draft' &&
                                        (
                                            (auth()->user()->role === 'requester' && $purchaseRequest->requester_id === auth()->user()->user_id) ||
                                            auth()->user()->role === 'admin'
                                        )
                                    )
                                        <a href="{{ route('purchase-requests.edit', $purchaseRequest->pr_id) }}"
                                           style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;color:#374151;text-decoration:none;padding:5px 12px;border:0.5px solid #e8e3da;border-radius:6px;background:#fff;transition:all 0.15s"
                                           onmouseover="this.style.background='#f8f7f4'"
                                           onmouseout="this.style.background='#fff'">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('purchase-requests.submit', $purchaseRequest->pr_id) }}" style="margin:0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;color:#f0f9ff;padding:5px 12px;border:none;border-radius:6px;background:#0d1117;cursor:pointer;transition:all 0.15s"
                                                    onmouseover="this.style.background='#1e293b'"
                                                    onmouseout="this.style.background='#0d1117'"
                                                    onclick="return confirm('Submit this Purchase Request for approval?')">
                                                Submit
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:48px;text-align:center;font-family:'DM Sans',sans-serif;font-size:13px;color:#9ca3af">
                                No Purchase Requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div>
            {{ $purchaseRequests->links() }}
        </div>

    </div>
</x-app-layout>