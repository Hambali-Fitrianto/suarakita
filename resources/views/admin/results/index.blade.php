@extends('layouts.admin')

@section('title', 'Hasil Voting')
@section('header', 'Hasil Voting')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <h2 class="text-2xl font-bold">
        Daftar Hasil Voting
    </h2>

    @foreach ($sessions as $eventName => $eventSessions)

        <div
            x-data="{ open: false }"
            class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden"
        >

            {{-- HEADER EVENT --}}
            <button
                @click="open = !open"
                class="w-full flex justify-between items-center px-6 py-4 bg-slate-800 hover:bg-slate-700"
            >
                <div class="text-left">
                    <h3 class="text-blue-400 font-semibold">
                        {{ $eventName }}
                    </h3>

                    <p class="text-xs text-gray-400">
                        {{ $eventSessions->count() }} Session
                    </p>
                </div>

                <span x-text="open ? '▲' : '▼'"></span>
            </button>

            {{-- SESSION LIST --}}
            <div x-show="open" x-collapse class="divide-y divide-white/10">

                @foreach ($eventSessions as $session)

                    <div class="flex justify-between items-center px-6 py-4">

                        <div>
                            <p class="font-semibold">
                                {{ $session->nama_sesi }}
                            </p>

                            <p class="text-xs text-gray-400">
                                Urutan {{ $session->urutan }}
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.results.show', $session) }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold"
                        >
                            Lihat Hasil →
                        </a>

                    </div>

                @endforeach

            </div>

        </div>

    @endforeach

</div>

@endsection