@extends('layouts.admin')

@section('title','Trash Event')
@section('header','Trash Event')

@section('content')

<div class="space-y-6">

    {{-- =====================================================
        HEADER
    ===================================================== --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">
                Event Trash
            </h2>

            <p class="text-sm text-gray-400">
                Event yang dihapus sementara (Soft Delete).
            </p>
        </div>

        <a href="{{ route('admin.events.index') }}"
           class="text-gray-400 hover:text-white transition">
            ← Kembali
        </a>

    </div>


    {{-- =====================================================
        TABLE
    ===================================================== --}}
    <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

        <table class="w-full text-sm">

            {{-- TABLE HEAD --}}
            <thead class="bg-white/5 text-gray-300">
                <tr>
                    <th class="p-4 text-left">Judul Event</th>
                    <th class="text-left">Dihapus Pada</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>

            {{-- TABLE BODY --}}
            <tbody class="divide-y divide-white/10">

                @forelse($events as $event)

                <tr class="hover:bg-white/5 transition">

                    {{-- JUDUL --}}
                    <td class="p-4 font-semibold">
                        {{ $event->judul }}
                    </td>

                    {{-- DELETED TIME --}}
                    <td class="text-gray-400">
                        {{ optional($event->deleted_at)->format('d M Y H:i') }}
                    </td>

                    {{-- ACTION --}}
                    <td class="p-4 text-right space-x-2">

                        {{-- ================= RESTORE ================= --}}
                        <form method="POST"
                              action="{{ route('admin.events.restore',$event->id) }}"
                              class="inline">
                            @csrf
                            @method('PUT')

                            <button
                                type="submit"
                                class="px-3 py-1.5 rounded-lg
                                       bg-green-500/20 text-green-300
                                       hover:bg-green-500/30 transition">
                                ♻ Restore
                            </button>
                        </form>


                        {{-- ================= FORCE DELETE ================= --}}
                        <form method="POST"
                              action="{{ route('admin.events.forceDelete',$event->id) }}"
                              class="inline form-delete">
                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                class="btn-delete px-3 py-1.5 rounded-lg
                                       bg-red-500/20 text-red-300
                                       hover:bg-red-500/30 transition">
                                🗑️ Hapus Permanen
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="3"
                        class="text-center py-16 text-gray-400">
                        Trash kosong.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
        PAGINATION
    ===================================================== --}}
    <div>
        {{ $events->links('pagination::tailwind') }}
    </div>

</div>

@endsection