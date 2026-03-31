@extends('layouts.app')

@section('title', 'Suara Berhasil Direkam — Suarakita')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-10 text-center max-w-lg w-full">

        <div class="text-green-600 text-6xl mb-4">
            ✅
        </div>

        <h2 class="text-2xl font-bold mb-3">
            Terima Kasih!
        </h2>

        <p class="text-gray-600 mb-6">
            Suara Anda telah berhasil direkam.
            Anda tidak perlu melakukan pemilihan kembali.
        </p>

        <div class="bg-slate-100 rounded-lg p-4 text-sm text-gray-500 mb-6">
            Sistem Suarakita menjamin kerahasiaan pilihan Anda.
        </div>

        <a href="/"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            Kembali ke Beranda
        </a>

    </div>

</div>

@endsection