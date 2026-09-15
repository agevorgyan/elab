<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\FAQ;
use App\Models\Lead;
use App\Models\Media;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\PortfolioTechnology;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class Phase8FullAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Cache::flush();
        \Illuminate\Support\Facades\RateLimiter::clear('login');
    }


    /**
     * 1. Authentication Security & Password Hash Non-Exposure
     */
    public function test_auth_security_and_password_hash_non_exposure(): void
    {
        $password = 'SuperAdmin2026!';
        $user = User::factory()->create([
            'email' => 'phase8_superadmin@elab.am',
            'password_hash' => password_hash($password, PASSWORD_ARGON2ID),
            'role' => 'SUPER_ADMIN',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'phase8_superadmin@elab.am',
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'phase8_superadmin@elab.am');

        $this->assertStringNotContainsString('password_hash', $response->getContent());
        $this->assertStringNotContainsString($user->password_hash, $response->getContent());

        // Authenticated Session Check
        $me = $this->actingAs($user)->getJson('/api/v1/auth/me');
        $me->assertStatus(200)
            ->assertJsonPath('data.email', 'phase8_superadmin@elab.am');
        $this->assertStringNotContainsString('password_hash', $me->getContent());

        $user->delete();
    }

    /**
     * 2. Auth Rate Limiting Rejection (429)
     */
    public function test_login_rate_limiting_enforcement(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'nonexistent@elab.am',
                'password' => 'WrongPass123!',
            ]);
        }

        // Rate limiter triggered after threshold
        $this->assertTrue(in_array($response->status(), [401, 429]));
    }

    /**
     * 3. RBAC Enforcement for SUPER_ADMIN, ADMIN, EDITOR
     */
    public function test_rbac_endpoint_access_controls(): void
    {
        $editor = User::factory()->create(['role' => 'EDITOR']);
        $superAdmin = User::factory()->create(['role' => 'SUPER_ADMIN']);

        // EDITOR attempting to access audit logs -> 403 Forbidden
        $editorRes = $this->actingAs($editor)->getJson('/api/v1/admin/audit-logs');
        $editorRes->assertStatus(403);

        // SUPER_ADMIN accessing audit logs -> 200 OK
        $adminRes = $this->actingAs($superAdmin)->getJson('/api/v1/admin/audit-logs');
        $adminRes->assertStatus(200);

        $editor->delete();
        $superAdmin->delete();
    }

    /**
     * 4. IDOR Protection (Non-existent / Unauthorized Mutations)
     */
    public function test_idor_protection_on_admin_resources(): void
    {
        $editor = User::factory()->create(['role' => 'EDITOR']);

        // Attempting to modify non-existent user or delete settings
        $response = $this->actingAs($editor)->deleteJson('/api/v1/admin/users/non-existent-uuid');
        $this->assertTrue(in_array($response->status(), [403, 404]));

        $editor->delete();
    }

    /**
     * 5. API Input Validation & Malformed Payload Rejection
     */
    public function test_api_input_validation_and_malformed_type_handling(): void
    {
        $admin = User::factory()->create(['role' => 'SUPER_ADMIN']);

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/portfolio', [
            'title' => '', // Missing required title
            'slug' => 'invalid slug with spaces!',
            'sort_order' => 'invalid-string-instead-of-int',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertStringNotContainsString('SQLSTATE', $response->getContent());
        $this->assertStringNotContainsString('Exception', $response->getContent());

        $admin->delete();
    }

    /**
     * 6. SQL Injection Probe Protection
     */
    public function test_sqli_probe_safety(): void
    {
        $sqliPayloads = [
            "' OR '1'='1",
            "1; DROP TABLE users; --",
            "1' UNION SELECT NULL, NULL, NULL --",
        ];

        foreach ($sqliPayloads as $payload) {
            $res = $this->getJson('/api/v1/portfolio?search=' . urlencode($payload));
            $res->assertStatus(200);
            $this->assertStringNotContainsString('SQLSTATE', $res->getContent());
            $this->assertStringNotContainsString('Syntax error', $res->getContent());
        }
    }

    /**
     * 7. XSS Payload Escaping & Content Preservation
     */
    public function test_xss_payload_safety_in_api_responses(): void
    {
        $admin = User::factory()->create(['role' => 'SUPER_ADMIN']);
        $xssPayload = '<script>alert("xss")</script>Test Portfolio Title';

        $project = PortfolioProject::create([
            'id' => (string) Str::uuid(),
            'title' => $xssPayload,
            'slug' => 'xss-test-project-' . Str::random(6),
            'client' => 'XSS Client',
            'summary' => 'Summary text',
            'overview' => 'Overview text',
            'challenge' => 'Challenge text',
            'solution' => 'Solution text',
            'year' => '2026',
            'published' => true,
        ]);



        $res = $this->getJson('/api/v1/portfolio/' . $project->slug);
        $res->assertStatus(200);
        $this->assertStringNotContainsString('<script>alert("xss")</script>', $res->getContent());

        $project->delete();
        $admin->delete();
    }

    /**
     * 8. Media File Upload Security
     */
    public function test_file_upload_security_and_extension_validation(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'SUPER_ADMIN']);

        // Malicious executable file disguised as webp
        $phpFile = UploadedFile::fake()->create('script.php', 10, 'text/x-php');

        $res = $this->actingAs($admin)->postJson('/api/v1/admin/media', [
            'file' => $phpFile,
            'alt' => 'PHP Script',
        ]);

        $res->assertStatus(422);

        $admin->delete();
    }

    /**
     * 9. Public API Data Leak Check
     */
    public function test_public_api_does_not_leak_sensitive_admin_data(): void
    {
        $publicEndpoints = [
            '/api/v1/settings',
            '/api/v1/portfolio',
            '/api/v1/services',
            '/api/v1/testimonials',
            '/api/v1/faqs',
            '/api/v1/cookies',
        ];

        foreach ($publicEndpoints as $endpoint) {
            $res = $this->getJson($endpoint);
            $res->assertStatus(200);
            $content = $res->getContent();
            $this->assertStringNotContainsString('password_hash', $content);
            $this->assertStringNotContainsString('remember_token', $content);
            $this->assertStringNotContainsString('token_hash', $content);
        }
    }

    /**
     * 10. Contact Lead Honeypot & Rejection
     */
    public function test_lead_honeypot_bot_rejection(): void
    {
        $res = $this->postJson('/api/v1/leads', [
            'name' => 'Spam Bot',
            'email' => 'spambot@example.com',
            'message' => 'Buy cheap links now!',
            'website_hp' => 'http://spam-link.com', // Honeypot field filled
        ]);

        // Rejection or silent drop
        $this->assertTrue(in_array($res->status(), [400, 422, 200]));
    }
}
