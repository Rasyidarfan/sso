<?php

namespace App\Helpers;

class SsoHelper
{
    /**
     * Get SSO API base URL based on environment
     */
    public static function getApiBaseUrl()
    {
        // Check if custom SSO_API_BASE_URL is set
        if (env('SSO_API_BASE_URL')) {
            return env('SSO_API_BASE_URL');
        }

        // Fallback to APP_URL based logic
        $appUrl = env('APP_URL', 'http://localhost');
        
        if (env('APP_ENV') === 'production') {
            return 'https://sso.bps9702.com/v1';
        } else {
            // Development environment
            return $appUrl . '/v1';
        }
    }

    /**
     * Get SSO API domain based on environment
     */
    public static function getApiDomain()
    {
        // Check if custom SSO_API_DOMAIN is set
        if (env('SSO_API_DOMAIN')) {
            return env('SSO_API_DOMAIN');
        }

        if (env('APP_ENV') === 'production') {
            return 'sso.bps9702.com';
        } else {
            // Development environment
            return '127.0.0.1:8000';
        }
    }

    /**
     * Get full authorize URL
     */
    public static function getAuthorizeUrl($clientId, $state = null)
    {
        $url = self::getApiBaseUrl() . '/authorize?client_id=' . urlencode($clientId);
        
        if ($state) {
            $url .= '&state=' . urlencode($state);
        }
        
        return $url;
    }

    /**
     * Get token endpoint URL
     */
    public static function getTokenUrl()
    {
        return self::getApiBaseUrl() . '/token';
    }
}
