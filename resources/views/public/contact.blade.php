@extends('public.layout')
@section('title', __('site.contact'))
@section('content')
<section class="mx-auto max-w-xl px-5 py-16">
    <h1 class="display text-4xl">{{ __('site.contact') }}</h1>
    @if (session('status'))
        <p class="mt-4 rounded-xl bg-emerald-100 px-4 py-3 text-emerald-900">{{ session('status') }}</p>
    @endif
    <form method="post" action="{{ route('inquiries.store') }}" class="mt-6 space-y-3">
        @csrf
        <input name="name" value="{{ old('name') }}" placeholder="Name" class="w-full rounded-xl border border-black/10 px-3 py-2" required>
        <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded-xl border border-black/10 px-3 py-2" required>
        <input name="phone" value="{{ old('phone') }}" placeholder="Phone" class="w-full rounded-xl border border-black/10 px-3 py-2">
        <input name="company" value="{{ old('company') }}" placeholder="Garment factory" class="w-full rounded-xl border border-black/10 px-3 py-2">
        <select name="product_id" class="w-full rounded-xl border border-black/10 px-3 py-2">
            <option value="">Any button</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @selected(request('product') == $product->id)>{{ $product->name }}</option>
            @endforeach
        </select>
        <input name="quantity" type="number" min="1" value="{{ old('quantity', 5000) }}" class="w-full rounded-xl border border-black/10 px-3 py-2" required>
        <textarea name="message" required class="w-full rounded-xl border border-black/10 px-3 py-2" rows="5" placeholder="Size, shade, finish, and delivery window.">{{ old('message') }}</textarea>
        @error('email')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
        <button class="rounded-full bg-[var(--ink)] px-5 py-3 text-[var(--paper)]">Send to sales</button>
    </form>
</section>
@endsection
