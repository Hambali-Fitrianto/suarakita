@extends('layouts.admin')

@section('title', 'Edit Event')
@section('header', 'Edit Event Voting')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- ================= CARD ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

        {{-- HEADER --}}
        <div>
            <h2 class="text-xl font-bold">
                Edit Event Voting
            </h2>

            <p class="text-sm text-gray-400">
                Event hanya berisi informasi dasar.
                Waktu voting diatur pada Session.
            </p>
        </div>


        {{-- ================= GLOBAL ERROR ================= --}}
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30
                        text-red-400 text-sm rounded-lg p-4">
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('admin.events.update', $event) }}"
              class="space-y-5">

            @csrf
            @method('PUT')


            {{-- ================= JUDUL ================= --}}
            <div class="space-y-2">

                <label class="text-sm text-gray-300">
                    Judul Event <span class="text-red-400">*</span>
                </label>

                <input
                    type="text"
                    name="judul"
                    value="{{ old('judul', $event->judul) }}"
                    required
                    placeholder="Contoh: Pemilihan Ketua BEM 2026"
                    class="w-full bg-slate-800 border border-white/10
                           rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('judul')
                    <p class="text-red-400 text-xs">{{ $message }}</p>
                @enderror

            </div>


            {{-- ================= DESKRIPSI ================= --}}
            <div class="space-y-2">

                <label class="text-sm text-gray-300">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    placeholder="Deskripsi event (opsional)"
                    class="w-full bg-slate-800 border border-white/10
                           rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >{{ old('deskripsi', $event->deskripsi) }}</textarea>

                @error('deskripsi')
                    <p class="text-red-400 text-xs">{{ $message }}</p>
                @enderror

            </div>


            {{-- ================= ACTION BUTTON ================= --}}
            <div class="flex items-center justify-between pt-4">

                {{-- BACK --}}
                <a href="{{ route('admin.events.index') }}"
                   class="text-gray-400 hover:text-white transition">
                    ← Kembali
                </a>

                {{-- SUBMIT --}}
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg font-semibold
                           bg-yellow-600 hover:bg-yellow-700 transition">

                    Update Event

                </button>

            </div>

        </form>

    </div>

</div>

@endsection