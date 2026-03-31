@extends('layouts.admin')

@section('title','Kandidat')
@section('header','Manajemen Kandidat')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | GROUP KANDIDAT BERDASARKAN EVENT
    |--------------------------------------------------------------------------
    */
    $groupedCandidates = $candidates->getCollection()->groupBy(function ($candidate) {
        return optional($candidate->event)->judul ?? 'Tanpa Event';
    });
@endphp


<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">
                Manajemen Kandidat
            </h2>

            <p class="text-gray-400 text-sm">
                Klik event untuk melihat kandidat.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('admin.candidates.trash') }}"
               class="bg-red-500/20 text-red-300 px-4 py-2.5 rounded-lg
               hover:bg-red-500/30 transition">
                🗑 Trash
            </a>

            <a href="{{ route('admin.candidates.create') }}"
               class="bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg
               font-semibold shadow-lg shadow-blue-600/20 transition">
                + Tambah Kandidat
            </a>

        </div>

    </div>


    {{-- ================= ACCORDION EVENT ================= --}}
    @forelse($groupedCandidates as $eventName => $eventCandidates)

        @php
            $accordionId = \Illuminate\Support\Str::slug($eventName);
        @endphp

        <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

            {{-- HEADER EVENT --}}
            <button
                onclick="toggleAccordion('{{ $accordionId }}')"
                class="w-full px-6 py-4 bg-slate-800 hover:bg-slate-700
                flex justify-between items-center">

                <div class="text-left">
                    <h3 class="font-semibold text-blue-400">
                        {{ $eventName }}
                    </h3>

                    <p class="text-xs text-gray-400">
                        Total Kandidat : {{ $eventCandidates->count() }}
                    </p>
                </div>

                <span id="icon-{{ $accordionId }}" class="transition">
                    ▼
                </span>

            </button>


            {{-- BODY (DEFAULT CLOSED) --}}
            <div id="accordion-{{ $accordionId }}" class="hidden">

                <table class="w-full text-sm">

                    <thead class="bg-white/5 text-gray-300">
                        <tr>
                            <th class="p-4 w-20 text-center">No</th>
                            <th class="p-4 w-24">Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Asal</th>
                            <th class="p-4 text-right w-64">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">

                    @foreach($eventCandidates as $candidate)

                        <tr class="hover:bg-white/5">

                            {{-- NOMOR --}}
                            <td class="p-4 text-center font-bold text-lg">
                                {{ $candidate->nomor_urut ?? '-' }}
                            </td>

                            {{-- FOTO --}}
                            <td class="p-4">
                                <img
                                    src="{{ $candidate->foto
                                        ? asset('storage/'.$candidate->foto)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($candidate->nama).'&background=0f172a&color=fff' }}"
                                    class="w-12 h-12 rounded-full object-cover border border-white/10">
                            </td>

                            {{-- NAMA --}}
                            <td class="font-semibold">
                                {{ $candidate->nama }}

                                @if($candidate->gelar)
                                    <span class="text-gray-400">
                                        , {{ $candidate->gelar }}
                                    </span>
                                @endif
                            </td>

                            {{-- JABATAN --}}
                            <td class="text-gray-400">
                                {{ $candidate->jabatan ?? '-' }}
                            </td>

                            {{-- ASAL --}}
                            <td class="text-gray-400">
                                {{ $candidate->asal_sekolah ?? '-' }}
                            </td>

                            {{-- ACTION --}}
                            <td class="p-4 text-right">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.candidates.show',$candidate) }}"
                                       class="px-3 py-1.5 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/30">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.candidates.edit',$candidate) }}"
                                       class="px-3 py-1.5 bg-yellow-500/20 text-yellow-300 rounded-lg hover:bg-yellow-500/30">
                                        Edit
                                    </a>

                                    {{-- GLOBAL DELETE --}}
                                    <form action="{{ route('admin.candidates.destroy',$candidate) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            class="btn-delete px-3 py-1.5
                                            bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/30">
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

        <div class="bg-slate-900 border border-white/10 rounded-xl py-16 text-center text-gray-400">
            <div class="text-5xl opacity-40 mb-3">🧑‍💼</div>
            <p class="text-lg">Belum ada kandidat</p>
            <p class="text-sm opacity-70">
                Tambahkan kandidat pertama untuk memulai voting.
            </p>
        </div>

    @endforelse


    {{-- ================= PAGINATION ================= --}}
    @if($candidates->hasPages())
        <div class="pt-2">
            {{ $candidates->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    @endif

</div>


{{-- ================= ACCORDION SCRIPT ================= --}}
<script>
function toggleAccordion(id)
{
    const body = document.getElementById('accordion-'+id);
    const icon = document.getElementById('icon-'+id);

    body.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>

@endsection