<?php

namespace App\Services;

use App\Models\Transaction;

class PaymentService
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    public function createTransaction($user_id, $regulation_id, $amount)
    {
        $reference = $this->generateReference();
        
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->regulation_id = $regulation_id;
        $transaction->amount = $amount;
        $transaction->reference = $reference;
        $transaction->save();

        return $transaction;
    }

    public function createSubscriptionPayment($user, $plan)
    {
        $paymentParam = json_encode([
            'em' => $user->email,
            'fn' => $user->name,
            'ln' => $user->name,
            'am' => $plan->price,
            'pn' => $user->phone,
            'scode' => '1101'
        ]);

        $encryptedParams = $this->encryptionService->encrypt($paymentParam);
        return "http://10.10.16.47/qpay/odrum/" . $encryptedParams;
    }

    public function generateReference()
    {
        $reference = (rand(100000000, 999999999) % 100000000);
        return "QPAY" . $reference;
    }

    public function preparePaymentParams($email, $name, $amount, $phone)
    {
        $paymentParam = json_encode([
            'em' => $email,
            'fn' => $name,
            'ln' => $name,
            'am' => $amount,
            'pn' => $phone,
            'scode' => '03'
        ]);

        return $this->encryptionService->encrypt($paymentParam);
    }

    public function getPaymentRedirectUrl($encryptedParams)
    {
        return config('payment.qpay_url') . $encryptedParams;
    }
}