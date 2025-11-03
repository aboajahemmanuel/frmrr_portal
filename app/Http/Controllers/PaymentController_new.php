<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests;
use App\Models\PayHub;
use App\Models\Regulation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'The paystack token has expired. Please refresh the page and try again.');
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        try {
            $paymentDetails = Paystack::getPaymentData();
            $payref = $paymentDetails['data']['reference'];

            // Update transaction status
            Transaction::where('reference', $payref)->update([
                'status' => $paymentDetails['data']['status'],
                'success_ref' => $payref
            ]);

            $paydetails = Transaction::where('success_ref', $payref)->firstOrFail();
            $doc_details = Regulation::where('id', $paydetails->regulation_id)->firstOrFail();

            return redirect()->route('document_download', [
                'slug' => $doc_details->slug,
                'payref' => $payref
            ]);
        } catch (\Exception $e) {
            return redirect()->route('payment.failed')->with('error', 'Payment verification failed.');
        }
    }

    public function documentpay(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'amount' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'regulation_id' => 'required|exists:regulations,id',
            ]);

            $amountinkobo = intval($validatedData['amount']) * 100;

            // Create transaction using service
            $transaction = $this->paymentService->createTransaction(
                $validatedData['user_id'],
                $validatedData['regulation_id'],
                $validatedData['amount']
            );

            try {
                $paystack = new \Yabacon\Paystack(config('services.paystack.secret'));
                $tranx = $paystack->transaction->initialize([
                    'amount' => $amountinkobo,
                    'email' => $validatedData['email'],
                    'reference' => $transaction->reference,
                ]);
            } catch (\Yabacon\Paystack\Exception\ApiException $e) {
                throw new \Exception($e->getMessage());
            }

            return redirect()->to($tranx->data->authorization_url);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function paystore(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string',
                'phone' => 'required|string',
                'price' => 'required|numeric',
                'regulation_id' => 'required|exists:regulations,id',
                'user_id' => 'required|exists:users,id',
            ]);

            // Create transaction using service
            $transaction = $this->paymentService->createTransaction(
                $validatedData['user_id'],
                $validatedData['regulation_id'],
                $validatedData['price']
            );

            // Prepare and encrypt payment parameters
            $encryptedParams = $this->paymentService->preparePaymentParams(
                $validatedData['email'],
                $validatedData['name'],
                $validatedData['price'],
                $validatedData['phone']
            );

            // Get payment URL and redirect
            $redirectUrl = $this->paymentService->getPaymentRedirectUrl($encryptedParams);
            
            return redirect($redirectUrl);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process payment request.')
                ->withInput();
        }
    }

    public function payment_success()
    {
        try {
            $user = Auth::user();
            $subscription = Subscription::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->firstOrFail();

            $subscription->status = 1;
            $subscription->save();

            return redirect()->route('success')
                ->with('success', 'Payment was successful.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Could not process payment success.');
        }
    }
}