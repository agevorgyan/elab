@extends('layouts.app')

@section('title', 'Մեր Մասին | eLab Digital Studio')
@section('meta_description', 'Ծանոթացեք eLab Digital Studio-ի առաքելությանը, արժեքներին, թիմին և տեխնոլոգիական հնարավորություններին։')

@section('content')

    <!-- Hero -->
    <section class="hero-section" style="padding: 70px 0 50px;">
        <div class="container">
            <span class="badge" style="margin-bottom: 16px;">Մեր Պատմությունը</span>
            <h1 class="hero-title" style="font-size: 3.2rem;">
                Թվային Բիզնեսի Ձեր <br><span class="text-gradient">Հուսալի Գործընկերը</span>
            </h1>
            <p class="hero-subtitle">
                eLab Digital Studio-ն Երևանում գործող վեբ մշակման, թվային մարքեթինգի, AI ավտոմատացումների և հեռահաղորդակցության գործակալություն է։
            </p>

        </div>
    </section>

    <!-- Leadership / CEO Message -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div style="background: var(--bg-card); border: 1px solid var(--border-active); border-radius: var(--radius-lg); padding: 48px; display: grid; grid-template-columns: 1fr 2fr; gap: 40px; align-items: center; margin-bottom: 60px;">
                <div style="text-align: center;">
                    <div style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--accent-cyan); overflow: hidden; margin: 0 auto 16px; background: #1e293b;">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Ավետիս Գևորգյան" style="width: 100%; height: 100%; object-fit: contain; padding: 10px;">
                    </div>
                    <h3 style="font-size: 1.4rem; color: #fff;">Ավետիս Գևորգյան</h3>
                    <div style="color: var(--accent-cyan); font-weight: 600; font-size: 0.95rem;">CEO & Հիմնադիր</div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 4px;">eLab Digital Studio</div>
                </div>

                <div>
                    <span class="badge" style="margin-bottom: 12px;">Ղեկավարի Ուղերձը</span>
                    <h2 style="font-size: 1.6rem; margin-bottom: 14px;">«Մենք չենք ստեղծում պարզապես կայքեր, մենք կառուցում ենք վաճառքի հզոր գործիքներ»</h2>
                    <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                        «Մեր փորձառու մասնագետների թիմը պատրաստակամ կերպով կօգնի Ձեզ ստեղծել նորաոճ և արագագործ վեբ կայք, որը Ձեր բիզնեսն ավելի ներկայանալի կդարձնի և կնպաստի վաճառքների աճին։ Վստահե՛ք Ձեր թվային բիզնեսը պրոֆեսիոնալ և փորձառու մասնագետների»։
                    </p>
                </div>
            </div>

            <!-- 4 Main Pillars -->
            <div class="section-header">
                <span class="badge">Մեր 4 Ուղղությունները</span>
                <h2 class="section-title">Համալիր <span class="text-gradient">Թվային Լուծումներ</span></h2>
                <p class="section-subtitle">Ամեն ինչ մեկ տեղում Ձեր բիզնեսի հաջող մեկնարկի և մասշտաբավորման համար։</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
                <div class="service-card" style="padding: 28px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">🌐</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 8px;">WEB Ծառայություններ</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        UI/UX դիզայն, կայքերի նախագծում, պատրաստում և սպասարկում, անհատական ծրագրեր (SaaS/ERP/CRM)։
                    </p>
                </div>

                <div class="service-card" style="padding: 28px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">🤖</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 8px;">AI Ավտոմատացումներ</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        Նոր ճյուղ՝ բիզնես գործընթացների ավտոմատացում, AI չաթբոտեր WhatsApp/Telegram-ում, վաճառքի AI ագենտներ։
                    </p>
                </div>

                <div class="service-card" style="padding: 28px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">📈</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 8px;">Մարքեթինգ</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        SEO որոնողական օպտիմիզացիա, GEO տեղական որոնում Google Maps-ում, SMM էջերի վարում, PPC գովազդ։
                    </p>
                </div>

                <div class="service-card" style="padding: 28px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">📞</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 8px;">Cloud PBX Կապ</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        Ամպային IP հեռախոսակապ բիզնեսի համար, զանգերի ձայնագրում, IVR ավտոքարտուղար, CRM ինտեգրացիա։
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection
