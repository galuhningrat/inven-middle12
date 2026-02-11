<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Get avatar URL with fallback to default
     */
    public static function getAvatarUrl($user)
    {
        if (!$user) {
            return asset('foto_users/default.png');
        }

        $imgPath = $user->img ?? 'foto_users/default.png';
        $fullPath = public_path($imgPath);

        if (file_exists($fullPath)) {
            return asset($imgPath);
        }

        return asset('foto_users/default.png');
    }

    /**
     * Get avatar HTML with circle styling
     */
    public static function getAvatarHtml($user, $size = '50px', $class = '')
    {
        $url = self::getAvatarUrl($user);
        $name = $user ? $user->nama : 'User';

        return '<div class="symbol symbol-circle symbol-' . $size . ' overflow-hidden ' . $class . '">
                    <div class="symbol-label">
                        <img src="' . $url . '" alt="' . htmlspecialchars($name) . '" class="w-100" style="object-fit: cover;" />
                    </div>
                </div>';
    }
}
