@extends('layouts.app')

@section('title', $service->title . ' | eLab Agency')
@section('meta_description', $service->description)

@section('content')

    <!-- Service Header -->
    <section class="hero-section" style="padding: 70px 0 40px; text-align: left;">
        <div class="container">
            <div style="margin-bottom: 16px;">
                <a href="{{ route('services.index') }}" style="color: var(--accent-cyan); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Բոլոր ծառայությունները</span>
                </a>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 24px;">
                <div style="max-width: 760px;">
                    <span class="badge" style="margin-bottom: 14px;">Ծառայության Մանրամասներ</span>
                    <h1 class="hero-title" style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 16px;">
                        {{ $service->title }}
                    </h1>
                    <p style="color: var(--text-secondary); font-size: 1.15rem; line-height: 1.6;">
                        {{ $service->tagline }}
                    </p>
                </div>

                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 24px 30px; text-align: center; min-width: 240px;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">{{ $service->price_label ?? 'Սկսած' }}</div>
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: #fff; margin: 6px 0 16px;">
                        {{ $service->price_amd }} {{ $service->price_currency }}
                    </div>
                    <button class="btn btn-primary" style="width: 100%;" data-open-modal="orderModal" data-service="{{ $service->title }}">
                        <span>Պատվիրել Հիմա</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Body -->
    <section class="section" style="padding-top: 20px;">
        <div class="container">
            <div class="service-details-grid">
                <!-- Main description & features -->
                <div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 40px; margin-bottom: 36px;">
                        <h2 style="font-size: 1.6rem; margin-bottom: 16px;">Ծառայության Նկարագրություն</h2>
                        <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 32px;">
                            {{ $service->description }}
                        </p>

                        <h3 style="font-size: 1.3rem; margin-bottom: 20px;">Ի՞նչ է Ներառված Փաթեթում</h3>
                        <div class="service-features-grid">
                            @foreach($service->features as $feature)
                                <div style="display: flex; align-items: center; gap: 12px; padding: 14px; background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-emerald)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span style="font-size: 0.95rem; color: #e2e8f0; font-weight: 500;">{{ $feature->text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FAQ related -->
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 40px;">
                        <h3 style="font-size: 1.4rem; margin-bottom: 20px;">Հաճախ Տրվող Հարցեր</h3>
                        <div class="faq-grid" style="max-width: 100%;">
                            @foreach($faqs as $faq)
                                <div class="faq-item">
                                    <button class="faq-question" style="font-size: 1rem; padding: 18px 20px;">
                                        <span>{{ $faq->question }}</span>
                                        <span class="faq-icon">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                                        </span>
                                    </button>
                                    <div class="faq-answer" style="padding: 0 20px 18px;">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- Order Box -->
                    <div style="background: var(--bg-card); border: 1px solid var(--border-active); border-radius: var(--radius-lg); padding: 32px; margin-bottom: 32px;">
                        <h3 style="font-size: 1.3rem; margin-bottom: 12px;">Ստացեք Անվճար Խորհրդատվություն</h3>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px;">
                            Մենք կօգնենք ճիշտ որոշել նախագծի տեխնիկական պահանջները և հաշվարկել վերջնական արժեքը։
                        </p>
                        <button class="btn btn-primary" style="width: 100%;" data-open-modal="orderModal" data-service="{{ $service->title }}">
                            <span>Պատվիրել Այս Ծառայությունը</span>
                        </button>
                    </div>

                    <!-- Other Services -->
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 32px;">
                        <h4 style="font-size: 1.1rem; margin-bottom: 18px;">Այլ Ծառայություններ</h4>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            @foreach($otherServices as $other)
                                <a href="{{ route('services.show', $other->slug) }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                                    <span style="font-size: 0.9rem; color: #fff;">{{ $other->title }}</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
