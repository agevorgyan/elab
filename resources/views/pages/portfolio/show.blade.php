@extends('layouts.app')

@section('title', $project->title . ' - Case Study | eLab Agency')
@section('meta_description', $project->summary)

@section('content')

    <!-- Project Header -->
    <section class="hero-section" style="padding: 60px 0 30px; text-align: left;">
        <div class="container">
            <div style="margin-bottom: 16px;">
                <a href="{{ route('portfolio.index') }}" style="color: var(--accent-cyan); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Վերադառնալ Պորտֆոլիո</span>
                </a>
            </div>

            <div class="portfolio-tags" style="margin-bottom: 16px;">
                @foreach($project->categories as $cat)
                    <span class="badge">{{ $cat->name }}</span>
                @endforeach
                <span class="badge badge-violet">{{ $project->year }}</span>
            </div>

            <h1 class="hero-title" style="margin-bottom: 20px;">
                {{ $project->title }}
            </h1>

            <p style="color: var(--text-secondary); font-size: 1.15rem; max-width: 800px; line-height: 1.6;">
                {{ $project->summary }}
            </p>
        </div>
    </section>

    <!-- Main Project Visual -->
    <section style="padding: 20px 0 60px;">
        <div class="container">
            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-lg); background: #0b111e; aspect-ratio: 16/9; max-height: 540px;">
                <img src="{{ asset($project->hero_image ?? 'assets/images/portfolio-gourmet.svg') }}" alt="{{ $project->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </section>

    <!-- Case Study Details -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="portfolio-details-grid">
                <!-- Main Body -->
                <div>
                    <!-- Challenge -->
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: clamp(20px, 4vw, 36px); margin-bottom: 28px;">
                        <span class="badge badge-popular" style="margin-bottom: 12px;">Խնդիրը</span>
                        <h2 style="font-size: 1.5rem; margin-bottom: 14px;">Բիզնեսի Մարտահրավերը</h2>
                        <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.7;">
                            {{ $project->challenge }}
                        </p>
                    </div>

                    <!-- Solution -->
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: clamp(20px, 4vw, 36px); margin-bottom: 28px;">
                        <span class="badge" style="margin-bottom: 12px;">Լուծումը</span>
                        <h2 style="font-size: 1.5rem; margin-bottom: 14px;">eLab Agency-ի Տեխնիկական Լուծումը</h2>
                        <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.7;">
                            {{ $project->solution }}
                        </p>
                    </div>

                    <!-- Results Metrics -->
                    @if(!empty($project->results) && is_array($project->results))
                        <div style="background: var(--bg-card); border: 1px solid var(--border-active); border-radius: var(--radius-lg); padding: clamp(20px, 4vw, 36px);">
                            <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--accent-emerald); border-color: rgba(16, 185, 129, 0.3); margin-bottom: 12px;">Արդյունքներ</span>
                            <h2 style="font-size: 1.5rem; margin-bottom: 20px;">Չափելի Ձեռքբերումներ</h2>
                            <div style="display: grid; grid-template-columns: 1fr; gap: 14px;">
                                @foreach($project->results as $result)
                                    <div style="padding: 16px; background: rgba(0, 0, 0, 0.3); border-radius: var(--radius-sm); border-left: 3px solid var(--accent-cyan); display: flex; align-items: center; gap: 12px;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-emerald)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span style="font-size: 1.05rem; color: #fff; font-weight: 500;">{{ $result }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Project Sidebar Meta -->
                <div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: clamp(20px, 4vw, 32px); margin-bottom: 28px;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 20px;">Նախագծի Տեղեկություն</h3>

                        <div style="margin-bottom: 18px;">
                            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Հաճախորդ</div>
                            <div style="font-size: 1rem; color: #fff; font-weight: 600;">{{ $project->client }}</div>
                        </div>

                        <div style="margin-bottom: 18px;">
                            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Տարեթիվ</div>
                            <div style="font-size: 1rem; color: #fff; font-weight: 600;">{{ $project->year }}</div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Օգտագործված Տեխնոլոգիաներ</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach($project->technologies as $tech)
                                    <span class="portfolio-tag" style="background: rgba(255, 255, 255, 0.08); color: #fff;">{{ $tech->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        @if(!empty($project->live_url))
                            <div style="margin-bottom: 20px;">
                                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-secondary" style="width: 100%; border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                    <span>Այցելել Կայք ({{ parse_url($project->live_url, PHP_URL_HOST) }}) ↗</span>
                                </a>
                            </div>
                        @endif

                        <button class="btn btn-primary" style="width: 100%;" data-open-modal="orderModal" data-service="Web Development">
                            <span>Պատվիրել Նմանատիպ Կայք</span>
                        </button>
                    </div>

                    <!-- Related Projects -->
                    @if($relatedProjects->count() > 0)
                        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 32px;">
                            <h4 style="font-size: 1.1rem; margin-bottom: 18px;">Այլ Նախագծեր</h4>
                            <div style="display: flex; flex-direction: column; gap: 14px;">
                                @foreach($relatedProjects as $rel)
                                    <a href="{{ route('portfolio.show', $rel->slug) }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                                        <span style="font-size: 0.9rem; color: #fff;">{{ $rel->title }}</span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
