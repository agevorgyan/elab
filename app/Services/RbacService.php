<?php

namespace App\Services;

use App\Models\User;

class RbacService
{
    public const PERMISSIONS = [
        'manage_users',
        'manage_settings',
        'manage_portfolio',
        'manage_services',
        'manage_media',
        'manage_leads',
        'manage_seo',
        'manage_legal',
        'manage_cookies',
        'view_audit_logs',
    ];

    public const ROLE_PERMISSIONS = [
        'SUPER_ADMIN' => [
            'manage_users',
            'manage_settings',
            'manage_portfolio',
            'manage_services',
            'manage_media',
            'manage_leads',
            'manage_seo',
            'manage_legal',
            'manage_cookies',
            'view_audit_logs',
        ],
        'ADMIN' => [
            'manage_settings',
            'manage_portfolio',
            'manage_services',
            'manage_media',
            'manage_leads',
            'manage_seo',
            'manage_legal',
            'manage_cookies',
            'view_audit_logs',
        ],
        'EDITOR' => [
            'manage_portfolio',
            'manage_services',
            'manage_media',
            'manage_seo',
            'manage_legal',
        ],
    ];

    public static function hasPermission(?User $user, string $permission): bool
    {
        if (!$user || empty($user->role)) {
            return false;
        }

        $allowedPermissions = self::ROLE_PERMISSIONS[$user->role] ?? [];
        return in_array($permission, $allowedPermissions, true);
    }
}
