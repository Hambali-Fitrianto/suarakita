@extends('layouts.admin')

@section('title','Token Voting')
@section('header','Daftar Token Voting')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold">
                {{ $session->nama_sesi }}
            </h2>

            <p class="text-sm text-gray-400">
                Token dikelompokkan berdasarkan event.
            </p>
        </div>

        {{-- ⭐ BUTTON KEMBALI --}}
        <a href="{{ route('admin.sessions.show', $session) }}"
           class="px-4 py-2 rounded-lg
                  bg-slate-700 hover:bg-slate-600
                  text-sm font-semibold transition">
            ← Kembali
        </a>

    </div>


    {{-- ================= EVENT GROUP ================= --}}
    @forelse($tokens as $eventName => $eventTokens)

    <div x-data="{ open:false }"
         class="bg-slate-900 border border-white/10 rounded-xl overflow-hidden">

        {{-- HEADER EVENT --}}
        <button
            @click="open=!open"
            class="w-full flex justify-between items-center
                   px-6 py-4 bg-slate-800 hover:bg-slate-700 transition">

            <div class="text-left">
                <h3 class="font-semibold text-blue-400">
                    {{ $eventName }}
                </h3>

                <p class="text-xs text-gray-400">
                    Total Token : {{ $eventTokens->count() }}
                </p>
            </div>

            <span x-text="open ? '▲' : '▼'"></span>
        </button>


        {{-- TOKEN TABLE --}}
        <div x-show="open" x-collapse>

            <table class="w-full text-sm">

                <thead class="bg-slate-800/60 text-gray-300">
                    <tr>
                        <th class="p-4 text-left">Pemilih</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">

                @foreach($eventTokens as $token)

                    <tr class="hover:bg-white/5">

                        {{-- PEMILIH --}}
                        <td class="p-4">
                            {{ $token->member->nama ?? '-' }}
                        </td>

                        {{-- TOKEN --}}
                        <td class="font-mono text-blue-400 tracking-widest">
                            {{ $token->token }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($token->is_used)
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">
                                    Used
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">
                                    Available
                                </span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td class="text-right p-4">
                            <button
                                onclick="copyToken('{{ $token->token }}')"
                                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 rounded text-xs">
                                Copy
                            </button>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @empty

        <div class="text-center py-16 text-gray-400">
            Belum ada token voting.
        </div>

    @endforelse

</div>


{{-- ================= COPY TOKEN ================= --}}
<script>
function copyToken(token)
{
    navigator.clipboard.writeText(token);

    Swal.fire({
        icon:'success',
        title:'Token disalin',
        text:token,
        timer:1500,
        showConfirmButton:false
    });
}
</script>

@endsection