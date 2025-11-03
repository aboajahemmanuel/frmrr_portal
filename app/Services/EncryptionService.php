<?php

namespace App\Services;

class EncryptionService
{
    protected $ciphering;
    protected $options;
    protected $encryptionIv;
    protected $encryptionKey;

    public function __construct()
    {
        $this->ciphering = 'AES-128-CTR';
        $this->options = 0;
        $this->encryptionIv = config('encryption.iv');
        $this->encryptionKey = config('encryption.key');
    }

    public function encrypt($data)
    {
        $iv = substr(hash('sha256', $this->encryptionIv), 0, 16);
        $key = hash('sha256', $this->encryptionKey);
        
        $encrypted = openssl_encrypt(
            $data,
            $this->ciphering,
            $key,
            $this->options,
            $iv
        );

        return base64_encode($encrypted);
    }
}