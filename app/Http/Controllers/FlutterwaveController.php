<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This generates a payment reference
        $reference = (rand(100000000,999999999)%100000000);
        $reference = "QPAY".$reference;
        $user_id = request()->user_id;
        $email = request()->email;
        $regulation_id = request()->regulation_id;
        $amount = request()->amount;

        $new_transaction = new Transaction();
        $new_transaction->user_id = $user_id;
        $new_transaction->regulation_id = $regulation_id;
        $new_transaction->amount = $amount;
        $new_transaction->reference = $reference;
        
        $new_transaction->save();

        // Enter the details of the payment
         $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $amount,
            'email' => $email,
            'tx_ref' => $reference,
            'currency' => "NGN",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => $email,
            ],

            "customizations" => [
                "title" => 'Movie Ticket',
                "description" => "20th October"
            ]
        ];

       
         $payment = Flutterwave::initializePayment($data);

          Log::info($payment);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return redirect($payment['data']['link']);
    
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        
          $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
        $transactionID = Flutterwave::getTransactionIDFromCallback();
        $paymentDetails = Flutterwave::verifyTransaction($transactionID);

        
       
        Transaction::where('reference',  $paymentDetails['data']['tx_ref'])->update([
            'status' => $paymentDetails['data']['status'],
            'success_ref' => $paymentDetails['data']['flw_ref']
           
        ]);

        return $paymentDetails;

        // Transaction::update([
        //     'status' => $data['data']['status'],
            
        // ]);
      

        //dd($data);
        
        

        // return $transaction = Transaction::where('reference', $reference)->get();

        // // $todos = Todo::where('id', $todoId)->get();
        // // return view('todos.show', compact('todos'));

        // $transaction->customer_code = $paymentDetails->customer_code;
        // $new_transaction->save();


        }
        elseif ($status ==  'cancelled'){
            return "transaction Cancelled";
            //Put desired action/code after transaction has been cancelled here
        }
        else{
            return "transaction failed";
            //Put desired action/code after transaction has failed here
        }
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here

    }
}