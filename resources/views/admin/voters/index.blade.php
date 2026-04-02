@extends('layouts.admin')

@section('title', 'Pemilih')
@section('header', 'Manajemen Pemilih')

@section('content')

@php
/**
* Grouping berdasarkan voting_event_id agar urutan Nama A-Z dari
* Controller tetap terjaga di dalam grup.
*/
$groupedVoters = $voters->groupBy('voting_event_id');
@endphp

<div class="space-y-6">

    {{-- 1. HEADER HALAMAN --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-white">Manajemen Pemilih</h2>
            <p class="text-gray-400 text-sm">
                Klik event untuk membuka daftar pemilih.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.voters.trash') }}"
                class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg text-sm text-gray-300 transition">
                🗑 Trash ({{ $trashCount ?? 0 }})
            </a>

            <a href="{{ route('admin.voters.create') }}"
                class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg font-semibold text-white transition">
                + Tambah Pemilih
            </a>
        </div>
    </div>

    {{-- 2. BOX IMPORT & DOWNLOAD TEMPLATE --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl p-6 mb-6">
        <div class="flex flex-wrap items-end gap-4">

            {{-- Form Import --}}
            <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3 flex-1">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Target Event</label>
                    <select name="voting_event_id" required class="block w-full bg-slate-800 border-white/10 rounded-lg text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs text-gray-400 font-semibold uppercase tracking-wider">File Excel (.xlsx)</label>
                    <input type="file" name="file_excel" required class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg text-sm font-bold text-white transition">
                    🚀 Upload & Import
                </button>
            </form>

            <div class="h-10 w-px bg-white/10 hidden md:block"></div>

            {{-- Tombol Download Template --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.voters.export-template') }}" class="bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 text-white transition">
                    📥 Download Template
                </a>
            </div>

        </div>
        <p class="mt-3 text-[10px] text-gray-500 italic">
            *Gunakan **Template** jika ingin tambah baru (kosongkan ID). Gunakan **Export Data** dari menu event jika ingin update data lama.
        </p>
    </div>

    {{-- 3. DAFTAR ACCORDION EVENT --}}
    @forelse($groupedVoters as $eventId => $eventVoters)

    @php
    // Ambil data pertama untuk mendapatkan informasi Event
    $firstVoter = $eventVoters->first();
    $eventName = optional($firstVoter->event)->judul ?? 'Tanpa Event';

    // Buat ID unik untuk accordion agar tidak bentrok
    $accordionId = 'event-' . ($eventId ?: 'none') . '-' . \Illuminate\Support\Str::slug($eventName);
    @endphp

    <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden mb-4">

        {{-- HEADER ACCORDION --}}
        <div class="w-full px-6 py-4 bg-slate-800 flex justify-between items-center group">

            {{-- Area Klik untuk Toggle --}}
            <button onclick="toggleAccordion('{{ $accordionId }}')" class="flex-1 text-left flex items-center gap-4">
                <div>
                    <h3 class="font-semibold text-blue-400 group-hover:text-blue-300 transition">
                        {{ $eventName }}
                    </h3>
                    <p class="text-xs text-gray-400">
                        Total Pemilih : {{ $eventVoters->count() }}
                    </p>
                </div>
                <span id="icon-{{ $accordionId }}" class="transition text-gray-500">▼</span>
            </button>

            {{-- TOMBOL EXPORT DATA --}}
            @if($firstVoter && $firstVoter->voting_event_id)
            <a href="{{ route('admin.voters.export-data', ['event_id' => $firstVoter->voting_event_id]) }}"
                class="ml-4 bg-white/5 hover:bg-white/10 text-gray-300 px-3 py-1.5 rounded-lg text-xs flex items-center gap-2 border border-white/10 transition whitespace-nowrap">
                📊 Export Data
            </a>
            @endif
        </div>

        {{-- BODY ACCORDION --}}
        <div id="accordion-{{ $accordionId }}" class="hidden">

            <table class="w-full text-sm">
                <thead class="bg-white/5 text-gray-300 border-b border-white/10">
                    <tr>
                        <th class="p-4 text-left">Nama</th>
                        <th class="text-left">Asal Sekolah</th>
                        <th class="text-left">No HP</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    @foreach($eventVoters as $voter)
                    <tr class="hover:bg-white/5 transition">
                        <td class="p-4 font-semibold text-gray-200">
                            <a href="{{ route('admin.voters.show', $voter) }}" class="hover:text-blue-400 transition">
                                {{ $voter->nama }}
                            </a>
                        </td>
                        <td class="text-gray-400">{{ $voter->asal_sekolah ?? '-' }}</td>
                        <td class="text-gray-400">{{ $voter->no_hp ?? '-' }}</td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.voters.show', $voter) }}"
                                    class="px-3 py-1 bg-blue-500/10 text-blue-300 rounded-lg hover:bg-blue-500/20 transition text-xs">
                                    Detail
                                </a>
                                <a href="{{ route('admin.voters.edit', $voter) }}"
                                    class="px-3 py-1 bg-yellow-500/10 text-yellow-300 rounded-lg hover:bg-yellow-500/20 transition text-xs">
                                    Edit
                                </a>
                                <form action="{{ route('admin.voters.destroy', $voter) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500/10 text-red-300 rounded-lg hover:bg-red-500/20 transition text-xs"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    @empty
    <div class="bg-slate-900 border border-white/10 rounded-xl py-16 text-center text-gray-500">
        📭 Belum ada data pemilih. Silakan gunakan fitur Import atau tambah secara manual.
    </div>
    @endforelse

</div>

{{-- ACCORDION SCRIPT --}}
<script>
    function toggleAccordion(id) {
        const body = document.getElementById('accordion-' + id);
        const icon = document.getElementById('icon-' + id);

        if (body && icon) {
            body.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }
</script>

@endsection