<?php

return [
    'enabled' => env('OTP_ENABLED', true),
    'type' => env('OTP_TYPE', 'numeric'),
    'length' => env('OTP_LENGTH', 4),
    'app_id' => env('OTP_APP_ID', '104'),
    'username' => env('OTP_USERNAME', 'onp'),
    'password' => env('OTP_PASSWORD', '111111'),
    'url' => env('OTP_URL', 'https://adgtest.fmdqgroup.com/otp/api/master'),
];
