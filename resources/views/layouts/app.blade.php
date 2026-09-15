<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'eLab Digital Studio | Վեբ Կայքերի Պատրաստում, AI Ավտոմատացում & Մարքեթինգ')</title>
    <meta name="description" content="@yield('meta_description', 'eLab Digital Studio — Վեբ կայքերի պատրաստում (այցեքարտ, լենդինգ, կորպորատիվ, օնլայն խանութ, կայք 0-ից), AI ավտոմատացումներ, SEO, GEO, SMM, PPC և Cloud PBX Երևանում։')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon with User's Official Flask Icon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-icon.png') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'eLab Digital Studio | Թվային Լուծումներ Երևանում')">
    <meta property="og:description" content="@yield('meta_description', 'eLab Digital Studio — Կայքերի նախագծում, պատրաստում, սպասարկում, AI ավտոմատացումներ և մարքեթինգ։')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/images/logo-full.png') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css?v=2.0') }}">
    @yield('styles')
</head>
<body>

    <!-- Top Announcement / Quick Contact Bar -->
    <div class="top-announcement-bar" style="background: rgba(13, 19, 31, 0.95); border-bottom: 1px solid var(--border-subtle); padding: 7px 0; font-size: 0.84rem;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 16px; color: var(--text-secondary);">
                <span>📍 Երևան, Հայաստան</span>
                <span style="display: inline-flex; align-items: center; gap: 6px; color: #fff; font-weight: 500;">
                    📞 <a href="tel:+37455776066" style="color: #fff;">+374 55 77 60 66</a>
                </span>
                <span style="color: var(--accent-emerald);">● Ընդունվում են նոր պատվերներ</span>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="https://wa.me/37455776066" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 5px; color: #25d366; font-weight: 600; font-size: 0.82rem;">
                    <span>WhatsApp</span>
                </a>
                <span style="color: var(--border-subtle);">|</span>
                <a href="https://t.me/+37455776066" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 5px; color: #38bdf8; font-weight: 600; font-size: 0.82rem;">
                    <span>Telegram</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Header Navigation -->
    <header class="site-header">
        <div class="container">
            <div class="nav-wrap">
                <a href="{{ route('home') }}" class="brand-logo" style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset('assets/images/logo-white.png') }}" alt="eLab Digital Studio" class="brand-logo-img">
                </a>

                <ul class="nav-links" id="navLinks">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Գլխավոր</a></li>
                    <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Ծառայություններ</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="{{ request()->routeIs('portfolio.*') ? 'active' : '' }}">Պորտֆոլիո (Քեյսեր)</a></li>
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Մեր Մասին</a></li>
                    <li><a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Կոնտակտներ</a></li>
                </ul>

                <div class="nav-actions">
                    <a href="#contactFormSection" class="btn btn-primary btn-sm" data-open-modal="orderModal" data-service="Կայքերի Պատրաստում">
                        <span>Պատվիրել Նախագիծ</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>

                    <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main>
        @yield('content')
    </main>

    <!-- Quick Order Modal -->
    <div class="modal-backdrop" id="orderModal">
        <div class="modal-window">
            <button class="modal-close-btn" id="orderModalClose" aria-label="Փակել">&times;</button>
            <div class="modal-header" style="margin-bottom: 24px;">
                <span class="badge" style="margin-bottom: 8px;">Արագ Հարցում</span>
                <h3 style="font-size: 1.6rem;">Պատվիրել eLab Ծառայություն</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 6px;">Լրացրեք կարճ տեղեկատվություն, և մեր առաջատար մասնագետը կկապվի Ձեզ հետ։</p>
            </div>

            <form action="{{ route('contact.submit') }}" method="POST" data-ajax="true">
                @csrf
                <!-- Honeypot -->
                <input type="text" name="website_hp" class="hp-field" tabindex="-1" autocomplete="off">

                <div class="form-response-alert" style="display: none;"></div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Անուն Ազգանուն *</label>
                    <input type="text" name="name" class="form-control" placeholder="Օր.՝ Արամ Պետրոսյան" required>
                </div>

                <div class="form-grid" style="margin-bottom: 14px;">
                    <div class="form-group">
                        <label class="form-label">Հեռախոսահամար *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+374 55 77 60 66" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Էլ. Փոստ *</label>
                        <input type="email" name="email" class="form-control" placeholder="aram@example.com" required>
                    </div>
                </div>

                <div class="form-grid" style="margin-bottom: 14px;">
                    <div class="form-group">
                        <label class="form-label">Ծառայության Ուղղություն</label>
                        <select name="project_type" class="form-control">
                            <optgroup label="WEB Ծառայություններ (Հիմնական)">
                                <option value="Կայք այցեքարտ (150,000 դր)">Կայք այցեքարտ (սկսած 150,000 դր)</option>
                                <option value="Լենդինգ էջ (190,000 դր)">Լենդինգ էջ (սկսած 190,000 դր)</option>
                                <option value="Կորպորատիվ կայք (290,000 դր)">Կորպորատիվ կայք (սկսած 290,000 դր)</option>
                                <option value="Օնլայն խանութ (350,000 դր)">Օնլայն խանութ (սկսած 350,000 դր)</option>
                                <option value="Նորությունների կայք (450,000 դր)">Նորությունների կայք (սկսած 450,000 դր)</option>
                                <option value="Կայք 0-ից & Անհատական Ծրագրեր">Կայք 0-ից & Անհատական Ծրագրեր (Պայմանագրային)</option>
                                <option value="Դիզայն UI/UX">Դիզայն UI/UX</option>
                            </optgroup>
                            <optgroup label="AI Ավտոմատացումներ (Նոր ճյուղ)">
                                <option value="AI Ավտոմատացումներ">AI Բիզնես Ավտոմատացում & Չաթբոտեր</option>
                            </optgroup>
                            <optgroup label="Մարքեթինգային Ծառայություններ">
                                <option value="SEO / GEO Օպտիմիզացիա">SEO & GEO (Google Maps)</option>
                                <option value="SMM & PPC Գովազդ">SMM & PPC Գովազդ (Meta, Google)</option>
                                <option value="Բրենդինգ & Դիզայն">Բրենդինգ & Ֆիրմային Ոճ</option>
                            </optgroup>
                            <optgroup label="Հեռահաղորդակցություն">
                                <option value="Cloud PBX Ծառայություններ">Cloud PBX Ամպային Հեռախոսակապ</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Նախնական Բյուջե</label>
                        <select name="budget" class="form-control">
                            <option value="150,000 - 300,000 դր">150,000 - 300,000 դր</option>
                            <option value="300,000 - 500,000 դր">300,000 - 500,000 դր</option>
                            <option value="500,000 - 1,000,000 դր">500,000 - 1,000,000 դր</option>
                            <option value="1,000,000+ դր">1,000,000+ դր</option>
                            <option value="Պայմանագրային">Պայմանագրային</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Նախագծի Նկարագրություն *</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Հակիրճ նկարագրեք Ձեր նպատակը կամ պահանջները..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <span>Ուղարկել Հարցումը</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand-col">
                    <a href="{{ route('home') }}" class="brand-logo" style="margin-bottom: 16px; display: inline-block;">
                        <img src="{{ asset('assets/images/logo-white.png') }}" alt="eLab Digital Studio" style="height: 80px; width: auto; object-fit: contain;">
                    </a>
                    <p class="footer-desc">
                        Վեբ կայքերի ձևավորումից մինչև թվային մարքեթինգ, AI ավտոմատացումներ և Cloud PBX հեռախոսակապ Երևանում։
                    </p>

                    <div style="margin-bottom: 16px; font-size: 0.9rem; color: #fff;">
                        <strong style="color: var(--accent-cyan);">CEO:</strong> Ավետիս Գևորգյան
                    </div>

                    <div class="social-links">
                        <a href="https://www.facebook.com/elab.am/" target="_blank" rel="noopener" class="social-btn" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/elab.armenia/" target="_blank" rel="noopener" class="social-btn" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="https://am.linkedin.com/company/elab-armenia" target="_blank" rel="noopener" class="social-btn" aria-label="LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                        <a href="https://wa.me/37455776066" target="_blank" rel="noopener" class="social-btn" aria-label="WhatsApp" style="color: #25d366;">
                            <span style="font-weight: bold; font-size: 0.9rem;">WA</span>
                        </a>
                        <a href="https://t.me/+37455776066" target="_blank" rel="noopener" class="social-btn" aria-label="Telegram" style="color: #38bdf8;">
                            <span style="font-weight: bold; font-size: 0.9rem;">TG</span>
                        </a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">WEB Ծառայություններ</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('services.show', 'business-card-website') }}">Կայք այցեքարտ</a></li>
                        <li><a href="{{ route('services.show', 'landing-page') }}">Լենդինգ էջ</a></li>
                        <li><a href="{{ route('services.show', 'corporate-website') }}">Կորպորատիվ կայք</a></li>
                        <li><a href="{{ route('services.show', 'online-shop') }}">Օնլայն խանութ</a></li>
                        <li><a href="{{ route('services.show', 'news-portal') }}">Նորությունների կայք</a></li>
                        <li><a href="{{ route('services.show', 'custom-web-from-scratch') }}">Կայք 0-ից & SaaS</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">Այլ Ուղղություններ</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('services.show', 'ai-automations') }}">🤖 AI Ավտոմատացումներ</a></li>
                        <li><a href="{{ route('services.show', 'marketing-seo-geo-smm-ppc') }}">📈 Մարքեթինգ (SEO, GEO, SMM, PPC)</a></li>
                        <li><a href="{{ route('services.show', 'ui-ux-design') }}">🎨 Դիզայն UI/UX & Բրենդինգ</a></li>
                        <li><a href="{{ route('services.show', 'cloud-pbx-telephony') }}">📞 Cloud PBX Հեռախոսակապ</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">Կոնտակտներ</h4>
                    <ul class="footer-links">
                        <li style="color: var(--text-secondary);">Հասցե՝ Երևան, Հայաստան</li>
                        <li><a href="tel:+37455776066" style="color: #fff; font-weight: 600;">+374 55 77 60 66</a></li>
                        <li><a href="mailto:hello@elab.am">hello@elab.am</a></li>
                        <li><a href="https://www.elab.am">www.elab.am</a></li>
                        <li style="display: flex; gap: 10px; margin-top: 8px;">
                            <a href="https://wa.me/37455776066" target="_blank" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.8rem; color: #25d366;">WhatsApp</a>
                            <a href="https://t.me/+37455776066" target="_blank" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.8rem; color: #38bdf8;">Telegram</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} <strong>eLab Digital Studio</strong> (www.elab.am). Բոլոր իրավունքները պաշտպանված են։
                </div>
                <div class="footer-legal-links">
                    <a href="{{ route('legal.show', 'privacy-policy') }}">Գաղտնիության Քաղաքականություն</a>
                    <a href="{{ route('legal.show', 'terms-of-service') }}">Օգտագործման Պայմաններ</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
