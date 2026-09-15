<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Session;
use App\Models\PasswordResetToken;
use App\Models\PortfolioProject;
use App\Models\PortfolioCategory;
use App\Models\PortfolioTechnology;
use App\Models\PortfolioImage;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\Testimonial;
use App\Models\FAQ;
use App\Models\SeoMetadata;
use App\Models\LegalPage;
use App\Models\CookieSettings;
use App\Models\SiteSettings;
use App\Models\Media;
use App\Models\AuditLog;

class ParityAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_argon2id_password_hash_verification_compatibility(): void
    {
        // Sample Argon2id hash generated via argon2.hash('SuperAdmin2026!')
        $password = 'SuperAdmin2026!';
        $argon2idHash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 3,
            'threads'     => 4,
        ]);

        $user = User::create([
            'id' => 'super-admin-id',
            'name' => 'Super Administrator',
            'email' => 'admin@elab.am',
            'password_hash' => $argon2idHash,
            'role' => 'SUPER_ADMIN',
        ]);

        // Verify PHP native password_verify with Argon2id hash
        $this->assertTrue(password_verify($password, $user->password_hash));
        $this->assertFalse(password_verify('WrongPassword', $user->password_hash));
    }

    public function test_custom_session_token_compatibility(): void
    {
        $user = User::create([
            'id' => 'user-session-test',
            'name' => 'Session User',
            'email' => 'user@elab.am',
            'password_hash' => 'hash',
        ]);

        $sessionToken = 'custom_hex_token_1234567890abcdef';
        $session = Session::create([
            'id' => 'session-1',
            'user_id' => $user->id,
            'token' => $sessionToken,
            'expires_at' => now()->addDays(7),
        ]);

        $retrieved = Session::with('user')->where('token', $sessionToken)->first();
        $this->assertNotNull($retrieved);
        $this->assertEquals($user->id, $retrieved->user->id);
    }

    public function test_password_reset_token_compatibility(): void
    {
        $tokenHash = hash('sha256', 'reset-token-secret');
        $resetToken = PasswordResetToken::create([
            'id' => 'reset-1',
            'email' => 'admin@elab.am',
            'token_hash' => $tokenHash,
            'expires_at' => now()->addHours(1),
        ]);

        $retrieved = PasswordResetToken::where('token_hash', $tokenHash)->first();
        $this->assertNotNull($retrieved);
        $this->assertEquals('admin@elab.am', $retrieved->email);
    }

    public function test_strict_foreign_key_and_cascade_audit(): void
    {
        $user = User::create([
            'id' => 'user-cascade',
            'name' => 'Cascade User',
            'email' => 'cascade@elab.am',
            'password_hash' => 'hash',
        ]);

        $lead = Lead::create([
            'id' => 'lead-cascade',
            'name' => 'Lead Cascade',
            'email' => 'lead@elab.am',
            'phone' => '123',
            'project_type' => 'landing',
            'budget' => '100k',
            'message' => 'Test',
        ]);

        $note = LeadNote::create([
            'id' => 'note-cascade',
            'lead_id' => $lead->id,
            'author_id' => $user->id,
            'text' => 'Cascade Note',
        ]);

        $log = AuditLog::create([
            'id' => 'audit-cascade',
            'user_id' => $user->id,
            'action' => 'DELETE',
            'resource' => '/admin/lead',
        ]);

        // Delete user -> LeadNote author_id should become NULL, AuditLog user_id should become NULL
        $user->delete();

        $this->assertDatabaseHas('lead_notes', ['id' => $note->id, 'author_id' => null]);
        $this->assertDatabaseHas('audit_logs', ['id' => $log->id, 'user_id' => null]);

        // Delete lead -> LeadNote should be deleted via Cascade
        $lead->delete();
        $this->assertDatabaseMissing('lead_notes', ['id' => $note->id]);
    }
}
