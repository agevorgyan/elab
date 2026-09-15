<?php

namespace Database\Seeders;

use App\Models\CookieSettings;
use App\Models\LegalPage;
use App\Models\PortfolioCategory;
use App\Models\SeoMetadata;
use App\Models\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class SystemSettingsSeeder extends Seeder
{
    /**
     * Seed initial system required defaults.
     */
    public function run(): void
    {
        // 1. Site Settings Defaults
        $settings = [
            ['key' => 'siteName', 'value' => 'eLab Digital Studio', 'category' => 'branding'],
            ['key' => 'siteDescription', 'value' => 'Modern web applications, e-commerce platforms, and digital solutions in Armenia.', 'category' => 'branding'],
            ['key' => 'logoUrl', 'value' => '/images/logo.svg', 'category' => 'branding'],
            ['key' => 'phone', 'value' => '+374 55 77 60 66', 'category' => 'contact'],
            ['key' => 'email', 'value' => 'hello@elab.am', 'category' => 'contact'],
            ['key' => 'address', 'value' => 'Yerevan, Armenia', 'category' => 'contact'],
            ['key' => 'facebook', 'value' => 'https://www.facebook.com/elab.am', 'category' => 'social'],
            ['key' => 'instagram', 'value' => 'https://www.instagram.com/elab.armenia/', 'category' => 'social'],
            ['key' => 'linkedin', 'value' => 'https://www.linkedin.com/company/elab-armenia/', 'category' => 'social'],
        ];

        foreach ($settings as $item) {
            SiteSettings::updateOrCreate(
                ['key' => $item['key']],
                ['id' => (string) Str::uuid(), 'value' => $item['value'], 'category' => $item['category']]
            );
        }

        // 2. Cookie Settings Defaults
        CookieSettings::updateOrCreate(
            ['id' => 'default'],
            [
                'id' => (string) Str::uuid(),
                'version' => 1,
                'banner_enabled' => true,
                'analytics_enabled' => false,
                'marketing_enabled' => false,
                'ga_measurement_id' => null,
                'meta_pixel_id' => null,
            ]
        );



        // 3. Legal Pages Defaults
        $legalPages = [
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy (Գաղտնիության Քաղաքականություն)',
                'content' => '<h2>Privacy Policy</h2><p>eLab Digital Studio is committed to protecting your privacy and personal data.</p>',
                'last_updated' => now()->toDateString(),
                'published' => true,
            ],
            [
                'slug' => 'terms-of-service',
                'title' => 'Terms of Service (Ծառայությունների Մատուցման Պայմաններ)',
                'content' => '<h2>Terms of Service</h2><p>General terms and conditions governing the use of eLab services and software.</p>',
                'last_updated' => now()->toDateString(),
                'published' => true,
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy (Cookie-ների Քաղաքականություն)',
                'content' => '<h2>Cookie Policy</h2><p>Information on how cookies and browser storage are utilized on eLab.am.</p>',
                'last_updated' => now()->toDateString(),
                'published' => true,
            ],
        ];


        foreach ($legalPages as $page) {
            LegalPage::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge(['id' => (string) Str::uuid()], $page)
            );
        }

        // 4. SEO Metadata Defaults
        $seoRecords = [
            [
                'path' => '/',
                'title' => 'eLab.am — Digital Studio & Software Engineering',
                'description' => 'High-performance web applications, custom corporate portals, and e-commerce platforms.',
                'keywords' => ['web development', 'software engineering', 'next.js', 'laravel', 'armenia'],
                'canonical' => 'https://elab.am',
                'og_title' => 'eLab.am — Digital Studio',
                'og_description' => 'High-performance web applications and digital solutions.',
                'og_image' => 'https://elab.am/og-image.jpg',
                'robots' => 'index, follow',
            ],
            [
                'path' => '/portfolio',
                'title' => 'Portfolio — eLab.am Case Studies',
                'description' => 'Explore corporate portals, landing pages, and custom web platforms engineered by eLab.',
                'keywords' => ['portfolio', 'case studies', 'web development armenia'],
                'canonical' => 'https://elab.am/portfolio',
                'og_title' => 'Portfolio — eLab.am',
                'og_description' => 'Explore corporate portals and custom web platforms.',
                'og_image' => 'https://elab.am/portfolio-og.jpg',
                'robots' => 'index, follow',
            ],
            [
                'path' => '/services',
                'title' => 'Services — eLab.am Development Services',
                'description' => 'Landing pages, corporate websites, online stores, and QR restaurant menus.',
                'keywords' => ['services', 'pricing', 'web engineering'],
                'canonical' => 'https://elab.am/services',
                'og_title' => 'Services — eLab.am',
                'og_description' => 'Development services and digital solutions.',
                'og_image' => 'https://elab.am/services-og.jpg',
                'robots' => 'index, follow',
            ],
        ];

        foreach ($seoRecords as $seo) {
            SeoMetadata::updateOrCreate(
                ['path' => $seo['path']],
                array_merge(['id' => (string) Str::uuid()], $seo)
            );
        }


        // 5. Portfolio Categories Defaults
        $categories = [
            ['slug' => 'all', 'name' => 'All Projects', 'description' => 'Complete portfolio showcase', 'sort_order' => 1, 'active' => true],
            ['slug' => 'corporate', 'name' => 'Corporate Websites', 'description' => 'Enterprise portals and business platforms', 'sort_order' => 2, 'active' => true],
            ['slug' => 'ecommerce', 'name' => 'E-Commerce & Retail', 'description' => 'Online stores with payment integration', 'sort_order' => 3, 'active' => true],
            ['slug' => 'landing-page', 'name' => 'Landing Pages', 'description' => 'High-conversion campaign single page sites', 'sort_order' => 4, 'active' => true],
            ['slug' => 'custom', 'name' => 'Custom Web Applications', 'description' => 'Bespoke web applications and tools', 'sort_order' => 5, 'active' => true],
        ];

        foreach ($categories as $cat) {
            PortfolioCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge(['id' => (string) Str::uuid()], $cat)
            );
        }
    }
}
