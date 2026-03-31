@extends('layouts.admin')

@section('title', 'Edit Session')
@section('header', 'Edit Session Voting')

@section('content')

    <div class="max-w-2xl mx-auto">

        {{-- ================= CARD ================= --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

            {{-- ===== HEADER ===== --}}
            <div>
                <h2 class="text-xl font-bold">
                    Edit Session Voting
                </h2>

                <p class="text-sm text-gray-400">
                    Perbarui informasi session voting.
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
                  action="{{ route('admin.sessions.update', $session) }}"
                  class="space-y-5">

                @csrf
                @method('PUT')


                {{-- ===== EVENT ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Event Voting
                    </label>

                    <select name="voting_event_id"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">

                        @foreach ($events as $event)
                            <option value="{{ $event->id }}"
                                @selected(old('voting_event_id', $session->voting_event_id) == $event->id)>
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
                            required
                            value="{{ old('nama_sesi', $session->nama_sesi) }}"
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
                            value="{{ old('urutan', $session->urutan) }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== MULAI ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Mulai Voting
                    </label>

                    <input  type="datetime-local"
                            name="mulai_at"
                            value="{{ old(
                                'mulai_at',
                                optional($session->mulai_at)->format('Y-m-d\TH:i')
                            ) }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== SELESAI ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Selesai Voting
                    </label>

                    <input  type="datetime-local"
                            name="selesai_at"
                            value="{{ old(
                                'selesai_at',
                                optional($session->selesai_at)->format('Y-m-d\TH:i')
                            ) }}"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                </div>


                {{-- ===== STATUS ===== --}}
                <div class="space-y-2">

                    <label class="text-sm text-gray-300">
                        Status Manual
                    </label>

                    <select name="status"
                            class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-2">

                        <option value="draft"
                            @selected(old('status', $session->status) === 'draft')>
                            Draft
                        </option>

                        <option value="jeda"
                            @selected(old('status', $session->status) === 'jeda')>
                            Jeda (Pause Voting)
                        </option>

                    </select>

                    <p class="text-xs text-gray-500">
                        Status aktif & selesai ditentukan otomatis oleh waktu voting.
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

                        Update Session

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection