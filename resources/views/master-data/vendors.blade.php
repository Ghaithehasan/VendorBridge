<x-app-layout>
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.vendor-card{background:#fff;border:0.5px solid #e8e3da;border-radius:12px;overflow:hidden;animation:fadeUp 0.4s ease both;margin-bottom:14px;transition:all 0.2s}
.vendor-card:hover{border-color:#b8b3a9;box-shadow:0 2px 8px rgba(0,0,0,0.02)}
.vendor-header{padding:20px 22px;border-bottom:0.5px solid #f0ece6;display:flex;align-items:center;justify-content:space-between}
.vendor-name{font-family:'DM Serif Display',serif;font-size:20px;color:#1a1a18;margin:0;letter-spacing:-0.2px}
.vendor-country{font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;margin-top:4px}
.vendor-contact{font-family:'DM Sans',sans-serif;font-size:12px;color:#6b7280;margin-top:6px}
.vendor-badge{font-family:'DM Mono',monospace;font-size:10px;padding:4px 10px;border-radius:6px;background:#eff6ff;color:#1d4ed8;border:0.5px solid #93c5fd}
.vendor-body{padding:20px 22px}
.mat-label{font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin-bottom:10px}
.mat-grid{display:grid;gap:10px;grid-template-columns:repeat(auto-fill,minmax(240px,1fr))}
.mat-item{background:#f8f7f4;border:0.5px solid #e8e3da;border-radius:7px;padding:12px 14px}
.mat-item-name{font-family:'DM Sans',sans-serif;font-size:13px;color:#374151;font-weight:500;margin-bottom:6px}
.mat-item-meta{font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af}
.mat-item-price{font-family:'DM Mono',monospace;font-size:11px;color:#166534;margin-top:4px;font-weight:500}
</style>

    <x-slot name="header">
        <div>
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                MASTER DATA — VENDORS
            </p>
            <h1 style="font-family:'DM Serif Display',serif;font-size:26px;color:#1a1a18;letter-spacing:-0.4px;line-height:1.1">
                Supplier <em style="font-style:italic;color:#38bdf8">Network</em>
            </h1>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:5px">
                {{ $vendors->count() }} vendors with material sourcing capabilities.
            </p>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:14px">
        @forelse ($vendors as $index => $vendor)
            <div class="vendor-card" style="animation-delay:{{ $index * 0.05 }}s">
                <div class="vendor-header">
                    <div>
                        <h2 class="vendor-name">{{ $vendor->name }}</h2>
                        <p class="vendor-country">{{ $vendor->country }}</p>
                        <div class="vendor-contact">
                            @if($vendor->email)
                                <span style="margin-right:12px">✉ {{ $vendor->email }}</span>
                            @endif
                            @if($vendor->phone)
                                <span>☎ {{ $vendor->phone }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="vendor-badge">{{ $vendor->vendorMaterials->count() }} MATERIALS</span>
                </div>

                @if($vendor->vendorMaterials->isNotEmpty())
                    <div class="vendor-body">
                        <p class="mat-label">MATERIALS SUPPLIED</p>
                        <div class="mat-grid">
                            @foreach($vendor->vendorMaterials as $vm)
                                <div class="mat-item">
                                    <p class="mat-item-name">{{ $vm->rawMaterial->name }}</p>
                                    <p class="mat-item-meta">
                                        Lead time: {{ $vm->lead_time_days ?? '—' }} days
                                        @if($vm->minimum_order_qty)
                                            · Min: {{ number_format((float) $vm->minimum_order_qty, 2) }} {{ $vm->rawMaterial->baseUnit->symbol }}
                                            
                                        @endif
                                    </p>
                                    @if($vm->last_price)
                                        <p class="mat-item-price">last price: {{ $vm->last_price }} {{ $vm->currency?->symbol ?? '' }} / {{ $vm->rawMaterial->baseUnit->name }} {{ $vm->rawMaterial->baseUnit->symbol }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:12px;padding:48px;text-align:center">
                <p style="font-family:'DM Sans',sans-serif;font-size:15px;color:#6b7280;margin:0">
                    No vendors found in the system.
                </p>
            </div>
        @endforelse
    </div>
</x-app-layout>
