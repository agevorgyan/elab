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

        
        // Clear old portfolio projects before seeding new ones
        \DB::table('portfolio_project_categories')->truncate();
        \DB::table('portfolio_project_technologies')->truncate();
        PortfolioProject::query()->delete();

        // 5. REAL Portfolio Projects from User Request
        $projects = [
            [
                'slug' => 'littleprince-am',
                'title' => 'LittlePrince.am — Մանկասայլակների Օնլայն Խանութ',
                'client' => 'Little Prince',
                'summary' => 'Մանկասայլակների և մանկական պարագաների առաջատար առցանց խանութ՝ հարմարավետ ֆիլտրերով և օնլայն վճարմամբ։',
                'overview' => 'Նախագծվել է ժամանակակից էլեկտրոնային խանութ մանկական ապրանքների համար, որն առաջարկում է հարուստ տեսականի, մանրամասն որոնման ֆիլտրեր և արագ գնումների համակարգ:',
                'challenge' => 'Մեծ քանակությամբ ապրանքների և տեսականու դասակարգում, ինտուիտիվ որոնման ապահովում և վճարային համակարգերի ինտեգրում:',
                'solution' => 'Ստեղծվեց օպտիմիզացված, մոբայլի համար հարմարավետ E-Commerce հարթակ արագ բեռնվող էջերով և անխափան վճարումներով։',
                'services' => ['E-Commerce', 'UI/UX Design', 'Payment Gateway'],
                'results' => ['Վաճառքների աճ', 'Հարմարավետ գնումների փորձ'],
                'year' => '2024',
                'live_url' => 'http://littleprince.am/',
                'hero_image' => '/assets/images/portfolio-02.jpg',
                'featured' => true,
                'published' => true,
                'sort_order' => 1,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'mysql', 'javascript'],
            ],
            [
                'slug' => 'gayanes-am',
                'title' => 'Gayanes.am — Կիսաֆաբրիկատների Արտադրություն',
                'client' => 'Gayanes',
                'summary' => 'Տնական թարմ կիսաֆաբրիկատների արտադրություն, վաճառք և արագ առաքում Երևանում։',
                'overview' => 'Համեղ և թարմ տնական սննդի օնլայն պատվերների կայք։ Հաճախորդներին հնարավորություն է տրվում ծանոթանալ մենյուին և գրանցել առաքման պատվեր։',
                'challenge' => 'Ապահովել պատվերների արագ գրանցում և մենյուի գրավիչ ներկայացում հաճախորդներին:',
                'solution' => 'Մինիմալիստական դիզայնով վեբ կայք, որտեղ շեշտը դրված է ուտեստների բարձրորակ նկարների և պարզ պատվերի գործընթացի վրա:',
                'services' => ['E-Commerce', 'Web Development', 'UI/UX'],
                'results' => ['Պատվերների արագ գրանցում', 'Հաճախորդների բազայի մեծացում'],
                'year' => '2024',
                'live_url' => 'http://gayanes.am/',
                'hero_image' => '/assets/images/portfolio-01.jpg',
                'featured' => true,
                'published' => true,
                'sort_order' => 2,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'tntes-am',
                'title' => 'Tntes.am — Տնտեսական Ապրանքներ',
                'client' => 'Tntes',
                'summary' => 'Տնտեսական ապրանքների օնլայն խանութ Երևանում՝ լայն տեսականիով և հարմարավետ առաքմամբ։',
                'overview' => 'Հազարավոր տնտեսական ապրանքների օնլայն կատալոգ և խանութ, որտեղ գնորդները կարող են հեշտությամբ գտնել իրենց անհրաժեշտ պարագաները։',
                'challenge' => 'Ապահովել մեծածավալ տվյալների բազայի արագ աշխատանքը և ճկուն որոնման հնարավորությունը:',
                'solution' => 'Նախագծվել է հզոր և արագ որոնման համակարգ ունեցող կայք՝ բազմաթիվ կատեգորիաներով և զտիչներով:',
                'services' => ['E-Commerce', 'Database Optimization'],
                'results' => ['Օգտատերերի բարձր ակտիվություն', 'Անխափան որոնում'],
                'year' => '2024',
                'live_url' => 'http://tntes.am/',
                'hero_image' => '/assets/images/portfolio-03.jpg',
                'featured' => true,
                'published' => true,
                'sort_order' => 3,
                'category_slug' => 'e-commerce',
                'tech_slugs' => ['laravel', 'mysql', 'javascript'],
            ],
            [
                'slug' => 'tonvisage-elab-am',
                'title' => 'Tonvisage — Գեղեցկության Սրահ և Խանութ',
                'client' => 'Tonvisage',
                'summary' => 'Գեղեցկության սրահ և դիմահարդարման պարագաների օնլայն խանութ՝ գեղեցկության համապարփակ լուծումներով։',
                'overview' => 'Մեկ հարթակում համատեղված գեղեցկության սրահի ծառայությունների ներկայացում և պրոֆեսիոնալ կոսմետիկայի առցանց վաճառք։',
                'challenge' => 'Նույն կայքում ներդաշնակորեն համադրել ծառայությունների ամրագրման և օնլայն գնումների ֆունկցիոնալը:',
                'solution' => 'Ստեղծվել է գեղեցիկ, նրբաճաշակ դիզայնով կայք՝ հարմարավետ E-commerce բաժնով և ծառայությունների գնացուցակով:',
                'services' => ['E-Commerce', 'Corporate Website', 'UI/UX Design'],
                'results' => ['Բրենդի ճանաչելիության բարձրացում', 'Ապրանքների և ծառայությունների համակցված վաճառք'],
                'year' => '2024',
                'live_url' => 'http://tonvisage.elab.am/',
                'hero_image' => '/assets/images/portfolio-04.jpg',
                'featured' => true,
                'published' => true,
                'sort_order' => 4,
                'category_slug' => 'web-services',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'argohouses-com',
                'title' => 'ArgoHouses.com — Անշարժ Գույք',
                'client' => 'Argo Houses',
                'summary' => 'Անշարժ գույքի վաճառքի ժամանակակից կայք ԱՄՆ-ում՝ գրավիչ և վստահելի դիզայնով։',
                'overview' => 'Ներկայացուցչական կայք անշարժ գույքի գործակալության համար, որն ուղղված է ԱՄՆ շուկայում հաճախորդների ներգրավմանը (lead generation):',
                'challenge' => 'Մրցակցային միջավայրում ստեղծել վստահություն ներշնչող և հարցումների (cash offer) խթանմանը միտված դիզայն:',
                'solution' => 'Նախագծվել է պրեմիում տեսքով Landing/Corporate վեբ կայք՝ հարմարեցված ամերիկյան շուկայի պահանջներին:',
                'services' => ['Corporate Website', 'Lead Generation', 'SEO'],
                'results' => ['Բարձր կոնվերսիա (Conversion rate)'],
                'year' => '2024',
                'live_url' => 'http://argohouses.com/',
                'hero_image' => '/assets/images/portfolio-05.jpg',
                'featured' => false,
                'published' => true,
                'sort_order' => 5,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'deka-am',
                'title' => 'DEKA.am — Շինարարական Ընկերություն',
                'client' => 'DEKA',
                'summary' => 'Շինարարական ընկերության պաշտոնական կորպորատիվ կայք՝ նախագծերի և ծառայությունների ներկայացմամբ։',
                'overview' => 'Ներկայացնում է ընկերության փորձը, կառուցված շենքերի պատկերասրահը և տրամադրվող շինարարական ծառայությունները։',
                'challenge' => 'Պրոֆեսիոնալիզմի և հուսալիության ցուցադրումը ժամանակակից թվային միջավայրում:',
                'solution' => 'Ստեղծվել է կորպորատիվ կայք վստահելի և կառուցողական ոճով, ներդրվել են ավարտված նախագծերի գեղեցիկ սլայդերներ:',
                'services' => ['Corporate Website', 'UI/UX Design'],
                'results' => ['Վստահության և իմիջի ամրապնդում'],
                'year' => '2024',
                'live_url' => 'http://deka.am/',
                'hero_image' => '/assets/images/portfolio-09.jpg',
                'featured' => false,
                'published' => true,
                'sort_order' => 6,
                'category_slug' => 'corporate',
                'tech_slugs' => ['html5-css3', 'javascript'],
            ],
            [
                'slug' => 'mijnaberd-com',
                'title' => 'Mijnaberd.com — Ալկոհոլային Խմիչքներ',
                'client' => 'Միջնաբերդ Ալկո',
                'summary' => 'Միջնաբերդ Ալկո ընկերության կորպորատիվ կայք՝ պրեմիում դասի արտադրանքի ցուցադրմամբ։',
                'overview' => 'Բարձրորակ ավանդական խմիչքների և գինիների բրենդային էջ և կատալոգ։',
                'challenge' => 'Ապրանքների պրեմիում դիրքավորումը և գրավիչ վիզուալների ինտեգրումը վեբ միջավայրում:',
                'solution' => 'Մինիմալիստական, էլեգանտ և մուգ երանգներով ոճավորված դիզայն՝ վիզուալ շեշտադրումներով յուրաքանչյուր ապրանքատեսակի վրա:',
                'services' => ['Corporate Website', 'Branding', 'UI/UX Design'],
                'results' => ['Պրեմիում բրենդի ներկայացում միջազգային շուկայում'],
                'year' => '2024',
                'live_url' => 'http://mijnaberd.com/',
                'hero_image' => '/assets/images/portfolio-10.jpg',
                'featured' => true,
                'published' => true,
                'sort_order' => 7,
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
