<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\AuditLog;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * User Login Endpoint
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $email = mb_strtolower(trim($request->input('email')));
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password_hash)) {
            AuditLog::create([
                'action' => 'LOGIN_FAILED',
                'resource' => '/api/v1/auth/login',
                'details' => 'Failed login attempt for email: ' . $email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        Auth::guard('web')->login($user);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'LOGIN',
            'resource' => '/api/v1/auth/login',
            'details' => 'User logged in successfully.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * User Logout Endpoint
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'LOGOUT',
                'resource' => '/api/v1/auth/logout',
                'details' => 'User logged out.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $user->tokens()->delete();
        }

        Auth::guard('web')->logout();
        Auth::forgetGuards();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Get Current Authenticated User Endpoint
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Change Password Endpoint
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!password_verify($request->input('current_password'), $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect current password.',
            ], 422);
        }

        $newPassword = $request->input('password');
        $newHash = password_hash($newPassword, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 3,
            'threads'     => 4,
        ]);

        $user->password_hash = $newHash;
        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'PASSWORD_CHANGE',
            'resource' => '/api/v1/auth/change-password',
            'details' => 'User updated password.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    /**
     * Forgot Password Endpoint
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = mb_strtolower(trim($request->input('email')));
        $user = User::where('email', $email)->first();

        if ($user) {
            $rawToken = Str::random(64);
            $tokenHash = hash('sha256', $rawToken);

            PasswordResetToken::where('email', $email)->delete();

            PasswordResetToken::create([
                'id' => (string) Str::uuid(),
                'email' => $email,
                'token_hash' => $tokenHash,
                'expires_at' => now()->addMinutes(60),
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'PASSWORD_RESET_REQUESTED',
                'resource' => '/api/v1/auth/forgot-password',
                'details' => 'Password reset requested for email: ' . $email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'If your email is registered, a password reset link has been sent.',
        ]);
    }

    /**
     * Reset Password Endpoint
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $email = mb_strtolower(trim($request->input('email')));
        $rawToken = $request->input('token');
        $tokenHash = hash('sha256', $rawToken);

        $resetRecord = PasswordResetToken::where('email', $email)
            ->where('token_hash', $tokenHash)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired password reset token.',
            ], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $newPassword = $request->input('password');
        $newHash = password_hash($newPassword, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 3,
            'threads'     => 4,
        ]);

        $user->password_hash = $newHash;
        $user->save();

        PasswordResetToken::where('email', $email)->delete();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'PASSWORD_RESET',
            'resource' => '/api/v1/auth/reset-password',
            'details' => 'Password reset successfully.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ]);
    }
}
