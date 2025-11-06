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

 

    public function payment_success(Request $request)
    {
        $email = Auth::user()->email;
        $user_id = Auth::user()->id;
        
        // Get the payment status from the request
        $paymentStatus = $request->get('status');
        
        $userpayment = Subscription::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        $transaction = Transaction::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        
        if (!$userpayment) {
            return Redirect::route('home')->with('error', 'No subscription found.');
        }
        
        // Check if payment was successful
        if ($paymentStatus === 'successful' || $paymentStatus === '1' || $paymentStatus === 1) {
            $userpayment->status = 1; // Success
            $transaction->status = 'success';
            $userpayment->save();
            $transaction->save();
            
            return Redirect::route('success')->with('success', 'Payment was successful.');
        } else {
            $userpayment->status = 0; // Failed
            $transaction->status = 'failed';
            $userpayment->save();
            $transaction->save();
            
            return Redirect::route('home')->with('sweetalert', [
                'type' => 'error',
                'title' => 'Payment Failed',
                'message' => 'Payment failed. Please try again.'
            ]);
        }
    }
}
