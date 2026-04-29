<?php
namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentService {
    private $api;

    public function __construct() {
        $this->api = new Api($_ENV['RAZORPAY_KEY_ID'], $_ENV['RAZORPAY_KEY_SECRET']);
    }

    public function createOrder($amount, $receiptId) {
        $orderData = [
            'receipt'         => $receiptId,
            'amount'          => $amount * 100, // in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        return $this->api->order->create($orderData);
    }

    public function verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature) {
        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ];
            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (SignatureVerificationError $e) {
            return false;
        }
    }
}
