@php
    $classes = match ($status) {
        'aktif'   => 'bg-green-500/20 text-green-400',
        'draft'   => 'bg-yellow-500/20 text-yellow-400',
        'selesai' => 'bg-blue-500/20 text-blue-400',
        default   => 'bg-slate-700 text-gray-300',
    };
@endphp

<span
    {{ $attributes->merge([
        'class' => "px-3 py-1 text-xs rounded-lg {$classes}"
    ]) }}
>
    {{ ucfirst($status) }}
</span>