@extends('layouts.admin')

@section('title','Edit Pemilih')
@section('header','Edit Pemilih')

@section('content')

<div class="max-w-xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">

        <a href="{{ route('admin.voters.index') }}"
           class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm transition">
            ← Kembali
        </a>

        <h2 class="text-lg font-semibold text-gray-200">
            Edit Data Pemilih
        </h2>

    </div>


    {{-- ================= ERROR MESSAGE ================= --}}
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ================= CARD ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-8">

        <form method="POST"
              action="{{ route('admin.voters.update', $voter) }}"
              class="space-y-5">

            @csrf
            @method('PUT')


            {{-- ================= EVENT ================= --}}
            <div>
                <label class="text-sm text-gray-400">
                    Event Voting
                </label>

                <select name="voting_event_id" required
                    class="w-full mt-1 bg-slate-800 border border-white/10 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-500/30">

                    <option value="">-- Pilih Event --</option>

                    @foreach($events as $event)
                        <option value="{{ $event->id }}"
                            {{ old('voting_event_id', $voter->voting_event_id) == $event->id ? 'selected' : '' }}>
                            {{ $event->judul }}
                        </option>
                    @endforeach

                </select>
            </div>


            {{-- ================= NAMA ================= --}}
            <div>
                <label class="text-sm text-gray-400">Nama</label>

                <input
                    name="nama"
                    value="{{ old('nama', $voter->nama) }}"
                    required
                    class="w-full mt-1 bg-slate-800 border border-white/10 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-500/30">
            </div>


            {{-- ================= ASAL SEKOLAH ================= --}}
            <div>
                <label class="text-sm text-gray-400">Asal Sekolah</label>

                <input
                    name="asal_sekolah"
                    value="{{ old('asal_sekolah', $voter->asal_sekolah) }}"
                    class="w-full mt-1 bg-slate-800 border border-white/10 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-500/30">
            </div>


            {{-- ================= NO HP ================= --}}
            <div>
                <label class="text-sm text-gray-400">No HP</label>

                <input
                    name="no_hp"
                    value="{{ old('no_hp', $voter->no_hp) }}"
                    class="w-full mt-1 bg-slate-800 border border-white/10 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-500/30">
            </div>


            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('admin.voters.index') }}"
                   class="px-4 py-2 bg-gray-600/40 hover:bg-gray-600/60 rounded-lg transition">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg font-semibold transition">
                    Update Pemilih
                </button>

            </div>

        </form>

    </div>

</div>

@endsection