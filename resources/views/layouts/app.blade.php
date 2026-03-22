<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
    @php
        $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
    @endphp
    @if ($hasViteAssets)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

<style>
@keyframes blink{0%,100%{opacity:1}50%{opacity:0.2}}
@keyframes fadeInSidebar{from{opacity:0;transform:translateX(-8px)}to{opacity:1;transform:translateX(0)}}

*{box-sizing:border-box}
body{margin:0;padding:0;background:#f8f7f4 !important;font-family:'DM Sans',sans-serif}

/* ── SHELL ── */
.app-shell{display:flex;min-height:100vh}

/* ── SIDEBAR ── */
.app-sidebar{
    width:230px;min-width:230px;
    background:#0a0f1a;
    display:flex;flex-direction:column;
    position:fixed;top:0;left:0;bottom:0;
    z-index:50;
    border-right:0.5px solid rgba(255,255,255,0.04);
    animation:fadeInSidebar 0.4s ease both;
}

/* Brand */
.sb-brand{
    padding:22px 22px 18px;
    border-bottom:0.5px solid rgba(255,255,255,0.05);
    margin-bottom:6px;
}
.sb-logo{
    display:flex;align-items:center;gap:9px;
    font-family:'DM Mono',monospace;
    font-size:12px;font-weight:500;
    color:#38bdf8;letter-spacing:0.18em;
}
.sb-logo-dot{
    width:7px;height:7px;border-radius:50%;
    background:#38bdf8;
    box-shadow:0 0 8px rgba(56,189,248,0.6);
    animation:blink 2.5s infinite;
    flex-shrink:0;
}
.sb-tagline{
    font-family:'DM Mono',monospace;
    font-size:9px;color:#1e2d3d;
    letter-spacing:0.1em;margin-top:6px;
}

/* Nav */
.sb-nav{padding:8px 14px;flex:1;overflow-y:auto}
.sb-nav::-webkit-scrollbar{display:none}

.sb-section{
    font-family:'DM Mono',monospace;
    font-size:9px;color:#1e3a4a;
    letter-spacing:0.12em;
    padding:0 8px;
    margin:14px 0 6px;
}

.sb-link{
    display:flex;align-items:center;gap:9px;
    padding:8px 10px;border-radius:7px;
    font-family:'DM Sans',sans-serif;
    font-size:13px;color:#3d5166;
    text-decoration:none;
    transition:all 0.15s;
    margin-bottom:2px;
}
.sb-link:hover{
    background:rgba(56,189,248,0.05);
    color:#7cb9d4;
}
.sb-link.active{
    background:rgba(56,189,248,0.09);
    color:#38bdf8;
}
.sb-pip{
    width:5px;height:5px;border-radius:50%;
    background:currentColor;flex-shrink:0;
    opacity:0.7;
}
.sb-link.active .sb-pip{opacity:1}

.sb-badge{
    margin-left:auto;
    background:rgba(239,68,68,0.12);
    color:#f87171;
    font-family:'DM Mono',monospace;
    font-size:9px;padding:2px 6px;border-radius:4px;
    border:0.5px solid rgba(239,68,68,0.15);
}

/* User area */
.sb-user{
    padding:14px 22px 16px;
    border-top:0.5px solid rgba(255,255,255,0.04);
}
.sb-user-row{
    display:flex;align-items:center;gap:10px;
    margin-bottom:10px;
}
.sb-avatar{
    width:30px;height:30px;border-radius:50%;
    background:rgba(56,189,248,0.1);
    border:0.5px solid rgba(56,189,248,0.2);
    display:flex;align-items:center;justify-content:center;
    font-family:'DM Mono',monospace;
    font-size:11px;font-weight:500;
    color:#38bdf8;flex-shrink:0;
    letter-spacing:0.02em;
}
.sb-uname{
    font-family:'DM Sans',sans-serif;
    font-size:13px;font-weight:500;color:#7a92a8;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.sb-urole{
    font-family:'DM Mono',monospace;
    font-size:9px;color:#1e3a4a;
    letter-spacing:0.08em;margin-top:2px;
}
.sb-logout{
    display:flex;align-items:center;gap:8px;
    width:100%;padding:7px 10px;
    border-radius:7px;
    font-family:'DM Sans',sans-serif;
    font-size:12px;color:#1e3a4a;
    background:none;border:none;cursor:pointer;
    transition:all 0.15s;text-decoration:none;
    letter-spacing:0.01em;
}
.sb-logout:hover{
    background:rgba(239,68,68,0.06);
    color:#f87171;
}
.sb-logout-dot{
    width:5px;height:5px;border-radius:50%;
    background:currentColor;flex-shrink:0;opacity:0.5;
}

/* ── MAIN ── */
.app-main{
    margin-left:230px;
    flex:1;display:flex;flex-direction:column;
    min-height:100vh;
}

/* ── HEADER ── */
.app-header{
    background:#f8f7f4;
    border-bottom:0.5px solid #e4dfd7;
    padding:22px 36px;
    position:sticky;top:0;z-index:40;
}

/* ── CONTENT ── */
.app-content{
    padding:28px 36px;
    flex:1;
}

/* ── FLASH ── */
.flash-success{
    margin-bottom:18px;
    border:0.5px solid #bbf7d0;
    background:#f0fdf4;
    border-radius:8px;
    padding:11px 16px;
    font-family:'DM Sans',sans-serif;
    font-size:13px;color:#166534;
}
.flash-error{
    margin-bottom:18px;
    border:0.5px solid #fecaca;
    background:#fef2f2;
    border-radius:8px;
    padding:11px 16px;
    font-family:'DM Sans',sans-serif;
    font-size:13px;color:#991b1b;
}
</style>
</head>

<body>
<div class="app-shell">

    {{-- ── SIDEBAR ── --}}
    <aside class="app-sidebar">
        <div class="sb-brand">
            <div class="sb-logo">
                <div class="sb-logo-dot"></div>
                PROCUREFLOW
            </div>
            <div class="sb-tagline">MANUFACTURING MVP</div>
        </div>

        <nav class="sb-nav">
            <div class="sb-section">MAIN</div>

            <a href="{{ route('dashboard') }}"
               class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                Dashboard
            </a>

            <a href="{{ route('purchase-requests.index') }}"
               class="sb-link {{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                Purchase Requests
            </a>

            <a href="{{ route('rfqs.index') }}"
               class="sb-link {{ request()->routeIs('rfqs.*') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                RFQs
            </a>

            <div class="sb-section">MASTER DATA</div>

            <a href="{{ route('products.index') }}"
               class="sb-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                Products
            </a>

            <a href="{{ route('raw-materials.index') }}"
               class="sb-link {{ request()->routeIs('raw-materials.*') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                Raw Materials
            </a>

            <a href="{{ route('vendors.index') }}"
               class="sb-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                <div class="sb-pip"></div>
                Vendors
            </a>

            @if(auth()->user()->role === 'admin')
                <div class="sb-section">ADMIN</div>
                <a href="#" class="sb-link">
                    <div class="sb-pip"></div>
                    Users
                </a>
            @endif
        </nav>

        <div class="sb-user">
            <div class="sb-user-row">
                <div class="sb-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? 'X', 0, 1)) }}
                </div>
                <div style="overflow:hidden">
                    <div class="sb-uname">{{ auth()->user()->name }}</div>
                    <div class="sb-urole">{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <div class="sb-logout-dot"></div>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN ── --}}
    <div class="app-main">

        <header class="app-header">
            @isset($header)
                {{ $header }}
            @else
                <h1 style="font-family:'DM Serif Display',serif;font-size:24px;color:#1a1a18;letter-spacing:-0.3px">
                    Dashboard
                </h1>
            @endisset
        </header>

        <main class="app-content">
            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-error">{{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>

    </div>
</div>
</body>
</html>