@extends('layouts.admin')

@section('title','Dashboard')
@section('header','Dashboard Admin')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold">
                👋 Selamat Datang, {{ auth()->user()->name }}
            </h1>

            <p class="text-gray-400 mt-1 text-sm">
                Ringkasan aktivitas sistem E-Voting Suarakita.
            </p>
        </div>

        <div class="text-right text-sm text-gray-400">
            {{ now()->format('l, d F Y') }}
        </div>

    </div>


    {{-- ================= STAT CARDS ================= --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">

        {{-- EVENT --}}
        <div class="group relative bg-gradient-to-br from-blue-600/10 to-blue-400/5
            border border-blue-500/20 rounded-2xl p-6 transition hover:scale-[1.03]">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total Event</p>
                    <p class="text-3xl font-bold mt-2 text-blue-400">
                        {{ $totalEvent }}
                    </p>
                </div>

                <div class="text-3xl opacity-70 group-hover:scale-110 transition">
                    🗓️
                </div>
            </div>

            <div class="mt-4 h-1 bg-blue-500/30 rounded-full overflow-hidden">
                <div class="h-full bg-blue-400 w-full"></div>
            </div>
        </div>


        {{-- SESSION --}}
        <div class="group relative bg-gradient-to-br from-indigo-600/10 to-indigo-400/5
            border border-indigo-500/20 rounded-2xl p-6 transition hover:scale-[1.03]">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Session Voting</p>
                    <p class="text-3xl font-bold mt-2 text-indigo-400">
                        {{ $totalSession }}
                    </p>
                </div>

                <div class="text-3xl group-hover:rotate-6 transition">
                    🗳️
                </div>
            </div>

            <div class="mt-4 h-1 bg-indigo-500/30 rounded-full">
                <div class="h-full bg-indigo-400 w-full"></div>
            </div>
        </div>


        {{-- PEMILIH --}}
        <div class="group relative bg-gradient-to-br from-sky-600/10 to-sky-400/5
            border border-sky-500/20 rounded-2xl p-6 transition hover:scale-[1.03]">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total Pemilih</p>
                    <p class="text-3xl font-bold mt-2 text-sky-400">
                        {{ $totalPemilih }}
                    </p>
                </div>

                <div class="text-3xl group-hover:scale-110 transition">
                    👥
                </div>
            </div>

            <div class="mt-4 h-1 bg-sky-500/30 rounded-full">
                <div class="h-full bg-sky-400 w-full"></div>
            </div>
        </div>


        {{-- KANDIDAT --}}
        <div class="group relative bg-gradient-to-br from-yellow-600/10 to-yellow-400/5
            border border-yellow-500/20 rounded-2xl p-6 transition hover:scale-[1.03]">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total Kandidat</p>
                    <p class="text-3xl font-bold mt-2 text-yellow-400">
                        {{ $totalKandidat }}
                    </p>
                </div>

                <div class="text-3xl group-hover:-rotate-6 transition">
                    🧑
                </div>
            </div>

            <div class="mt-4 h-1 bg-yellow-500/30 rounded-full">
                <div class="h-full bg-yellow-400 w-full"></div>
            </div>
        </div>


        {{-- VOTE --}}
        <div class="group relative bg-gradient-to-br from-green-600/10 to-green-400/5
            border border-green-500/20 rounded-2xl p-6 transition hover:scale-[1.03]">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Suara Masuk</p>
                    <p class="text-3xl font-bold mt-2 text-green-400">
                        {{ $totalVote }}
                    </p>
                </div>

                <div class="text-3xl animate-pulse">
                    ✅
                </div>
            </div>

            <div class="mt-4 h-1 bg-green-500/30 rounded-full">
                <div class="h-full bg-green-400 w-full"></div>
            </div>
        </div>

    </div>


    {{-- ================= QUICK ACTION ================= --}}
    <div class="grid md:grid-cols-3 gap-6">

        <a href="{{ route('admin.events.index') }}"
           class="bg-slate-900 border border-white/10 rounded-2xl p-6
           hover:border-blue-500/40 hover:bg-white/5 transition">

            <h3 class="font-semibold text-lg mb-2">Kelola Event</h3>
            <p class="text-gray-400 text-sm">
                Buat dan atur event voting.
            </p>
        </a>

        <a href="{{ route('admin.sessions.index') }}"
           class="bg-slate-900 border border-white/10 rounded-2xl p-6
           hover:border-indigo-500/40 hover:bg-white/5 transition">

            <h3 class="font-semibold text-lg mb-2">Session Voting</h3>
            <p class="text-gray-400 text-sm">
                Atur sesi voting aktif.
            </p>
        </a>

        <a href="{{ route('admin.results.index') }}"
           class="bg-slate-900 border border-white/10 rounded-2xl p-6
           hover:border-green-500/40 hover:bg-white/5 transition">

            <h3 class="font-semibold text-lg mb-2">Lihat Hasil</h3>
            <p class="text-gray-400 text-sm">
                Monitor hasil voting realtime.
            </p>
        </a>

    </div>

</div>

@endsection