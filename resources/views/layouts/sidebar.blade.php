<aside
class="w-64 bg-slate-900 border-r border-white/10
flex flex-col">

{{-- LOGO --}}
<div class="h-16 flex items-center px-6
border-b border-white/10">

    <span class="text-xl font-bold
    bg-gradient-to-r from-blue-400 to-indigo-400
    bg-clip-text text-transparent">
        Suarakita
    </span>

</div>


{{-- MENU --}}
<nav class="flex-1 px-4 py-6 space-y-2">

    {{-- DASHBOARD --}}
    <a href="{{ route('admin.dashboard') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg
    transition
    {{ request()->routeIs('dashboard')
        ? 'bg-blue-600 text-white'
        : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">

        📊 <span>Dashboard</span>
    </a>


    <a href="{{ route('admin.candidates.index') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg transition
    {{ request()->routeIs('admin.candidates.*')
        ? 'bg-blue-600 text-white'
        : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">

        🧑 <span>Kandidat</span>
    </a>

    <a href="#"
    class="flex items-center gap-3 px-4 py-3 rounded-lg
    text-gray-400 hover:bg-white/5 hover:text-white">
        👥 Pemilih
    </a>

    <a href="#"
    class="flex items-center gap-3 px-4 py-3 rounded-lg
    text-gray-400 hover:bg-white/5 hover:text-white">
        🗳 Voting
    </a>

    <a href="{{ route('public.result') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg
    text-gray-400 hover:bg-white/5 hover:text-white">
        📈 Hasil Voting
    </a>

</nav>


{{-- USER --}}
<div class="p-4 border-t border-white/10">

    <div class="text-sm text-gray-400">
        Login sebagai
    </div>

    <div class="font-semibold">
        {{ auth()->user()->name }}
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button class="w-full text-left text-red-400 hover:text-red-300">
            Logout
        </button>
    </form>

</div>

</aside>