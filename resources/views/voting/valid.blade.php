@extends('layouts.app')

@section('title', 'Token Valid — Suarakita')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-lg text-center">

        <div class="text-green-600 text-5xl mb-4">
            ✅
        </div>

        <h2 class="text-2xl font-bold mb-2">
            Token Berhasil Diverifikasi
        </h2>

        <p class="text-gray-500 mb-6">
            Pastikan data berikut adalah Anda sebelum melanjutkan.
        </p>

        <div class="bg-slate-100 rounded-lg p-6 mb-6">

            <p class="text-lg font-semibold">
                {{ $member->nama }}
                @if($member->gelar)
                    , {{ $member->gelar }}
                @endif
            </p>

            @if($member->jabatan)
                <p class="text-gray-600 mt-2">
                    {{ $member->jabatan }}
                </p>
            @endif

        </div>

        <p class="text-sm text-gray-500 mb-6">
            Silakan lanjutkan ke proses pemilihan.
        </p>

        <a href="{{ route('vote.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            Mulai Memilih
        </a>

    </div>

</div>

@endsection