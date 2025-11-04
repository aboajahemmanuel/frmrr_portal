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
        $this->encryptionIv = env('ENCRYPTION_IV', '1789123114561012'); // User defined secret key
        $this->encryptionKey = env('ENCRYPTION_KEY', 'AKY_45_EncryptToDecrypt_DHJA'); // User defined private key
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