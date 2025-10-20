<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SessionSetting extends Model
{
    protected $fillable = ['timeout_minutes'];
    
    protected $table = 'session_settings';
    
    /**
     * Get the current session timeout setting
     *
     * @return int
     */
    public static function getCurrentTimeout()
    {
        try {
            $setting = self::first();
            $timeout = $setting ? $setting->timeout_minutes : 15; // Default to 15 minutes
            
            // Log the timeout value for debugging
            Log::info('Session timeout value: ' . $timeout);
            
            return $timeout;
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error getting session timeout: ' . $e->getMessage());
            return 15; // Default to 15 minutes
        }
    }
}