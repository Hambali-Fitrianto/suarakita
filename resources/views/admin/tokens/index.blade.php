@extends('layouts.admin')

@section('title','Token Voting')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">
            Token Voting — {{ $event->judul }}
        </h2>

        <a href="{{ route('admin.events.tokens.export',$event) }}"
           class="px-4 py-2 bg-green-600 rounded-lg">
            Export CSV
        </a>
    </div>

    <div class="bg-slate-900 rounded-xl border border-white/10 overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-slate-800 text-gray-300">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Session</th>
                    <th class="p-3 text-left">Token</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tokens as $token)
                <tr class="border-t border-white/5">
                    <td class="p-3">{{ $token->member->nama }}</td>
                    <td class="p-3">{{ $token->session->nama_sesi }}</td>
                    <td class="p-3 font-mono">
                        {{ $token->token }}
                    </td>
                    <td class="p-3">
                        @if($token->is_used)
                            <span class="text-red-400">Digunakan</span>
                        @else
                            <span class="text-green-400">Aktif</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    {{ $tokens->links() }}

</div>

@endsection