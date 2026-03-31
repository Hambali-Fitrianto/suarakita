@extends('layouts.admin')

@section('title','Detail Pemilih')
@section('header','Detail Pemilih')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | STATISTIC DATA
    |--------------------------------------------------------------------------
    */
    $totalToken = $voter->tokens()->count();
    $usedToken  = $voter->tokens()->where('is_used', true)->count();
    $unusedToken = $voter->tokens()->where('is_used', false)->count();

    $statusVoting = $usedToken > 0 ? 'sudah' : 'belum';

    $badge = $statusVoting === 'sudah'
        ? 'bg-green-500/20 text-green-400'
        : 'bg-yellow-500/20 text-yellow-400';
@endphp


<div class="max-w-4xl mx-auto space-y-6">

    {{-- ================= HEADER ACTION ================= --}}
    <div class="flex items-center justify-between">

        <a href="{{ route('admin.voters.index') }}"
           class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm transition">
            ← Kembali
        </a>

        <div class="flex gap-2">

            <a href="{{ route('admin.voters.edit',$voter) }}"
               class="px-4 py-2 bg-yellow-500/20 text-yellow-300 rounded-lg hover:bg-yellow-500/30 transition">
                Edit
            </a>

            <form id="delete-{{ $voter->id }}"
                  action="{{ route('admin.voters.destroy',$voter) }}"
                  method="POST">
                @csrf
                @method('DELETE')

                <button type="button"
                    onclick="deleteConfirm('delete-{{ $voter->id }}','Pemilih akan masuk Trash')"
                    class="px-4 py-2 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/30 transition">
                    Hapus
                </button>
            </form>

        </div>

    </div>


    {{-- ================= STATUS CARD ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-6 flex items-center justify-between">

        <div>
            <h2 class="text-lg font-semibold text-white">
                Status Voting
            </h2>

            <p class="text-sm text-gray-400">
                Menunjukkan apakah pemilih sudah menggunakan token voting.
            </p>
        </div>

        <span class="px-4 py-1 rounded-full text-xs font-semibold {{ $badge }}">
            {{ strtoupper($statusVoting) }}
        </span>

    </div>


    {{-- ================= STATISTICS ================= --}}
    <div class="grid grid-cols-3 gap-4 text-center">

        <div class="bg-slate-900 border border-white/10 rounded-xl p-5">
            <p class="text-xs text-gray-400">Total Token</p>
            <p class="text-2xl font-bold">{{ $totalToken }}</p>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-xl p-5">
            <p class="text-xs text-gray-400">Sudah Digunakan</p>
            <p class="text-2xl font-bold text-green-400">{{ $usedToken }}</p>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-xl p-5">
            <p class="text-xs text-gray-400">Belum Digunakan</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $unusedToken }}</p>
        </div>

    </div>


    {{-- ================= DETAIL CARD ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

        <h2 class="text-xl font-bold text-white">
            Informasi Pemilih
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- EVENT --}}
            <div>
                <p class="text-sm text-gray-400">Event Voting</p>
                <p class="font-semibold">
                    {{ optional($voter->event)->judul ?? '-' }}
                </p>
            </div>

            {{-- NAMA --}}
            <div>
                <p class="text-sm text-gray-400">Nama</p>
                <p class="font-semibold text-lg">
                    {{ $voter->nama }}
                </p>
            </div>

            {{-- ASAL SEKOLAH --}}
            <div>
                <p class="text-sm text-gray-400">Asal Sekolah</p>
                <p class="font-semibold">
                    {{ $voter->asal_sekolah ?? '-' }}
                </p>
            </div>

            {{-- NO HP --}}
            <div>
                <p class="text-sm text-gray-400">No HP</p>
                <p class="font-semibold">
                    {{ $voter->no_hp ?? '-' }}
                </p>
            </div>

            {{-- CREATED --}}
            <div>
                <p class="text-sm text-gray-400">Terdaftar Pada</p>
                <p class="font-semibold">
                    {{ $voter->created_at->format('d M Y H:i') }}
                </p>
            </div>

            {{-- UPDATED --}}
            <div>
                <p class="text-sm text-gray-400">Terakhir Update</p>
                <p class="font-semibold">
                    {{ $voter->updated_at->format('d M Y H:i') }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection