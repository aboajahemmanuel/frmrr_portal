<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{







    public function subscribe_payment(Request $request)
    {
        //return $request;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = Auth::user();


        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($plan->duration);

        $reference = (rand(100000000, 999999999) % 100000000);
        $reference = "QPAY" . $reference;

        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->subscription_plan_id = $plan->id;
        $subscription->start_date = $startDate;
        $subscription->end_date = $endDate;
        $user->name;
        $user->phone;
        $plan->price;
        $user->id;



        $subscription->save();

        function dzHideMe($dzRecord)
        {
            // Store the cipher method
            $ciphering = 'AES-128-CTR';
            // Use OpenSSl Encryption method
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            //Non-NULL Initialization Vector for encryption
            $encryption_iv = '1789123114561012'; //User defined secret key 
            $iv = substr(hash('sha256', $encryption_iv), 0, 16); // sha256 is hash_hmac_algo
            //Store the encryption key
            $encryption_key = 'AKY_45_EncryptToDecrypt_DHJA';  //User defined private key 
            $key = hash('sha256', $encryption_key);
            //Use openssl_encrypt() function to encrypt the data
            $dzEncrypted = openssl_encrypt($dzRecord, $ciphering, $key, $options, $iv);
            $dzEncrypted = base64_encode($dzEncrypted);

            return $dzEncrypted;
        }

        $PaymentParam = '{"em":"' . $user->email . '","fn":"' . $user->name . '","ln":"' . $user->name . '","am":"' . $plan->price . '","pn":"' . $user->phone . '","scode":"03014444"}';
        return redirect("http://10.10.16.47/qpay/odrum/" . dzhideMe($PaymentParam));
    }
}
