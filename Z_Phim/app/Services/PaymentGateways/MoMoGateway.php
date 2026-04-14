<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;

class MoMoGateway implements PaymentGatewayInterface
{
    private string $endpoint = 'https://test-payment.momo.vn/gw_payment/pay/v2';
    private string $partnerCode;
    private string $accessKey;
    private string $secretKey;
    private string $returnUrl;

    public function __construct()
    {
        $this->partnerCode = config('payment.momo.partner_code', '');
        $this->accessKey = config('payment.momo.access_key', '');
        $this->secretKey = config('payment.momo.secret_key', '');
        $this->returnUrl = config('payment.momo.return_url', '');
    }

    public function process(string $bookingId, float $amount, array $paymentDetails): array
    {
        try {
            $orderId = $bookingId . time();
            $requestId = time();

            $rawSignature = "accessKey={$this->accessKey}&amount=" . (int)$amount . "&extraData=&orderId={$orderId}&orderInfo=Booking%23{$bookingId}&partnerCode={$this->partnerCode}&requestId={$requestId}&redirectUrl={$this->returnUrl}";

            $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'requestId' => $requestId,
                'orderId' => $orderId,
                'amount' => (int)$amount,
                'ordersInfo' => "Booking #{$bookingId}",
                'orderGroupId' => '',
                'autoCapture' => true,
                'lang' => 'vi',
                'signature' => $signature,
                'requestType' => 'captureMoMoWallet',
                'returnUrl' => $this->returnUrl . "?booking_id={$bookingId}",
                'notifyUrl' => route('api.payment.webhook.momo'),
            ];

            // In production, make actual CURL request to MoMo
            // For now, return redirect URL
            $queryString = http_build_query($data);

            return [
                'success' => true,
                'transaction_id' => $orderId,
                'message' => 'Redirecting to MoMo',
                'redirect_url' => $this->endpoint . "?{$queryString}",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'MoMo Error: ' . $e->getMessage(),
            ];
        }
    }

    public function verify(array $response): array
    {
        try {
            $signature = $response['signature'] ?? '';
            unset($response['signature']);

            $rawSignature = "";
            foreach ($response as $key => $value) {
                $rawSignature .= $key . "=" . $value . "&";
            }
            $rawSignature = rtrim($rawSignature, "&");

            $validSignature = hash_hmac('sha256', $rawSignature, $this->secretKey);

            if ($validSignature === $signature && $response['errorCode'] === 0) {
                return [
                    'success' => true,
                    'transaction_id' => $response['transId'],
                ];
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
}