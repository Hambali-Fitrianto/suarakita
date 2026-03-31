@extends('layouts.admin')

@section('title','Edit Kandidat')
@section('header','Edit Kandidat')

@section('content')

<div class="max-w-3xl mx-auto">

    <form method="POST"
          action="{{ route('admin.candidates.update',$candidate->id) }}"
          enctype="multipart/form-data"
          class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

        @csrf
        @method('PUT')

        {{-- ================= HEADER ================= --}}
        <div>
            <h2 class="text-xl font-bold">
                Edit Data Kandidat
            </h2>

            <p class="text-sm text-gray-400">
                Perbarui informasi kandidat voting.
            </p>
        </div>


        {{-- ================= EVENT ================= --}}
        <div>
            <label class="block text-sm mb-2 text-gray-300">
                Event Voting
            </label>

            <select name="voting_event_id"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

                @foreach($events as $event)
                    <option value="{{ $event->id }}"
                        {{ old('voting_event_id',$candidate->voting_event_id)==$event->id?'selected':'' }}>
                        {{ $event->judul }}
                    </option>
                @endforeach

            </select>
        </div>


        {{-- ================= NAMA ================= --}}
        <div>
            <label class="block text-sm mb-2">Nama Kandidat</label>

            <input type="text"
                name="nama"
                value="{{ old('nama',$candidate->nama) }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= GELAR ================= --}}
        <div>
            <label class="block text-sm mb-2">Gelar</label>

            <input type="text"
                name="gelar"
                value="{{ old('gelar',$candidate->gelar) }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= JABATAN ================= --}}
        <div>
            <label class="block text-sm mb-2">Jabatan</label>

            <input type="text"
                name="jabatan"
                value="{{ old('jabatan',$candidate->jabatan) }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= ASAL ================= --}}
        <div>
            <label class="block text-sm mb-2">Asal Sekolah</label>

            <input type="text"
                name="asal_sekolah"
                value="{{ old('asal_sekolah',$candidate->asal_sekolah) }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= NO HP ================= --}}
        <div>
            <label class="block text-sm mb-2">No HP</label>

            <input type="text"
                name="no_hp"
                value="{{ old('no_hp',$candidate->no_hp) }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= FOTO ================= --}}
        <div>

            <label class="block text-sm mb-3">
                Foto Kandidat
            </label>

            <div class="flex items-center gap-6">

                {{-- PREVIEW --}}
                <img id="preview"
                    src="{{ $candidate->foto
                        ? asset('storage/'.$candidate->foto)
                        : 'https://ui-avatars.com/api/?name='.urlencode($candidate->nama).'&background=0f172a&color=fff' }}"
                    class="w-24 h-24 rounded-full object-cover border border-white/10">

                {{-- UPLOAD --}}
                <div class="flex-1 space-y-2">

                    <label
                        class="cursor-pointer inline-flex items-center gap-3
                        bg-blue-600 hover:bg-blue-700
                        px-5 py-3 rounded-lg font-semibold transition">

                        🔄 Ganti Foto

                        <input type="file"
                            name="foto"
                            accept="image/*"
                            onchange="previewImage(event)"
                            class="hidden">
                    </label>

                    <p class="text-xs text-gray-400">
                        Kosongkan jika tidak ingin mengganti foto.
                    </p>

                </div>

            </div>

        </div>


        {{-- ================= ACTION ================= --}}
        <div class="flex justify-between pt-4 border-t border-white/10">

            <a href="{{ route('admin.candidates.index') }}"
                class="text-gray-400 hover:text-white">
                ← Kembali
            </a>

            <button
                class="bg-green-600 hover:bg-green-700
                px-6 py-3 rounded-lg font-semibold shadow-lg shadow-green-600/20">

                Update Kandidat
            </button>

        </div>

    </form>

</div>


{{-- ================= PREVIEW SCRIPT ================= --}}
<script>
function previewImage(event)
{
    const reader = new FileReader();

    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection