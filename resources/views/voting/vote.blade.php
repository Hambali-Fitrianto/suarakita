@extends('layouts.guest')

@section('title','Voting')

@section('content')
<div x-data="voteApp()" x-cloak class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900 text-white pb-20">

    {{-- HEADER --}}
    <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <h1 class="text-4xl font-extrabold text-blue-500 tracking-tight mb-2">
            {{ $event->judul }}
        </h1>
        <p class="text-slate-400 text-lg uppercase tracking-widest">{{ $session->nama_sesi }}</p>

        <div class="mt-6 inline-block bg-blue-500/10 border border-blue-500/20 px-4 py-2 rounded-full">
            <span class="text-blue-400 text-sm font-medium">
                ⏱️ Sisa Waktu: <span class="font-mono ml-1" x-text="countdown">--:--:--</span>
            </span>
        </div>
    </div>

    {{-- FORM --}}
    <form id="vote-form" method="POST" action="{{ route('vote.submit') }}" class="max-w-7xl mx-auto px-6">
        @csrf
        <input type="hidden" name="candidate_id" :value="selected">

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($candidates as $candidate)
            <div
                @click="choose({{ $candidate->id }})"
                :class="selected == {{ $candidate->id }} ? 'ring-4 ring-blue-500 scale-105 bg-slate-800' : 'hover:scale-[1.02] bg-slate-900'"
                class="cursor-pointer transition-all duration-300 border border-white/5 rounded-3xl overflow-hidden shadow-2xl group">

                {{-- FOTO --}}
                <div class="h-80 bg-slate-800 overflow-hidden relative">
                    <img src="{{ asset('storage/'.$candidate->foto) }}"
                        class="w-full h-full object-cover group-hover:opacity-80 transition-opacity"
                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($candidate->nama) }}&background=0D8ABC&color=fff&size=512'">

                    <div x-show="selected == {{ $candidate->id }}" class="absolute top-4 right-4 bg-blue-500 p-2 rounded-full shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- INFO --}}
                <div class="p-8 text-center">
                    <span class="text-xs font-bold text-blue-500 uppercase tracking-tighter">Kandidat No. {{ $candidate->nomor_urut }}</span>
                    <h2 class="text-2xl font-bold mt-1 text-white">{{ $candidate->nama }}</h2>
                    <p class="text-slate-500 mt-2 text-sm">{{ $candidate->jabatan ?? 'Calon Ketua' }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- SUBMIT --}}
        <div class="fixed bottom-10 left-0 right-0 flex justify-center z-50">
            <button
                type="button"
                @click="confirmVote"
                class="px-10 py-4 bg-blue-600 hover:bg-blue-500 rounded-2xl text-lg font-bold shadow-2xl shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-3">
                <span>🗳️</span> Kirim Suara Sekarang
            </button>
        </div>
    </form>
</div>

<script>
    function voteApp() {
        return {
            selected: null,
            countdown: '00:00:00',

            choose(id) {
                this.selected = id;
            },

            confirmVote() {
                if (!this.selected) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Ada Pilihan',
                        text: 'Silakan klik pada salah satu kartu kandidat dulu bos!',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Sudah Yakin?',
                    text: "Pilihan yang sudah dikirim tidak bisa dibatalkan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Cek Lagi',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#475569',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('vote-form').submit();
                    }
                });
            },

            init() {
                const end = @json($endsAt);
                if (!end) return;

                const timer = setInterval(() => {
                    const now = Math.floor(Date.now() / 1000);
                    const diff = end - now;

                    if (diff <= 0) {
                        clearInterval(timer);
                        this.countdown = "Waktu Habis";
                        location.reload();
                        return;
                    }

                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;

                    this.countdown =
                        String(h).padStart(2, '0') + ':' +
                        String(m).padStart(2, '0') + ':' +
                        String(s).padStart(2, '0');
                }, 1000);
            }
        }
    }
</script>
@endsection