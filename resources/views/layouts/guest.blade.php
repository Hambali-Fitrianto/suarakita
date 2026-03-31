<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title','Suarakita')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen font-sans antialiased
    bg-gradient-to-br from-slate-950 via-blue-900 to-black
    text-white">

    @yield('content')

</body>
</html>