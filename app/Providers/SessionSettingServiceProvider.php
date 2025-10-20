<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\SessionSetting;
use Illuminate\Support\Facades\Schema;

class SessionSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Update session lifetime from database setting
        try {
            // Only try to get the setting if the table exists
            if (Schema::hasTable('session_settings')) {
                $timeout = SessionSetting::getCurrentTimeout();
                Config::set('session.lifetime', $timeout);
                
                // Also update the environment variable if needed
                putenv("SESSION_LIFETIME={$timeout}");
                $_ENV['SESSION_LIFETIME'] = $timeout;
                $_SERVER['SESSION_LIFETIME'] = $timeout;
            }
        } catch (\Exception $e) {
            // If there's an error (e.g., during migration), use default
            Config::set('session.lifetime', 15);
        }
    }
}