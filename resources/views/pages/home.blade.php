@extends('layouts.app')

@section('title', 'eLab Digital Studio | Վեբ Կայքերի Պատրաստում, AI Ավտոմատացում & Մարքեթինգ')
@section('meta_description', 'eLab Digital Studio — Կայքերի նախագծում, պատրաստում (այցեքարտ, լենդինգ, կորպորատիվ, օնլայն խանութ, կայք 0-ից), AI ավտոմատացումներ, SEO, GEO, SMM, PPC և Cloud PBX Երևանում։')

@section('content')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-badge-wrap">
                <span class="badge badge-popular">
                    <span style="color: var(--accent-cyan);">✦</span>
                    eLab Digital Studio • Ողջո՜ւյն 👋
                </span>
            </div>

            <h1 class="hero-title">
                Վեբ Կայքերի Ձևավորումից մինչև <br>
                <span class="text-gradient">Թվային Մարքեթինգ & AI</span>
            </h1>

            <p class="hero-subtitle">
                Մեր փորձառու մասնագետների թիմը պատրաստակամ կերպով կօգնի Ձեզ ստեղծել նորաոճ և արագագործ վեբ կայք, որը Ձեր բիզնեսն ավելի ներկայանալի կդարձնի և կնպաստի վաճառքների աճին։
            </p>

            <div class="hero-actions">
                <a href="#contactFormSection" class="btn btn-primary btn-lg">
                    <span>Սկսել Նախագիծը</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('portfolio.index') }}" class="btn btn-secondary btn-lg">
                    <span>Դիտել Պորտֆոլիոն</span>
                </a>
                <a href="https://wa.me/37455776066" target="_blank" class="btn btn-secondary btn-lg" style="color: #25d366; border-color: rgba(37, 211, 102, 0.4);">
                    <span>WhatsApp</span>
                </a>
            </div>

            <!-- Stats Row -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number text-gradient">50+</div>
                    <div class="stat-label">Հաջողված Նախագիծ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: var(--accent-cyan);">99.4%</div>
                    <div class="stat-label">Գոհ Հաճախորդներ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: var(--accent-violet);">8+</div>
                    <div class="stat-label">Տարվա Փորձառություն</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: var(--accent-emerald);">&lt; 1վ</div>
                    <div class="stat-label">Միջին Բեռնման Արագություն</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section section-alt" id="services">
        <div class="container">
            <div class="section-header">
                <span class="badge">Ի՞նչ Կայքեր և Ծառայություններ ենք Առաջարկում</span>
                <h2 class="section-title">Մեր Հիմնական <span class="text-gradient">Ուղղությունները</span></h2>
                <p class="section-subtitle">Վեբ կայքերի պատրաստում, AI ավտոմատացումներ, մարքեթինգ և Cloud PBX կապ։</p>
            </div>

            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-card">
                        <div class="service-icon-box">
                            @if(str_contains($service->slug, 'card'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            @elseif(str_contains($service->slug, 'landing'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            @elseif(str_contains($service->slug, 'corporate'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            @elseif(str_contains($service->slug, 'shop'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            @elseif(str_contains($service->slug, 'news'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @elseif(str_contains($service->slug, 'scratch') || str_contains($service->slug, 'code'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            @elseif(str_contains($service->slug, 'ai'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            @elseif(str_contains($service->slug, 'marketing'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            @elseif(str_contains($service->slug, 'pbx'))
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            @else
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/></svg>
                            @endif
                        </div>

                        <h3 class="service-card-title">{{ $service->title }}</h3>
                        <div class="service-card-tagline">{{ $service->tagline }}</div>
                        <p class="service-card-desc">{{ $service->description }}</p>

                        @if($service->features->count() > 0)
                            <ul class="service-features-list">
                                @foreach($service->features->take(4) as $feature)
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
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-secondary btn-sm" title="Դիտել Մանրամասն">
                                    <span>Ավելին</span>
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

    <!-- Real Portfolio Section -->
    <section class="section" id="portfolio">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-violet">Մեր Քեյսերը</span>
                <h2 class="section-title">Մեր <span class="text-gradient">Աշխատանքներից</span></h2>
                <p class="section-subtitle">Ծանոթացեք eLab Digital Studio-ի կողմից մշակված իրական նախագծերին Հայաստանում և ԱՄՆ-ում։</p>
            </div>

            <!-- Category Filter Bar -->
            <div class="portfolio-filter-bar">
                <button class="filter-btn active" data-filter="all">Բոլորը</button>
                @foreach($portfolioCategories as $cat)
                    <button class="filter-btn" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
                @endforeach
            </div>

            <div class="portfolio-grid">
                @foreach($portfolioProjects as $project)
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
                                @if(!empty($project->live_url))
                                    <span class="portfolio-tag" style="color: var(--accent-emerald);">● {{ parse_url($project->live_url, PHP_URL_HOST) }}</span>
                                @endif
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

                            <div class="portfolio-footer" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                                <span class="client-name">Հաճախորդ՝ {{ $project->client }} ({{ $project->year }})</span>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    @if(!empty($project->live_url))
                                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="border-color: var(--accent-cyan); color: var(--accent-cyan);" title="Այցելել Կայքը">
                                            <span>Այցելել Կայքը ↗</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('portfolio.show', $project->slug) }}" class="btn btn-secondary btn-sm">
                                        <span>Տեսնել ավելին</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 48px;">
                <a href="{{ route('portfolio.index') }}" class="btn btn-secondary btn-lg">
                    <span>Դիտել Բոլոր Քեյսերը ({{ $portfolioProjects->count() }})</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Procedure & Methodology (Directly from PDF Slide 10) -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <span class="badge">Ընթացակարգ</span>
                <h2 class="section-title">Կայքի Ստեղծման <span class="text-gradient">Փուլերը</span></h2>
                <p class="section-subtitle">Թափանցիկ, հստակ և արդյունավետ ընթացակարգ յուրաքանչյուր նախագծի համար։</p>
            </div>

            <div class="process-grid process-grid-steps">
                <div class="process-card">
                    <div class="process-num">01</div>
                    <h3 class="process-title">Դիզայնի Ընտրություն</h3>
                    <p class="process-desc">Առաջին փուլով ընտրում և համաձայնեցնում ենք կայքի ոճը, UI/UX դիզայնը և տեսողական կառուցվածքը։</p>
                </div>
                <div class="process-card">
                    <div class="process-num">02</div>
                    <h3 class="process-title">Կայքի Ստեղծում</h3>
                    <p class="process-desc">Երկրորդ փուլով ստեղծում ենք կայքը նախապես տրամադրված քոնթենթով և ընտրված ֆունկցիոնալով (Clean Code & Laravel)։</p>
                </div>
                <div class="process-card">
                    <div class="process-num">03</div>
                    <h3 class="process-title">Կայքի Թողարկում</h3>
                    <p class="process-desc">Երրորդ փուլով կայքը տեղադրում ենք հոսթինգում, միացնում դոմեյնը, կարգավորում SSL-ը և հանձնում շահագործման։</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Temporarily hidden Testimonials Section
    <section class="section" id="testimonials">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-popular">Գնահատականներ</span>
                <h2 class="section-title">Ի՞նչ են Ասում Մեր <span class="text-gradient">Գործընկերները</span></h2>
                <p class="section-subtitle">Մեր նվիրված աշխատանքի լավագույն ապացույցը մեր գործընկերների հաջողությունն է։</p>
            </div>

            <div class="testimonial-wrapper">
                @foreach($testimonials as $idx => $item)
                    <div class="testimonial-card {{ $idx === 0 ? 'active' : '' }}">
                        <div class="testimonial-stars">
                            @for($i = 0; $i < $item->rating; $i++)
                                ★
                            @endfor
                        </div>
                        <p class="testimonial-text">«{{ $item->content }}»</p>
                        <div class="testimonial-author-row">
                            <div class="author-avatar">
                                <img src="{{ asset($item->photo ?? 'assets/images/avatar-1.svg') }}" alt="{{ $item->name }}">
                            </div>
                            <div>
                                <div class="author-name">{{ $item->name }}</div>
                                <div class="author-role">{{ $item->position }} • {{ $item->company }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="testimonial-controls">
                    <button class="carousel-btn" id="testimPrev" aria-label="Նախորդ">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="carousel-btn" id="testimNext" aria-label="Հաջորդ">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
    --}}

    <!-- FAQ Accordion -->
    <section class="section section-alt" id="faq">
        <div class="container">
            <div class="section-header">
                <span class="badge">ՀՏՀ</span>
                <h2 class="section-title">Հաճախ Տրվող <span class="text-gradient">Հարցեր</span></h2>
                <p class="section-subtitle">Գտեք պատասխաններ կայքի պատրաստման, գների, ժամկետների և տեխնոլոգիաների վերաբերյալ։</p>
            </div>

            <div class="faq-grid">
                @foreach($faqs as $idx => $faq)
                    <div class="faq-item {{ $idx === 0 ? 'active' : '' }}">
                        <button class="faq-question">
                            <span>{{ $faq->question }}</span>
                            <span class="faq-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact & Lead Form Section -->
    <section class="section" id="contactFormSection">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-popular">Հետադարձ Կապ</span>
                <h2 class="section-title">Քննարկենք Ձեր <span class="text-gradient">Նախագիծը</span></h2>
                <p class="section-subtitle">Ուղարկեք Ձեր հարցումը, կամ կապվեք ուղիղ WhatsApp-ով և Telegram-ով։</p>
            </div>

            <div class="contact-section-wrap">
                <!-- Info Left -->
                <div class="contact-info-panel">
                    <span class="badge" style="margin-bottom: 16px;">Ուղիղ Կապ</span>
                    <h3 style="font-size: 1.8rem; margin-bottom: 8px;">Ավետիս Գևորգյան</h3>
                    <div style="color: var(--accent-cyan); font-weight: 600; margin-bottom: 16px;">CEO & Հիմնադիր</div>
                    <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">
                        Վստահե՛ք Ձեր թվային բիզնեսը պրոֆեսիոնալ և փորձառու մասնագետների։ Մենք պատրաստ ենք պատասխանել Ձեր բոլոր հարցերին։
                    </p>

                    <ul class="contact-meta-list">
                        <li class="contact-meta-item">
                            <div class="contact-meta-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <div class="contact-meta-title">Հեռախոսահամար</div>
                                <a href="tel:+37455776066" class="contact-meta-val">+374 55 77 60 66</a>
                            </div>
                        </li>

                        <li class="contact-meta-item">
                            <div class="contact-meta-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <div class="contact-meta-title">Էլ. Փոստ</div>
                                <a href="mailto:hello@elab.am" class="contact-meta-val">hello@elab.am</a>
                            </div>
                        </li>

                        <li class="contact-meta-item">
                            <div class="contact-meta-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <div>
                                <div class="contact-meta-title">Վեբ Կայք</div>
                                <div class="contact-meta-val">www.elab.am</div>
                            </div>
                        </li>
                    </ul>

                    <div style="display: flex; gap: 12px; margin-top: 20px;">
                        <a href="https://wa.me/37455776066" target="_blank" class="btn btn-primary" style="background: #25d366; flex: 1;">
                            <span>WhatsApp</span>
                        </a>
                        <a href="https://t.me/+37455776066" target="_blank" class="btn btn-primary" style="background: #0284c7; flex: 1;">
                            <span>Telegram</span>
                        </a>
                    </div>
                </div>

                <!-- Form Right -->
                <div class="form-card">
                    <form action="{{ route('contact.submit') }}" method="POST" data-ajax="true">
                        @csrf
                        <input type="text" name="website_hp" class="hp-field" tabindex="-1" autocomplete="off">
                        <div class="form-response-alert" style="display: none;"></div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Անուն Ազգանուն *</label>
                                <input type="text" name="name" class="form-control" placeholder="Արմեն Պետրոսյան" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Հեռախոսահամար *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+374 55 77 60 66" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Էլ. Փոստ *</label>
                                <input type="email" name="email" class="form-control" placeholder="name@company.am" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ընկերություն</label>
                                <input type="text" name="company" class="form-control" placeholder="Ընկերության անվանում">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ծառայության Տեսակ</label>
                                <select name="project_type" class="form-control">
                                    <option value="Կայք այցեքարտ (150,000 դր)">Կայք այցեքարտ (150,000 դր)</option>
                                    <option value="Լենդինգ էջ (190,000 դր)">Լենդինգ էջ (190,000 դր)</option>
                                    <option value="Կորպորատիվ կայք (290,000 դր)">Կորպորատիվ կայք (290,000 դր)</option>
                                    <option value="Օնլայն խանութ (350,000 դր)">Օնլայն խանութ (350,000 դր)</option>
                                    <option value="Նորությունների կայք (450,000 դր)">Նորությունների կայք (450,000 դր)</option>
                                    <option value="Կայք 0-ից & Անհատական Ծրագրեր">Կայք 0-ից & Անհատական Ծրագրեր</option>
                                    <option value="AI Ավտոմատացումներ">AI Ավտոմատացումներ (Նոր ճյուղ)</option>
                                    <option value="Մարքեթինգային Ծառայություններ">Մարքեթինգ (SEO, GEO, SMM, PPC)</option>
                                    <option value="Cloud PBX Ծառայություններ">Cloud PBX Հեռախոսակապ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Նախատեսվող Բյուջե</label>
                                <select name="budget" class="form-control">
                                    <option value="150,000 - 300,000 դր">150,000 - 300,000 դր</option>
                                    <option value="300,000 - 500,000 դր">300,000 - 500,000 դր</option>
                                    <option value="500,000 - 1,000,000 դր">500,000 - 1,000,000 դր</option>
                                    <option value="1,000,000+ դր">1,000,000+ դր</option>
                                    <option value="Պայմանագրային">Պայմանագրային</option>
                                </select>
                            </div>
                            <div class="form-group form-group-full">
                                <label class="form-label">Նախագծի Մանրամասները *</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Ի՞նչ կայք կամ համակարգ է Ձեզ անհրաժեշտ, ինչպիսի՞ հնարավորություններ եք ցանկանում..." required></textarea>
                            </div>
                            <div class="form-group form-group-full">
                                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                    <span>Ուղարկել Հարցումը</span>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="cta-banner">
                <h2 class="cta-title">Պատվիրե՛ք Ձեր Կայքը Պրոֆեսիոնալ Թիմից</h2>
                <p class="cta-subtitle">Միացեք eLab Digital Studio-ի գոհունակ գործընկերներին և բարձրացրեք Ձեր բիզնեսի վաճառքները։</p>
                <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                    <a href="#contactFormSection" class="btn btn-primary btn-lg">
                        <span>Պատվիրել Խորհրդատվություն</span>
                    </a>
                    <a href="tel:+37455776066" class="btn btn-secondary btn-lg">
                        <span>Զանգահարել՝ +374 55 77 60 66</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
