@extends('layouts.admin')

@section('title', 'Pemilih')
@section('header', 'Manajemen Pemilih')

@section('content')

@php
    $groupedVoters = $voters->getCollection()->groupBy(function ($voter) {
        return optional($voter->event)->judul ?? 'Tanpa Event';
    });
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">Manajemen Pemilih</h2>
            <p class="text-gray-400 text-sm">
                Klik event untuk membuka daftar pemilih.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.voters.trash') }}"
               class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg text-sm">
                🗑 Trash ({{ $trashCount ?? 0 }})
            </a>

            <a href="{{ route('admin.voters.create') }}"
               class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg font-semibold">
                + Tambah Pemilih
            </a>

        </div>

    </div>


    {{-- ACCORDION EVENT --}}
    @forelse($groupedVoters as $eventName => $eventVoters)

        @php
            $accordionId = \Illuminate\Support\Str::slug($eventName);
        @endphp

        <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

            {{-- HEADER --}}
            <button
                onclick="toggleAccordion('{{ $accordionId }}')"
                class="w-full px-6 py-4 bg-slate-800 hover:bg-slate-700 flex justify-between items-center">

                <div class="text-left">
                    <h3 class="font-semibold text-blue-400">
                        {{ $eventName }}
                    </h3>

                    <p class="text-xs text-gray-400">
                        Total Pemilih : {{ $eventVoters->count() }}
                    </p>
                </div>

                <span id="icon-{{ $accordionId }}" class="transition">
                    ▼
                </span>

            </button>


            {{-- BODY DEFAULT CLOSED --}}
            <div id="accordion-{{ $accordionId }}" class="hidden">

                <table class="w-full text-sm">

                    <thead class="bg-white/5 text-gray-300">
                        <tr>
                            <th class="p-4 text-left">Nama</th>
                            <th>Asal Sekolah</th>
                            <th>No HP</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">

                        @foreach($eventVoters as $voter)

                        <tr class="hover:bg-white/5">

                            <td class="p-4 font-semibold">
                                <a href="{{ route('admin.voters.show',$voter) }}"
                                   class="hover:text-blue-400">
                                    {{ $voter->nama }}
                                </a>
                            </td>

                            <td>{{ $voter->asal_sekolah ?? '-' }}</td>
                            <td>{{ $voter->no_hp ?? '-' }}</td>

                            <td class="p-4 text-right">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.voters.show',$voter) }}"
                                       class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-lg">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.voters.edit',$voter) }}"
                                       class="px-3 py-1 bg-yellow-500/20 text-yellow-300 rounded-lg">
                                        Edit
                                    </a>

                                    {{-- ✅ GLOBAL SWEET ALERT DELETE --}}
                                    <form action="{{ route('admin.voters.destroy',$voter) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            class="btn-delete px-3 py-1 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/30">
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
        <div class="text-center py-16 text-gray-400">
            Belum ada pemilih.
        </div>
    @endforelse

</div>


{{-- ACCORDION SCRIPT --}}
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