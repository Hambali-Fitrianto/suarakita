@extends('layouts.guest')

@section('title','Suarakita')

@section('content')

<div class="relative min-h-screen overflow-hidden">

    {{-- ================= BACKGROUND ================= --}}
    <div class="absolute inset-0 bg-gradient-to-br
        from-slate-950 via-blue-900 to-black"></div>

    {{-- GLOW EFFECT --}}
    <div class="absolute w-[500px] h-[500px]
        bg-blue-600/20 blur-[140px] -top-20 -left-20"></div>

    <div class="absolute w-[500px] h-[500px]
        bg-indigo-500/20 blur-[140px] bottom-0 right-0"></div>


    {{-- ================= NAVBAR ================= --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">

        <h1 class="text-xl font-bold text-white tracking-wide">
            🗳️ Suarakita
        </h1>

        <a href="{{ route('login') }}"
           class="px-5 py-2 border border-white/30 rounded-lg
                  text-sm hover:bg-white hover:text-black
                  transition duration-300">
            Login Admin
        </a>

    </div>


    {{-- ================= HERO ================= --}}
    <div class="relative z-10 flex items-center justify-center
                min-h-[80vh] text-center px-6">

        <div class="max-w-4xl">

            {{-- TITLE --}}
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                <span class="bg-gradient-to-r from-blue-400 to-indigo-400
                    bg-clip-text text-transparent">
                    Suarakita
                </span>
            </h1>

            {{-- DESCRIPTION --}}
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed">
                Sistem
                <span class="text-blue-400 font-semibold">
                    E-Voting Digital
                </span>
                yang aman, transparan, dan realtime untuk mendukung
                pemilihan modern berbasis teknologi.
            </p>

            {{-- ================= BUTTONS ================= --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">

                {{-- MULAI VOTING --}}
                <a href="{{ route('token.index') }}"
                   class="px-8 py-4 bg-blue-600 rounded-lg
                   hover:bg-blue-700 transition font-semibold
                   shadow-lg hover:scale-105 duration-300">
                    🚀 Mulai Voting
                </a>

                {{-- HASIL VOTING --}}
                <a href="{{ route('public.result.index') }}"
                   class="px-8 py-4 border border-white rounded-lg
                   hover:bg-white hover:text-black transition
                   hover:scale-105 duration-300">
                    📊 Lihat Hasil
                </a>

            </div>


            {{-- ================= FEATURES ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-16 text-gray-400 text-sm">

                <div class="bg-white/5 border border-white/10 rounded-xl p-6 backdrop-blur">
                    🔐
                    <p class="mt-2 font-medium text-gray-300">
                        Keamanan Tinggi
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-xl p-6 backdrop-blur">
                    ⚡
                    <p class="mt-2 font-medium text-gray-300">
                        Realtime Counting
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-xl p-6 backdrop-blur">
                    🌐
                    <p class="mt-2 font-medium text-gray-300">
                        Akses Semua Perangkat
                    </p>
                </div>

            </div>


            {{-- ================= FOOTER ================= --}}
            <p class="text-gray-500 mt-16 text-sm">
                © {{ date('Y') }} Suarakita — Sistem E-Voting Modern
            </p>

        </div>

    </div>

</div>

@endsection