<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name','Suarakita') }}</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display:none !important; }
    </style>
</head>

<body class="bg-slate-950 text-white font-sans">

{{-- ================= WRAPPER ================= --}}
<div x-data="{ sidebar:true }" class="flex h-screen relative">

    {{-- ================= SIDEBAR ================= --}}
    <aside
        :class="sidebar ? 'w-64' : 'w-20'"
        class="relative z-40
        bg-slate-900 border-r border-white/10
        transition-all duration-300
        flex flex-col">

        {{-- LOGO --}}
        <div class="h-16 flex items-center px-4 border-b border-white/10">
            <span x-show="sidebar" x-transition
                class="font-bold text-lg text-blue-400">
                Suarakita
            </span>

            <span x-show="!sidebar" class="text-xl">🗳️</span>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-3 space-y-2">

            {{-- DASHBOARD --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.dashboard')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'hover:bg-white/10 text-gray-300' }}">

                📊
                <span x-show="sidebar">Dashboard</span>
            </a>

            {{-- KANDIDAT --}}
            <a href="{{ route('admin.candidates.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.candidates.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'hover:bg-white/10 text-gray-300' }}">

                👤
                <span x-show="sidebar">Kandidat</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-gray-300">
                👥 <span x-show="sidebar">Pemilih</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-gray-300">
                🗳️ <span x-show="sidebar">Voting</span>
            </a>

            <a href="{{ route('public.result') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-gray-300">
                📈 <span x-show="sidebar">Hasil Voting</span>
            </a>

        </nav>

        {{-- USER --}}
        <div class="p-4 border-t border-white/10 text-sm">

            <div x-show="sidebar">
                Login sebagai<br>
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="text-red-400 hover:text-red-300">
                    Logout
                </button>
            </form>

        </div>

    </aside>


    {{-- ================= MAIN ================= --}}
    <div class="flex-1 flex flex-col relative z-0">

        {{-- TOPBAR --}}
        <header class="h-16 flex items-center justify-between px-6
            border-b border-white/10
            bg-slate-900/60 backdrop-blur">

            <div class="flex items-center gap-3">

                {{-- TOGGLE --}}
                <button @click="sidebar = !sidebar"
                        class="p-2 rounded-lg hover:bg-white/10 transition">
                    ☰
                </button>

                <h1 class="font-semibold">
                    Dashboard Admin
                </h1>

            </div>

        </header>

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-8">
            {{ $slot }}
        </main>

    </div>

</div>

</body>
</html>