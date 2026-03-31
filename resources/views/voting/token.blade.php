@extends('layouts.guest')

@section('title', 'Masukkan Token — Suarakita')

@section('content')

<div class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 bg-gradient-to-br
        from-slate-950 via-blue-950 to-black"></div>

    <div class="absolute w-[500px] h-[500px]
        bg-blue-600/20 blur-[140px] -top-20 -left-20"></div>

    <div class="absolute w-[500px] h-[500px]
        bg-indigo-500/20 blur-[140px] bottom-0 right-0"></div>

    {{-- CARD --}}
    <div class="relative z-10 w-full max-w-md
        backdrop-blur-xl bg-white/5
        border border-white/10
        rounded-2xl p-8 shadow-2xl">

        <h2 class="text-2xl font-bold text-center mb-2 text-white">
            Validasi Token
        </h2>

        <p class="text-center text-gray-300 mb-6 text-sm">
            Masukkan token voting yang Anda terima melalui WhatsApp.
        </p>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-400/30
                text-red-300 p-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/token/verify') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm mb-2 text-gray-300">
                    Token Voting
                </label>

                <input
                    type="text"
                    name="token"
                    placeholder="Contoh: C6372B"
                    autocomplete="off"
                    required
                    class="w-full bg-black/40 border border-white/20
                           rounded-lg px-4 py-3 text-center text-lg
                           tracking-widest uppercase text-white
                           focus:ring-2 focus:ring-blue-500
                           outline-none"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700
                       py-3 rounded-lg font-semibold
                       transition duration-200 shadow-lg">
                Verifikasi Token
            </button>

        </form>

        <a href="/"
           class="block text-center text-sm text-gray-400
                  hover:text-white mt-6 transition">
            ← Kembali ke Beranda
        </a>

    </div>

</div>

@endsection