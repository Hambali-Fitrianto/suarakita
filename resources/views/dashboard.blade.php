@extends('layouts.admin')

@section('content')

<div class="space-y-10">

    {{-- WELCOME --}}
    <div>
        <h1 class="text-3xl font-bold text-white">
            Selamat Datang 👋
        </h1>

        <p class="text-gray-400 mt-2">
            Dashboard monitoring voting realtime.
        </p>
    </div>


    {{-- STATS --}}
    <div class="grid gap-6 md:grid-cols-3">

        {{-- TOTAL PEMILIH --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl p-6">
            <p class="text-gray-400 text-sm">
                Total Pemilih
            </p>

            <h3 class="text-3xl font-bold mt-2">
                {{ number_format($totalPemilih) }}
            </h3>
        </div>


        {{-- SUDAH VOTING --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl p-6">
            <p class="text-gray-400 text-sm">
                Sudah Voting
            </p>

            <h3 class="text-3xl font-bold mt-2">
                {{ number_format($sudahVoting) }}
            </h3>
        </div>


        {{-- PARTISIPASI --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl p-6">
            <p class="text-gray-400 text-sm">
                Partisipasi
            </p>

            <h3 class="text-3xl font-bold mt-2 text-blue-400">
                {{ $partisipasi }}%
            </h3>
        </div>

    </div>


    {{-- QUICK ACTION --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8">

        <h3 class="text-lg font-semibold mb-6">
            Aksi Cepat
        </h3>

        <div class="flex flex-wrap gap-4">

            <a href="{{ route('admin.candidates.index') }}"
                class="px-6 py-3 rounded-lg
                bg-blue-600 hover:bg-blue-700 transition">

                Kelola Kandidat
            </a>

            <a href="{{ route('public.result') }}"
                class="px-6 py-3 rounded-lg
                border border-white/20 hover:bg-white hover:text-black transition">

                Lihat Hasil Voting
            </a>

        </div>

    </div>

</div>

@endsection