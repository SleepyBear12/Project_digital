<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Toko Kelontong')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Inter'] text-gray-800">
    @auth
    <div class="flex h-screen">
        <aside class="w-64 bg-slate-800 text-white flex flex-col shadow-xl">
            <div class="p-6 border-b border-slate-700">
                <h1 class="text-xl font-bold tracking-tight">PT SEJAHTERA ABADI</h1>
                <p class="text-xs text-slate-400 mt-1">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('dashboard') ? 'bg-slate-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('pos') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('pos') ? 'bg-slate-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Kasir (POS)
                </a>
                <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('transactions.*') ? 'bg-slate-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Riwayat Transaksi
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('products.*') ? 'bg-slate-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Kelola Barang
                </a>
                <a href="{{ route('reports.monthly') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('reports.*') ? 'bg-slate-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan Bulanan
                </a>
                @endif
            </nav>
            <div class="p-4 border-t border-slate-700">
                <div class="flex items-center px-4 py-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-400 hover:text-white transition">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto">
            @if(session('success'))
            <div class="m-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif
            @yield('content')
        </main>
    </div>
    @else
        @yield('content')
    @endauth
</body>
</html>

