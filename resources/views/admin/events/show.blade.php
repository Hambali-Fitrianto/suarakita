@extends('layouts.admin')

@section('title', 'Detail Event')
@section('header', 'Detail Event Voting')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- =====================================================
        HEADER
    ===================================================== --}}
    <div class="flex items-center justify-between flex-wrap gap-3">

        <div>
            <h2 class="text-2xl font-bold">
                {{ $event->judul }}
            </h2>

            <p class="text-sm text-gray-400">
                Event adalah workspace voting. Session mengatur putaran voting.
            </p>
        </div>

        <div class="flex gap-2 flex-wrap">

            {{-- SESSION --}}
            <a href="{{ route('admin.sessions.create', ['event' => $event->id]) }}"
               class="px-5 py-2 rounded-lg
                      bg-blue-600 hover:bg-blue-700 transition">
                + Buat Session
            </a>

            {{-- KANDIDAT --}}
            <a href="{{ route('admin.candidates.index',['event'=>$event->id]) }}"
               class="px-5 py-2 rounded-lg
                      bg-emerald-600 hover:bg-emerald-700 transition">
                Kandidat
            </a>

            {{-- PEMILIH --}}
            <a href="{{ route('admin.voters.index',['event'=>$event->id]) }}"
               class="px-5 py-2 rounded-lg
                      bg-purple-600 hover:bg-purple-700 transition">
                Pemilih
            </a>

            {{-- TOKEN --}}
            <a href="{{ route('admin.events.tokens.index',$event) }}"
               class="px-5 py-2 rounded-lg
                      bg-indigo-600 hover:bg-indigo-700 transition">
                Kelola Token
            </a>

            {{-- EDIT --}}
            <a href="{{ route('admin.events.edit', $event) }}"
               class="px-5 py-2 rounded-lg
                      bg-yellow-600 hover:bg-yellow-700 transition">
                Edit Event
            </a>

        </div>

    </div>


    {{-- =====================================================
        INFO CARD
    ===================================================== --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-8">

        {{-- ================= DESKRIPSI ================= --}}
        <div class="space-y-2">
            <p class="text-gray-400 text-sm">
                Deskripsi
            </p>

            <div class="bg-slate-800/60 rounded-lg p-4 text-gray-200">
                {{ $event->deskripsi ?: 'Tidak ada deskripsi.' }}
            </div>
        </div>


        {{-- ================= STATISTIK UTAMA ================= --}}
        <div class="grid md:grid-cols-5 grid-cols-2 gap-6 pt-4 border-t border-white/10">

            <div>
                <p class="text-sm text-gray-400">Total Session</p>
                <p class="text-xl font-bold">
                    {{ $event->sessions()->count() }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400">Session Aktif</p>
                <p class="text-xl font-bold text-green-400">
                    {{ $event->sessions->filter(fn($s)=>$s->isAktif())->count() }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400">Session Selesai</p>
                <p class="text-xl font-bold text-blue-400">
                    {{ $event->sessions->filter(fn($s)=>$s->sudahSelesai())->count() }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400">Kandidat</p>
                <p class="text-xl font-bold text-emerald-400">
                    {{ $event->kandidat()->count() }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400">Pemilih</p>
                <p class="text-xl font-bold text-purple-400">
                    {{ $event->pemilih()->count() }}
                </p>
            </div>

        </div>


        {{-- ================= STATUS SESSION AKTIF ================= --}}
        @php
            $activeSession = $event->activeSession();
        @endphp

        <div class="pt-4 border-t border-white/10">

            <p class="text-sm text-gray-400 mb-2">
                Session Aktif
            </p>

            @if($activeSession)
                <div class="bg-green-500/10 border border-green-500/30
                            rounded-lg p-4">

                    <p class="font-semibold text-green-400">
                        {{ $activeSession->nama_sesi }}
                    </p>

                    <p class="text-sm text-gray-300">
                        {{ $activeSession->mulai_at?->format('d M Y H:i') }}
                        —
                        {{ $activeSession->selesai_at?->format('d M Y H:i') }}
                    </p>

                </div>
            @else
                <div class="bg-yellow-500/10 border border-yellow-500/30
                            rounded-lg p-4 text-yellow-400">
                    Tidak ada session aktif saat ini.
                </div>
            @endif

        </div>


        {{-- ================= META ================= --}}
        <div class="grid grid-cols-2 gap-6 pt-4 border-t border-white/10">

            <div>
                <p class="text-sm text-gray-400">Dibuat</p>
                <p>
                    {{ $event->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400">Terakhir Update</p>
                <p>
                    {{ $event->updated_at->format('d M Y H:i') }}
                </p>
            </div>

        </div>

    </div>


    {{-- =====================================================
        FOOTER
    ===================================================== --}}
    <div class="flex justify-between">

        <a href="{{ route('admin.events.index') }}"
           class="text-gray-400 hover:text-white transition">
            ← Kembali ke daftar event
        </a>

    </div>

</div>

@endsection