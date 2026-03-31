@extends('layouts.guest')

@section('title','Masukkan Token')

@section('content')

<div class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    {{-- ================= BACKGROUND ================= --}}
    <div class="absolute inset-0 bg-gradient-to-br
        from-slate-950 via-blue-900 to-black"></div>

    <div class="absolute w-[450px] h-[450px]
        bg-blue-600/20 blur-[140px] -top-20 -left-20"></div>

    <div class="absolute w-[450px] h-[450px]
        bg-indigo-500/20 blur-[140px] bottom-0 right-0"></div>


    {{-- ================= CARD ================= --}}
    <div class="relative z-10 w-full max-w-md">

        <div class="backdrop-blur-xl bg-white/5
            border border-white/10 rounded-2xl
            p-8 shadow-2xl">

            {{-- TITLE --}}
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold
                    bg-gradient-to-r from-blue-400 to-indigo-400
                    bg-clip-text text-transparent">
                    Masukkan Token
                </h1>

                <p class="text-gray-400 text-sm mt-2">
                    Gunakan token voting yang diberikan panitia.
                </p>
            </div>


            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div class="mb-4 text-red-400 text-sm text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-400 text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif


            {{-- ================= FORM ================= --}}
            <form method="POST"
                  action="{{ route('token.verify') }}"
                  class="space-y-5">

                @csrf

                {{-- TOKEN INPUT --}}
                <div>
                    <input
                        id="tokenInput"
                        type="text"
                        name="token"
                        autofocus
                        required
                        autocomplete="off"
                        placeholder="CONTOH: AB12CD34"
                        class="w-full text-center tracking-widest
                        text-lg px-4 py-4 rounded-lg
                        bg-black/40 border border-white/20
                        text-white uppercase
                        focus:ring-2 focus:ring-blue-500
                        outline-none">
                </div>


                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg
                    bg-blue-600 hover:bg-blue-700
                    font-semibold transition shadow-lg">

                    🔐 Masuk Voting
                </button>

            </form>


            {{-- ================= BACK BUTTON ================= --}}
            <a href="{{ route('landing') }}"
               class="block text-center text-sm text-gray-400
               hover:text-white mt-6 transition">
                ← Kembali ke Beranda
            </a>

        </div>

    </div>

</div>


{{-- ================= UX SCRIPT ================= --}}
<script>
    /*
    |----------------------------------------------------------
    | AUTO UPPERCASE TOKEN
    |----------------------------------------------------------
    */
    const tokenInput = document.getElementById('tokenInput');

    tokenInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/\s/g,'');
    });

    /*
    |----------------------------------------------------------
    | ENTER AUTO SUBMIT (UX HALUS)
    |----------------------------------------------------------
    */
    tokenInput.addEventListener('keypress', function(e){
        if(e.key === 'Enter'){
            this.closest('form').submit();
        }
    });
</script>

@endsection