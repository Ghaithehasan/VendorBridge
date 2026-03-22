<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap');
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes shimmer{0%{left:-100%}100%{left:200%}}
@keyframes barGrow{from{width:0}to{width:var(--w)}}
@keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:0.3}}

body{background:#f8f7f4 !important}

.metric-card{background:#fff;border:0.5px solid #e8e3da;border-radius:12px;padding:18px 20px;animation:fadeUp 0.5s ease both;position:relative;overflow:hidden}
.metric-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent,#38bdf8);opacity:0.6}
.metric-label{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:10px}
.metric-value{font-family:'DM Serif Display',serif;font-size:36px;color:#1a1a18;letter-spacing:-0.5px;line-height:1}
.metric-delta{font-family:'DM Mono',monospace;font-size:10px;margin-top:6px}
.metric-bar{height:2px;border-radius:2px;background:#f0ece6;margin-top:12px}
.metric-fill{height:100%;border-radius:2px;animation:barGrow 1s ease both 0.3s}

.dash-card{background:#fff;border:0.5px solid #e8e3da;border-radius:12px;padding:22px;animation:fadeUp 0.5s ease both}
.card-eyebrow{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.12em;margin-bottom:6px}
.card-heading{font-family:'DM Serif Display',serif;font-size:20px;color:#1a1a18;margin-bottom:18px;letter-spacing:-0.2px}

.count-box{background:#f8f7f4;border:0.5px solid #e8e3da;border-radius:8px;padding:16px 18px;margin-bottom:16px}
.count-label{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;margin-bottom:8px}
.count-value{font-family:'DM Serif Display',serif;font-size:36px;color:#1a1a18;letter-spacing:-0.5px}

.btn-primary{background:#0d1117;color:#f0f9ff;border:none;padding:9px 20px;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;letter-spacing:0.02em;position:relative;overflow:hidden;transition:transform 0.15s;text-decoration:none;display:inline-flex;align-items:center;gap:7px}
.btn-primary:hover{transform:translateY(-1px);color:#f0f9ff}
.btn-primary::after{content:'';position:absolute;top:0;width:30%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.07),transparent);animation:shimmer 3s infinite}

.dash-table{width:100%;border-collapse:collapse}
.dash-table th{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.08em;text-align:left;padding:0 0 10px;border-bottom:0.5px solid #ede8e0;font-weight:400}
.dash-table td{font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;padding:10px 0;border-bottom:0.5px solid #f5f0ea}
.dash-table tr:last-child td{border-bottom:none}
.dash-table tr:hover td{background:#faf8f5}
.pr-num-cell{font-family:'DM Mono',monospace;font-size:12px;color:#1a1a18;font-weight:500}
.date-cell{font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af}

.sbadge{display:inline-block;font-family:'DM Mono',monospace;font-size:10px;padding:3px 8px;border-radius:5px;letter-spacing:0.04em}
.b-draft    {background:#f1efe8;color:#5f5e5a}
.b-submitted{background:#fffbeb;color:#92400e}
.b-approved {background:#f0fdf4;color:#166534}
.b-cancelled{background:#fef2f2;color:#991b1b}
.b-issued   {background:#eff6ff;color:#1d4ed8}

.act-item{display:flex;align-items:flex-start;gap:12px;padding:11px 0;border-bottom:0.5px solid #f5f0ea}
.act-item:last-child{border-bottom:none}
.act-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px}
.act-main{font-family:'DM Mono',monospace;font-size:12px;color:#1a1a18;margin:0}
.act-sub{font-family:'DM Mono',monospace;font-size:10px;margin:4px 0 0 0}
.act-sub-green{color:#166534}
.act-sub-red{color:#991b1b}
.act-sub-muted{color:#9ca3af}

.pr-item{display:block;border:0.5px solid #e8e3da;border-radius:9px;padding:13px 16px;transition:all 0.15s;text-decoration:none;margin-bottom:9px}
.pr-item:hover{border-color:#b8b3a9;background:#faf8f5}
.pr-item-num{font-family:'DM Mono',monospace;font-size:13px;font-weight:500;color:#1a1a18}
.pr-item-meta{font-family:'DM Sans',sans-serif;font-size:12px;color:#9ca3af;margin-top:4px}

.empty-msg{font-family:'DM Sans',sans-serif;font-size:13px;color:#9ca3af;padding:20px 0;text-align:center}

.section-divider{display:flex;align-items:center;gap:12px;margin-bottom:4px}
.divider-label{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.12em;white-space:nowrap}
.divider-line{flex:1;height:0.5px;background:#e8e3da}
</style>

    <x-slot name="header">
        <div style="display:flex;align-items:flex-start;justify-content:space-between">
            <div>
                <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                    PROCUREMENT SYSTEM — DASHBOARD
                </p>
                <h1 style="font-family:'DM Serif Display',serif;font-size:26px;color:#1a1a18;letter-spacing:-0.4px;line-height:1.1">
                    Operational <em style="font-style:italic;color:#38bdf8">Overview</em>
                </h1>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:5px">
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>
            @if(in_array(auth()->user()->role, ['requester','admin']))
                <a href="{{ route('purchase-requests.create') }}" class="btn-primary">
                    + New Purchase Request
                </a>
            @endif
        </div>
    </x-slot>

    @php($role = auth()->user()->role)

    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- ── METRICS ROW — visible to ALL roles ── --}}
        <div style="display:grid;gap:12px;grid-template-columns:repeat(4,minmax(0,1fr))">
            <div class="metric-card" style="--accent:#38bdf8;animation-delay:0s">
                <p class="metric-label">TOTAL PRs</p>
                <p class="metric-value">{{ $adminTotals['purchase_requests'] }}</p>
                <div class="metric-delta" style="color:#3b6d11">All time</div>
                <div class="metric-bar"><div class="metric-fill" style="--w:70%;width:70%;background:#38bdf8"></div></div>
            </div>
            <div class="metric-card" style="--accent:#f59e0b;animation-delay:0.06s">
                <p class="metric-label">PENDING APPROVAL</p>
                <p class="metric-value">{{ $pendingApprovalCount }}</p>
                <div class="metric-delta" style="color:#854f0b">Awaiting manager</div>
                <div class="metric-bar"><div class="metric-fill" style="--w:30%;width:30%;background:#f59e0b"></div></div>
            </div>
            <div class="metric-card" style="--accent:#22c55e;animation-delay:0.12s">
                <p class="metric-label">ACTIVE RFQs</p>
                <p class="metric-value">{{ $adminTotals['rfqs'] }}</p>
                <div class="metric-delta" style="color:#3b6d11">In progress</div>
                <div class="metric-bar"><div class="metric-fill" style="--w:55%;width:55%;background:#22c55e"></div></div>
            </div>
            <div class="metric-card" style="--accent:#a78bfa;animation-delay:0.18s">
                <p class="metric-label">VENDORS</p>
                <p class="metric-value">{{ $adminTotals['vendors'] }}</p>
                <div class="metric-delta" style="color:#9ca3af">Across countries</div>
                <div class="metric-bar"><div class="metric-fill" style="--w:40%;width:40%;background:#a78bfa"></div></div>
            </div>
        </div>

        {{-- ── REQUESTER ── --}}
        @if ($role === 'requester')
            <div class="section-divider">
                <span class="divider-label">MY REQUESTS</span>
                <div class="divider-line"></div>
            </div>
            <div style="display:grid;gap:16px;grid-template-columns:2fr 1fr">
                <div class="dash-card" style="animation-delay:0.2s">
                    <p class="card-eyebrow">PURCHASE REQUESTS</p>
                    <h2 class="card-heading">My Requests</h2>
                    <div style="display:grid;gap:10px;grid-template-columns:repeat(3,1fr);margin-bottom:18px">
                        <div class="count-box">
                            <p class="count-label">DRAFT</p>
                            <p class="count-value">{{ $requesterSummary['draft'] }}</p>
                        </div>
                        <div class="count-box">
                            <p class="count-label">SUBMITTED</p>
                            <p class="count-value">{{ $requesterSummary['submitted'] }}</p>
                        </div>
                        <div class="count-box">
                            <p class="count-label">APPROVED</p>
                            <p class="count-value">{{ $requesterSummary['approved'] }}</p>
                        </div>
                    </div>
                    <table class="dash-table">
                        <thead><tr>
                            <th>PR NUMBER</th><th>MATERIAL</th><th>STATUS</th><th>DATE</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($requesterRecentPrs as $pr)
                                <tr>
                                    <td class="pr-num-cell">
                                        <a href="{{ route('purchase-requests.show', $pr->pr_id) }}"
                                           style="text-decoration:none;color:#1a1a18">
                                            {{ $pr->pr_number }}
                                        </a>
                                    </td>
                                    <td>{{ $pr->lines->first()?->rawMaterial?->name ?? '—' }}</td>
                                    <td>
                                        <span class="sbadge b-{{ $pr->status }}">{{ strtoupper($pr->status) }}</span>
                                        @if($pr->status === 'approved')
                                            <div style="display:flex;align-items:center;gap:4px;margin-top:4px">
                                                <div style="width:4px;height:4px;border-radius:50%;background:#22c55e;flex-shrink:0"></div>
                                                <span style="font-family:'DM Mono',monospace;font-size:9px;color:#166534">{{ $pr->approver?->name ?? 'System' }}</span>
                                            </div>
                                        @elseif($pr->status === 'cancelled')
                                            <div style="display:flex;align-items:center;gap:4px;margin-top:4px">
                                                <div style="width:4px;height:4px;border-radius:50%;background:#ef4444;flex-shrink:0"></div>
                                                <span style="font-family:'DM Mono',monospace;font-size:9px;color:#991b1b">{{ $pr->canceller?->name ?? 'System' }}</span>
                                            </div>
                                        @elseif($pr->status === 'submitted')
                                            <div style="display:flex;align-items:center;gap:4px;margin-top:4px">
                                                <div style="width:4px;height:4px;border-radius:50%;background:#f59e0b;flex-shrink:0;animation:pulse-dot 2s infinite"></div>
                                                <span style="font-family:'DM Mono',monospace;font-size:9px;color:#92400e">awaiting review</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="date-cell">{{ $pr->request_date->format('d M') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-msg">No Purchase Requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="dash-card" style="animation-delay:0.26s">
                    <p class="card-eyebrow">LIVE FEED</p>
                    <h2 class="card-heading">Recent Activity</h2>
                    @forelse ($requesterRecentPrs as $pr)
                        <div class="act-item">
                            <div class="act-dot" style="background:{{ $pr->status === 'approved' ? '#22c55e' : ($pr->status === 'submitted' ? '#f59e0b' : ($pr->status === 'cancelled' ? '#ef4444' : '#9ca3af')) }}"></div>
                            <div>
                                <p class="act-main">{{ $pr->pr_number }}</p>
                                @if($pr->status === 'approved')
                                    <p class="act-sub act-sub-green">
                                        Approved by {{ $pr->approver?->name ?? 'System' }} · {{ $pr->approved_at?->format('d M') ?? '—' }}
                                    </p>
                                @elseif($pr->status === 'cancelled')
                                    <p class="act-sub act-sub-red">
                                        Cancelled by {{ $pr->canceller?->name ?? 'System' }} · {{ $pr->cancelled_at?->format('d M') ?? '—' }}
                                    </p>
                                @else
                                    <p class="act-sub act-sub-muted">
                                        {{ strtoupper($pr->status) }} · {{ $pr->request_date->format('d M') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No activity yet.</p>
                    @endforelse
                </div>
            </div>

        {{-- ── PROCUREMENT MANAGER ── --}}
        @elseif ($role === 'procurement_manager')
            <div class="section-divider">
                <span class="divider-label">PENDING REVIEW</span>
                <div class="divider-line"></div>
            </div>
            <div style="display:grid;gap:16px;grid-template-columns:2fr 1fr">
                <div class="dash-card" style="animation-delay:0.2s">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
                        <div>
                            <p class="card-eyebrow">AWAITING DECISION</p>
                            <h2 class="card-heading">PRs Pending Approval</h2>
                        </div>
                        <a href="{{ route('purchase-requests.index') }}" class="btn-primary">Review PRs →</a>
                    </div>
                    <div class="count-box">
                        <p class="count-label">PENDING COUNT</p>
                        <p class="count-value">{{ $pendingApprovalCount }}</p>
                    </div>
                    <table class="dash-table">
                        <thead><tr>
                            <th>PR NUMBER</th><th>REQUESTER</th><th>DEPARTMENT</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($pendingApprovalPrs as $pr)
                                <tr>
                                    <td class="pr-num-cell">
                                        <a href="{{ route('purchase-requests.show', $pr->pr_id) }}"
                                           style="text-decoration:none;color:#1a1a18">
                                            {{ $pr->pr_number }}
                                        </a>
                                    </td>
                                    <td>{{ $pr->requester->name }}</td>
                                    <td>{{ $pr->department->name }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-msg">No PRs pending approval.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="dash-card" style="animation-delay:0.26s">
                    <p class="card-eyebrow">LIVE FEED</p>
                    <h2 class="card-heading">Recent Activity</h2>
                    @forelse ($recentApprovalActivity as $pr)
                        <div class="act-item">
                            <div class="act-dot" style="background:{{ $pr->status === 'approved' ? '#22c55e' : '#ef4444' }}"></div>
                            <div>
                                <p class="act-main">{{ $pr->pr_number }}</p>
                                @if($pr->status === 'approved')
                                    <p class="act-sub act-sub-green">
                                        Approved by {{ $pr->approver?->name ?? 'System' }} · {{ $pr->approved_at?->format('d M') ?? '—' }}
                                    </p>
                                @elseif($pr->status === 'cancelled')
                                    <p class="act-sub act-sub-red">
                                        Cancelled by {{ $pr->canceller?->name ?? 'System' }} · {{ $pr->cancelled_at?->format('d M') ?? '—' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No recent activity.</p>
                    @endforelse
                </div>
            </div>

        {{-- ── PURCHASING OFFICER ── --}}
        @elseif ($role === 'purchasing_officer')
            <div class="section-divider">
                <span class="divider-label">READY FOR OUTREACH</span>
                <div class="divider-line"></div>
            </div>
            <div style="display:grid;gap:16px;grid-template-columns:2fr 1fr">
                <div class="dash-card" style="animation-delay:0.2s">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
                        <div>
                            <p class="card-eyebrow">APPROVED — NO RFQ YET</p>
                            <h2 class="card-heading">Lines Awaiting RFQ</h2>
                        </div>
                        <a href="{{ route('purchase-requests.index') }}" class="btn-primary">Create RFQ →</a>
                    </div>
                    <div class="count-box">
                        <p class="count-label">LINES READY</p>
                        <p class="count-value">{{ $approvedLinesReadyForRfqCount }}</p>
                    </div>
                    <table class="dash-table">
                        <thead><tr>
                            <th>PR</th><th>MATERIAL</th><th>QUANTITY</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($approvedLinesReadyForRfq as $line)
                                <tr>
                                    <td class="pr-num-cell">{{ $line->purchaseRequest->pr_number }}</td>
                                    <td>{{ $line->rawMaterial->name }}</td>
                                    <td class="date-cell">{{ number_format((float)$line->quantity, 2) }} {{ $line->unit->symbol }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-msg">No approved lines waiting.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="dash-card" style="animation-delay:0.26s">
                    <p class="card-eyebrow">OPEN DOCUMENTS</p>
                    <h2 class="card-heading">Recent RFQs</h2>
                    @forelse ($recentRfqs as $rfq)
                        <a href="{{ route('rfqs.show', $rfq->rfq_id) }}" class="pr-item">
                            <p class="pr-item-num">{{ $rfq->rfq_number }}</p>
                            <p class="pr-item-meta">{{ $rfq->rawMaterial->name }} · {{ ucfirst($rfq->status) }}</p>
                        </a>
                    @empty
                        <p class="empty-msg">No RFQs created yet.</p>
                    @endforelse
                </div>
            </div>

        {{-- ── ADMIN ── --}}
        @elseif ($role === 'admin')
            <div class="section-divider">
                <span class="divider-label">FULL OVERVIEW</span>
                <div class="divider-line"></div>
            </div>
            <div style="display:grid;gap:16px;grid-template-columns:1fr 1fr">
                <div class="dash-card" style="animation-delay:0.2s">
                    <p class="card-eyebrow">RECENT DOCUMENTS</p>
                    <h2 class="card-heading">PR Activity</h2>
                    @forelse ($adminRecentPrs as $pr)
                        <div class="act-item">
                            <div class="act-dot" style="background:{{ $pr->status === 'approved' ? '#22c55e' : ($pr->status === 'cancelled' ? '#ef4444' : ($pr->status === 'submitted' ? '#f59e0b' : '#9ca3af')) }}"></div>
                            <div>
                                <p class="act-main">{{ $pr->pr_number }}</p>
                                @if($pr->status === 'approved')
                                    <p class="act-sub act-sub-green">
                                        Approved by {{ $pr->approver?->name ?? 'System' }} · {{ $pr->approved_at?->format('d M') ?? '—' }}
                                    </p>
                                @elseif($pr->status === 'cancelled')
                                    <p class="act-sub act-sub-red">
                                        Cancelled by {{ $pr->canceller?->name ?? 'System' }} · {{ $pr->cancelled_at?->format('d M') ?? '—' }}
                                    </p>
                                @else
                                    <p class="act-sub act-sub-muted">
                                        {{ $pr->requester->name }} · {{ strtoupper($pr->status) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No PR activity found.</p>
                    @endforelse
                </div>
                <div class="dash-card" style="animation-delay:0.26s">
                    <p class="card-eyebrow">OPEN DOCUMENTS</p>
                    <h2 class="card-heading">RFQ Activity</h2>
                    @forelse ($adminRecentRfqs as $rfq)
                        <div class="act-item">
                            <div class="act-dot" style="background:#a78bfa"></div>
                            <div>
                                <p class="act-main">{{ $rfq->rfq_number }}</p>
                                <p class="act-sub act-sub-muted">
                                    {{ $rfq->rawMaterial->name }} · {{ strtoupper($rfq->status) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No RFQ activity found.</p>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
</x-app-layout>

<!-- <x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-600">Operational overview tailored to your procurement role.</p>
        </div>
    </x-slot>

    @php($role = auth()->user()->role)

    <div class="space-y-8">
        @if ($role === 'requester')
            <div class="grid gap-6 xl:grid-cols-[2fr,1fr]">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">My Purchase Requests</h2>
                            <p class="text-sm text-slate-600">Track the status of requests you created.</p>
                        </div>
                        <a href="{{ route('purchase-requests.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                            New Purchase Request
                        </a>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Draft</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $requesterSummary['draft'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Submitted</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $requesterSummary['submitted'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Approved</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $requesterSummary['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Recent PRs</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($requesterRecentPrs as $pr)
                            <a href="{{ route('purchase-requests.show', $pr->pr_id) }}" class="block rounded-2xl border border-slate-200 p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <p class="font-semibold text-slate-900">{{ $pr->pr_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $pr->request_date->format('d M Y') }} • {{ ucfirst($pr->status) }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No Purchase Requests created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        @elseif ($role === 'procurement_manager')
            <div class="grid gap-6 xl:grid-cols-[2fr,1fr]">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">PRs Pending Approval</h2>
                            <p class="text-sm text-slate-600">Submitted PRs waiting for management review.</p>
                        </div>
                        <a href="{{ route('purchase-requests.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Review Pending PRs
                        </a>
                    </div>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Pending Approval Count</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingApprovalCount }}</p>
                    </div>

                    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                                    <th class="px-4 py-3">PR Number</th>
                                    <th class="px-4 py-3">Requester</th>
                                    <th class="px-4 py-3">Department</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                                @forelse ($pendingApprovalPrs as $pr)
                                    <tr>
                                        <td class="px-4 py-3">{{ $pr->pr_number }}</td>
                                        <td class="px-4 py-3">{{ $pr->requester->name }}</td>
                                        <td class="px-4 py-3">{{ $pr->department->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">No PRs are currently pending approval.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Recent Activity</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($recentApprovalActivity as $pr)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $pr->pr_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $pr->requester->name }} • {{ ucfirst($pr->status) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No recent approval activity found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        @elseif ($role === 'purchasing_officer')
            <div class="grid gap-6 xl:grid-cols-[2fr,1fr]">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Approved PR Lines Ready for RFQ</h2>
                            <p class="text-sm text-slate-600">Approved PR demand that has not yet been converted into an RFQ.</p>
                        </div>
                        <a href="{{ route('purchase-requests.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Create RFQ
                        </a>
                    </div>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Ready for RFQ Count</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $approvedLinesReadyForRfqCount }}</p>
                    </div>

                    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                                    <th class="px-4 py-3">PR</th>
                                    <th class="px-4 py-3">Material</th>
                                    <th class="px-4 py-3">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                                @forelse ($approvedLinesReadyForRfq as $line)
                                    <tr>
                                        <td class="px-4 py-3">{{ $line->purchaseRequest->pr_number }}</td>
                                        <td class="px-4 py-3">{{ $line->rawMaterial->name }}</td>
                                        <td class="px-4 py-3">{{ number_format((float) $line->quantity, 4) }} {{ $line->unit->symbol }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">No approved PR lines are waiting for RFQ creation.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Recent RFQs</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($recentRfqs as $rfq)
                            <a href="{{ route('rfqs.show', $rfq->rfq_id) }}" class="block rounded-2xl border border-slate-200 p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                <p class="font-semibold text-slate-900">{{ $rfq->rfq_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $rfq->rawMaterial->name }} • {{ ucfirst($rfq->status) }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No RFQs created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        @elseif ($role === 'admin')
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Total PRs</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $adminTotals['purchase_requests'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Total RFQs</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $adminTotals['rfqs'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Total Vendors</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $adminTotals['vendors'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Recent PR Activity</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($adminRecentPrs as $pr)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $pr->pr_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $pr->requester->name }} • {{ ucfirst($pr->status) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No PR activity found.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Recent RFQ Activity</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($adminRecentRfqs as $rfq)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $rfq->rfq_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $rfq->rawMaterial->name }} • {{ ucfirst($rfq->status) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No RFQ activity found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout> -->

