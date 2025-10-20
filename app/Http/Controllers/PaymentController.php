<?php

namespace App\Http\Controllers;

use Paystack;

use App\Http\Requests;
use App\Models\PayHub;
use App\Models\Regulation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {

        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage(['msg' => 'The paystack token has expired. Please refresh the page and try again.', 'type' => 'error']);
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        $payref = $paymentDetails['data']['reference'];

        // dd($paymentDetails);



        Transaction::where('reference',  $paymentDetails['data']['reference'])->update([
            'status' => $paymentDetails['data']['status'],
            'success_ref' => $paymentDetails['data']['reference']

        ]);

        $paydetails =   Transaction::where('success_ref', $payref)->first();
        $doc_details =   Regulation::where('id', $paydetails->regulation_id)->first();


        //return  Redirect::to('/document/download/'.['slug' => $doc_details->slug, 'payref' => $payref]);

        return \Redirect::route('document_download', ['slug' => $doc_details->slug, 'payref' => $payref]);

        //{{route('alphaname',['slug' => $data->slug, 'payref' => $payref->payref])}}

        // $reference = $paymentDetails->reference;
        // // Now you have the payment details,
        // return $transaction = Transaction::where('reference', $reference)->get();

        // $todos = Todo::where('id', $todoId)->get();
        // return view('todos.show', compact('todos'));

        // $transaction->customer_code = $paymentDetails->customer_code;
        // $new_transaction->save();

        // return $paymentDetails;
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }




    public function documentpay(Request $request)
    {

        $email = $request['email'];
        $amount = $request['amount'];
        $amountinkobo = intval($amount) * 100;
        $reference = (rand(100000000, 999999999) % 100000000);
        $reference = "QPAY" . $reference;


        $new_transaction = new Transaction();
        $new_transaction->user_id = $request['user_id'];
        $new_transaction->regulation_id = $request['regulation_id'];
        $new_transaction->amount = $request['amount'];
        // $new_transaction->customer_code = $request['customer_code'];
        $new_transaction->reference = $reference;

        $new_transaction->save();




        //$paystack = new Yabacon\paystack("sk_test_488cd3c1fc20a903c84e9a25e6b3ab0b033a65f8");

        $paystack = new \Yabacon\Paystack("sk_test_488cd3c1fc20a903c84e9a25e6b3ab0b033a65f8");
        try {
            $tranx = $paystack->transaction->initialize([
                'amount' => $amountinkobo,       // in kobo
                'email' => $email,         // unique to customers
                'reference' => $reference, // unique to transactions
            ]);
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            print_r($e->getResponseObject());
            die($e->getMessage());
        }

        return redirect()->to($tranx->data->authorization_url)->send();
    }


    public function paystore(Request $request)
    {
        //return $request; 
        $email =  $request['email'];
        $name =  $request['name'];
        $phone =  $request['phone'];
        $amount = $request['price'];
        $regulation_id = $request['regulation_id'];
        $user_id = $request['user_id'];
        $reference = (rand(100000000, 999999999) % 100000000);
        $reference = "QPAY" . $reference;

        $new_transaction = new Transaction();
        $new_transaction->user_id = $user_id;
        $new_transaction->regulation_id = $regulation_id;
        $new_transaction->amount = $amount;
        $new_transaction->reference = $reference;
        // $new_transaction->customer_code = $request['customer_code'];


        $new_transaction->save();

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

        $PaymentParam = '{"em":"' . $email . '","fn":"' . $name . '","ln":"' . $name . '","am":"' . $amount . '","pn":"' . $phone . '","scode":"03"}';
        return redirect("https://10.10.16.47/qpay/odrum/" . dzhideMe($PaymentParam));
    }



    public function payment_success()
    {

        $email = Auth::user()->email;
        $user_id = Auth::user()->id;



        $userpayment =  Subscription::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();

        $status = 1;
        $userpayment->status = $status;
        $userpayment->save();


        // $paydetails =   Transaction::where('success_ref', $succes_payment->Reference)->first();
        $doc_details =   Subscription::where('user_id', $user_id)->first();


        //return  Redirect::to('/document/download/'.['slug' => $doc_details->slug, 'payref' => $payref]);

        return Redirect::route('success')->with('success', 'Payment was successful.');
        //return \Redirect::route('document_download', ['slug' => $doc_details->slug, 'payref' => $reference])->with('success', 'Payment was successful.');

        // return "good";



        $payment_details = PayHub::where('Email', $email)
            ->latest()->first();
    }
}
