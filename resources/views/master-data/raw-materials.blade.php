<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap');
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.mat-card{background:#fff;border:0.5px solid #e8e3da;border-radius:12px;overflow:hidden;animation:fadeUp 0.4s ease both}
.mat-top{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:0.5px solid #f5f0ea}
.mat-name{font-family:'DM Serif Display',serif;font-size:20px;color:#1a1a18;margin:0;letter-spacing:-0.2px}
.mat-body{padding:16px 22px}
.section-label{font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.12em;margin:0 0 10px 0}
.vdr-table{width:100%;border-collapse:collapse}
.vdr-table th{font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:0 0 8px;border-bottom:0.5px solid #f0ece6}
.vdr-table td{font-family:'DM Sans',sans-serif;font-size:12px;color:#374151;padding:9px 0;border-bottom:0.5px solid #f8f5f0}
.vdr-table tr:last-child td{border-bottom:none}
.vdr-table tr:hover td{background:#faf8f5}
.vname{font-weight:500;color:#1a1a18;font-family:'DM Sans',sans-serif}
.vmono{font-family:'DM Mono',monospace;font-size:11px;color:#374151}
.vmuted{font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af}
.badge-pref{font-family:'DM Mono',monospace;font-size:9px;padding:2px 7px;border-radius:4px;background:#fffbeb;color:#92400e;border:0.5px solid #fde68a}
</style>

    <x-slot name="header">
        <div>
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                MASTER DATA — RAW MATERIALS
            </p>
            <h1 style="font-family:'DM Serif Display',serif;font-size:26px;color:#1a1a18;letter-spacing:-0.4px;line-height:1.1">
                Raw <em style="font-style:italic;color:#38bdf8">Materials</em>
            </h1>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:5px">
                {{ $rawMaterials->count() }} purchasable materials with vendor sourcing data.
            </p>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:14px">
        @forelse ($rawMaterials as $index => $material)
            <div class="mat-card" style="animation-delay:{{ $index * 0.05 }}s">

                {{-- TOP: name + unit + vendor count --}}
                <div class="mat-top">
                    <div>
                        <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin:0 0 5px 0">RAW MATERIAL</p>
                        <h2 class="mat-name">{{ $material->name }}</h2>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;padding:4px 10px;border-radius:5px;background:#f8f7f4;border:0.5px solid #e8e3da">
                            {{ $material->baseUnit->name }} ({{ $material->baseUnit->symbol }})
                        </span>
                        @if($material->vendorMaterials->isNotEmpty())
                            <span style="font-family:'DM Mono',monospace;font-size:10px;padding:4px 10px;border-radius:5px;background:#eff6ff;color:#1d4ed8;border:0.5px solid #bfdbfe">
                                {{ $material->vendorMaterials->count() }} VENDOR{{ $material->vendorMaterials->count() > 1 ? 'S' : '' }}
                            </span>
                        @else
                            <span style="font-family:'DM Mono',monospace;font-size:10px;padding:4px 10px;border-radius:5px;background:#f1efe8;color:#5f5e5a;border:0.5px solid #e8e3da">
                                NO VENDORS
                            </span>
                        @endif
                    </div>
                </div>

                {{-- BODY: vendor sourcing table --}}
                <div class="mat-body">
                    @if($material->vendorMaterials->isNotEmpty())
                        <p class="section-label">SOURCED FROM</p>
                        <table class="vdr-table">
                            <thead>
                                <tr>
                                    <th style="width:30%">VENDOR</th>
                                    <th style="width:20%">LAST PRICE</th>
                                    <th style="width:18%">LEAD TIME</th>
                                    <th style="width:18%">MIN ORDER</th>
                                    <th style="width:14%">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($material->vendorMaterials as $vm)
                                    <tr>
                                        <td class="vname">{{ $vm->vendor->name }}</td>
                                        <td class="vmono">
                                            @if($vm->last_price)
                                                {{ $vm->currency?->symbol ?? '' }} {{ number_format((float)$vm->last_price, 2) }} / {{ $material->baseUnit->symbol }}
                                            @else
                                                <span class="vmuted">—</span>
                                            @endif
                                        </td>
                                        <td class="vmuted">
                                            {{ $vm->lead_time_days ? $vm->lead_time_days . ' days' : '—' }}
                                        </td>
                                        <td class="vmuted">
                                            @if($vm->minimum_order_qty)
                                                {{ number_format((float)$vm->minimum_order_qty, 2) }} {{ $material->baseUnit->symbol }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($vm->preferred_vendor)
                                                <span class="badge-pref">PREFERRED</span>
                                            @else
                                                <span class="vmuted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9ca3af;margin:0">
                            No vendor sourcing data available for this material.
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:12px;padding:48px;text-align:center">
                <p style="font-family:'DM Sans',sans-serif;font-size:15px;color:#6b7280;margin:0">
                    No raw materials found in the system.
                </p>
            </div>
        @endforelse
    </div>
</x-app-layout>