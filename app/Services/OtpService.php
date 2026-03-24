<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class OtpService
{
    protected $http;
    protected $url;

    public function __construct()
    {
        $this->url = config('otp.url');
        $this->http = Http::withoutVerifying()->withHeaders([
            'ID' => config('otp.app_id'),
            'username' => config('otp.username'),
            'password' => config('otp.password'),
        ]);
    }

    public function generateOtp($user)
    {
        try {
            $payload = [
                "appID" => config('otp.app_id'),
                "username" => $user->email,
                "name" => $user->name ?? "$user->first_name $user->last_name",
            ];
            
            \Log::info('OTP Request Payload:', $payload);
            \Log::info('OTP Request URL: ' . $this->url . '/generator');

            $response = $this->http->post($this->url . '/generator', $payload);

            \Log::info('OTP API Response Status: ' . $response->status());
            \Log::info('OTP API Response Body: ' . $response->body());

            if (!$response->ok()) {
                \Log::error('OTP API Error: ' . $response->body());
                $response->throw();
            }

            return $response->json();

        } catch (Throwable $th) {
            \Log::error('OTP Generation Exception: ' . $th->getMessage());
            return [];
        }
    }

    public function verifyOtp($user, $otp)
    {
        try {
            $response = $this->http->post($this->url . '/validator', [
                "appID" => config('otp.app_id'),
                "username" => $user->email,
                "otp" => $otp,
            ]);

            \Log::info('OTP Validation Request URL: ' . $this->url . '/validator');
            \Log::info('OTP Validation API Response Status: ' . $response->status());
            \Log::info('OTP Validation API Response Body: ' . $response->body());

            if (!$response->ok()) {
                \Log::error('OTP Validation API Error: ' . $response->body());
                $response->throw();
            }

            return $response->json();

        } catch (Throwable $th) {
            logger('OTP Verification Error: ' . $th->getMessage());
            return [];
        }
    }
}
