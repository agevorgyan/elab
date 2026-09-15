@extends('layouts.app')

@section('title', 'Ծառայություններ | eLab Agency - Կայքերի Պատրաստում & Թվային Լուծումներ')
@section('meta_description', 'eLab Agency-ի բոլոր ծառայությունները՝ կայքերի պատրաստում, օնլայն խանութներ, CRM/ERP համակարգեր, UI/UX դիզայն և SEO առաջխաղացում։')

@section('content')

    <!-- Page Hero -->
    <section class="hero-section" style="padding: 70px 0 50px;">
        <div class="container">
            <span class="badge" style="margin-bottom: 16px;">Պրոֆեսիոնալ Մոտեցում</span>
            <h1 class="hero-title" style="font-size: clamp(2.2rem, 5vw, 3.2rem); word-break: break-word;">
                Մեր Բոլոր <span class="text-gradient">Ծառայությունները</span>
            </h1>
            <p class="hero-subtitle">
                Թվային ամբողջական լուծումներ՝ նախագծումից և դիզայնից մինչև բարձրակարգ ծրագրավորում և տեխնիկական սպասարկում։
            </p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-card">
                        <div class="service-icon-box">
                            @if($service->slug === 'web-development')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            @elseif($service->slug === 'e-commerce')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            @elseif($service->slug === 'custom-web-app')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                            @elseif($service->slug === 'ui-ux-design')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            @elseif($service->slug === 'seo-optimization')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            @else
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            @endif
                        </div>

                        <h3 class="service-card-title">{{ $service->title }}</h3>
                        <div class="service-card-tagline">{{ $service->tagline }}</div>
                        <p class="service-card-desc">{{ $service->description }}</p>

                        @if($service->features->count() > 0)
                            <ul class="service-features-list">
                                @foreach($service->features as $feature)
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>{{ $feature->text }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="service-card-footer">
                            <div class="service-price-block">
                                <span class="service-price-label">{{ $service->price_label ?? 'Սկսած' }}</span>
                                <span class="service-price-val">{{ $service->price_amd }} {{ $service->price_currency }}</span>
                            </div>

                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-secondary btn-sm">
                                    <span>Մանրամասն</span>
                                </a>
                                <button class="btn btn-primary btn-sm" data-open-modal="orderModal" data-service="{{ $service->title }}">
                                    <span>Պատվիրել</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
