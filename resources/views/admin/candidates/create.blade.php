@extends('layouts.admin')

@section('title','Tambah Kandidat')
@section('header','Tambah Kandidat')

@section('content')

<div class="max-w-3xl mx-auto">

    <form method="POST"
          action="{{ route('admin.candidates.store') }}"
          enctype="multipart/form-data"
          class="bg-slate-900 border border-white/10 rounded-xl p-8 space-y-6">

        @csrf

        {{-- ================= HEADER ================= --}}
        <div>
            <h2 class="text-xl font-bold">
                Tambah Kandidat Baru
            </h2>

            <p class="text-sm text-gray-400">
                Data kandidat akan otomatis mendapatkan nomor urut.
            </p>
        </div>


        {{-- ================= EVENT ================= --}}
        <div>
            <label class="block text-sm mb-2 text-gray-300">
                Event Voting
            </label>

            <select name="voting_event_id"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

                <option value="">-- Pilih Event --</option>

                @foreach($events as $event)
                    <option value="{{ $event->id }}"
                        {{ old('voting_event_id')==$event->id?'selected':'' }}>
                        {{ $event->judul }}
                    </option>
                @endforeach

            </select>

            @error('voting_event_id')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- ================= NAMA ================= --}}
        <div>
            <label class="block text-sm mb-2">Nama Kandidat</label>

            <input type="text"
                name="nama"
                value="{{ old('nama') }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                placeholder="Nama lengkap kandidat">

            @error('nama')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- ================= GELAR ================= --}}
        <div>
            <label class="block text-sm mb-2">Gelar (Opsional)</label>

            <input type="text"
                name="gelar"
                value="{{ old('gelar') }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= JABATAN ================= --}}
        <div>
            <label class="block text-sm mb-2">Jabatan</label>

            <input type="text"
                name="jabatan"
                value="{{ old('jabatan') }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3"
                placeholder="Contoh: Ketua OSIS">
        </div>


        {{-- ================= ASAL ================= --}}
        <div>
            <label class="block text-sm mb-2">Asal Sekolah</label>

            <input type="text"
                name="asal_sekolah"
                value="{{ old('asal_sekolah') }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= NO HP ================= --}}
        <div>
            <label class="block text-sm mb-2">No HP</label>

            <input type="text"
                name="no_hp"
                value="{{ old('no_hp') }}"
                class="w-full bg-slate-800 border border-white/10 rounded-lg px-4 py-3">
        </div>


        {{-- ================= FOTO UPLOAD MODERN ================= --}}
        <div>

            <label class="block text-sm mb-3">
                Foto Kandidat
            </label>

            <div class="flex items-center gap-6">

                {{-- PREVIEW --}}
                <div>
                    <img id="preview"
                        src="https://ui-avatars.com/api/?name=Kandidat&background=0f172a&color=fff"
                        class="w-24 h-24 rounded-full object-cover border border-white/10">
                </div>

                {{-- CUSTOM FILE INPUT --}}
                <div class="flex-1">

                    <label
                        class="cursor-pointer inline-flex items-center gap-3
                        bg-blue-600 hover:bg-blue-700
                        px-5 py-3 rounded-lg font-semibold transition">

                        📷 Pilih Foto

                        <input type="file"
                            name="foto"
                            accept="image/*"
                            onchange="previewImage(event)"
                            class="hidden">
                    </label>

                    <p class="text-xs text-gray-400 mt-2">
                        JPG / PNG maksimal 2MB
                    </p>

                </div>

            </div>

            @error('foto')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror

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

                Simpan Kandidat
            </button>

        </div>

    </form>

</div>


{{-- ================= IMAGE PREVIEW SCRIPT ================= --}}
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