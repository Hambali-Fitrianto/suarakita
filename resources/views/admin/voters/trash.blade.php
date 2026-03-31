@extends('layouts.admin')

@section('title','Trash Pemilih')
@section('header','Trash Pemilih')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold">Trash Pemilih</h2>
            <p class="text-sm text-gray-400">
                Data pemilih yang telah dihapus sementara.
            </p>
        </div>

        <a href="{{ route('admin.voters.index') }}"
           class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm transition">
            ← Kembali
        </a>

    </div>


    {{-- ================= SUCCESS MESSAGE ================= --}}
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- ================= TABLE ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-white/5 text-gray-300">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="text-left">Event</th>
                    <th class="text-left">Dihapus Pada</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">

                @forelse($voters as $voter)

                <tr class="hover:bg-white/5 transition">

                    {{-- NAMA --}}
                    <td class="p-4 font-semibold">
                        {{ $voter->nama }}
                    </td>

                    {{-- EVENT --}}
                    <td class="text-gray-400">
                        {{ optional($voter->event)->judul ?? '-' }}
                    </td>

                    {{-- DELETED AT --}}
                    <td class="text-gray-400">
                        {{ $voter->deleted_at->format('d M Y H:i') }}
                    </td>

                    {{-- ACTION --}}
                    <td class="p-4 text-right">

                        <div class="flex justify-end gap-2">

                            {{-- RESTORE --}}
                            <form method="POST"
                                  action="{{ route('admin.voters.restore',$voter->id) }}">
                                @csrf
                                @method('PUT')

                                <button
                                    onclick="return confirm('Restore pemilih ini?')"
                                    class="px-3 py-1.5 bg-green-500/20 text-green-300 rounded-lg hover:bg-green-500/30 transition">
                                    Restore
                                </button>
                            </form>

                            {{-- FORCE DELETE --}}
                            <form method="POST"
                                  action="{{ route('admin.voters.forceDelete',$voter->id) }}">
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')"
                                    class="px-3 py-1.5 bg-red-600/20 text-red-300 rounded-lg hover:bg-red-600/30 transition">
                                    Hapus Permanen
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="py-16 text-center text-gray-400">
                        Trash kosong.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ================= PAGINATION ================= --}}
    <div>
        {{ $voters->links('pagination::tailwind') }}
    </div>

</div>

@endsection