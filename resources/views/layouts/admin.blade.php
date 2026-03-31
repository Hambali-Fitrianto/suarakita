<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Suarakita Admin')</title>

    {{-- ASSETS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- GLOBAL STYLE --}}
    <style>
        [x-cloak]{display:none!important;}

        .swal2-popup{
            background:#020617!important;
            color:#e5e7eb!important;
            border:1px solid rgba(255,255,255,.08);
            border-radius:14px;
        }

        .swal2-title{color:#fff!important;}
        .swal2-html-container{color:#94a3b8!important;}

        .swal2-confirm{
            background:#ef4444!important;
            border:none!important;
        }

        .swal2-cancel{
            background:#475569!important;
            border:none!important;
        }
    </style>
</head>

<body class="bg-slate-950 text-white font-sans">

<div x-data="{ sidebar:true }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside :class="sidebar ? 'w-64' : 'w-20'"
        class="bg-slate-900 border-r border-white/10 transition-all duration-300 flex flex-col">

        {{-- LOGO --}}
        <div class="h-16 flex items-center px-4 border-b border-white/10">
            <span x-show="sidebar" class="font-bold text-lg text-blue-400">Suarakita</span>
            <span x-show="!sidebar" class="text-xl">🗳️</span>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-3 space-y-2 text-sm">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.dashboard')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                📊 <span x-show="sidebar">Dashboard</span>
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.events.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                🗓️ <span x-show="sidebar">Event Voting</span>
            </a>

            <a href="{{ route('admin.sessions.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.sessions.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                🗳️ <span x-show="sidebar">Session Voting</span>
            </a>

            <a href="{{ route('admin.candidates.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.candidates.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                🧑 <span x-show="sidebar">Kandidat</span>
            </a>

            <a href="{{ route('admin.voters.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.voters.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                👥 <span x-show="sidebar">Pemilih</span>
            </a>

            <a href="{{ route('admin.results.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.results.*')
                    ? 'bg-blue-600/20 text-blue-400'
                    : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
                📈 <span x-show="sidebar">Hasil Voting</span>
            </a>

        </nav>

        {{-- USER --}}
        <div class="p-4 border-t border-white/10 text-sm">
            <div x-show="sidebar">
                Login sebagai<br>
                <strong>{{ auth()->user()->name ?? '-' }}</strong>
            </div>

            {{-- ✅ LOGOUT SWEET ALERT --}}
            <form method="POST"
                  action="{{ route('logout') }}"
                  class="mt-3 logout-form">
                @csrf

                <button type="button"
                        class="text-red-400 hover:text-red-300 btn-logout">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="h-16 flex items-center px-6 border-b border-white/10 bg-slate-900/60 backdrop-blur">
            <button @click="sidebar=!sidebar"
                class="p-2 rounded-lg hover:bg-white/10 mr-3">☰</button>

            <h1 class="font-semibold text-gray-200">
                @yield('header','Dashboard Admin')
            </h1>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>

    </div>

</div>

{{-- SUCCESS ALERT --}}
@if(session('success'))
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:@json(session('success')),
    timer:2200,
    showConfirmButton:false
});
</script>
@endif

{{-- DELETE ALERT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // DELETE CONFIRM
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title:'Hapus data?',
                text:'Data tidak bisa dikembalikan.',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Ya, hapus',
                cancelButtonText:'Batal'
            }).then((r)=>{
                if(r.isConfirmed) form.submit();
            });
        });
    });

    // ✅ LOGOUT CONFIRM
    document.querySelectorAll('.btn-logout').forEach(button => {

        button.addEventListener('click', function () {

            const form = this.closest('form');

            Swal.fire({
                title:'Logout sekarang?',
                text:'Session login akan berakhir.',
                icon:'question',
                showCancelButton:true,
                confirmButtonText:'Ya, Logout',
                cancelButtonText:'Batal'
            }).then((result)=>{

                if(result.isConfirmed){

                    Swal.fire({
                        title:'Logging out...',
                        allowOutsideClick:false,
                        didOpen:()=>Swal.showLoading()
                    });

                    form.submit();
                }
            });

        });

    });

});
</script>

</body>
</html>