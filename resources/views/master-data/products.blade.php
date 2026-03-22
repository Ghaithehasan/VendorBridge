<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap');
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.prod-card{background:#fff;border:0.5px solid #e8e3da;border-radius:12px;overflow:hidden;animation:fadeUp 0.4s ease both}
.prod-top{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:0.5px solid #f5f0ea}
.prod-name{font-family:'DM Serif Display',serif;font-size:20px;color:#1a1a18;margin:0;letter-spacing:-0.2px}
.prod-code{font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af;margin-top:4px}
.prod-body{padding:16px 22px}
.prod-desc{font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;line-height:1.6;margin:0 0 16px 0;padding-bottom:14px;border-bottom:0.5px solid #f5f0ea}
.bom-label{font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.12em;margin:0 0 10px 0}
.bom-table{width:100%;border-collapse:collapse}
.bom-table th{font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.08em;font-weight:400;text-align:left;padding:0 0 8px;border-bottom:0.5px solid #f0ece6}
.bom-table td{font-family:'DM Sans',sans-serif;font-size:12px;color:#374151;padding:9px 0;border-bottom:0.5px solid #f8f5f0}
.bom-table tr:last-child td{border-bottom:none}
.bom-table tr:hover td{background:#faf8f5}
.bom-mat{font-weight:500;color:#1a1a18}
.bom-qty{font-family:'DM Mono',monospace;font-size:11px;color:#374151}
.bom-unit{font-family:'DM Mono',monospace;font-size:11px;color:#9ca3af}
</style>

    <x-slot name="header">
        <div>
            <p style="font-family:'DM Mono',monospace;font-size:10px;color:#9ca3af;letter-spacing:0.15em;margin-bottom:5px">
                MASTER DATA — PRODUCTS
            </p>
            <h1 style="font-family:'DM Serif Display',serif;font-size:26px;color:#1a1a18;letter-spacing:-0.4px;line-height:1.1">
                Finished <em style="font-style:italic;color:#38bdf8">Products</em>
            </h1>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#6b7280;margin-top:5px">
                {{ $products->count() }} products with their Bill of Materials (BOM).
            </p>
        </div>
    </x-slot>

    <div style="display:flex;flex-direction:column;gap:14px">
        @forelse ($products as $index => $product)
            <div class="prod-card" style="animation-delay:{{ $index * 0.05 }}s">

                {{-- TOP: name + code + bom count --}}
                <div class="prod-top">
                    <div>
                        <p style="font-family:'DM Mono',monospace;font-size:9px;color:#9ca3af;letter-spacing:0.1em;margin:0 0 5px 0">FINISHED PRODUCT</p>
                        <h2 class="prod-name">{{ $product->name }}</h2>
                        @if($product->code)
                            <p class="prod-code">CODE: {{ $product->code }}</p>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        @if($product->is_active)
                            <span style="font-family:'DM Mono',monospace;font-size:10px;padding:4px 10px;border-radius:5px;background:#f0fdf4;color:#166534;border:0.5px solid #bbf7d0">
                                ACTIVE
                            </span>
                        @endif
                        <span style="font-family:'DM Mono',monospace;font-size:10px;padding:4px 10px;border-radius:5px;background:#eff6ff;color:#1d4ed8;border:0.5px solid #bfdbfe">
                            {{ $product->bomLines->count() }} MATERIAL{{ $product->bomLines->count() !== 1 ? 'S' : '' }}
                        </span>
                    </div>
                </div>

                {{-- BODY: description + bom table --}}
                <div class="prod-body">

                    @if($product->description)
                        <p class="prod-desc">{{ $product->description }}</p>
                    @endif

                    @if($product->bomLines->isNotEmpty())
                        <p class="bom-label">BILL OF MATERIALS</p>
                        <table class="bom-table">
                            <thead>
                                <tr>
                                    <th style="width:50%">MATERIAL</th>
                                    <th style="width:25%">QUANTITY REQUIRED</th>
                                    <th style="width:25%">UNIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->bomLines as $bom)
                                    <tr>
                                        <td class="bom-mat">{{ $bom->rawMaterial->name }}</td>
                                        <td class="bom-qty">{{ number_format((float) $bom->quantity_required, 4) }}</td>
                                        <td class="bom-unit">{{ $bom->unit->name }} ({{ $bom->unit->symbol }})</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9ca3af;margin:0">
                            No Bill of Materials defined yet.
                        </p>
                    @endif
                </div>

            </div>
        @empty
            <div style="background:#fff;border:0.5px solid #e8e3da;border-radius:12px;padding:48px;text-align:center">
                <p style="font-family:'DM Sans',sans-serif;font-size:15px;color:#6b7280;margin:0">
                    No products found in the system.
                </p>
            </div>
        @endforelse
    </div>
</x-app-layout>