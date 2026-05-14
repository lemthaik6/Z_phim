<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;

class SimulationGateway implements PaymentGatewayInterface
{
    public function process(string $bookingId, float $amount, array $paymentDetails): array
    {
        return [
            'success' => true,
            'transaction_id' => $bookingId . '_' . time(),
            'message' => 'Simulation payment ready',
            'redirect_url' => route('payments.confirmQr', ['booking' => $bookingId, 'payment' => $paymentDetails['payment_id'] ?? 0]),
        ];
    }

    public function verify(array $response): array
    {
        return ['success' => true, 'transaction_id' => $response['transaction_id'] ?? null];
    }

    public function refund(string $transactionId, float $amount): array
    {
        return ['success' => true, 'message' => 'Simulation refund successful'];
    }

    public function getStatus(string $transactionId): string
    {
        return 'completed';
    }
}
