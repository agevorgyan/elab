<?php
$file = '/Users/apple/Projects/elab/database/seeders/ElabAgencySeeder.php';
$content = file_get_contents($file);

$startMarker = '// 5. REAL Portfolio Projects from Presentation (12 Projects!)';
$endMarker = '        foreach ($projects as $p) {';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker);

if ($startPos !== false && $endPos !== false) {
    $newCode = "
        // Clear old portfolio projects before seeding new ones
        \\DB::table('portfolio_project_category')->truncate();
        \\DB::table('portfolio_project_technology')->truncate();
        PortfolioProject::query()->delete();

        // 5. REAL Portfolio Projects from User Request
        \$projects = [
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
        ];\n\n";

    $newContent = substr($content, 0, $startPos) . $newCode . substr($content, $endPos);
    file_put_contents($file, $newContent);
    echo "Updated successfully.";
} else {
    echo "Markers not found.";
}
