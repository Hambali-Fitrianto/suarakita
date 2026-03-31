@extends('layouts.guest')

@section('title','Login')

@section('content')

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md px-6">

        {{-- LOGIN CARD --}}
        <div class="backdrop-blur-xl bg-white/5
            border border-white/10 rounded-2xl
            p-8 shadow-2xl">

            {{-- TITLE --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold
                    bg-gradient-to-r from-blue-400 to-indigo-400
                    bg-clip-text text-transparent">
                    Suarakita
                </h1>

                <p class="text-gray-400 text-sm mt-2">
                    Login Admin E-Voting
                </p>
            </div>

            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('login') }}"
                  class="space-y-5">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-lg
                        bg-black/40 border border-white/20
                        text-white focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 rounded-lg
                        bg-black/40 border border-white/20
                        text-white focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- REMEMBER --}}
                <div class="flex items-center justify-between text-sm">

                    <label class="flex items-center text-gray-400">
                        <input type="checkbox"
                               name="remember"
                               class="mr-2 rounded bg-black/40 border-gray-600">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-blue-400 hover:text-blue-300">
                            Lupa password?
                        </a>
                    @endif

                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    class="w-full py-3 rounded-lg
                    bg-blue-600 hover:bg-blue-700
                    font-semibold transition shadow-lg">

                    Log in

                </button>

            </form>

            {{-- BACK --}}
            <a href="/"
               class="block text-center text-sm text-gray-400
               hover:text-white mt-6">
                ← Kembali ke Beranda
            </a>

        </div>

    </div>

</div>


{{-- ================= SWEET ALERT SECTION ================= --}}

{{-- ✅ LOGOUT SUCCESS --}}
@if(session('status'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Logout Berhasil',
    text: 'Anda telah keluar dari sistem.',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Gagal',
    text: @json($errors->first()),
    confirmButtonText: 'Coba Lagi'
});
</script>
@endif

@endsection