<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('factory.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,520;9..144,680&family=Outfit:wght@380;520;650&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#1c1712; --paper:#f4efe6; --brass:#a9783a; --card:#fffaf3; }
        body { font-family: Outfit, ui-sans-serif, sans-serif; background: var(--paper); color: var(--ink); }
        h1,h2,.display { font-family: Fraunces, ui-serif, serif; }
        .hole { box-shadow: inset 0 0 0 8px var(--ink), inset 0 0 0 14px var(--paper); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>
    <header class="border-b border-black/10" x-data="{ open: false }">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="hole inline-block h-9 w-9 rounded-full bg-[var(--brass)]"></span>
                <span class="display text-xl">{{ config('factory.name') }}</span>
            </a>
            <button type="button" class="rounded-full border border-black/20 px-3 py-1 text-sm md:hidden" @click="open = !open">Menu</button>
            <nav class="hidden items-center gap-5 text-sm md:flex">
                <a href="{{ route('catalog') }}">{{ __('site.catalog') }}</a>
                <a href="{{ route('about') }}">{{ __('site.about') }}</a>
                <a href="{{ route('contact') }}">{{ __('site.contact') }}</a>
                <a href="{{ route('locale', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" class="rounded-full border border-black/20 px-3 py-1">{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</a>
                <a href="/admin" class="rounded-full bg-[var(--ink)] px-3 py-1 text-[var(--paper)]">Factory</a>
            </nav>
        </div>
        <nav x-show="open" x-cloak class="flex flex-col gap-3 px-5 pb-4 text-sm md:hidden">
            <a href="{{ route('catalog') }}">{{ __('site.catalog') }}</a>
            <a href="{{ route('about') }}">{{ __('site.about') }}</a>
            <a href="{{ route('contact') }}">{{ __('site.contact') }}</a>
            <a href="/admin">Factory</a>
        </nav>
    </header>
    <main>@yield('content')</main>
    <footer class="mt-16 border-t border-black/10">
        <div class="mx-auto flex max-w-6xl justify-between px-5 py-8 text-sm text-black/60">
            <p>Gazipur · polyester, shell, and specialty buttons · {{ \Tarikh\Tarikh::now(config('factory.timezone'))->format('Y') }}</p>
            <p>Ligne 14–60 · 2-hole, 4-hole, shank</p>
        </div>
    </footer>
</body>
</html>
