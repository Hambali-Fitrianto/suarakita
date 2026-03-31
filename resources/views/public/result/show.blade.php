@extends('layouts.guest')

@section('title','Detail Hasil')

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
           class="inline-block mt-4 text-gray-400 hover:text-white">
            ← Kembali ke Daftar Session
        </a>

    </div>


    <div class="grid md:grid-cols-3 gap-8">

        @foreach($results as $candidate)

            <div class="bg-white/5 border border-white/10
                        rounded-xl p-6 text-center">

                @if($candidate->foto)
                    <img src="{{ asset('storage/'.$candidate->foto) }}"
                         class="w-24 h-24 mx-auto rounded-full object-cover mb-4">
                @else
                    <div class="w-24 h-24 mx-auto rounded-full
                        bg-slate-800 flex items-center justify-center text-xl mb-4">
                        {{ strtoupper(substr($candidate->nama,0,2)) }}
                    </div>
                @endif

                <h3 class="font-semibold text-lg">
                    {{ $candidate->nama }}
                </h3>

                <p class="text-sm text-gray-400">
                    {{ $candidate->jabatan }}
                </p>

                <div class="mt-4 text-gray-400 text-sm">
                    Total Suara
                </div>

                <div class="text-3xl font-bold text-green-400">
                    {{ $candidate->total_suara }}
                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection