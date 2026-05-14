<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;

class MoMoGateway implements PaymentGatewayInterface
{
    private string $endpoint = 'https://test-payment.momo.vn/gw_payment/transactionProcessor';
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
            $orderId = $bookingId . '_' . time();
            $requestId = $bookingId . '_' . time();
            $orderInfo = "Booking #{$bookingId}";
            $redirectUrl = $this->returnUrl . "?booking_id={$bookingId}";
            $notifyUrl = route('api.payment.webhook.momo');
            $extraData = '';
            $requestType = 'captureMoMoWallet';

            $data = [
                'partnerCode' => $this->partnerCode,
                'accessKey' => $this->accessKey,
                'requestId' => $requestId,
                'amount' => (string)round($amount),
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $notifyUrl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'lang' => 'vi',
            ];

            $rawSignature = sprintf(
                'accessKey=%s&amount=%s&extraData=%s&ipnUrl=%s&orderId=%s&orderInfo=%s&partnerCode=%s&redirectUrl=%s&requestId=%s&requestType=%s',
                $this->accessKey,
                $data['amount'],
                $extraData,
                $notifyUrl,
                $orderId,
                $orderInfo,
                $this->partnerCode,
                $redirectUrl,
                $requestId,
                $requestType
            );

            $data['signature'] = hash_hmac('sha256', $rawSignature, $this->secretKey);

            $response = Http::asJson()->post($this->endpoint, $data);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'MoMo request failed with HTTP status ' . $response->status(),
                ];
            }

            $body = $response->json();

            if (empty($body['payUrl'])) {
                return [
                    'success' => false,
                    'message' => $body['message'] ?? 'MoMo response missing payUrl',
                ];
            }

            return [
                'success' => true,
                'transaction_id' => $orderId,
                'message' => 'Redirecting to MoMo',
                'redirect_url' => $body['payUrl'],
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
            $signature = $response['signature'] ?? $response['paySignature'] ?? '';
            unset($response['signature'], $response['paySignature']);

            ksort($response);
            $rawSignature = '';
            foreach ($response as $key => $value) {
                $rawSignature .= $key . '=' . $value . '&';
            }
            $rawSignature = rtrim($rawSignature, '&');

            $validSignature = hash_hmac('sha256', $rawSignature, $this->secretKey);
            $success = $validSignature === $signature && (
                (isset($response['errorCode']) && (int)$response['errorCode'] === 0) ||
                (isset($response['resultCode']) && (int)$response['resultCode'] === 0)
            );

            if ($success) {
                return [
                    'success' => true,
                    'transaction_id' => $response['transId'] ?? $response['transactionId'] ?? null,
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