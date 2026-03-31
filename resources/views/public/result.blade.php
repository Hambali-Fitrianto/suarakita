@extends('layouts.guest')

@section('title','Hasil Voting')

@section('content')

<div class="max-w-7xl mx-auto py-16 px-6 space-y-12">

    {{-- ================= HEADER ================= --}}
    <div class="text-center space-y-4">

        <h1 class="text-4xl font-bold">
            Hasil Voting
        </h1>

        <p class="text-gray-400">
            Perolehan suara realtime per session.
        </p>

        <a href="{{ route('landing') }}"
           class="inline-block mt-4 px-6 py-3
           bg-blue-600 hover:bg-blue-700
           rounded-lg font-semibold transition shadow-lg">

            ← Kembali ke Beranda
        </a>

    </div>


    {{-- ================= RESULT LIST ================= --}}
    @foreach($results as $eventName => $sessions)

        <div class="bg-white/5 border border-white/10 rounded-2xl p-8">

            {{-- EVENT TITLE --}}
            <h2 class="text-xl font-semibold text-blue-400 mb-10">
                {{ $eventName }}
            </h2>

            @foreach($sessions as $sessionName => $candidates)

                {{-- SESSION TITLE --}}
                <h3 class="text-lg font-semibold mb-6 text-indigo-300">
                    🗳️ {{ $sessionName }}
                </h3>

                <div class="grid md:grid-cols-3 gap-6 mb-12">

                    @foreach($candidates as $candidate)

                        <div class="bg-slate-900 rounded-xl p-6 text-center
                                    border border-white/5 hover:border-blue-500/30
                                    transition">

                            {{-- FOTO --}}
                            @if($candidate->foto)
                                <img src="{{ asset('storage/'.$candidate->foto) }}"
                                     class="w-24 h-24 mx-auto rounded-full object-cover mb-4">
                            @else
                                <div class="w-24 h-24 mx-auto rounded-full
                                    bg-slate-800 flex items-center justify-center
                                    text-2xl mb-4">
                                    {{ strtoupper(substr($candidate->nama,0,2)) }}
                                </div>
                            @endif

                            {{-- NAMA --}}
                            <h4 class="font-semibold text-lg">
                                {{ $candidate->nama }}
                            </h4>

                            {{-- JABATAN --}}
                            <p class="text-sm text-gray-400">
                                {{ $candidate->jabatan }}
                            </p>

                            {{-- TOTAL SUARA --}}
                            <div class="mt-4 text-sm text-gray-400">
                                Total Suara
                            </div>

                            <div class="text-3xl font-bold text-green-400">
                                {{ $candidate->total_suara }}
                            </div>

                        </div>

                    @endforeach

                </div>

            @endforeach

        </div>

    @endforeach

</div>

@endsection