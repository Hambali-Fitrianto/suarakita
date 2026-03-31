@extends('layouts.admin')

@section('title','Trash Kandidat')
@section('header','Trash Kandidat')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">
                Trash Kandidat
            </h2>

            <p class="text-gray-400 text-sm">
                Kandidat yang dihapus sementara.
            </p>
        </div>

        <a href="{{ route('admin.candidates.index') }}"
            class="px-4 py-2 rounded-lg
            bg-blue-600 hover:bg-blue-700
            font-semibold transition">
            ← Kembali
        </a>

    </div>


    {{-- ================= SUCCESS ALERT ================= --}}
    @if(session('success'))
    <div
        x-data="{ show:true }"
        x-init="setTimeout(() => show=false,3000)"
        x-show="show"
        x-transition.opacity
        class="bg-green-500/10 border border-green-500/30
        text-green-300 px-4 py-3 rounded-xl flex justify-between">

        <span>✅ {{ session('success') }}</span>
        <button @click="show=false">✕</button>

    </div>
    @endif


    {{-- ================= TABLE ================= --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

        <table class="w-full text-sm align-middle">

            <thead class="bg-white/5 text-gray-300">
                <tr>
                    <th class="p-4 w-24">Foto</th>
                    <th>Nama</th>
                    <th>Event</th>
                    <th>Dihapus</th>
                    <th class="p-4 text-right w-72">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">

            @forelse($candidates as $candidate)

                <tr class="hover:bg-white/5">

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
                    </td>

                    {{-- EVENT --}}
                    <td class="text-gray-400">
                        {{ $candidate->event->judul ?? '-' }}
                    </td>

                    {{-- DELETED AT --}}
                    <td class="text-gray-400">
                        {{ optional($candidate->deleted_at)->format('d M Y H:i') }}
                    </td>

                    {{-- ACTION --}}
                    <td class="p-4">

                        <div class="flex justify-end gap-2">

                            {{-- RESTORE --}}
                            <form method="POST"
                                  action="{{ route('admin.candidates.restore',$candidate->id) }}">
                                @csrf
                                @method('PUT')

                                <button
                                    class="px-3 py-1.5 text-sm
                                    bg-green-500/20 text-green-300
                                    rounded-lg hover:bg-green-500/30">

                                    Restore
                                </button>
                            </form>


                            {{-- FORCE DELETE --}}
                            <button
                                onclick="forceDelete({{ $candidate->id }})"
                                class="px-3 py-1.5 text-sm
                                bg-red-500/20 text-red-300
                                rounded-lg hover:bg-red-500/30">

                                Hapus Permanen
                            </button>

                            <form id="force-delete-{{ $candidate->id }}"
                                  method="POST"
                                  action="{{ route('admin.candidates.forceDelete',$candidate->id) }}"
                                  hidden>
                                @csrf
                                @method('DELETE')
                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="py-16 text-center text-gray-400">

                        <div class="flex flex-col items-center gap-3">

                            <div class="text-5xl opacity-40">🗑</div>

                            <p class="text-lg">
                                Trash kosong
                            </p>

                            <p class="text-sm opacity-70">
                                Tidak ada kandidat yang dihapus.
                            </p>

                        </div>

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- ================= PAGINATION ================= --}}
    @if($candidates->hasPages())
        <div>
            {{ $candidates->links('pagination::tailwind') }}
        </div>
    @endif

</div>



{{-- ================= SWEET ALERT ================= --}}
<script>

function forceDelete(id)
{
    Swal.fire({
        title: 'Hapus Permanen?',
        text: 'Data tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if(result.isConfirmed){
            document.getElementById('force-delete-'+id).submit();
        }

    });
}

</script>

@endsection