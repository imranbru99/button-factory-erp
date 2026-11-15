@extends('admin-panel::layouts.app')

@section('content')
@php($stats = $stats ?? app(\App\Services\Insights\InsightService::class)->snapshot())
<div class="space-y-6">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ config('factory.name') }}</h1>
            <p class="page-subtitle">Factory clock {{ $stats['clock'] }} · Asia/Dhaka</p>
        </div>
        <div class="page-actions">
            <a href="/admin/shop-floor" class="admin-btn admin-btn-primary">Open shop floor</a>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
        @foreach ([
            'Output today' => $stats['output_today'],
            'Scrap %' => $stats['scrap_rate'],
            'Open work orders' => $stats['open_orders'],
            'Open issues' => $stats['open_issues'],
            'On-time %' => $stats['on_time'],
            'OEE %' => $stats['oee'],
        ] as $label => $value)
            <x-admin-panel::card>
                <p style="font-size:.8rem;color:var(--admin-text-muted)">{{ $label }}</p>
                <p style="font-size:1.7rem;font-weight:700">{{ $value }}</p>
            </x-admin-panel::card>
        @endforeach
    </div>
</div>
@endsection
