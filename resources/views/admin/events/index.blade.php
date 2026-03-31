@extends('layouts.admin')

@section('title', 'Event Voting')
@section('header', 'Event Voting')

@section('content')

<div class="space-y-6">

    {{-- =====================================================
        HEADER
    ===================================================== --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-2xl font-bold">
                Event Voting
            </h2>

            <p class="text-sm text-gray-400">
                Kelola master event voting.
            </p>
        </div>

        <div class="flex gap-3">

            {{-- ================= TRASH ================= --}}
            <a
                href="{{ route('admin.events.trash') }}"
                class="relative px-4 py-2 rounded-lg
                       bg-slate-800 hover:bg-slate-700 transition flex items-center gap-2"
            >
                🗑️ Trash

                @if(($trashCount ?? 0) > 0)
                    <span
                        class="px-2 py-0.5 text-xs rounded-full
                               bg-red-500 text-white font-semibold"
                    >
                        {{ $trashCount }}
                    </span>
                @endif
            </a>

            {{-- ================= CREATE ================= --}}
            <a
                href="{{ route('admin.events.create') }}"
                class="px-5 py-2.5 rounded-lg font-semibold
                       bg-blue-600 hover:bg-blue-700 transition"
            >
                + Buat Event
            </a>

        </div>

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
                    <th class="text-left">Deskripsi</th>
                    <th class="text-left">Dibuat</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>

            {{-- TABLE BODY --}}
            <tbody class="divide-y divide-white/10">

                @forelse ($events as $event)

                    <tr class="hover:bg-white/5 transition">

                        {{-- JUDUL --}}
                        <td class="p-4 font-semibold">
                            {{ $event->judul }}
                        </td>

                        {{-- DESKRIPSI --}}
                        <td class="text-gray-400">
                            {{ Str::limit($event->deskripsi, 60) ?? '-' }}
                        </td>

                        {{-- CREATED --}}
                        <td class="text-gray-400">
                            {{ $event->created_at->format('d M Y H:i') }}
                        </td>

                        {{-- AKSI --}}
                        <td class="p-4 text-right space-x-2">

                            {{-- DETAIL --}}
                            <a
                                href="{{ route('admin.events.show', $event) }}"
                                class="px-3 py-1.5 rounded-lg
                                       bg-blue-500/20 text-blue-300
                                       hover:bg-blue-500/30 transition"
                            >
                                Detail
                            </a>

                            {{-- EDIT --}}
                            <a
                                href="{{ route('admin.events.edit', $event) }}"
                                class="px-3 py-1.5 rounded-lg
                                       bg-yellow-500/20 text-yellow-300
                                       hover:bg-yellow-500/30 transition"
                            >
                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form
                                method="POST"
                                action="{{ route('admin.events.destroy', $event) }}"
                                class="inline form-delete"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="button"
                                    class="btn-delete px-3 py-1.5 rounded-lg
                                           bg-red-500/20 text-red-300
                                           hover:bg-red-500/30 transition"
                                >
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4"
                            class="py-16 text-center text-gray-400">
                            Belum ada event voting.
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