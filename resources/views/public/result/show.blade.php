@extends('layouts.guest')

@section('title', 'Detail Hasil - ' . $session->nama_sesi)

@section('content')

<div class="max-w-6xl mx-auto py-16 px-6">

    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold">
            {{ $session->nama_sesi }}
        </h1>
        <p class="text-gray-400">
            Hasil Voting Realtime
        </p>
        <a href="{{ route('public.result.index') }}"
            class="inline-block mt-4 text-gray-400 hover:text-white transition-colors">
            ← Kembali ke Daftar Session
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        @foreach($results as $candidate)
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center hover:border-white/20 transition-all">
            @if($candidate->foto)
            <img src="{{ asset('storage/'.$candidate->foto) }}"
                class="w-24 h-24 mx-auto rounded-full object-cover mb-4 ring-2 ring-white/10">
            @else
            <div class="w-24 h-24 mx-auto rounded-full bg-slate-800 flex items-center justify-center text-xl mb-4 font-bold text-white">
                {{ strtoupper(substr($candidate->nama, 0, 2)) }}
            </div>
            @endif

            <h3 class="font-semibold text-lg text-white">
                {{ $candidate->nama }}
            </h3>
            <p class="text-sm text-gray-400">
                {{ $candidate->jabatan }}
            </p>

            <div class="mt-6 pt-4 border-t border-white/5">
                <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">
                    Total Suara
                </div>
                <div class="text-4xl font-black text-green-400">
                    {{ $candidate->total_suara }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- --- BAGIAN SCRIPT SAKTI --- --}}
{{-- Panggil CDN di sini untuk memastikan library tersedia --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Gunakan window.onload agar yakin semua resource (termasuk library Swal) sudah siap
    window.onload = function() {

        // Ambil data session Laravel
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";

        console.log("Success Message:", successMessage); // Cek di F12 Console browser

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                background: '#0f172a',
                color: '#ffffff',
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'Mantap!',
                allowOutsideClick: false
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Waduh!',
                text: errorMessage,
                background: '#0f172a',
                color: '#ffffff',
                confirmButtonColor: '#ef4444'
            });
        }
    };
</script>

@endsection