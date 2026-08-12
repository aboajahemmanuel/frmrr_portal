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

    public function createTransaction($user, $plan, $currency = 'NGN')
    {
        $reference = $this->generateReference();
        
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->subscription_plan_id = $plan->id;
        $transaction->amount = $currency === 'USD' ? $plan->price_usd : $plan->price;
        $transaction->currency = $currency;
        $transaction->reference = $reference;
        $transaction->description = "Subscription to " . $plan->name . " (" . $currency . ")";
        $transaction->save();

        return $transaction;
    }

    public function createSubscriptionPayment($user, $plan, $currency = 'NGN')
    {
        $nameParts = explode(' ', trim($user->name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? $firstName;

        $amount = $currency === 'USD' ? $plan->price_usd : $plan->price;
        $scode = $currency === 'USD' ? '1302' : '1101';

        $paymentParam = json_encode([
            'em' => $user->email,
            'fn' => $firstName,
            'ln' => $lastName,
            'am' => $amount,
            'pn' => $user->phone,
            'scode' => $scode
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
        $nameParts = explode(' ', trim($name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? $firstName;

        $paymentParam = json_encode([
            'em' => $email,
            'fn' => $firstName,
            'ln' => $lastName,
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