@extends('layouts.admin')

@section('title', 'Session Voting')
@section('header', 'Session Voting')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold">
                Daftar Session Voting
            </h2>

            <p class="text-sm text-gray-400">
                Kelola seluruh sesi voting pada setiap event.
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a href="{{ route('admin.sessions.trash') }}"
               class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition text-sm">
                🗑 Trash ({{ $trashCount }})
            </a>

            <a href="{{ route('admin.sessions.create') }}"
               class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-sm font-semibold">
                + Buat Session
            </a>

        </div>

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
                    <th class="px-6 py-3 text-left">Waktu Voting</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>


            {{-- ===== TABLE BODY --}}
            <tbody class="divide-y divide-white/5">

                @forelse ($sessions as $session)

                    @php
                        $status = $session->computed_status;

                        $badge = match ($status) {
                            'aktif'   => 'bg-green-500/20 text-green-400',
                            'jeda'    => 'bg-yellow-500/20 text-yellow-400',
                            'selesai' => 'bg-gray-500/20 text-gray-300',
                            default   => 'bg-blue-500/20 text-blue-400',
                        };
                    @endphp

                    <tr class="hover:bg-white/5 transition">

                        {{-- EVENT --}}
                        <td class="px-6 py-4">
                            <span class="font-medium">
                                {{ optional($session->event)->judul ?? '-' }}
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


                        {{-- WAKTU --}}
                        <td class="px-6 py-4 text-xs text-gray-300 space-y-1">

                            @if ($session->mulai_at)
                                <div>
                                    Mulai :
                                    {{ $session->mulai_at->format('d M Y H:i') }}
                                </div>
                            @endif

                            @if ($session->selesai_at)
                                <div>
                                    Selesai :
                                    {{ $session->selesai_at->format('d M Y H:i') }}
                                </div>
                            @endif

                            @if (!$session->mulai_at && !$session->selesai_at)
                                <span class="text-gray-500">
                                    Belum dijadwalkan
                                </span>
                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ strtoupper($status) }}
                            </span>
                        </td>


                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">

                                {{-- DETAIL --}}
                                <a href="{{ route('admin.sessions.show', $session) }}"
                                class="px-3 py-1.5
                                bg-blue-500/20 text-blue-300
                                rounded-lg text-xs font-medium
                                hover:bg-blue-500/30 transition">
                                    Detail
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.sessions.edit', $session) }}"
                                class="px-3 py-1.5
                                bg-yellow-500/20 text-yellow-300
                                hover:bg-yellow-500/30
                                rounded-lg text-xs font-medium transition">
                                    Edit
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.sessions.destroy', $session) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Pindahkan session ke trash?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="px-3 py-1.5
                                        bg-red-500/20 text-red-300
                                        hover:bg-red-500/30
                                        rounded-lg text-xs font-medium transition">
                                        Hapus
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-14 text-gray-400">
                            <div class="space-y-2">
                                <div class="text-lg">📭</div>
                                <div>Belum ada session voting.</div>
                            </div>
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