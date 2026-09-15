<?php

namespace Database\Seeders;

use App\Models\FAQ;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\PortfolioTechnology;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\SiteSettings;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ElabAgencySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Site Settings Update with exact info from User
        $settings = [
            ['key' => 'siteName', 'value' => 'eLab Digital Studio', 'category' => 'branding'],
            ['key' => 'siteDescription', 'value' => 'Վեբ կայքերի ձևավորումից մինչև թվային մարքեթինգ և AI ավտոմատացումներ։ Մեր փորձառու թիմը կօգնի ստեղծել նորաոճ և արագագործ վեբ կայք։', 'category' => 'branding'],
            ['key' => 'logoUrl', 'value' => '/assets/images/logo-full.png', 'category' => 'branding'],
            ['key' => 'phone', 'value' => '+374 55 77 60 66', 'category' => 'contact'],
            ['key' => 'whatsapp', 'value' => 'https://wa.me/37455776066', 'category' => 'contact'],
            ['key' => 'telegram', 'value' => 'https://t.me/+37455776066', 'category' => 'contact'],
            ['key' => 'email', 'value' => 'hello@elab.am', 'category' => 'contact'],
            ['key' => 'website', 'value' => 'www.elab.am', 'category' => 'contact'],
            ['key' => 'address', 'value' => 'Երևան, Հայաստան', 'category' => 'contact'],
            ['key' => 'ceo', 'value' => 'Ավետիս Գևորգյան', 'category' => 'team'],
            ['key' => 'facebook', 'value' => 'https://www.facebook.com/elab.am/', 'category' => 'social'],
            ['key' => 'instagram', 'value' => 'https://www.instagram.com/elab.armenia/', 'category' => 'social'],
            ['key' => 'linkedin', 'value' => 'https://am.linkedin.com/company/elab-armenia', 'category' => 'social'],
            ['key' => 'presentationPdf', 'value' => '/assets/docs/elab-proposal.pdf', 'category' => 'downloads'],
        ];

        foreach ($settings as $item) {
            $setting = SiteSettings::where('key', $item['key'])->first();
            if (!$setting) {
                $setting = new SiteSettings();
                $setting->id = (string) Str::uuid();
                $setting->key = $item['key'];
            }
            $setting->value = $item['value'];
            $setting->category = $item['category'];
            $setting->save();
        }

        // 2. Portfolio & Service Categories
        $categories = [
            ['name' => 'WEB Ծառայություններ', 'slug' => 'web-services'],
            ['name' => 'AI Ավտոմատացումներ', 'slug' => 'ai-automation'],
            ['name' => 'Մարքեթինգ (SEO/SMM/PPC)', 'slug' => 'marketing'],
            ['name' => 'Cloud PBX Կապ', 'slug' => 'cloud-pbx'],
            ['name' => 'Օնլայն Խանութներ', 'slug' => 'e-commerce'],
            ['name' => 'Կորպորատիվ Կայքեր', 'slug' => 'corporate'],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $model = PortfolioCategory::where('slug', $cat['slug'])->first();
            if (!$model) {
                $model = new PortfolioCategory();
                $model->id = (string) Str::uuid();
                $model->slug = $cat['slug'];
            }
            $model->name = $cat['name'];
            $model->save();
            $categoryMap[$cat['slug']] = $model->id;
        }

        // 3. Technologies
        $technologies = [
            ['name' => 'PHP & Laravel', 'slug' => 'laravel', 'icon' => 'server'],
            ['name' => 'JavaScript (ES6+)', 'slug' => 'javascript', 'icon' => 'code'],
            ['name' => 'HTML5 & CSS3', 'slug' => 'html5-css3', 'icon' => 'layout'],
            ['name' => 'MySQL Database', 'slug' => 'mysql', 'icon' => 'database'],
            ['name' => 'React & Next.js', 'slug' => 'react-nextjs', 'icon' => 'cpu'],
            ['name' => 'UI/UX & Figma', 'slug' => 'figma', 'icon' => 'pen-tool'],
            ['name' => 'AI Models & LLM', 'slug' => 'ai-models', 'icon' => 'zap'],
            ['name' => 'Cloud PBX & VoIP', 'slug' => 'cloud-pbx', 'icon' => 'phone-call'],
            ['name' => 'Google Ads & Meta', 'slug' => 'ads-ppc', 'icon' => 'target'],
            ['name' => 'SEO & Google Maps', 'slug' => 'seo-geo', 'icon' => 'search'],
        ];

        $techMap = [];
        foreach ($technologies as $tech) {
            $model = PortfolioTechnology::where('slug', $tech['slug'])->first();
            if (!$model) {
                $model = new PortfolioTechnology();
                $model->id = (string) Str::uuid();
                $model->slug = $tech['slug'];
            }
            $model->name = $tech['name'];
            $model->icon = $tech['icon'];
            $model->save();
            $techMap[$tech['slug']] = $model->id;
        }

        // 4. Services from User Request & Official Presentation
        $servicesData = [
            // WEB SECTION
            [
                'slug' => 'business-card-website',
                'title' => 'Կայք Այցեքարտ',
                'tagline' => 'Ստատիկ կայք, որը հավուր պատշաճի կներկայացնի Ձեր ընկերությունը',
                'description' => 'Ստատիկ կայք, որը հավուր պատշաճի կներկայացնի Ձեր ընկերությունն ինտերնետ միջավայրում՝ պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայնով։',
                'price_amd' => '150,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => false,
                'icon' => 'credit-card',
                'cta_text' => 'Պատվիրել Այցեքարտ Կայք',
                'sort_order' => 1,
                'features' => [
                    'Պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայն',
                    'Mobile, Tablet, Desktop տարբերակներ',
                    'Անսահմանափակ էջեր',
                    'SEO friendly օպտիմիզացիա',
                    'Օնլայն չաթի հնարավորություն',
                    'Բլոգ վարելու հնարավորություն',
                    'Հետադարձ կապ և ավելին...',
                ],
            ],
            [
                'slug' => 'landing-page',
                'title' => 'Լենդինգ Էջ (Landing Page)',
                'tagline' => 'Ապրանքը կամ ծառայությունը ներկայացնող բարձր կոնվերսիայով էջ',
                'description' => 'Կայք էջ, որը հնարավորինս ամբողջական կներկայացնի ձեր ապրանքը կամ ծառայությունը, գերազանցելով սպասվելիք վաճառքները։',
                'price_amd' => '190,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => true,
                'icon' => 'trending-up',
                'cta_text' => 'Պատվիրել Լենդինգ Էջ',
                'sort_order' => 2,
                'features' => [
                    'Պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայն',
                    'Mobile, Tablet, Desktop տարբերակներ',
                    'SEO friendly',
                    'Ապրանքի կամ ծառայության պատվիրման հնարավորություն',
                    'Օնլայն վճարման համակարգի հնարավորություն',
                    'Օնլայն չաթի հնարավորություն',
                    'Հնարավորություն ինտեգրելու ձեր CRM համակարգին',
                    'Հետադարձ կապ և ավելին...',
                ],
            ],
            [
                'slug' => 'corporate-website',
                'title' => 'Կորպորատիվ Կայք',
                'tagline' => 'Կընդգծի Ձեր ընկերության առավելություններն ու հնարավորությունները',
                'description' => 'Կայք, որը կընդգծի Ձեր ընկերության առավելություններն ու հնարավորությունները, հավուր պատշաճի կներկայացնի ձեր ծառայություններն ինտերնետ միջավայրում։',
                'price_amd' => '290,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => true,
                'icon' => 'briefcase',
                'cta_text' => 'Պատվիրել Կորպորատիվ Կայք',
                'sort_order' => 3,
                'features' => [
                    'Պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայն',
                    'Mobile, Tablet, Desktop տարբերակներ',
                    'Անսահմանափակ էջեր',
                    'SEO friendly',
                    'Օնլայն չաթի հնարավորություն',
                    'Բլոգ վարելու հնարավորություն',
                    'Ընկերության գործունեությանը համապատասխան ֆունկցիոնալ',
                    'Հետադարձ կապ և ավելին...',
                ],
            ],
            [
                'slug' => 'online-shop',
                'title' => 'Օնլայն Խանութ (E-Commerce)',
                'tagline' => 'Առցանց առևտրի հարթակ վճարային և առաքման համակարգերով',
                'description' => 'Առցանց խանութ, որտեղից ձեր հաճախորդները հնարավորություն կունենան պատվիրել ապրանքները, ինչպես նաև վճարել դրանց դիմաց (Idram, Telcell, ArCa, Visa/Mastercard)։',
                'price_amd' => '350,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => true,
                'icon' => 'shopping-cart',
                'cta_text' => 'Սկսել Օնլայն Վաճառքը',
                'sort_order' => 4,
                'features' => [
                    'Պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայն',
                    'Mobile, Tablet, Desktop տարբերակներ',
                    'SEO friendly',
                    'Հաճախորդների գրանցման համակարգ',
                    'Մնացորդների կառավարման համակարգ',
                    'Օնլայն վճարային համակարգ (Idram, Telcell, ArCa)',
                    'Օնլայն չաթի հնարավորություն',
                    'Հնարավորություն ինտեգրելու ձեր CRM համակարգին',
                    'Հետադարձ կապ և ավելին...',
                ],
            ],
            [
                'slug' => 'news-portal',
                'title' => 'Նորությունների Կայք',
                'tagline' => 'Կայք, որն ամենարագը ձեր նորությունները կհասցնի ընթերցողին',
                'description' => 'Բարձր ծանրաբեռնվածությանը դիմացող նորությունների և մեդիա հարթակ՝ բազմալեզու աջակցությամբ և գովազդային բլոկներով։',
                'price_amd' => '450,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => false,
                'icon' => 'file-text',
                'cta_text' => 'Պատվիրել Նորությունների Կայք',
                'sort_order' => 5,
                'features' => [
                    'Պրոֆեսիոնալ թիմի կողմից մշակված UI/UX դիզայն',
                    'Mobile, Tablet, Desktop տարբերակներով / Responsive',
                    'SEO friendly',
                    'Բազմալեզու համակարգ',
                    'Բաժանորդագրության համակարգ',
                    'Գովազդի տեղադրման հնարավորություն',
                    'Հետադարձ կապ և ավելին...',
                ],
            ],
            [
                'slug' => 'custom-web-from-scratch',
                'title' => 'Կայք 0-ից & Անհատական Ծրագրեր',
                'tagline' => 'Կայք, որը կծրագրավորվի ձեր պահանջներին և ցանկություններին համապատասխան',
                'description' => 'Անհատական ծրագրերի, SaaS, ERP, CRM համակարգերի նախագծում, պատրաստում և շարունակական սպասարկում PHP Laravel-ով։',
                'price_amd' => 'Պայմանագրային',
                'price_currency' => '',
                'show_price' => true,
                'price_label' => 'Գինը',
                'popular' => false,
                'icon' => 'code',
                'cta_text' => 'Քննարկել Նախագիծը',
                'sort_order' => 6,
                'features' => [
                    'Անհատական տեխնիկական առաջադրանք (ՏԱ)',
                    'Անհատական ծրագրավորում PHP Laravel հզոր ֆրեյմվորքով',
                    'Տվյալների բազայի խորքային ճարտարապետություն (MySQL/Redis)',
                    'RBAC օգտատերերի դերերի համակարգ',
                    'API ինտեգրացիաներ բանկերի, պահեստների և CRM-ների հետ',
                    'Երաշխիքային սպասարկում և զարգացում',
                ],
            ],

            // AI AUTOMATION SECTION
            [
                'slug' => 'ai-automations',
                'title' => 'AI Ավտոմատացումներ (Նոր ճյուղ)',
                'tagline' => 'Արհեստական բանականության ինտեգրում բիզնես գործընթացներում',
                'description' => 'Ներդնում ենք AI գործիքներ՝ խելացի չաթբոտեր, վաճառքի ավտոմատացված AI ագենտներ, փաստաթղթերի վերլուծություն և բիզնեսի արդյունավետության բարձրացում։',
                'price_amd' => '200,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Սկսած',
                'popular' => true,
                'icon' => 'zap',
                'cta_text' => 'Ակտիվացնել AI Լուծումներ',
                'sort_order' => 7,
                'features' => [
                    'AI չաթբոտեր WhatsApp-ում, Telegram-ում և կայքում',
                    '24/7 հաճախորդների սպասարկման AI ագենտներ',
                    'Տվյալների և վաճառքների ավտոմատացված վերլուծություն',
                    'CRM համակարգերի հետ AI ինտեգրացիա',
                    'Բիզնեսի ծախսերի կրճատում մինչև 50%',
                ],
            ],

            // MARKETING SECTION
            [
                'slug' => 'marketing-seo-geo-smm-ppc',
                'title' => 'Մարքեթինգային Ծառայություններ (SEO, GEO, SMM, PPC, Դիզայն)',
                'tagline' => 'Համապարփակ թվային առաջխաղացում և վաճառքների աճ',
                'description' => 'Մեր հմուտ մասնագետները կմշակեն գրագետ մարքեթինգային ռազմավարություն՝ SEO առաջխաղացում, GEO տեղական որոնում (Google Maps), SMM արշավներ և PPC թիրախային գովազդ։',
                'price_amd' => '120,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Ամսական սկսած',
                'popular' => false,
                'icon' => 'target',
                'cta_text' => 'Պատվիրել Մարքեթինգ',
                'sort_order' => 8,
                'features' => [
                    'SEO առաջխաղացում Google որոնողական համակարգում',
                    'GEO տեղական օպտիմիզացիա (Google Business / Maps)',
                    'SMM առաջխաղացում (Facebook, Instagram, LinkedIn)',
                    'PPC գովազդ (Google Ads, Meta Ads)',
                    'Բրենդինգ, լոգո և ֆիրմային ոճի ստեղծում',
                    'Ամսական մանրամասն վիճակագրություն և հաշվետվություն',
                ],
            ],

            // CLOUD PBX SECTION
            [
                'slug' => 'cloud-pbx-telephony',
                'title' => 'Cloud PBX Ծառայություններ (Ամպային Հեռախոսակապ)',
                'tagline' => 'Ժամանակակից կորպորատիվ հեռախոսակապ և զանգերի կառավարում',
                'description' => 'Ամպային IP PBX հեռախոսակապ ընկերությունների համար՝ բազմալիքային համարներով, զանգերի ձայնագրմամբ, ավտոքարտուղարով (IVR) և CRM ինտեգրացիայով։',
                'price_amd' => '50,000',
                'price_currency' => 'դր',
                'show_price' => true,
                'price_label' => 'Ամսական սկսած',
                'popular' => false,
                'icon' => 'phone-call',
                'cta_text' => 'Միացնել Cloud PBX',
                'sort_order' => 9,
                'features' => [
                    'Բազմալիքային կորպորատիվ քաղաքային և բջջային համարներ',
                    'Ձայնային ողջույն և IVR մենյու',
                    'Զանգերի ավտոմատ ձայնագրում և վերահասցեավորում',
                    'CRM համակարգերի հետ լիարժեք ինտեգրում',
                    'Աշխատանք աշխարհի ցանկացած կետից (Softphone / IP Phone)',
                    '24/7 տեխնիկական աջակցություն',
                ],
            ],
        ];

        foreach ($servicesData as $s) {
            $features = $s['features'];
            unset($s['features']);

            $service = Service::where('slug', $s['slug'])->first();
            if (!$service) {
                $service = new Service();
                $service->id = (string) Str::uuid();
            }
            $service->fill(array_merge($s, ['published' => true]));
            $service->save();

            // Seed features
            ServiceFeature::where('service_id', $service->id)->delete();
            foreach ($features as $idx => $fTitle) {
                ServiceFeature::create([
                    'id' => (string) Str::uuid(),
                    'service_id' => $service->id,
                    'text' => $fTitle,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        // 5. REAL Portfolio Projects from Presentation (12 Projects!)
        $projects = [
            [
                'slug' => 'littleprince-am',
                'title' => 'LittlePrince.am — Մանկասայլակների Օնլայն Խանութ',
                'client' => 'Little Prince Armenia',
                'summary' => 'Մանկասայլակների և մանկական պարագաների առաջատար առցանց խանութ՝ հարմարավետ ֆիլտրերով և օնլայն վճարմամբ։',
                'overview' => 'Նախագծվել է ժամանակակից էլեկտրոնային խանութ, որը ներառում է ապրանքների հարուստ կատալոգ, բրենդների առանձնացում, ակցիաների համակարգ և արագ գնումների հնարավորություն։',
                'challenge' => 'Ապահովել ապրանքների բարձր որակի լուսանկարների արագ բեռնումը, մանկական պարագաների բազմակողմանի չափսերի ու գույների ընտրությունը։',
                'solution' => 'Մշակվեց բարձր արագության ինտերֆեյս, բջջային սարքերի համար օպտիմիզացված զամբյուղ և վճարային համակարգերի ինտեգրում։',
                'services' => ['E-Commerce', 'UI/UX Design', 'SEO', 'Maintenance'],
                'results' => [
                    'Առցանց պատվերների կայուն աճ',
                    'Mobile գնումների բարձր մասնաբաժին՝ 75%',
                    'Գերազանց արագություն բոլոր սարքերում',
                ],
                'year' => '2024',
                'live_url' => 'https://www.littleprince.am',
                'hero_image' => '/assets/images/portfolio-gourmet.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 1,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'mysql', 'javascript', 'html5-css3'],
            ],
            [
                'slug' => 'mijnaberd-com',
                'title' => 'Mijnaberd.com — Ալկոհոլային Խմիչքների Կորպորատիվ Կայք',
                'client' => 'Mijnaberd LLC',
                'summary' => 'Պրեմիում ալկոհոլային խմիչքների բրենդային կորպորատիվ կայք և էլեգանտ կատալոգ։',
                'overview' => 'Կայքը ներկայացնում է հայկական ավանդական թորվածքների և գինիների բացառիկ տեսականին՝ շեշտադրելով որակն ու շքեղությունը։',
                'challenge' => 'Ստեղծել էլիտար ոճ, որը համապատասխանում է պրեմիում դասի ալկոհոլի միջազգային ստանդարտներին։',
                'solution' => 'Կիրառվեց մինիմալիստական մուգ ոճ, ինտերակտիվ անիմացիաներ և բազմալեզու կառուցվածք։',
                'services' => ['Corporate Website', 'UI/UX Design', 'Branding'],
                'results' => [
                    'Միջազգային գործընկերների հարցումների աճ +80%',
                    'Էլեգանտ և ճանաչելի ֆիրմային ոճ',
                ],
                'year' => '2024',
                'live_url' => 'https://www.mijnaberd.com',
                'hero_image' => '/assets/images/portfolio-erp.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 2,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript', 'figma'],
            ],
            [
                'slug' => 'hairbysiri-com',
                'title' => 'HairBySiri.com — Գեղեցկության Սրահ',
                'client' => 'Hair by Siri Studio',
                'summary' => 'Գեղեցկության սրահի նորաոճ վեբ կայք՝ ծառայությունների մանրամասն մենյուով և առցանց գրանցմամբ։',
                'overview' => 'Ստեղծվել է գրավիչ և թրենդային ինտերֆեյս սրահի այցելուների համար՝ գների, աշխատանքների օրինակների և վարպետների ներկայացմամբ։',
                'challenge' => 'Հեշտացնել հաճախորդների կողմից սրահի ծառայություններին ծանոթանալը և զանգերի կամ առցանց հարցումների կատարումը։',
                'solution' => 'Ներդրվեց ինտերակտիվ պորտֆոլիո, գնացուցակ և ակնթարթային կապի կոճակներ (WhatsApp, Call)։',
                'services' => ['Web Development', 'UI/UX Design', 'SMM & Marketing'],
                'results' => [
                    'Սրահի նոր այցելուների աճ 40%-ով',
                    'Կատարյալ հարմարեցվածություն սմարթֆոններին',
                ],
                'year' => '2024',
                'live_url' => 'https://www.hairbysiri.com',
                'hero_image' => '/assets/images/portfolio-fintech.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 3,
                'category_slug' => 'web-services',
                'tech_slugs' => ['html5-css3', 'javascript', 'figma'],
            ],
            [
                'slug' => 'tntes-am',
                'title' => 'Tntes.am — Տնտեսական Ապրանքների Օնլայն Խանութ',
                'client' => 'Tntes LLC',
                'summary' => 'Տնտեսական և կենցաղային ապրանքների մասշտաբային օնլայն խանութ՝ առաքման համակարգով։',
                'overview' => 'Հազարավոր ապրանքներով օնլայն հարթակ, որն ապահովում է արագ որոնում, զամբյուղ և անվճար առաքման պայմանների հաշվարկ։',
                'challenge' => 'Մեծ ծավալի ապրանքատեսականու կառուցվածքավորում և արագագործության ապահովում։',
                'solution' => 'Օպտիմիզացված տվյալների բազա, խելացի կատեգորիաների համակարգ և մեկ քայլով պատվեր։',
                'services' => ['E-Commerce', 'Database Optimization', 'Payment Gateways'],
                'results' => [
                    'Վաճառքների ծավալի կրկնապատկում',
                    '0.9 վայրկյան բեռնման արագություն',
                ],
                'year' => '2024',
                'live_url' => 'https://www.tntes.am',
                'hero_image' => '/assets/images/portfolio-medcare.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 4,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'mysql', 'javascript'],
            ],
            [
                'slug' => 'tatevaloyan-com',
                'title' => 'TatevAloyan.com — Անձնական Բլոգ',
                'client' => 'Տաթև Ալոյան',
                'summary' => 'Անհատական բլոգ ստեղծագործությունների, հոդվածների և գրական նյութերի համար։',
                'overview' => 'Էսթետիկ և նուրբ դիզայնով անձնական հարթակ ընթերցողների հետ կապի և ստեղծագործությունների հրապարակման համար։',
                'challenge' => 'Ստեղծել կարդալու համար հանգիստ, մաքուր և տիպոգրաֆիկապես կատարյալ միջավայր։',
                'solution' => 'Custom բլոգային շարժիչ հարմարավետ կառավարման վահանակով։',
                'services' => ['Personal Blog', 'UI/UX Design', 'SEO'],
                'results' => [
                    'Հավատարիմ ընթերցողների լսարանի ձևավորում',
                    'Google որոնումներում բարձր տեսանելիություն',
                ],
                'year' => '2024',
                'live_url' => 'https://www.tatevaloyan.com',
                'hero_image' => '/assets/images/portfolio-gourmet.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 5,
                'category_slug' => 'web-services',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'deka-am',
                'title' => 'Deka.am — Շինարարական Ընկերություն Երևանում',
                'client' => 'Deka Construction',
                'summary' => 'Շինարարական ընկերության պաշտոնական կորպորատիվ կայք՝ կառուցված շենքերի և նախագծերի ցուցադրմամբ։',
                'overview' => 'Ներկայացնում է ընկերության պրոֆեսիոնալիզմը, ավարտված նախագծերի պատկերասրահը և ծառայությունները։',
                'challenge' => 'Ապահովել շինարարական նախագծերի բարձր որակի լուսանկարների գեղեցիկ ցուցադրումը։',
                'solution' => 'Ժամանակակից ինտերակտիվ պատկերասրահ, նախագծերի ֆիլտր և հետադարձ կապի ֆորմա։',
                'services' => ['Corporate Website', 'UI/UX Design', 'SEO'],
                'results' => [
                    'Պատվիրատուների վստահության և դիմումների աճ',
                ],
                'year' => '2024',
                'live_url' => 'https://www.deka.am',
                'hero_image' => '/assets/images/portfolio-erp.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 6,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript', 'figma'],
            ],
            [
                'slug' => 'argohouses-com',
                'title' => 'ArgoHouses.com — Անշարժ Գույքի Գործակալություն Նյու Յորքում',
                'client' => 'Argo Houses NYC',
                'summary' => 'Նյու Յորքում անշարժ գույքի գործակալության այցեքարտ կայք և առանձնատների վաճառքի հարթակ։',
                'overview' => 'ԱՄՆ շուկայի համար մշակված պրեմիում կայք անշարժ գույքի արագ գնահատման և վաճառքի հարցումների համար։',
                'challenge' => 'Միջազգային խիստ մրցակցային միջավայրում աչքի ընկնող և վստահություն ներշնչող տեսք։',
                'solution' => 'Cash offer հարցման ինտուիտիվ ֆորմա, ԱՄՆ ստանդարտների համապատասխանություն։',
                'services' => ['Website Development', 'Lead Generation', 'UI/UX'],
                'results' => [
                    'Լիդերի փոխակերպման բարձր ցուցանիշ (High Conversion)',
                ],
                'year' => '2024',
                'live_url' => 'https://www.argohouses.com',
                'hero_image' => '/assets/images/portfolio-fintech.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 7,
                'category_slug' => 'web-services',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'khohanots-am',
                'title' => 'Khohanots.am — Կիսաֆաբրիկատների Օնլայն Խանութ',
                'client' => 'Khohanots Armenia',
                'summary' => 'Համեղ կիսաֆաբրիկատների և պատրաստի սննդի օնլայն պատվերների կայք Երևանում։',
                'overview' => 'Արագ պատվերների ձևակերպում, մենյուի ընտրություն և ժամանակին առաքման կառավարում։',
                'challenge' => 'Պարզեցնել պատվերի ընթացքը սմարթֆոնից օգտվող հաճախորդների համար։',
                'solution' => 'Մոբայլ հարմարավետություն, ակնթարթային ծանուցումներ խոհանոցին։',
                'services' => ['E-Commerce', 'Mobile First Design'],
                'results' => [
                    'Առցանց պատվերների կայուն հոսք',
                ],
                'year' => '2024',
                'live_url' => 'https://www.khohanots.am',
                'hero_image' => '/assets/images/portfolio-medcare.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 8,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'javascript'],
            ],
            [
                'slug' => 'sigma-medicare-com',
                'title' => 'Sigma-Medicare.com — Բժշկական Պարագաների Արտադրություն',
                'client' => 'Sigma Medicare CJSC',
                'summary' => 'Բժշկական պարագաների առաջատար արտադրողի կորպորատիվ բազմալեզու կայք։',
                'overview' => 'Միջազգային սերտիֆիկացված արտադրանքի կատալոգ, B2B գործընկերների հարցումների համակարգ։',
                'challenge' => 'Բժշկական ստանդարտների համապատասխանություն և B2B վստահելիության ապահովում։',
                'solution' => 'Հստակ տեխնիկական բնութագրեր, PDF սերտիֆիկատների ներբեռնում, B2B կոնտակտ։',
                'services' => ['Corporate Website', 'B2B Catalog', 'Multilingual'],
                'results' => [
                    'Արտահանման նոր պայմանագրերի կնքում',
                ],
                'year' => '2024',
                'live_url' => 'https://www.sigma-medicare.com',
                'hero_image' => '/assets/images/portfolio-gourmet.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 9,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'gastroclinic-am',
                'title' => 'GastroClinic.am — Բժշկական Կենտրոն',
                'client' => 'Gastro Clinic Yerevan',
                'summary' => 'Մասնագիտացված բժշկական կլինիկայի պաշտոնական կայք՝ բժիշկների և հետազոտությունների տեղեկատվությամբ։',
                'overview' => 'Հիվանդների համար հուսալի տեղեկատվական հարթակ, բժիշկների գրաֆիկ և առցանց հերթագրում։',
                'challenge' => 'Բժշկական տեղեկատվությունը մատուցել պարզ, հասկանալի և հուսալի ձևով։',
                'solution' => 'Հարմարավետ նավիգացիա, ծառայությունների հստակ գնացուցակ, բժշկի ընտրություն։',
                'services' => ['Medical Website', 'UI/UX Design', 'SEO'],
                'results' => [
                    'Այցելուների վստահության և գրանցումների բարձր աճ',
                ],
                'year' => '2024',
                'live_url' => 'https://www.gastroclinic.am',
                'hero_image' => '/assets/images/portfolio-medcare.svg',
                'featured' => true,
                'published' => true,
                'sort_order' => 10,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript', 'figma'],
            ],
            [
                'slug' => 'mercuryhouses-com',
                'title' => 'MercuryHouses.com — Հավելումների Օնլայն Խանութ ԱՄՆ-ում',
                'client' => 'Mercury Houses US',
                'summary' => 'Առողջարար հավելումների և պարագաների օնլայն հարթակ ԱՄՆ շուկայի համար։',
                'overview' => 'ԱՄՆ հաճախորդների համար օպտիմիզացված առցանց գնումների համակարգ Stripe և PayPal վճարումներով։',
                'challenge' => 'Արագագործություն ԱՄՆ սերվերներից և պարզ check-out գործընթաց։',
                'solution' => 'Գերարագ ճարտարապետություն, անվտանգ վճարումներ և ավտոմատ առաքման թրեքինգ։',
                'services' => ['E-Commerce', 'Stripe / PayPal Integration'],
                'results' => [
                    'Հաջող վաճառքներ ԱՄՆ նահանգներում',
                ],
                'year' => '2024',
                'live_url' => 'https://www.mercuryhouses.com',
                'hero_image' => '/assets/images/portfolio-erp.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 11,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'javascript'],
            ],
            [
                'slug' => 'cargonewhorizon-com',
                'title' => 'CargoNewHorizon.com — Լոգիստիկ Ընկերություն ԱՄՆ-ում',
                'client' => 'New Horizon Logistics US',
                'summary' => 'Միջազգային բեռնափոխադրումների և լոգիստիկայի այցեքարտ կայք ԱՄՆ-ում։',
                'overview' => 'Բեռնատարների պարկի, երթուղիների և բեռնափոխադրման գնահատման ֆորմայով կորպորատիվ կայք։',
                'challenge' => 'Հաճախորդներից բեռների չափսերի և ուղղությունների հաշվարկի հեշտացում։',
                'solution' => 'Ինտերակտիվ Quote Request համակարգ և վարորդների հավաքագրման էջ։',
                'services' => ['Logistics Website', 'Quote System', 'UI/UX'],
                'results' => [
                    'Օրական բեռնափոխադրման տասնյակ նոր հարցումներ',
                ],
                'year' => '2024',
                'live_url' => 'https://www.cargonewhorizon.com',
                'hero_image' => '/assets/images/portfolio-fintech.svg',
                'featured' => false,
                'published' => true,
                'sort_order' => 12,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
        ];

        foreach ($projects as $p) {
            $catSlug = $p['category_slug'];
            $techSlugs = $p['tech_slugs'];
            unset($p['category_slug'], $p['tech_slugs']);

            $project = PortfolioProject::where('slug', $p['slug'])->first();
            if (!$project) {
                $project = new PortfolioProject();
                $project->id = (string) Str::uuid();
            }
            $project->fill($p);
            $project->save();

            // Attach category
            if (isset($categoryMap[$catSlug])) {
                $project->categories()->syncWithoutDetaching([$categoryMap[$catSlug]]);
            }

            // Attach technologies
            $techIds = [];
            foreach ($techSlugs as $tSlug) {
                if (isset($techMap[$tSlug])) {
                    $techIds[] = $techMap[$tSlug];
                }
            }
            if (!empty($techIds)) {
                $project->technologies()->syncWithoutDetaching($techIds);
            }
        }

        // 6. Testimonials from Real Clients
        $testimonials = [
            [
                'name' => 'Ավետիս Գևորգյան',
                'position' => 'CEO & Հիմնադիր',
                'company' => 'eLab Digital Studio',
                'content' => 'Մեր նպատակն է յուրաքանչյուր գործընկերոջ տրամադրել ոչ միայն գեղեցիկ կայք, այլև իրական բիզնես արդյունքներ բերող թվային գործիք։ Մենք երաշխավորում ենք կոդի մաքրությունը, բարձր արագությունը և հուսալիությունը։',
                'rating' => 5,
                'photo' => '/assets/images/avatar-1.svg',
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Արմեն Գրիգորյան',
                'position' => 'Հիմնադիր',
                'company' => 'Mijnaberd LLC',
                'content' => 'eLab-ի թիմը մեր պրեմիում ալկոհոլի կորպորատիվ կայքը պատրաստեց բարձրագույն մակարդակով։ Դիզայնը հիացրել է մեր բոլոր արտասահմանյան գործընկերներին։',
                'rating' => 5,
                'photo' => '/assets/images/avatar-2.svg',
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Սիրանուշ Հարությունյան',
                'position' => 'Տնօրեն',
                'company' => 'Hair by Siri Studio',
                'content' => 'Մեր սրահի կայքը դարձավ մեր այցեքարտը։ Հաճախորդները հեշտությամբ գտնում են ծառայությունները և կապվում մեզ հետ WhatsApp-ով։ Շնորհակալություն eLab-ին։',
                'rating' => 5,
                'photo' => '/assets/images/avatar-3.svg',
                'published' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            $testim = Testimonial::where('company', $t['company'])->first();
            if (!$testim) {
                $testim = new Testimonial();
                $testim->id = (string) Str::uuid();
            }
            $testim->fill($t);
            $testim->save();
        }

        // 7. FAQs directly aligned with User Requirements & Presentation
        $faqs = [
            [
                'question' => 'Ի՞նչ տեսակի կայքեր եք պատրաստում և որքա՞ն արժեն։',
                'answer' => 'Մենք առաջարկում ենք՝ Կայք այցեքարտ (սկսած 150,000 դր), Լենդինգ էջ (սկսած 190,000 դր), Կորպորատիվ կայք (սկսած 290,000 դր), Օնլայն խանութ (սկսած 350,000 դր), Նորությունների կայք (սկսած 450,000 դր), ինչպես նաև Կայք 0-ից և անհատական ծրագրեր պայմանագրային արժեքով։',
                'category' => 'Pricing',
                'sort_order' => 1,
                'published' => true,
            ],
            [
                'question' => 'Որո՞նք են կայքի ստեղծման հիմնական փուլերը (Ընթացակարգը)։',
                'answer' => 'Գործընթացը բաղկացած է 3 հիմնական փուլից՝ 1. Դիզայնի ընտրություն (նախագծում և UI/UX հաստատում), 2. Կայքի ստեղծում (նախապես տրամադրված քոնթենթով և ընտրված ֆունկցիոնալով ծրագրավորում), 3. Կայքի թողարկում (տեղադրում հոսթինգում, դոմեյնի միացում և թեստավորում)։',
                'category' => 'Process',
                'sort_order' => 2,
                'published' => true,
            ],
            [
                'question' => 'Ի՞նչ են ներառում AI Ավտոմատացումները։',
                'answer' => 'Մեր նոր ճյուղը ներառում է բիզնես գործընթացների ավտոմատացում արհեստական բանականության միջոցով՝ 24/7 հաճախորդների սպասարկման AI ագենտներ (WhatsApp, Telegram, Web), վաճառքի ավտոմատացում և տվյալների վերլուծություն։',
                'category' => 'AI',
                'sort_order' => 3,
                'published' => true,
            ],
            [
                'question' => 'Ի՞նչ մարքեթինգային ծառայություններ եք առաջարկում։',
                'answer' => 'Տրամադրում ենք համալիր մարքեթինգ՝ Դիզայն և բրենդինգ, SEO (որոնողական օպտիմիզացիա), GEO (տեղական առաջխաղացում Google Maps-ում), SMM (սոցիալական էջերի կառավարում և գովազդ), ինչպես նաև PPC (Google Ads, Meta Ads թիրախային գովազդ)։',
                'category' => 'Marketing',
                'sort_order' => 4,
                'published' => true,
            ],
            [
                'question' => 'Ի՞նչ է Cloud PBX ծառայությունը։',
                'answer' => 'Cloud PBX-ը ամպային կորպորատիվ հեռախոսակապ է ընկերությունների համար։ Այն ապահովում է բազմալիքային համարներ, զանգերի ավտոմատ ձայնագրում, ձայնային ողջույն (IVR), վերահասցեավորում և CRM ինտեգրացիա՝ հնարավորություն տալով ընդունել զանգեր աշխարհի ցանկացած կետից։',
                'category' => 'PBX',
                'sort_order' => 5,
                'published' => true,
            ],
            [
                'question' => 'Ինչպե՞ս կարող եմ կապվել eLab Agency-ի հետ։',
                'answer' => 'Կարող եք զանգահարել կամ գրել +374 55 77 60 66 հեռախոսահամարին (WhatsApp, Telegram), ուղարկել նամակ hello@elab.am էլ. հասցեին կամ հետևել մեր սոցիալական էջերին Facebook-ում, Instagram-ում և LinkedIn-ում։',
                'category' => 'Contact',
                'sort_order' => 6,
                'published' => true,
            ],
        ];

        foreach ($faqs as $f) {
            $faq = FAQ::where('question', $f['question'])->first();
            if (!$faq) {
                $faq = new FAQ();
                $faq->id = (string) Str::uuid();
            }
            $faq->fill($f);
            $faq->save();
        }
    }
}
