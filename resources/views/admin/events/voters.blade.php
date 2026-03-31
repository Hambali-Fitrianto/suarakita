<form method="POST">
@csrf

@foreach($members as $member)

<label class="flex gap-2">
    <input type="checkbox"
        name="members[]"
        value="{{ $member->id }}"
        @checked(in_array($member->id,$invited))>

    {{ $member->nama }}
</label>

@endforeach

<button>Simpan Invite</button>
</form>