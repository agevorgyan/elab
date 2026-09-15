@extends('layouts.app')

@section('title', 'Կոնտակտներ | eLab Digital Studio')
@section('meta_description', 'Կապվեք eLab Digital Studio-ի հետ Երևանում։ Հեռախոս՝ +374 55 77 60 66 (WhatsApp, Telegram), Էլ. փոստ՝ hello@elab.am, CEO՝ Ավետիս Գևորգյան։')

@section('content')

    <!-- Hero -->
    <section class="hero-section" style="padding: 70px 0 40px;">
        <div class="container">
            <span class="badge" style="margin-bottom: 16px;">Հետադարձ Կապ</span>
            <h1 class="hero-title" style="font-size: 3.2rem;">
                Կապվեք <span class="text-gradient">eLab Digital Studio-ի</span> Հետ
            </h1>
            <p class="hero-subtitle">
                Ցանկանո՞ւմ եք պատվիրել նոր կայք, ներդնել AI ավտոմատացումներ կամ միացնել Cloud PBX հեռախոսակապ։ Մենք պատրաստ ենք աջակցել։
            </p>
        </div>
    </section>

    <!-- Main Contact Form & Info -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="contact-section-wrap">
                <!-- Info Left -->
                <div class="contact-info-panel">
                    <span class="badge" style="margin-bottom: 16px;">Ղեկավարություն</span>
                    <h2 style="font-size: 1.8rem; margin-bottom: 4px;">Ավետիս Գևորգյան</h2>
                    <div style="color: var(--accent-cyan); font-weight: 600; margin-bottom: 18px;">CEO & Հիմնադիր</div>

                    <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">
                        Վստահե՛ք Ձեր թվային բիզնեսը պրոֆեսիոնալ և փորձառու մասնագետների։ Գրեք կամ զանգահարեք Ձեզ հարմար ցանկացած եղանակով։
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

                    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
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
                                <input type="text" name="name" class="form-control" placeholder="Ձեր անունը" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Հեռախոսահամար *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+374 55 77 60 66" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Էլ. Փոստ *</label>
                                <input type="email" name="email" class="form-control" placeholder="name@domain.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ընկերություն</label>
                                <input type="text" name="company" class="form-control" placeholder="Ձեր ընկերությունը">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ծառայության Ուղղություն</label>
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
                                <label class="form-label">Նախագծի Նկարագրություն *</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Պատմեք Ձեր նախագծի մասին..." required></textarea>
                            </div>
                            <div class="form-group form-group-full">
                                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                    <span>Ուղարկել Հաղորդագրությունը</span>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
