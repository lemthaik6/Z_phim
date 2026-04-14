<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;

class VNPayGateway implements PaymentGatewayInterface
{
    private string $vnpayUrl = 'https://sandbox.vnpayment.vn/paygate';
    private string $tmnCode;
    private string $hashSecret;
    private string $returnUrl;

    public function __construct()
    {
        $this->tmnCode = config('payment.vnpay.tmn_code', '');
        $this->hashSecret = config('payment.vnpay.hash_secret', '');
        $this->returnUrl = config('payment.vnpay.return_url', '');
    }

    public function process(string $bookingId, float $amount, array $paymentDetails): array
    {
        try {
            $startTime = date("YmdHis");
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            $vnpData = [
                "vnp_Version" => "2.1.0",
                "vnp_Command" => "pay",
                "vnp_TmnCode" => $this->tmnCode,
                "vnp_Amount" => (int)($amount * 100), // VNPay needs amount in cents
                "vnp_CurrCode" => "VND",
                "vnp_TxnRef" => $bookingId . time(),
                "vnp_OrderInfo" => "Booking #{$bookingId}",
                "vnp_OrderType" => "other",
                "vnp_Locale" => "vn",
                "vnp_ReturnUrl" => $this->returnUrl . "?booking_id={$bookingId}",
                "vnp_ExpireDate" => $expire,
                "vnp_IpAddr" => $this->getClientIp(),
                "vnp_CreateDate" => $startTime,
            ];

            ksort($vnpData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($vnpData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= "&" . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $this->vnpayUrl . "?" . $query;

            if (isset($this->hashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return [
                'success' => true,
                'transaction_id' => $vnpData['vnp_TxnRef'],
                'message' => 'Redirecting to VNPay',
                'redirect_url' => $vnp_Url,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'VNPay Error: ' . $e->getMessage(),
            ];
        }
    }

    public function verify(array $response): array
    {
        try {
            $vnp_HashSecret = $this->hashSecret;
            $vnp_SecureHash = $response['vnp_SecureHash'] ?? '';
            unset($response['vnp_SecureHash']);

            ksort($response);
            $i = 0;
            $hashData = "";
            foreach ($response as $key => $value) {
                if ($i == 1) {
                    $hashData .= "&" . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash === $vnp_SecureHash) {
                if ($response['vnp_ResponseCode'] === '00') {
                    return [
                        'success' => true,
                        'transaction_id' => $response['vnp_TransactionNo'],
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Invalid signature or payment failed',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification Error: ' . $e->getMessage(),
            ];
        }
    }

    public function refund(string $transactionId, float $amount): array
    {
        // Implement refund logic
        return ['success' => true, 'message' => 'Refund initiated'];
    }

    public function getStatus(string $transactionId): string
    {
        // Implement status check logic
        return 'completed';
    }

    private function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}