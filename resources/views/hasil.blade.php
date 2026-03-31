@extends('layouts.guest')

@section('title','Hasil Voting — Suarakita')

@section('content')

<div class="relative min-h-screen flex items-center justify-center">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 bg-gradient-to-br
        from-slate-950 via-blue-950 to-black"></div>

    <div class="relative z-10 max-w-4xl w-full px-6 text-center">

        <h1 class="text-3xl md:text-4xl font-bold mb-6">
            Hasil Voting Sementara
        </h1>

        {{-- CARD --}}
        <div class="bg-white/5 backdrop-blur-lg
            border border-white/10 rounded-xl
            p-10 shadow-2xl">

            <p class="text-gray-300">
                Belum ada hasil voting.
            </p>

        </div>

        <a href="/"
           class="inline-block mt-8 px-6 py-3
           bg-blue-600 hover:bg-blue-700
           rounded-lg transition font-semibold">
            ← Kembali ke Beranda
        </a>

    </div>

</div>

@endsection