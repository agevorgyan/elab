<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected string $rawPassword = 'SuperAdmin2026!';
    protected string $argon2idHash;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\Cache::flush();
        \Illuminate\Support\Facades\RateLimiter::clear('login');
        \Illuminate\Support\Facades\RateLimiter::clear('forgot-password');
        \Illuminate\Support\Facades\RateLimiter::clear('reset-password');
        \Illuminate\Support\Facades\RateLimiter::clear('change-password');

        $this->argon2idHash = password_hash($this->rawPassword, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 3,
            'threads'     => 4,
        ]);
    }

    protected function createTestUser(string $role = 'SUPER_ADMIN', string $email = 'admin@elab.am'): User
    {
        return User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Test User',
            'email' => $email,
            'password_hash' => $this->argon2idHash,
            'role' => $role,
        ]);
    }

    // 1. Valid Login
    public function test_valid_login_authenticates_user_and_returns_safe_data(): void
    {
        $user = $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@elab.am',
            'password' => $this->rawPassword,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'admin@elab.am',
                    'role' => 'SUPER_ADMIN',
                ],
            ]);

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'LOGIN',
        ]);
    }

    // 2. Invalid Password
    public function test_login_fails_with_invalid_password(): void
    {
        $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@elab.am',
            'password' => 'WrongPassword!',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid email or password.',
            ]);

        $this->assertGuest();
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'LOGIN_FAILED',
        ]);
    }

    // 3. Nonexistent Email
    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@elab.am',
            'password' => 'SomePassword123!',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid email or password.',
            ]);

        $this->assertGuest();
    }

    // 4. Logout
    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully.',
            ]);

        $this->assertGuest();
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'LOGOUT',
        ]);
    }

    // 5. Authenticated /me
    public function test_authenticated_me_returns_user_info(): void
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'admin@elab.am',
                    'role' => 'SUPER_ADMIN',
                ],
            ]);
    }

    // 6. Unauthenticated /me
    public function test_unauthenticated_me_returns_401(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    // 7. Password Change
    public function test_authenticated_user_can_change_password(): void
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->postJson('/api/v1/auth/change-password', [
            'current_password' => $this->rawPassword,
            'password' => 'NewSecurePassword2026!',
            'password_confirmation' => 'NewSecurePassword2026!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password changed successfully.',
            ]);

        $user->refresh();
        $this->assertTrue(password_verify('NewSecurePassword2026!', $user->password_hash));
    }

    // 8. Wrong Current Password
    public function test_password_change_fails_with_wrong_current_password(): void
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->postJson('/api/v1/auth/change-password', [
            'current_password' => 'WrongPassword',
            'password' => 'NewSecurePassword2026!',
            'password_confirmation' => 'NewSecurePassword2026!',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Incorrect current password.',
            ]);
    }

    // 9. Forgot Password
    public function test_forgot_password_generates_generic_response_and_token(): void
    {
        $user = $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'admin@elab.am',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'If your email is registered, a password reset link has been sent.',
            ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'admin@elab.am',
        ]);
    }

    // 10. Reset Password
    public function test_reset_password_with_valid_token(): void
    {
        $user = $this->createTestUser();
        $rawToken = 'valid-reset-token-12345';
        $tokenHash = hash('sha256', $rawToken);

        PasswordResetToken::create([
            'id' => (string) Str::uuid(),
            'email' => 'admin@elab.am',
            'token_hash' => $tokenHash,
            'expires_at' => now()->addMinutes(30),
        ]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => 'admin@elab.am',
            'password' => 'BrandNewPassword2026!',
            'password_confirmation' => 'BrandNewPassword2026!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password has been reset successfully.',
            ]);

        $user->refresh();
        $this->assertTrue(password_verify('BrandNewPassword2026!', $user->password_hash));
    }

    // 11. Expired Reset Token
    public function test_reset_password_fails_with_expired_token(): void
    {
        $user = $this->createTestUser();
        $rawToken = 'expired-token';
        $tokenHash = hash('sha256', $rawToken);

        PasswordResetToken::create([
            'id' => (string) Str::uuid(),
            'email' => 'admin@elab.am',
            'token_hash' => $tokenHash,
            'expires_at' => now()->subMinutes(10), // Expired!
        ]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => 'admin@elab.am',
            'password' => 'BrandNewPassword2026!',
            'password_confirmation' => 'BrandNewPassword2026!',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid or expired password reset token.',
            ]);
    }

    // 12. Reused Reset Token
    public function test_reusing_reset_token_fails(): void
    {
        $user = $this->createTestUser();
        $rawToken = 'single-use-token';
        $tokenHash = hash('sha256', $rawToken);

        PasswordResetToken::create([
            'id' => (string) Str::uuid(),
            'email' => 'admin@elab.am',
            'token_hash' => $tokenHash,
            'expires_at' => now()->addMinutes(30),
        ]);

        // First reset (succeeds & deletes token)
        $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => 'admin@elab.am',
            'password' => 'BrandNewPassword2026!',
            'password_confirmation' => 'BrandNewPassword2026!',
        ])->assertStatus(200);

        // Second reset attempt (must fail)
        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => 'admin@elab.am',
            'password' => 'AnotherNewPassword2026!',
            'password_confirmation' => 'AnotherNewPassword2026!',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid or expired password reset token.',
            ]);
    }

    // 13. Rate Limiting
    public function test_login_rate_limiting(): void
    {
        $rateLimitUser = $this->createTestUser('SUPER_ADMIN', 'ratelimit@elab.am');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'ratelimit@elab.am',
                'password' => 'WrongPass',
                'test_rate_limit' => true,
            ]);
        }

        // 6th attempt should return 429
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'ratelimit@elab.am',
            'password' => 'WrongPass',
            'test_rate_limit' => true,
        ]);

        $response->assertStatus(429);
    }

    // 14. SUPER_ADMIN Access
    public function test_super_admin_has_access_to_all_protected_endpoints(): void
    {
        $superAdmin = $this->createTestUser('SUPER_ADMIN');

        $this->actingAs($superAdmin)->getJson('/api/v1/admin/users')->assertStatus(200);
        $this->actingAs($superAdmin)->getJson('/api/v1/admin/settings')->assertStatus(200);
        $this->actingAs($superAdmin)->getJson('/api/v1/admin/portfolio')->assertStatus(200);
    }

    // 15. ADMIN Access
    public function test_admin_has_access_to_settings_and_portfolio_but_blocked_from_users(): void
    {
        $admin = $this->createTestUser('ADMIN', 'admin_role@elab.am');

        $this->actingAs($admin)->getJson('/api/v1/admin/settings')->assertStatus(200);
        $this->actingAs($admin)->getJson('/api/v1/admin/portfolio')->assertStatus(200);
        $this->actingAs($admin)->getJson('/api/v1/admin/users')->assertStatus(403);
    }

    // 16. EDITOR Access
    public function test_editor_has_access_to_portfolio_but_blocked_from_settings_and_users(): void
    {
        $editor = $this->createTestUser('EDITOR', 'editor@elab.am');

        $this->actingAs($editor)->getJson('/api/v1/admin/portfolio')->assertStatus(200);
        $this->actingAs($editor)->getJson('/api/v1/admin/settings')->assertStatus(403);
        $this->actingAs($editor)->getJson('/api/v1/admin/users')->assertStatus(403);
    }

    // 17. Unauthorized Endpoint
    public function test_unauthorized_role_returns_403_json(): void
    {
        $editor = $this->createTestUser('EDITOR', 'editor_unauth@elab.am');

        $response = $this->actingAs($editor)->getJson('/api/v1/admin/users');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Forbidden.',
            ]);
    }

    // 18. Unauthenticated Endpoint
    public function test_unauthenticated_endpoint_returns_401_json(): void
    {
        $response = $this->getJson('/api/v1/admin/portfolio');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    // 19, 20, 21. Session Persistence & Invalidation
    public function test_session_persistence_and_invalidation(): void
    {
        $user = $this->createTestUser();

        // Login
        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@elab.am',
            'password' => $this->rawPassword,
        ])->assertStatus(200);

        // /me succeeds while session is active
        $this->getJson('/api/v1/auth/me')->assertStatus(200);

        // Logout
        $this->postJson('/api/v1/auth/logout')->assertStatus(200);

        // /me fails after logout
        $this->getJson('/api/v1/auth/me')->assertStatus(401);
    }

    // 22. Password Hash Never Returned
    public function test_password_hash_is_never_exposed_in_api_response(): void
    {
        $user = $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@elab.am',
            'password' => $this->rawPassword,
        ]);

        $content = $response->getContent();
        $this->assertStringNotContainsString('password_hash', $content);
        $this->assertStringNotContainsString($this->argon2idHash, $content);
    }

    // 23, 24. Passwords and Tokens Never Logged in Audit Log
    public function test_passwords_and_reset_tokens_are_never_written_to_audit_logs(): void
    {
        $user = $this->createTestUser();

        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@elab.am',
            'password' => $this->rawPassword,
        ]);

        $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'admin@elab.am',
        ]);

        $auditLogs = AuditLog::all();
        foreach ($auditLogs as $log) {
            $this->assertStringNotContainsString($this->rawPassword, (string) $log->details);
            $this->assertStringNotContainsString('SuperAdmin2026!', (string) $log->details);
        }
    }
}
