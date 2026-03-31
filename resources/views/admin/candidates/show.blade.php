@extends('layouts.admin')

@section('title','Detail Kandidat')
@section('header','Detail Kandidat')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- ================= CARD PROFILE ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8">

        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">

            {{-- ================= FOTO ================= --}}
            <div class="flex flex-col items-center gap-4">

                <img
                    src="{{ $candidate->foto
                        ? asset('storage/'.$candidate->foto)
                        : 'https://ui-avatars.com/api/?name='.urlencode($candidate->nama).'&background=0f172a&color=fff' }}"
                    class="w-40 h-40 rounded-full object-cover border border-white/10 shadow-lg">

                {{-- NOMOR URUT --}}
                @if($candidate->nomor_urut)
                    <span class="px-4 py-1 rounded-full
                        bg-blue-500/20 text-blue-300 text-sm font-semibold">
                        No Urut {{ $candidate->nomor_urut }}
                    </span>
                @endif

            </div>


            {{-- ================= DATA ================= --}}
            <div class="flex-1 space-y-6">

                {{-- NAMA --}}
                <div>
                    <h2 class="text-2xl font-bold">
                        {{ $candidate->nama }}

                        @if($candidate->gelar)
                            <span class="text-gray-400 text-lg">
                                , {{ $candidate->gelar }}
                            </span>
                        @endif
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Kandidat Voting
                    </p>
                </div>


                {{-- ================= INFO GRID ================= --}}
                <div class="grid md:grid-cols-2 gap-6 text-sm">

                    {{-- EVENT --}}
                    <div class="space-y-1">
                        <p class="text-gray-400">Event</p>
                        <p class="font-medium">
                            {{ $candidate->event->judul ?? '-' }}
                        </p>
                    </div>

                    {{-- JABATAN --}}
                    <div class="space-y-1">
                        <p class="text-gray-400">Jabatan</p>
                        <p>
                            {{ $candidate->jabatan ?? '-' }}
                        </p>
                    </div>

                    {{-- ASAL --}}
                    <div class="space-y-1">
                        <p class="text-gray-400">Asal Sekolah</p>
                        <p>
                            {{ $candidate->asal_sekolah ?? '-' }}
                        </p>
                    </div>

                    {{-- NO HP --}}
                    <div class="space-y-1">
                        <p class="text-gray-400">No HP</p>
                        <p>
                            {{ $candidate->no_hp ?? '-' }}
                        </p>
                    </div>

                </div>


                {{-- ================= META ================= --}}
                <div class="border-t border-white/10 pt-4 grid md:grid-cols-2 gap-6 text-sm">

                    <div>
                        <p class="text-gray-400">Dibuat</p>
                        <p>{{ $candidate->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-gray-400">Terakhir Update</p>
                        <p>{{ $candidate->updated_at->format('d M Y H:i') }}</p>
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ================= ACTION BUTTON ================= --}}
    <div class="flex justify-between items-center">

        <a href="{{ route('admin.candidates.index') }}"
            class="text-gray-400 hover:text-white transition">
            ← Kembali ke daftar kandidat
        </a>

        <div class="flex gap-3">

            <a href="{{ route('admin.candidates.edit',$candidate->id) }}"
                class="px-5 py-2 rounded-lg
                bg-yellow-500 hover:bg-yellow-600
                text-black font-semibold">

                Edit Kandidat
            </a>

        </div>

    </div>

</div>

@endsection