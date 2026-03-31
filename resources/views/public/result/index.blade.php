@extends('layouts.guest')

@section('title','Hasil Voting')

@section('content')

<div class="max-w-5xl mx-auto py-16 px-6">

    <h1 class="text-4xl font-bold text-center mb-12">
        Hasil Voting
    </h1>

    <div class="space-y-6">

        @forelse($sessions as $session)

            <div class="bg-white/5 border border-white/10
                        rounded-xl p-6 flex justify-between items-center">

                <div>
                    <h2 class="text-lg font-semibold text-blue-400">
                        {{ $session->event->judul ?? '-' }}
                    </h2>

                    <p class="text-gray-400 text-sm">
                        {{ $session->nama_sesi }}
                    </p>
                </div>

                <a href="{{ route('public.result.show', $session) }}"
                   class="px-5 py-2 bg-blue-600 hover:bg-blue-700
                          rounded-lg text-sm font-semibold transition">

                    Lihat Hasil →
                </a>

            </div>

        @empty

            <div class="text-center text-gray-400 py-20">
                Belum ada hasil voting.
            </div>

        @endforelse

    </div>

    {{-- BACK --}}
    <div class="text-center mt-12">
        <a href="{{ route('landing') }}"
           class="text-gray-400 hover:text-white">
            ← Kembali ke Beranda
        </a>
    </div>

</div>

@endsection