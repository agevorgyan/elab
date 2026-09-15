<?php

use App\Http\Controllers\Api\V1\Admin\AuditLogController;
use App\Http\Controllers\Api\V1\Admin\CookieController;
use App\Http\Controllers\Api\V1\Admin\FaqController;
use App\Http\Controllers\Api\V1\Admin\LeadController;
use App\Http\Controllers\Api\V1\Admin\LeadNoteController;
use App\Http\Controllers\Api\V1\Admin\LegalController;
use App\Http\Controllers\Api\V1\Admin\MediaController;
use App\Http\Controllers\Api\V1\Admin\PortfolioCategoryController;
use App\Http\Controllers\Api\V1\Admin\PortfolioController;
use App\Http\Controllers\Api\V1\Admin\PortfolioImageController;
use App\Http\Controllers\Api\V1\Admin\PortfolioTechnologyController;
use App\Http\Controllers\Api\V1\Admin\SeoController;
use App\Http\Controllers\Api\V1\Admin\ServiceController;
use App\Http\Controllers\Api\V1\Admin\SettingsController;
use App\Http\Controllers\Api\V1\Admin\TestimonialController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Public\CookieController as PublicCookieController;
use App\Http\Controllers\Api\V1\Public\FaqController as PublicFaqController;
use App\Http\Controllers\Api\V1\Public\LeadController as PublicLeadController;
use App\Http\Controllers\Api\V1\Public\LegalController as PublicLegalController;
use App\Http\Controllers\Api\V1\Public\PortfolioController as PublicPortfolioController;
use App\Http\Controllers\Api\V1\Public\SeoController as PublicSeoController;
use App\Http\Controllers\Api\V1\Public\ServiceController as PublicServiceController;
use App\Http\Controllers\Api\V1\Public\SettingsController as PublicSettingsController;
use App\Http\Controllers\Api\V1\Public\TestimonialController as PublicTestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Health Check Endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'service' => 'eLab.am Laravel REST API',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // Public REST Endpoints

    Route::get('/settings', [PublicSettingsController::class, 'index']);
    Route::get('/portfolio', [PublicPortfolioController::class, 'index']);
    Route::get('/portfolio/{slug}', [PublicPortfolioController::class, 'show']);
    Route::get('/services', [PublicServiceController::class, 'index']);
    Route::get('/testimonials', [PublicTestimonialController::class, 'index']);
    Route::get('/faqs', [PublicFaqController::class, 'index']);
    Route::get('/legal/{slug}', [PublicLegalController::class, 'show']);
    Route::get('/seo/{slug?}', [PublicSeoController::class, 'show']);
    Route::get('/cookies', [PublicCookieController::class, 'show']);

    Route::post('/leads', [PublicLeadController::class, 'store'])
        ->middleware('throttle:leads-submission');
    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:reset-password');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('throttle:change-password');
        });
    });

    // Admin CMS Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
        // Users Management
        Route::apiResource('users', UserController::class)->middleware('permission:manage_users');

        // Site Settings
        Route::get('/settings', [SettingsController::class, 'index'])->middleware('permission:manage_settings');
        Route::put('/settings', [SettingsController::class, 'update'])->middleware('permission:manage_settings');

        // Portfolio Management
        Route::prefix('portfolio')->middleware('permission:manage_portfolio')->group(function () {
            Route::apiResource('categories', PortfolioCategoryController::class);
            Route::apiResource('technologies', PortfolioTechnologyController::class);

            Route::get('/{project}/images', [PortfolioImageController::class, 'index']);
            Route::post('/{project}/images', [PortfolioImageController::class, 'store']);
            Route::put('/{project}/images/{image}', [PortfolioImageController::class, 'update']);
            Route::delete('/{project}/images/{image}', [PortfolioImageController::class, 'destroy']);
        });
        Route::apiResource('portfolio', PortfolioController::class)->middleware('permission:manage_portfolio');

        // Services CMS
        Route::apiResource('services', ServiceController::class)->middleware('permission:manage_services');

        // Media Library
        Route::apiResource('media', MediaController::class)->middleware('permission:manage_media');

        // Leads CRM & Lead Notes
        Route::prefix('leads')->middleware('permission:manage_leads')->group(function () {
            Route::get('/{lead}/notes', [LeadNoteController::class, 'index']);
            Route::post('/{lead}/notes', [LeadNoteController::class, 'store']);
            Route::put('/{lead}/notes/{note}', [LeadNoteController::class, 'update']);
            Route::delete('/{lead}/notes/{note}', [LeadNoteController::class, 'destroy']);
        });
        Route::apiResource('leads', LeadController::class)->middleware('permission:manage_leads');

        // Testimonials
        Route::apiResource('testimonials', TestimonialController::class)->middleware('permission:manage_settings');

        // FAQs
        Route::apiResource('faqs', FaqController::class)->middleware('permission:manage_settings');

        // SEO Metadata
        Route::get('/seo', [SeoController::class, 'index'])->middleware('permission:manage_seo');
        Route::get('/seo/{id}', [SeoController::class, 'show'])->middleware('permission:manage_seo');
        Route::put('/seo/{id}', [SeoController::class, 'update'])->middleware('permission:manage_seo');

        // Legal Pages
        Route::apiResource('legal', LegalController::class)->middleware('permission:manage_legal');

        // Cookie Settings
        Route::get('/cookies', [CookieController::class, 'show'])->middleware('permission:manage_cookies');
        Route::put('/cookies', [CookieController::class, 'update'])->middleware('permission:manage_cookies');

        // Audit Logs (Read-only)
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('permission:view_audit_logs');
        Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->middleware('permission:view_audit_logs');
    });
});
