@extends('layouts.admin')

@section('title','Invite Pemilih')
@section('header','Invite Pemilih')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-slate-900 border border-white/10 rounded-xl p-6">

        <h2 class="text-lg font-bold mb-4">
            Invite Pemilih — {{ $event->judul }}
        </h2>

        <form method="POST">
            @csrf

            {{-- SELECT ALL --}}
            <div class="mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="checkAll">
                    <span>Pilih Semua</span>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-3 max-h-[400px] overflow-y-auto">

                @foreach($members as $member)

                    <label class="flex items-center justify-between
                                  bg-slate-800 px-4 py-2 rounded-lg cursor-pointer">

                        <div class="flex items-center gap-2">
                            <input type="checkbox"
                                name="members[]"
                                value="{{ $member->id }}"
                                class="member-checkbox"
                                @checked(in_array($member->id,$invited))>

                            <span>{{ $member->nama }}</span>
                        </div>

                        @if(in_array($member->id,$invited))
                            <span class="text-xs text-green-400">
                                invited
                            </span>
                        @endif

                    </label>

                @endforeach

            </div>

            <div class="flex justify-between mt-6">

                <a href="{{ route('admin.events.index') }}"
                   class="text-gray-400 hover:text-white">
                   ← Kembali
                </a>

                <button
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold">
                    Simpan Invite
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.member-checkbox')
        .forEach(cb => cb.checked = this.checked);
});
</script>

@endsection