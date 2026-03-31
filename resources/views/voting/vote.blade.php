@extends('layouts.guest')

@section('title','Voting')

@section('content')

<div x-data="voteApp()" class="min-h-screen bg-slate-950 text-white">

    {{-- HEADER --}}
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">

        <h1 class="text-3xl font-bold text-blue-400">
            {{ $event->judul }}
        </h1>

        <p class="text-gray-400 mt-2">
            {{ $session->nama_sesi }}
        </p>

        {{-- COUNTDOWN --}}
        <div class="mt-4 text-sm text-yellow-400">
            Voting berakhir dalam:
            <span class="font-bold" x-text="countdown"></span>
        </div>

    </div>


    {{-- FORM --}}
    <form id="vote-form"
          method="POST"
          action="{{ route('vote.submit') }}"
          class="max-w-7xl mx-auto px-6 pb-20">

        @csrf
        <input type="hidden" name="candidate_id" x-model="selected">

        {{-- GRID KANDIDAT --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($candidates as $candidate)

            <div
                @click="choose({{ $candidate->id }})"
                :class="selected == {{ $candidate->id }}
                    ? 'ring-4 ring-blue-500 scale-105'
                    : 'hover:scale-105'"
                class="cursor-pointer transition duration-300
                       bg-slate-900 border border-white/10
                       rounded-2xl overflow-hidden shadow-xl">

                {{-- FOTO --}}
                <div class="h-72 bg-slate-800 overflow-hidden">

                    <img src="{{ asset('storage/'.$candidate->foto) }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- INFO --}}
                <div class="p-6 text-center space-y-2">

                    <div class="text-sm text-gray-400">
                        Kandidat {{ $candidate->nomor_urut }}
                    </div>

                    <h2 class="text-xl font-bold">
                        {{ $candidate->nama }}
                    </h2>

                    @if($candidate->jabatan)
                        <p class="text-gray-400 text-sm">
                            {{ $candidate->jabatan }}
                        </p>
                    @endif

                </div>

            </div>

            @endforeach

        </div>


        {{-- BUTTON SUBMIT --}}
        <div class="text-center mt-14">

            <button
                type="button"
                @click="confirmVote"
                class="px-10 py-4 rounded-xl
                       bg-blue-600 hover:bg-blue-700
                       text-lg font-semibold shadow-lg
                       disabled:opacity-40">

                🗳️ Kirim Pilihan
            </button>

        </div>

    </form>

</div>


{{-- ================= SCRIPT ================= --}}
<script>

function voteApp()
{
    return {

        selected:null,
        countdown:'--:--',

        choose(id)
        {
            this.selected = id;
        },

        confirmVote()
        {
            if(!this.selected)
            {
                Swal.fire({
                    icon:'warning',
                    title:'Pilih kandidat dulu'
                });
                return;
            }

            Swal.fire({
                title:'Yakin memilih?',
                text:'Pilihan tidak bisa diubah setelah dikirim.',
                icon:'question',
                showCancelButton:true,
                confirmButtonText:'Ya, Vote',
                cancelButtonText:'Batal'
            }).then((r)=>{
                if(r.isConfirmed)
                {
                    document.getElementById('vote-form').submit();
                }
            });
        },

        init()
        {
            const end = {{ $endsAt ?? 'null' }};

            if(!end) return;

            setInterval(()=>{

                const now = Math.floor(Date.now()/1000);
                const diff = end - now;

                if(diff <= 0)
                {
                    location.reload();
                    return;
                }

                const m = Math.floor(diff/60);
                const s = diff % 60;

                this.countdown =
                    String(m).padStart(2,'0') + ':' +
                    String(s).padStart(2,'0');

            },1000);
        }
    }
}

</script>

@endsection