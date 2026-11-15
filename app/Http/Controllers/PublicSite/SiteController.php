<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use App\Notifications\FactoryAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $products = Product::query()->where('is_published', true)->latest('id')->limit(8)->get();

        return view('public.home', [
            'products' => $products,
            'families' => Product::query()->where('is_published', true)->distinct()->orderBy('button_family')->pluck('button_family'),
        ]);
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact', ['products' => Product::query()->where('is_published', true)->orderBy('name')->get()]);
    }

    public function catalog(Request $request): View
    {
        $products = Product::query()
            ->where('is_published', true)
            ->when($request->filled('family'), fn ($q) => $q->where('button_family', $request->string('family')))
            ->when($request->filled('size'), fn ($q) => $q->where('size_ligne', $request->integer('size')))
            ->when($request->filled('color'), fn ($q) => $q->where('color', $request->string('color')))
            ->when($request->filled('hole'), fn ($q) => $q->where('hole_type', $request->string('hole')))
            ->when($request->filled('finish'), fn ($q) => $q->where('finish', $request->string('finish')))
            ->when($request->filled('design'), fn ($q) => $q->where('design_pattern', $request->string('design')))
            ->orderBy('size_ligne')
            ->paginate(12)
            ->withQueryString();

        return view('public.catalog', [
            'products' => $products,
            'filters' => [
                'family' => Product::query()->distinct()->orderBy('button_family')->pluck('button_family'),
                'size' => Product::query()->distinct()->orderBy('size_ligne')->pluck('size_ligne'),
                'color' => Product::query()->distinct()->orderBy('color')->pluck('color'),
                'hole' => Product::query()->distinct()->orderBy('hole_type')->pluck('hole_type'),
                'finish' => Product::query()->distinct()->orderBy('finish')->pluck('finish'),
                'design' => Product::query()->distinct()->orderBy('design_pattern')->pluck('design_pattern'),
            ],
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::query()
            ->where('is_published', true)
            ->where(fn ($query) => $query->where('slug', $slug)->orWhere('slug_bn', $slug))
            ->firstOrFail();

        return view('public.product', ['product' => $product]);
    }

    public function inquire(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'product_id' => ['nullable', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $inquiry = Inquiry::query()->create($data + ['locale' => app()->getLocale(), 'status' => 'new']);

        User::query()->whereIn('role', ['sales', 'super_admin'])->where('is_active', true)->get()
            ->each(fn (User $user) => $user->notify(new FactoryAlertNotification(
                'New button inquiry',
                "{$inquiry->name} asked for {$inquiry->quantity} pcs.",
                url('/admin/inquiries'),
            )));

        return back()->with('status', __('site.inquiry_sent'));
    }

    public function locale(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['en', 'bn'], true), 404);
        session(['locale' => $locale]);

        return back();
    }
}
