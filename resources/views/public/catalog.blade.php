@extends('public.layout')
@section('title', __('site.catalog'))
@section('content')
<section class="mx-auto max-w-6xl px-5 py-12">
    <h1 class="display text-4xl">{{ __('site.catalog') }}</h1>
    <form class="mt-6 grid gap-3 md:grid-cols-6" method="get">
        @foreach (['family' => 'Family', 'size' => 'Ligne', 'color' => 'Color', 'hole' => 'Holes', 'finish' => 'Finish', 'design' => 'Design'] as $key => $label)
            <label class="text-xs uppercase tracking-wider text-black/50">{{ $label }}
                <select name="{{ $key }}" class="mt-1 w-full rounded-xl border border-black/10 bg-white px-2 py-2 text-sm text-[var(--ink)]">
                    <option value="">Any</option>
                    @foreach ($filters[$key] as $option)
                        <option value="{{ $option }}" @selected(request($key) == $option)>{{ str_replace('_', ' ', $option) }}</option>
                    @endforeach
                </select>
            </label>
        @endforeach
        <button class="md:col-span-6 w-fit rounded-full bg-[var(--ink)] px-4 py-2 text-sm text-[var(--paper)]">Filter</button>
    </form>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($products as $product)
            <a href="{{ route('product', $product->slug) }}" class="rounded-3xl bg-[var(--card)] p-5 ring-1 ring-black/5">
                <p class="text-xs uppercase tracking-wider text-[var(--brass)]">{{ $product->sku }}</p>
                <h2 class="display mt-1 text-2xl">{{ app()->getLocale() === 'bn' ? ($product->name_bn ?: $product->name) : $product->name }}</h2>
                <p class="mt-2 text-sm text-black/70">{{ $product->excerpt }}</p>
            </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
</section>
@endsection
