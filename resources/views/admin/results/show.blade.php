@extends('layouts.admin')

@section('title', 'Detail Hasil')
@section('header', 'Detail Hasil Voting')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <a
        href="{{ route('admin.results.index') }}"
        class="text-gray-400 hover:text-white"
    >
        ← Kembali
    </a>

    <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

        <div>
            <h2 class="text-xl font-bold">
                {{ $session->nama_sesi }}
            </h2>

            <p class="text-sm text-gray-400">
                Total Vote : {{ $totalVotes }}
            </p>
        </div>

        <div class="space-y-4">

            @foreach ($results as $index => $row)

                @php
                    $candidate = $row['candidate'];
                    $percent   = $totalVotes > 0
                        ? round(($row['votes'] / $totalVotes) * 100)
                        : 0;
                @endphp

                <div class="bg-slate-950 border border-white/5 rounded-lg p-4">

                    <div class="flex items-center gap-4">

                        {{-- RANK --}}
                        <div class="w-8 text-center font-bold text-lg">
                            {{ $index + 1 }}
                        </div>

                        {{-- FOTO --}}
                        <img
                            src="{{ $candidate->foto
                                ? asset('storage/' . $candidate->foto)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($candidate->nama) . '&background=0f172a&color=fff' }}"
                            class="w-14 h-14 rounded-full object-cover"
                        >

                        {{-- NAMA --}}
                        <div class="flex-1">
                            <p class="font-semibold">
                                {{ $candidate->nama }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ $candidate->jabatan }}
                            </p>
                        </div>

                        {{-- JUMLAH SUARA --}}
                        <div class="text-right">
                            <p class="text-xl font-bold text-blue-400">
                                {{ $row['votes'] }}
                            </p>
                            <p class="text-xs text-gray-400">
                                suara
                            </p>
                        </div>

                    </div>

                    {{-- PROGRESS --}}
                    <div class="mt-3">
                        <div class="w-full bg-slate-800 rounded-full h-2">
                            <div
                                class="bg-blue-500 h-2 rounded-full"
                                style="width: {{ $percent }}%"
                            ></div>
                        </div>

                        <p class="text-right text-xs text-gray-400 mt-1">
                            {{ $percent }}%
                        </p>
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection