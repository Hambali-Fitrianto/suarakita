@extends('layouts.admin')

@section('title', 'Detail Session')
@section('header', 'Detail Session Voting')

@section('content')

@php
$totalToken = method_exists($session,'totalToken')
    ? $session->totalToken()
    : $session->tokens()->count();

$usedToken = method_exists($session,'tokenTerpakai')
    ? $session->tokenTerpakai()
    : $session->tokens()->where('is_used', true)->count();

$availableToken = method_exists($session,'tokenTersisa')
    ? $session->tokenTersisa()
    : $session->tokens()->where('is_used', false)->count();

$status = $session->computed_status ?? $session->status ?? 'draft';

$badge = match ($status) {
    'aktif'   => 'bg-green-500/20 text-green-400',
    'jeda'    => 'bg-yellow-500/20 text-yellow-400',
    'selesai' => 'bg-gray-500/20 text-gray-300',
    default   => 'bg-blue-500/20 text-blue-400',
};
@endphp


<div class="max-w-3xl mx-auto space-y-6">

<div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start">

        <div>
            <h2 class="text-xl font-bold">
                {{ $session->nama_sesi }}
            </h2>

            <p class="text-sm text-gray-400">
                Informasi lengkap session voting.
            </p>
        </div>

        <span class="px-4 py-1 rounded-full text-xs font-semibold {{ $badge }}">
            {{ strtoupper($status) }}
        </span>

    </div>


    {{-- STATISTIC --}}
    <div class="grid grid-cols-3 gap-4 text-center">

        <div class="bg-slate-800/60 rounded-lg p-4">
            <p class="text-xs text-gray-400">Total Token</p>
            <p class="text-xl font-bold">{{ $totalToken }}</p>
        </div>

        <div class="bg-slate-800/60 rounded-lg p-4">
            <p class="text-xs text-gray-400">Sudah Voting</p>
            <p class="text-xl font-bold text-green-400">{{ $usedToken }}</p>
        </div>

        <div class="bg-slate-800/60 rounded-lg p-4">
            <p class="text-xs text-gray-400">Belum Voting</p>
            <p class="text-xl font-bold text-yellow-400">{{ $availableToken }}</p>
        </div>

    </div>


    {{-- DETAIL --}}
    <div class="grid grid-cols-1 gap-4 text-sm">

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Event</span>
            <span class="font-medium">
                {{ optional($session->event)->judul ?? '-' }}
            </span>
        </div>

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Urutan</span>
            <span>{{ $session->urutan }}</span>
        </div>

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Mulai Voting</span>
            <span>{{ $session->mulai_at?->format('d M Y H:i') ?? '-' }}</span>
        </div>

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Selesai Voting</span>
            <span>{{ $session->selesai_at?->format('d M Y H:i') ?? '-' }}</span>
        </div>

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Jumlah Perpanjangan</span>
            <span>{{ $session->jumlah_perpanjangan }}</span>
        </div>

        <div class="flex justify-between border-b border-white/5 pb-3">
            <span class="text-gray-400">Dibuat</span>
            <span>{{ $session->created_at?->format('d M Y H:i') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">Terakhir Update</span>
            <span>{{ $session->updated_at?->format('d M Y H:i') }}</span>
        </div>

    </div>


    {{-- TOKEN ACTION --}}
    <div class="border-t border-white/10 pt-6 space-y-4">

        <h3 class="text-sm font-semibold text-gray-300">
            Manajemen Token Voting
        </h3>

        <div class="flex flex-wrap gap-3">

            <form id="generate-token-form"
                  method="POST"
                  action="{{ route('admin.sessions.tokens.generate',$session) }}">
                @csrf

                <button type="button"
                    onclick="confirmGenerateToken()"
                    class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold">
                    ⚡ Generate Token
                </button>
            </form>

            <a href="{{ route('admin.sessions.tokens.index',$session) }}"
            class="px-5 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">
                🔑 Lihat Token
            </a>

        </div>

    </div>


    {{-- ACTION --}}
    <div class="flex items-center justify-between pt-4">

        <a href="{{ route('admin.sessions.index') }}"
           class="text-gray-400 hover:text-white transition">
            ← Kembali
        </a>

        <div class="flex gap-2">

            <a href="{{ route('admin.sessions.edit', $session) }}"
               class="px-5 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-black text-sm font-semibold">
                Edit
            </a>

            <form method="POST"
                  action="{{ route('admin.sessions.destroy', $session) }}">
                @csrf
                @method('DELETE')

                <button type="button"
                    onclick="confirmDeleteSession(this)"
                    class="px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-sm font-semibold">
                    Hapus
                </button>
            </form>

        </div>

    </div>

</div>
</div>


<script>

function confirmGenerateToken()
{
    Swal.fire({
        title: 'Generate Token?',
        text: 'Token akan dibuat untuk semua pemilih.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Generate',
        cancelButtonText: 'Batal'
    }).then((result)=>{
        if(result.isConfirmed){

            Swal.fire({
                title:'Memproses...',
                allowOutsideClick:false,
                didOpen:()=>Swal.showLoading()
            });

            document.getElementById('generate-token-form').submit();
        }
    });
}

function confirmDeleteSession(btn)
{
    const form = btn.closest('form');

    Swal.fire({
        title:'Pindahkan ke Trash?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Ya, Hapus'
    }).then((r)=>{
        if(r.isConfirmed){
            form.submit();
        }
    });
}

</script>

@endsection