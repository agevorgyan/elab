<?php

namespace Tests\Feature;

use App\Models\CookieSettings;
use App\Models\FAQ;
use App\Models\Lead;
use App\Models\LegalPage;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use App\Models\PortfolioTechnology;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\SiteSettings;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_settings_returns_200_and_only_public_data(): void
    {
        SiteSettings::create([
            'id' => (string) Str::uuid(),
            'key' => 'siteName',
            'value' => 'eLab Digital Studio',
            'category' => 'branding',
        ]);
        SiteSettings::create([
            'id' => (string) Str::uuid(),
            'key' => 'email',
            'value' => 'hello@elab.am',
            'category' => 'contact',
        ]);

        $response = $this->getJson('/api/v1/settings');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'siteName' => 'eLab Digital Studio',
                    'email' => 'hello@elab.am',
                ],
            ]);
    }

    public function test_public_portfolio_returns_only_published_projects_with_relations(): void
    {
        $publishedProject = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'published-app',
            'title' => 'Published App',
            'client' => 'Acme Corp',
            'summary' => 'Summary text',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => true,
            'featured' => true,
            'sort_order' => 1,
        ]);

        $draftProject = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'draft-app',
            'title' => 'Draft App',
            'client' => 'Beta Inc',
            'summary' => 'Draft summary',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => false,
            'featured' => false,
            'sort_order' => 2,
        ]);

        $category = PortfolioCategory::create([
            'id' => (string) Str::uuid(),
            'slug' => 'web',
            'name' => 'Web Apps',
            'sort_order' => 1,
        ]);

        $technology = PortfolioTechnology::create([
            'id' => (string) Str::uuid(),
            'slug' => 'laravel',
            'name' => 'Laravel Framework',
        ]);

        $image = PortfolioImage::create([
            'id' => (string) Str::uuid(),
            'project_id' => $publishedProject->id,
            'url' => '/uploads/hero.png',
            'alt' => 'Hero Image',
            'sort_order' => 1,
        ]);

        $publishedProject->categories()->attach($category->id);
        $publishedProject->technologies()->attach($technology->id);

        $response = $this->getJson('/api/v1/portfolio');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('published-app', $data[0]['slug']);
        $this->assertCount(1, $data[0]['categories']);
        $this->assertEquals('web', $data[0]['categories'][0]['slug']);
        $this->assertCount(1, $data[0]['technologies']);
        $this->assertEquals('laravel', $data[0]['technologies'][0]['slug']);
        $this->assertCount(1, $data[0]['images']);
        $this->assertEquals('/uploads/hero.png', $data[0]['images'][0]['url']);
    }

    public function test_public_portfolio_supports_filtering_and_pagination(): void
    {
        $category1 = PortfolioCategory::create([
            'id' => (string) Str::uuid(),
            'slug' => 'web',
            'name' => 'Web Applications',
        ]);
        $category2 = PortfolioCategory::create([
            'id' => (string) Str::uuid(),
            'slug' => 'mobile',
            'name' => 'Mobile Applications',
        ]);

        $p1 = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'project-one',
            'title' => 'Project One',
            'client' => 'Client A',
            'summary' => 'Summary 1',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => true,
            'featured' => true,
            'sort_order' => 1,
        ]);
        $p1->categories()->attach($category1->id);

        $p2 = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'project-two',
            'title' => 'Project Two',
            'client' => 'Client B',
            'summary' => 'Summary 2',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => true,
            'featured' => false,
            'sort_order' => 2,
        ]);
        $p2->categories()->attach($category2->id);

        // Filter by category
        $resCat = $this->getJson('/api/v1/portfolio?category=web');
        $resCat->assertStatus(200);
        $this->assertCount(1, $resCat->json('data'));
        $this->assertEquals('project-one', $resCat->json('data.0.slug'));

        // Filter by featured
        $resFeat = $this->getJson('/api/v1/portfolio?featured=true');
        $resFeat->assertStatus(200);
        $this->assertCount(1, $resFeat->json('data'));
        $this->assertEquals('project-one', $resFeat->json('data.0.slug'));

        // Pagination
        $resPag = $this->getJson('/api/v1/portfolio?page=1&per_page=1');
        $resPag->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
        $this->assertCount(1, $resPag->json('data'));
        $this->assertEquals(2, $resPag->json('meta.total'));
    }

    public function test_public_portfolio_slug_lookup_returns_published_project_or_404(): void
    {
        PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'published-slug',
            'title' => 'Published Project',
            'client' => 'Client',
            'summary' => 'Summary',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => true,
        ]);

        PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'unpublished-slug',
            'title' => 'Unpublished Project',
            'client' => 'Client',
            'summary' => 'Summary',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => false,
        ]);

        // Valid published slug -> 200
        $resValid = $this->getJson('/api/v1/portfolio/published-slug');
        $resValid->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'slug' => 'published-slug',
                    'title' => 'Published Project',
                ],
            ]);

        // Unpublished slug -> 404
        $resUnpublished = $this->getJson('/api/v1/portfolio/unpublished-slug');
        $resUnpublished->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'Resource not found']);

        // Non-existent slug -> 404
        $resNonExistent = $this->getJson('/api/v1/portfolio/non-existent');
        $resNonExistent->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'Resource not found']);
    }

    public function test_public_services_returns_only_published_services_and_features(): void
    {
        $publishedService = Service::create([
            'id' => (string) Str::uuid(),
            'slug' => 'web-dev',
            'title' => 'Web Development',
            'price_amd' => 150000,
            'tagline' => 'Custom websites',
            'description' => 'Full-stack development',
            'published' => true,
            'sort_order' => 1,
        ]);

        ServiceFeature::create([
            'id' => (string) Str::uuid(),
            'service_id' => $publishedService->id,
            'text' => 'Responsive Design',
            'sort_order' => 1,
        ]);

        Service::create([
            'id' => (string) Str::uuid(),
            'slug' => 'draft-service',
            'title' => 'Draft Service',
            'price_amd' => 150000,
            'tagline' => 'Draft tagline',
            'description' => 'Draft desc',
            'published' => false,
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('web-dev', $data[0]['slug']);
        $this->assertCount(1, $data[0]['features']);
        $this->assertEquals('Responsive Design', $data[0]['features'][0]['text']);
    }

    public function test_public_testimonials_returns_only_published_testimonials(): void
    {
        Testimonial::create([
            'id' => (string) Str::uuid(),
            'name' => 'John Doe',
            'company' => 'Tech Corp',
            'position' => 'CEO',
            'content' => 'Great work!',
            'rating' => 5,
            'published' => true,
            'sort_order' => 1,
        ]);

        Testimonial::create([
            'id' => (string) Str::uuid(),
            'name' => 'Jane Smith',
            'company' => 'Unpublished LLC',
            'content' => 'Hidden testimonial',
            'rating' => 4,
            'published' => false,
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/v1/testimonials');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('John Doe', $data[0]['name']);
    }

    public function test_public_faqs_returns_only_published_faqs(): void
    {
        FAQ::create([
            'id' => (string) Str::uuid(),
            'question' => 'How long does a project take?',
            'answer' => '2-4 weeks usually.',
            'category' => 'General',
            'published' => true,
            'sort_order' => 1,
        ]);

        FAQ::create([
            'id' => (string) Str::uuid(),
            'question' => 'Internal question?',
            'answer' => 'Internal answer.',
            'category' => 'Admin',
            'published' => false,
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/v1/faqs');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('How long does a project take?', $data[0]['question']);
    }

    public function test_public_legal_page_returns_published_page_or_404(): void
    {
        LegalPage::create([
            'id' => (string) Str::uuid(),
            'slug' => 'privacy-policy',
            'title' => 'Privacy Policy',
            'content' => 'Privacy policy content...',
            'last_updated' => '2026-01-01',
            'published' => true,
        ]);

        LegalPage::create([
            'id' => (string) Str::uuid(),
            'slug' => 'draft-terms',
            'title' => 'Draft Terms',
            'content' => 'Draft content...',
            'last_updated' => '2026-01-01',
            'published' => false,
        ]);

        // Valid published -> 200
        $resValid = $this->getJson('/api/v1/legal/privacy-policy');
        $resValid->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'slug' => 'privacy-policy',
                    'title' => 'Privacy Policy',
                ],
            ]);

        // Unpublished -> 404
        $resDraft = $this->getJson('/api/v1/legal/draft-terms');
        $resDraft->assertStatus(404);

        // Invalid slug -> 404
        $resInvalid = $this->getJson('/api/v1/legal/invalid-slug');
        $resInvalid->assertStatus(404);
    }

    public function test_public_seo_metadata_returns_public_fields(): void
    {
        SeoMetadata::create([
            'id' => (string) Str::uuid(),
            'path' => '/about',
            'title' => 'About Us - eLab',
            'description' => 'Learn about eLab team',
            'keywords' => ['web', 'design'],
            'robots' => 'index, follow',
        ]);

        $response = $this->getJson('/api/v1/seo/about');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'path' => '/about',
                    'title' => 'About Us - eLab',
                ],
            ]);
    }

    public function test_public_cookies_returns_active_cookie_settings(): void
    {
        CookieSettings::create([
            'id' => (string) Str::uuid(),
            'version' => 1,
            'banner_enabled' => true,
            'analytics_enabled' => true,
            'marketing_enabled' => false,
            'ga_measurement_id' => 'G-123456789',
        ]);

        $response = $this->getJson('/api/v1/cookies');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'version' => 1,
                    'banner_enabled' => true,
                    'analytics_enabled' => true,
                    'ga_measurement_id' => 'G-123456789',
                ],
            ]);
    }

    public function test_public_lead_submission_creates_lead_and_returns_generic_201(): void
    {
        $payload = [
            'name' => 'Arman Gevorgyan',
            'phone' => '+37455776066',
            'email' => 'arman@example.com',
            'company' => 'Tech Corp',
            'project_type' => 'corporate-website',
            'budget' => '$3,000 - $5,000',
            'message' => 'We need a modern website built in Laravel.',
            'source' => 'Website Contact Page',
        ];

        $response = $this->postJson('/api/v1/leads', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'message' => 'Your message has been received.',
                ],
            ]);

        // Assert lead exists in database with status NEW
        $this->assertDatabaseHas('leads', [
            'name' => 'Arman Gevorgyan',
            'phone' => '+37455776066',
            'email' => 'arman@example.com',
            'company' => 'Tech Corp',
            'status' => 'NEW',
        ]);

        // Assert response DOES NOT expose internal Lead ID or internal metadata
        $this->assertArrayNotHasKey('id', $response->json('data'));
        $this->assertArrayNotHasKey('leadId', $response->json('data'));
        $this->assertArrayNotHasKey('status', $response->json('data'));
    }

    public function test_public_lead_submission_validation_errors(): void
    {
        // Missing name and phone
        $response = $this->postJson('/api/v1/leads', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name', 'phone', 'email'],
            ]);
    }

    public function test_public_lead_submission_honeypot_is_silently_ignored(): void
    {
        $payload = [
            'name' => 'Bot Spammer',
            'phone' => '+1234567890',
            'email' => 'bot@spam.com',
            'message' => 'Cheap SEO services',
            'website_url' => 'http://spam-link.com', // Honeypot field filled!
        ];

        $response = $this->postJson('/api/v1/leads', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'message' => 'Your message has been received.',
                ],
            ]);

        // Assert lead was NOT persisted in database
        $this->assertDatabaseMissing('leads', [
            'email' => 'bot@spam.com',
        ]);
    }

    public function test_public_lead_submission_duplicate_prevention(): void
    {
        $payload = [
            'name' => 'Repeat User',
            'phone' => '+37499112233',
            'email' => 'repeat@example.com',
            'message' => 'First message inquiry',
        ];

        // 1st submission -> creates lead
        $res1 = $this->postJson('/api/v1/leads', $payload);
        $res1->assertStatus(201);
        $this->assertDatabaseCount('leads', 1);

        // 2nd submission within 60s -> duplicate ignored, returns success 201 without creating 2nd row
        $res2 = $this->postJson('/api/v1/leads', $payload);
        $res2->assertStatus(201);
        $this->assertDatabaseCount('leads', 1);
    }

    public function test_public_lead_submission_rate_limiting(): void
    {
        $payload = [
            'name' => 'Rate Limit Tester',
            'phone' => '+37477000000',
            'email' => 'ratelimit@example.com',
            'message' => 'Testing rate limits',
            'test_rate_limit' => 1,
        ];

        // Perform 5 requests -> allowed
        for ($i = 0; $i < 5; $i++) {
            $payload['phone'] = '+3747700000' . $i;
            $payload['email'] = "ratelimit{$i}@example.com";
            $res = $this->postJson('/api/v1/leads', $payload);
            $res->assertStatus(201);
        }

        // 6th request within 1 minute -> rate limited with 429
        $payload['phone'] = '+37477000099';
        $payload['email'] = 'ratelimit99@example.com';
        $resThrottled = $this->postJson('/api/v1/leads', $payload);

        $resThrottled->assertStatus(429);
    }
}
