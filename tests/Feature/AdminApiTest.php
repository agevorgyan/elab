<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\CookieSettings;
use App\Models\FAQ;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LegalPage;
use App\Models\Media;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use App\Models\PortfolioTechnology;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\SiteSettings;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $admin;
    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $hash = password_hash('SuperAdmin2026!', PASSWORD_ARGON2ID);

        $this->superAdmin = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'super@elab.am',
            'password_hash' => $hash,
            'role' => 'SUPER_ADMIN',
        ]);

        $this->admin = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Standard Admin',
            'email' => 'admin_role@elab.am',
            'password_hash' => $hash,
            'role' => 'ADMIN',
        ]);

        $this->editor = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Editor User',
            'email' => 'editor@elab.am',
            'password_hash' => $hash,
            'role' => 'EDITOR',
        ]);
    }

    // AUTHORIZATION TESTS
    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/v1/admin/portfolio')->assertStatus(401);
    }

    public function test_unauthorized_user_without_permission_returns_403(): void
    {
        $this->actingAs($this->editor)->getJson('/api/v1/admin/users')->assertStatus(403);
    }

    public function test_super_admin_has_full_access(): void
    {
        $this->actingAs($this->superAdmin)->getJson('/api/v1/admin/users')->assertStatus(200);
        $this->actingAs($this->superAdmin)->getJson('/api/v1/admin/settings')->assertStatus(200);
    }

    // USERS TESTS
    public function test_user_crud_operations(): void
    {
        // 1. Create User
        $res = $this->actingAs($this->superAdmin)->postJson('/api/v1/admin/users', [
            'name' => 'New User',
            'email' => 'newuser@elab.am',
            'password' => 'NewUserPass123!',
            'role' => 'EDITOR',
        ]);

        $res->assertStatus(201)
            ->assertJsonPath('data.email', 'newuser@elab.am');

        $userId = $res->json('data.id');

        // 2. Password Hash Not Exposed
        $this->assertStringNotContainsString('password_hash', $res->getContent());

        // 3. Update User
        $this->actingAs($this->superAdmin)->putJson("/api/v1/admin/users/{$userId}", [
            'name' => 'Updated User Name',
        ])->assertStatus(200)->assertJsonPath('data.name', 'Updated User Name');

        // 4. Delete User
        $this->actingAs($this->superAdmin)->deleteJson("/api/v1/admin/users/{$userId}")
            ->assertStatus(200);
    }

    public function test_prevent_demoting_or_deleting_last_super_admin(): void
    {
        $this->actingAs($this->superAdmin)->putJson("/api/v1/admin/users/{$this->superAdmin->id}", [
            'role' => 'ADMIN',
        ])->assertStatus(422);

        $this->actingAs($this->superAdmin)->deleteJson("/api/v1/admin/users/{$this->superAdmin->id}")
            ->assertStatus(422);
    }

    // SITE SETTINGS TESTS
    public function test_site_settings_get_and_update(): void
    {
        $this->actingAs($this->admin)->getJson('/api/v1/admin/settings')->assertStatus(200);

        $res = $this->actingAs($this->admin)->putJson('/api/v1/admin/settings', [
            'settings' => [
                ['key' => 'siteName', 'value' => 'eLab Digital Studio', 'category' => 'general'],
                ['key' => 'email', 'value' => 'hello@elab.am', 'category' => 'contact'],
            ],
        ]);

        $res->assertStatus(200);
        $this->assertDatabaseHas('site_settings', ['key' => 'siteName', 'value' => 'eLab Digital Studio']);
    }

    // PORTFOLIO TESTS
    public function test_portfolio_crud_and_relationships(): void
    {
        $cat = PortfolioCategory::create([
            'id' => (string) Str::uuid(),
            'slug' => 'corporate',
            'name' => 'Corporate',
        ]);

        $tech = PortfolioTechnology::create([
            'id' => (string) Str::uuid(),
            'slug' => 'react',
            'name' => 'React',
        ]);

        // Create Project
        $res = $this->actingAs($this->editor)->postJson('/api/v1/admin/portfolio', [
            'title' => 'Ararat Beverages',
            'slug' => 'ararat-beverages',
            'client' => 'Ararat LLC',
            'summary' => 'Corporate Site',
            'challenge' => 'Modern UX',
            'solution' => 'React Web App',
            'year' => '2026',
            'categories' => [$cat->id],
            'technologies' => [$tech->id],
            'images' => [
                ['url' => '/hero.png', 'alt' => 'Hero Banner'],
            ],
        ]);

        $res->assertStatus(201);
        $projId = $res->json('data.id');

        // Duplicate Slug 409 Rejection
        $this->actingAs($this->editor)->postJson('/api/v1/admin/portfolio', [
            'title' => 'Duplicate Project',
            'slug' => 'ararat-beverages',
            'client' => 'Client',
            'summary' => 'Summary',
            'challenge' => 'Challenge',
            'solution' => 'Solution',
            'year' => '2026',
        ])->assertStatus(409);

        // Same project update allowed
        $this->actingAs($this->editor)->putJson("/api/v1/admin/portfolio/{$projId}", [
            'slug' => 'ararat-beverages',
            'title' => 'Ararat Beverages Updated',
        ])->assertStatus(200);

        // Delete project
        $this->actingAs($this->editor)->deleteJson("/api/v1/admin/portfolio/{$projId}")
            ->assertStatus(200);
    }

    // PORTFOLIO IMAGE IDOR OWNERSHIP TEST
    public function test_portfolio_image_idor_protection(): void
    {
        $proj1 = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'proj-1',
            'title' => 'Project 1',
            'client' => 'Client 1',
            'summary' => 'Sum 1',
            'challenge' => 'Ch 1',
            'solution' => 'Sol 1',
            'year' => '2026',
        ]);

        $proj2 = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'slug' => 'proj-2',
            'title' => 'Project 2',
            'client' => 'Client 2',
            'summary' => 'Sum 2',
            'challenge' => 'Ch 2',
            'solution' => 'Sol 2',
            'year' => '2026',
        ]);

        $img1 = PortfolioImage::create([
            'id' => (string) Str::uuid(),
            'project_id' => $proj1->id,
            'url' => '/proj1.png',
        ]);

        // Attempting to update img1 belonging to proj1 through proj2 URL must be REJECTED (403/404)
        $this->actingAs($this->editor)->putJson("/api/v1/admin/portfolio/{$proj2->id}/images/{$img1->id}", [
            'url' => '/hacked.png',
        ])->assertStatus(403);
    }

    // SERVICES TESTS
    public function test_services_crud_and_nested_features(): void
    {
        $res = $this->actingAs($this->editor)->postJson('/api/v1/admin/services', [
            'title' => 'Landing Page',
            'slug' => 'landing-page',
            'price_amd' => '150,000',
            'description' => 'Single page high conversion site',
            'features' => [
                ['text' => 'Responsive Design'],
                ['text' => 'Fast Speed'],
            ],
        ]);

        $res->assertStatus(201)->assertJsonCount(2, 'data.features');
        $serviceId = $res->json('data.id');

        $this->actingAs($this->editor)->deleteJson("/api/v1/admin/services/{$serviceId}")
            ->assertStatus(200);
    }

    // MEDIA TESTS
    public function test_media_upload_validation_and_unsafe_file_rejection(): void
    {
        Storage::fake('public');

        // Valid upload
        $file = UploadedFile::fake()->image('banner.jpg');
        $res = $this->actingAs($this->editor)->postJson('/api/v1/admin/media', [
            'file' => $file,
            'alt' => 'Banner image',
        ]);

        $res->assertStatus(201);

        // Unsafe PHP executable script upload attempt MUST BE REJECTED
        $unsafeScript = UploadedFile::fake()->create('malicious.php', 10, 'text/x-php');
        $this->actingAs($this->editor)->postJson('/api/v1/admin/media', [
            'file' => $unsafeScript,
        ])->assertStatus(422);
    }

    // LEADS TESTS & NOTE IDOR PROTECTION
    public function test_leads_crud_and_note_idor_protection(): void
    {
        $lead1 = Lead::create([
            'id' => (string) Str::uuid(),
            'name' => 'Arman',
            'email' => 'arman@example.com',
            'phone' => '+374 91 11 22 33',
            'project_type' => 'online-store',
            'budget' => '350,000 AMD',
            'message' => 'E-commerce request',
            'status' => 'NEW',
        ]);

        $lead2 = Lead::create([
            'id' => (string) Str::uuid(),
            'name' => 'Siranush',
            'email' => 'siranush@example.com',
            'phone' => '+374 94 99 88 77',
            'project_type' => 'corporate',
            'budget' => '200,000 AMD',
            'message' => 'Corporate site',
            'status' => 'CONTACTED',
        ]);

        // Add Note
        $res = $this->actingAs($this->admin)->postJson("/api/v1/admin/leads/{$lead1->id}/notes", [
            'text' => 'Called client.',
        ]);
        $res->assertStatus(201);
        $noteId = $res->json('data.id');

        // IDOR Protection: Updating lead1 note through lead2 URL must be REJECTED (403)
        $this->actingAs($this->admin)->putJson("/api/v1/admin/leads/{$lead2->id}/notes/{$noteId}", [
            'text' => 'Hacked Note',
        ])->assertStatus(403);
    }

    // OTHER CONTENT CMS TESTS (Testimonials, FAQ, SEO, Legal, Cookies, Audit Logs)
    public function test_testimonials_faq_seo_legal_cookies_audit_logs(): void
    {
        // Testimonials
        $tRes = $this->actingAs($this->admin)->postJson('/api/v1/admin/testimonials', [
            'name' => 'John Doe',
            'content' => 'Excellent work!',
            'rating' => 5,
        ])->assertStatus(201);

        // FAQ
        $fRes = $this->actingAs($this->admin)->postJson('/api/v1/admin/faqs', [
            'question' => 'How long?',
            'answer' => '2 weeks',
        ])->assertStatus(201);

        // SEO
        $this->actingAs($this->admin)->putJson('/api/v1/admin/seo/home-page-id', [
            'path' => '/',
            'title' => 'eLab Digital Studio',
            'description' => 'Modern websites in Armenia',
        ])->assertStatus(200);

        // Legal
        $this->actingAs($this->admin)->postJson('/api/v1/admin/legal', [
            'slug' => 'privacy',
            'title' => 'Privacy Policy',
            'content' => 'Legal Content',
            'last_updated' => '2026-09-07',
        ])->assertStatus(201);

        // Cookies
        $this->actingAs($this->admin)->putJson('/api/v1/admin/cookies', [
            'banner_enabled' => true,
            'analytics_enabled' => true,
            'marketing_enabled' => false,
        ])->assertStatus(200);

        // Audit Logs (Read-only)
        $this->actingAs($this->admin)->getJson('/api/v1/admin/audit-logs')->assertStatus(200);

        // Audit Logs mutation must NOT be supported
        $this->actingAs($this->admin)->postJson('/api/v1/admin/audit-logs', [])->assertStatus(405);
    }
}
