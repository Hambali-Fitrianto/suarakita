@extends('layouts.admin')

@section('title', 'Trash Session')
@section('header', 'Session Trash')

@section('content')

    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-bold">
                    Session Terhapus
                </h2>

                <p class="text-sm text-gray-400">
                    Daftar session yang berada di dalam trash.
                </p>
            </div>

            <a  href="{{ route('admin.sessions.index') }}"
                class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition text-sm">
                ← Kembali
            </a>

        </div>


        {{-- ================= SUCCESS MESSAGE ================= --}}
        @if (session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif


        {{-- ================= TABLE ================= --}}
        <div class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

            <table class="w-full text-sm">

                {{-- ===== TABLE HEAD ===== --}}
                <thead class="bg-slate-800 text-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left">Event</th>
                        <th class="px-6 py-3 text-left">Session</th>
                        <th class="px-6 py-3 text-left">Dihapus Pada</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>


                {{-- ===== TABLE BODY ===== --}}
                <tbody class="divide-y divide-white/5">

                    @forelse ($sessions as $session)

                        <tr class="hover:bg-white/5 transition">

                            {{-- EVENT --}}
                            <td class="px-6 py-4">
                                <span class="font-medium">
                                    {{ $session->event->judul ?? '-' }}
                                </span>
                            </td>


                            {{-- SESSION --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold">
                                    {{ $session->nama_sesi }}
                                </div>

                                <span class="text-xs text-gray-400">
                                    Urutan : {{ $session->urutan }}
                                </span>
                            </td>


                            {{-- DELETED AT --}}
                            <td class="px-6 py-4 text-gray-300 text-xs">
                                {{ $session->deleted_at->format('d M Y H:i') }}
                            </td>


                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-right space-x-2">

                                {{-- RESTORE --}}
                                <form method="POST"
                                      action="{{ route('admin.sessions.restore', $session->id) }}"
                                      class="inline">
                                    @csrf

                                    <button
                                        class="px-3 py-1 rounded bg-green-600 hover:bg-green-700 text-xs font-semibold">
                                        Restore
                                    </button>
                                </form>


                                {{-- FORCE DELETE --}}
                                <form method="POST"
                                      action="{{ route('admin.sessions.forceDelete', $session->id) }}"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Hapus permanen session ini?')"
                                        class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-xs font-semibold">
                                        Delete Permanen
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center py-12 text-gray-400">
                                Tidak ada session di trash.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================= PAGINATION ================= --}}
        <div>
            {{ $sessions->links() }}
        </div>

    </div>

@endsection