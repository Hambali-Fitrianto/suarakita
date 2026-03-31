@extends('layouts.guest')

@section('title','Voting Ditutup')

@section('content')

<div class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 bg-gradient-to-br
        from-slate-950 via-blue-900 to-black"></div>

    <div class="absolute w-[450px] h-[450px]
        bg-blue-600/20 blur-[140px] -top-20 -left-20"></div>

    <div class="absolute w-[450px] h-[450px]
        bg-indigo-500/20 blur-[140px] bottom-0 right-0"></div>


    {{-- CARD --}}
    <div class="relative z-10 w-full max-w-lg text-center">

        <div class="backdrop-blur-xl bg-white/5
            border border-white/10 rounded-2xl
            p-10 shadow-2xl">

            {{-- ICON --}}
            <div class="text-6xl mb-6 animate-pulse">
                🗳️
            </div>

            {{-- TITLE --}}
            <h1 class="text-3xl font-bold mb-3
                bg-gradient-to-r from-blue-400 to-indigo-400
                bg-clip-text text-transparent">

                Voting Tidak Tersedia
            </h1>

            {{-- MESSAGE --}}
            <p class="text-gray-400 mb-8 leading-relaxed">
                Session voting belum dimulai atau sudah berakhir.
                Silakan kembali ke halaman utama.
            </p>

            {{-- BUTTON RESET SESSION --}}
            <a href="{{ route('vote.reset') }}"
               class="inline-flex items-center gap-2
               px-6 py-3 bg-blue-600 hover:bg-blue-700
               rounded-lg font-semibold transition shadow-lg">

                ← Kembali ke Beranda
            </a>

        </div>

    </div>

</div>

@endsection