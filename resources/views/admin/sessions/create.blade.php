@extends('layouts.admin')

@section('title', 'Buat Session')
@section('header', 'Buat Session Voting')

@section('content')

    <div class="max-w-2xl mx-auto">

        {{-- ================= CARD ================= --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

            {{-- ===== HEADER ===== --}}
            <div>
                <h2 class="text-xl font-bold">
                    Tambah Session Voting
                </h2>

                <p class="text-sm text-gray-400">
                    Session digunakan untuk mengatur waktu voting dalam sebuah event.
                </p>
            </div>


            {{-- ===== GLOBAL ERROR ===== --}}
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg p-4 text-sm">
                    <ul class="list-disc ml-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- ================= FORM ================= --}}
            <form method="POST"
                  action="{{ route('admin.sessions.store') }}"
                  class="space-y-5">

                @csrf


                {{-- ===== EVENT ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Event Voting <span class="text-red-400">*</span>
                    </label>

                    <select name="voting_event_id"
                            required
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">-- Pilih Event --</option>

                        @foreach ($events as $event)
                            <option value="{{ $event->id }}"
                                {{ old('voting_event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->judul }}
                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- ===== NAMA SESI ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Nama Session <span class="text-red-400">*</span>
                    </label>

                    <input  type="text"
                            name="nama_sesi"
                            value="{{ old('nama_sesi') }}"
                            required
                            placeholder="Contoh: Putaran Voting 1"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>


                {{-- ===== URUTAN ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Urutan Session
                    </label>

                    <input  type="number"
                            name="urutan"
                            value="{{ old('urutan', 1) }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== MULAI ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Mulai Voting
                    </label>

                    <input  type="datetime-local"
                            name="mulai_at"
                            value="{{ old('mulai_at') }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== SELESAI ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Selesai Voting
                    </label>

                    <input  type="datetime-local"
                            name="selesai_at"
                            value="{{ old('selesai_at') }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== STATUS ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Status Awal
                    </label>

                    <select name="status"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                        <option value="draft">Draft</option>
                        <option value="jeda">Jeda</option>

                    </select>

                    <p class="text-xs text-gray-500">
                        Status aktif akan otomatis mengikuti waktu voting.
                    </p>

                </div>


                {{-- ===== ACTION BUTTON ===== --}}
                <div class="flex items-center justify-between pt-4">

                    <a  href="{{ route('admin.sessions.index') }}"
                        class="text-gray-400 hover:text-white transition">
                        ← Kembali
                    </a>

                    <button type="submit"
                            class="px-6 py-2 rounded-lg font-semibold
                                   bg-blue-600 hover:bg-blue-700 transition">

                        Simpan Session

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection