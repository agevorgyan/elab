<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Session;
use App\Models\PasswordResetToken;
use App\Models\SiteSettings;
use App\Models\PortfolioCategory;
use App\Models\PortfolioTechnology;
use App\Models\PortfolioProject;
use App\Models\PortfolioImage;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\Media;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\Testimonial;
use App\Models\FAQ;
use App\Models\SeoMetadata;
use App\Models\LegalPage;
use App\Models\CookieSettings;
use App\Models\AuditLog;

class SchemaVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_and_relations(): void
    {
        $user = User::create([
            'id' => 'test-user-1',
            'name' => 'Test Super Admin',
            'email' => 'admin@elab.am',
            'password_hash' => '$argon2id$v=19$m=65536,t=3,p=4$fakehash',
            'role' => 'SUPER_ADMIN',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'admin@elab.am']);
        $this->assertEquals('SUPER_ADMIN', $user->role);
        $this->assertArrayNotHasKey('password_hash', $user->toArray());
    }

    public function test_portfolio_project_relationships_and_slug_uniqueness(): void
    {
        $category = PortfolioCategory::create([
            'id' => 'cat-1',
            'slug' => 'corporate',
            'name' => 'Corporate Websites',
        ]);

        $tech = PortfolioTechnology::create([
            'id' => 'tech-1',
            'slug' => 'nextjs',
            'name' => 'Next.js',
        ]);

        $project = PortfolioProject::create([
            'id' => 'proj-1',
            'slug' => 'ararat-beverages',
            'title' => 'Ararat Beverages Corporate Portal',
            'client' => 'Ararat LLC',
            'summary' => 'Corporate site summary',
            'challenge' => 'Modern branding',
            'solution' => 'Bespoke Next.js site',
            'services' => ['Web Development', 'UI/UX Design'],
            'results' => ['+150% traffic', 'Sub-second load'],
            'year' => '2026',
        ]);

        $project->categories()->attach($category->id);
        $project->technologies()->attach($tech->id);

        $image = PortfolioImage::create([
            'id' => 'img-1',
            'project_id' => $project->id,
            'url' => '/images/hero.jpg',
            'alt' => 'Hero image',
        ]);

        // Verify relationships
        $retrievedProject = PortfolioProject::with(['categories', 'technologies', 'images'])->find($project->id);
        $this->assertCount(1, $retrievedProject->categories);
        $this->assertEquals('Corporate Websites', $retrievedProject->categories->first()->name);
        $this->assertCount(1, $retrievedProject->technologies);
        $this->assertEquals('Next.js', $retrievedProject->technologies->first()->name);
        $this->assertCount(1, $retrievedProject->images);
        $this->assertEquals('/images/hero.jpg', $retrievedProject->images->first()->url);
        $this->assertIsArray($retrievedProject->services);
        $this->assertContains('Web Development', $retrievedProject->services);

        // Test duplicate slug uniqueness rejection
        $this->expectException(QueryException::class);
        PortfolioProject::create([
            'id' => 'proj-2',
            'slug' => 'ararat-beverages', // Duplicate slug!
            'title' => 'Duplicate Slug Project',
            'client' => 'Client',
            'summary' => 'Summary',
            'challenge' => 'Challenge',
            'solution' => 'Solution',
            'year' => '2026',
        ]);
    }

    public function test_lead_and_lead_notes_relations(): void
    {
        $user = User::create([
            'id' => 'author-1',
            'name' => 'Admin Author',
            'email' => 'author@elab.am',
            'password_hash' => 'hash',
        ]);

        $lead = Lead::create([
            'id' => 'lead-101',
            'name' => 'Arman Petrosyan',
            'email' => 'arman@example.com',
            'phone' => '+374 91 12 34 56',
            'project_type' => 'online-store',
            'budget' => '350,000 AMD',
            'message' => 'Need e-commerce site',
            'status' => 'NEW',
        ]);

        $note = LeadNote::create([
            'id' => 'note-1',
            'lead_id' => $lead->id,
            'author_id' => $user->id,
            'text' => 'Initial inquiry received.',
        ]);

        $retrievedLead = Lead::with('notes.author')->find($lead->id);
        $this->assertCount(1, $retrievedLead->notes);
        $this->assertEquals('Initial inquiry received.', $retrievedLead->notes->first()->text);
        $this->assertEquals('Admin Author', $retrievedLead->notes->first()->author->name);
    }

    public function test_all_remaining_models(): void
    {
        SiteSettings::create(['key' => 'siteName', 'value' => 'eLab', 'category' => 'general']);
        $service = Service::create([
            'slug' => 'corporate-website',
            'title' => 'Corporate Site',
            'price_amd' => '250,000',
            'description' => 'Multi-page site',
        ]);
        ServiceFeature::create(['service_id' => $service->id, 'text' => 'Bilingual support']);
        Testimonial::create(['name' => 'John Doe', 'content' => 'Great work!']);
        FAQ::create(['question' => 'How long does it take?', 'answer' => '2-4 weeks']);
        SeoMetadata::create(['path' => '/', 'title' => 'Home', 'description' => 'eLab digital studio']);
        LegalPage::create(['slug' => 'privacy', 'title' => 'Privacy Policy', 'content' => 'Content', 'last_updated' => '2026-09-07']);
        CookieSettings::create(['version' => 1, 'banner_enabled' => true]);
        Media::create(['name' => 'logo.png', 'path' => 'uploads/logo.png', 'url' => '/storage/uploads/logo.png', 'mime_type' => 'image/png', 'size_bytes' => 12345]);
        AuditLog::create(['action' => 'LOGIN', 'resource' => '/auth/login']);

        $this->assertDatabaseHas('site_settings', ['key' => 'siteName']);
        $this->assertDatabaseHas('services', ['slug' => 'corporate-website']);
        $this->assertDatabaseHas('service_features', ['text' => 'Bilingual support']);
        $this->assertDatabaseHas('testimonials', ['name' => 'John Doe']);
        $this->assertDatabaseHas('faqs', ['question' => 'How long does it take?']);
        $this->assertDatabaseHas('seo_metadata', ['path' => '/']);
        $this->assertDatabaseHas('legal_pages', ['slug' => 'privacy']);
        $this->assertDatabaseHas('cookie_settings', ['version' => 1]);
        $this->assertDatabaseHas('media', ['path' => 'uploads/logo.png']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'LOGIN']);
    }
}
