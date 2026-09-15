@extends('layouts.app')

@section('title', $page->title . ' | eLab Agency')
@section('meta_description', $page->seo_description ?? $page->title)

@section('content')

    <section class="hero-section" style="padding: 70px 0 30px; text-align: left;">
        <div class="container container-narrow">
            <span class="badge" style="margin-bottom: 14px;">Իրավական Տեղեկություն</span>
            <h1 class="hero-title" style="font-size: clamp(2rem, 4vw, 2.8rem); margin-bottom: 16px;">
                {{ $page->title }}
            </h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">
                Վերջին թարմացում՝ {{ $page->updated_at ? $page->updated_at->format('d.m.Y') : date('d.m.Y') }}
            </p>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container container-narrow">
            <div class="legal-content" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 48px 40px; color: #cbd5e1; line-height: 1.8; font-size: 1.05rem;">
                {!! $page->content !!}
            </div>
        </div>
    </section>

@endsection
