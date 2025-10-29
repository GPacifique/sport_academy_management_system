<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentGatewayTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhooksController extends Controller
{
    /**
     * Handle Flutterwave webhook
     */
    public function flutterwave(Request $request)
    {
        // Verify signature
        $signature = $request->header('verif-hash');
        if (!$signature || $signature !== config('services.flutterwave.webhook_secret')) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        Log::info('Flutterwave webhook received', $payload);

        $transactionId = $payload['data']['id'] ?? null;
        $status = $payload['data']['status'] ?? null;

        if ($transactionId) {
            $gwTx = PaymentGatewayTransaction::where('transaction_id', $transactionId)
                ->where('gateway', 'flutterwave')
                ->first();

            if ($gwTx) {
                $newStatus = match(strtolower($status)) {
                    'successful' => 'succeeded',
                    'failed' => 'failed',
                    default => 'processing',
                };
                $gwTx->update(['status' => $newStatus]);

                // Update parent payment
                if ($newStatus === 'succeeded') {
                    $gwTx->payment->update(['status' => 'succeeded']);
                    // Mark invoice as paid if fully covered
                    if ($gwTx->payment->invoice_id) {
                        $gwTx->payment->invoice->markAsPaidIfFull();
                    }
                } elseif ($newStatus === 'failed') {
                    $gwTx->payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Stripe webhook
     */
    public function stripe(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            // Verify signature (requires Stripe PHP library)
            // $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            $event = json_decode($payload, true);
            Log::info('Stripe webhook received', $event);

            if ($event['type'] === 'payment_intent.succeeded') {
                $intentId = $event['data']['object']['id'] ?? null;
                if ($intentId) {
                    $gwTx = PaymentGatewayTransaction::where('transaction_id', $intentId)
                        ->where('gateway', 'stripe')
                        ->first();
                    if ($gwTx) {
                        $gwTx->update(['status' => 'succeeded']);
                        $gwTx->payment->update(['status' => 'succeeded']);
                        if ($gwTx->payment->invoice_id) {
                            $gwTx->payment->invoice->markAsPaidIfFull();
                        }
                    }
                }
            } elseif ($event['type'] === 'payment_intent.payment_failed') {
                $intentId = $event['data']['object']['id'] ?? null;
                if ($intentId) {
                    $gwTx = PaymentGatewayTransaction::where('transaction_id', $intentId)
                        ->where('gateway', 'stripe')
                        ->first();
                    if ($gwTx) {
                        $gwTx->update(['status' => 'failed']);
                        $gwTx->payment->update(['status' => 'failed']);
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 400);
        }
    }
}
