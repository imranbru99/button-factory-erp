@extends('public.layout')
@section('title', $product->meta_title ?: $product->name)
@section('content')
@php($copy = app()->getLocale() === 'bn' ? ($product->description_bn ?: $product->description) : $product->description)
<section class="mx-auto grid max-w-6xl gap-10 px-5 py-14 md:grid-cols-2">
    <div class="flex items-center justify-center rounded-[2rem] bg-[#efe6d6] p-10">
        <span class="hole h-40 w-40 rounded-full bg-[var(--brass)]"></span>
    </div>
    <div>
        <p class="text-sm uppercase tracking-[0.18em] text-[var(--brass)]">{{ $product->sku }} · {{ $product->size_ligne }}L</p>
        <h1 class="display mt-2 text-5xl">{{ app()->getLocale() === 'bn' ? ($product->name_bn ?: $product->name) : $product->name }}</h1>
        <p class="mt-4 text-lg text-black/70">{{ $copy }}</p>
        <dl class="mt-6 grid grid-cols-2 gap-3 text-sm">
            <div><dt class="text-black/50">Material</dt><dd>{{ $product->material_label }}</dd></div>
            <div><dt class="text-black/50">Holes</dt><dd>{{ str_replace('_', ' ', $product->hole_type) }}</dd></div>
            <div><dt class="text-black/50">Finish</dt><dd>{{ str_replace('_', ' ', $product->finish) }}</dd></div>
            <div><dt class="text-black/50">Thickness</dt><dd>{{ $product->thickness_mm }} mm</dd></div>
        </dl>
        <p class="mt-6 text-2xl">{{ config('factory.currency') }} {{ number_format((float) $product->list_price, 2) }} <span class="text-sm text-black/50">list / piece</span></p>
        <a href="{{ route('contact', ['product' => $product->id]) }}" class="mt-6 inline-block rounded-full bg-[var(--ink)] px-5 py-3 text-[var(--paper)]">Request this button</a>
    </div>
</section>
<script type="application/ld+json">{!! json_encode($product->jsonLdPayload(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endsection
