@extends('layouts.app')

@section('title', 'Պորտֆոլիո | eLab Agency - Իրականացված Նախագծեր')
@section('meta_description', 'Ծանոթացեք eLab Agency-ի կողմից մշակված կայքերին, օնլայն խանութներին և անհատական վեբ համակարգերին։')

@section('content')

    <!-- Page Header -->
    <section class="hero-section" style="padding: 70px 0 40px;">
        <div class="container">
            <span class="badge badge-violet" style="margin-bottom: 16px;">Մեր Աշխատանքները</span>
            <h1 class="hero-title" style="font-size: 3.2rem;">
                eLab Agency-ի <span class="text-gradient">Պորտֆոլիոն</span>
            </h1>
            <p class="hero-subtitle">
                Յուրաքանչյուր նախագիծ մեր նվիրվածության, բարձրակարգ ինժեներիայի և աճի վրա հիմնված դիզայնի արդյունք է։
            </p>

            <!-- Filter Buttons -->
            <div class="portfolio-filter-bar">
                <button class="filter-btn active" data-filter="all">Բոլորը</button>
                @foreach($categories as $cat)
                    <button class="filter-btn" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="portfolio-grid">
                @foreach($projects as $project)
                    @php
                        $catSlugs = $project->categories->pluck('slug')->implode(' ');
                    @endphp
                    <div class="portfolio-card" data-category="{{ $catSlugs }}">
                        <div class="portfolio-media-wrap">
                            <img src="{{ asset($project->hero_image ?? 'assets/images/portfolio-gourmet.svg') }}" alt="{{ $project->title }}" loading="lazy">
                        </div>

                        <div class="portfolio-body">
                            <div class="portfolio-tags">
                                @foreach($project->categories as $cat)
                                    <span class="portfolio-tag" style="background: rgba(6, 182, 212, 0.1); color: var(--accent-cyan);">{{ $cat->name }}</span>
                                @endforeach
                                @foreach($project->technologies->take(3) as $tech)
                                    <span class="portfolio-tag">{{ $tech->name }}</span>
                                @endforeach
                            </div>

                            <h3 class="portfolio-title">{{ $project->title }}</h3>
                            <p class="portfolio-summary">{{ $project->summary }}</p>

                            @if(!empty($project->results) && is_array($project->results))
                                <div class="portfolio-metrics">
                                    @foreach($project->results as $result)
                                        <div class="portfolio-metric-item">★ {{ $result }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="portfolio-footer">
                                <span class="client-name">Հաճախորդ՝ {{ $project->client }} ({{ $project->year }})</span>
                                <div style="display: flex; gap: 8px;">
                                    @if(!empty($project->live_url))
                                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" title="Բացել Կայքը">
                                            <span>Կայք ↗</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('portfolio.show', $project->slug) }}" class="btn btn-secondary btn-sm">
                                        <span>Քեյս</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
