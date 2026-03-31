<nav x-data="{ open:false, userMenu:false }"
    class="bg-white/5 backdrop-blur border-b border-white/10 relative">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16 items-center">

    {{-- LEFT --}}
    <div class="flex items-center space-x-10">

        <a href="{{ route('admin.dashboard') }}"
            class="text-white font-bold text-lg">
            Suarakita
        </a>

        <div class="hidden sm:flex space-x-6">

            <a href="{{ route('admin.dashboard') }}"
                class="text-sm font-medium transition
                {{ request()->routeIs('dashboard')
                    ? 'text-blue-400'
                    : 'text-gray-300 hover:text-white' }}">
                Dashboard
            </a>

        </div>
    </div>


    {{-- USER MENU --}}
    @auth
    <div class="hidden sm:flex items-center relative">

        {{-- BUTTON --}}
        <button @click="userMenu=!userMenu"
            class="flex items-center gap-2 px-3 py-2
            rounded-lg bg-white/5 hover:bg-white/10
            text-gray-300 hover:text-white transition">

            {{ auth()->user()->name }}

            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10
                    10.586l3.293-3.293a1 1 0
                    111.414 1.414l-4 4a1 1 0
                    01-1.414 0l-4-4a1 1 0
                    010-1.414z"
                    clip-rule="evenodd"/>
            </svg>
        </button>

        {{-- DROPDOWN --}}
        <div x-show="userMenu"
            x-cloak
            @click.outside="userMenu=false"
            x-transition
            class="absolute right-0 top-12 w-48
            bg-slate-900 border border-white/10
            rounded-lg shadow-xl overflow-hidden
            z-50">

            <a href="{{ route('profile.edit') }}"
                class="block px-4 py-2 text-sm
                text-gray-300 hover:bg-white/10">
                Profile
            </a>

            {{-- LOGOUT FIX --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm
                    text-gray-300 hover:text-red-400
                    hover:bg-white/10">
                    Log Out
                </button>
            </form>

        </div>

    </div>
    @endauth


    {{-- MOBILE BUTTON --}}
    <div class="sm:hidden">
        <button @click="open=!open"
            class="p-2 text-gray-300 hover:text-white">

            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">

                <path x-show="!open"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>

                <path x-show="open"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>

        </button>
    </div>

</div>
</div>


{{-- MOBILE MENU --}}
<div x-show="open"
    x-transition
    class="sm:hidden border-t border-white/10">

    <div class="px-4 py-3 space-y-2">

        <a href="{{ route('admin.dashboard') }}"
            class="block text-gray-300 hover:text-white">
            Dashboard
        </a>

    </div>

    @auth
    <div class="px-4 pb-4 border-t border-white/10">

        <div class="mt-3 text-white font-medium">
            {{ auth()->user()->name }}
        </div>

        <div class="text-sm text-gray-400">
            {{ auth()->user()->email }}
        </div>

        <div class="mt-3 space-y-2">

            <a href="{{ route('profile.edit') }}"
                class="block text-gray-300 hover:text-white">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left
                    text-gray-300 hover:text-red-400">
                    Log Out
                </button>
            </form>

        </div>

    </div>
    @endauth

</div>

</nav>