@extends('public.layout')
@section('title', config('factory.name').' · Button manufacturer')
@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-5 py-16 md:grid-cols-[1.2fr_.8fr] md:items-center">
    <div>
        <p class="text-sm uppercase tracking-[0.22em] text-[var(--brass)]">Traditional factory, live floor</p>
        <h1 class="display mt-3 text-5xl leading-tight md:text-6xl">Buttons cut, turned, drilled, and polished in Gazipur.</h1>
        <p class="mt-5 max-w-xl text-lg text-black/70">Polyester sheet and rod, natural shell, metal, wood, and laser-engraved specialty. Every lot keeps its formula version, stage times, and scrap.</p>
        <div class="mt-8 flex gap-3">
            <a href="{{ route('catalog') }}" class="rounded-full bg-[var(--ink)] px-5 py-3 text-[var(--paper)]">Browse the ligne sizes</a>
            <a href="{{ route('contact') }}" class="rounded-full border border-black/20 px-5 py-3">Request a quotation</a>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @foreach ([['14L','Shirt'],['24L','Coat'],['Shell','Natural'],['Shank','Metal'],['Matte','Wood'],['Laser','Mark']] as $swatch)
            <div class="flex aspect-square flex-col items-center justify-center rounded-full bg-[var(--card)] shadow-sm ring-1 ring-black/5">
                <span class="hole h-14 w-14 rounded-full bg-[var(--brass)]"></span>
                <span class="mt-3 text-xs uppercase tracking-wider">{{ $swatch[0] }}</span>
                <span class="text-xs text-black/50">{{ $swatch[1] }}</span>
            </div>
        @endforeach
    </div>
</section>
<section class="mx-auto max-w-6xl px-5 pb-8">
    <h2 class="display text-3xl">In the catalog</h2>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($products as $product)
            <a href="{{ route('product', $product->slug) }}" class="rounded-3xl bg-[var(--card)] p-4 ring-1 ring-black/5">
                <div class="flex h-28 items-center justify-center rounded-2xl bg-[#efe6d6]">
                    <span class="hole h-16 w-16 rounded-full" style="background: {{ $product->color === 'Black' ? '#1c1712' : ($product->color === 'Navy' ? '#1e3a5f' : '#d7c4a3') }}"></span>
                </div>
                <p class="mt-3 font-medium">{{ app()->getLocale() === 'bn' ? ($product->name_bn ?: $product->name) : $product->name }}</p>
                <p class="text-sm text-black/60">{{ $product->size_ligne }}L · {{ str_replace('_', ' ', $product->finish) }}</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
