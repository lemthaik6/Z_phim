<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Process payment
     *
     * @param string $bookingId
     * @param float $amount
     * @param array $paymentDetails
     * @return array ['success' => bool, 'transaction_id' => string, 'message' => string, 'redirect_url' => string]
     */
    public function process(string $bookingId, float $amount, array $paymentDetails): array;

    /**
     * Verify payment from gateway response
     *
     * @param array $response
     * @return array
     */
    public function verify(array $response): array;

    /**
     * Refund payment
     *
     * @param string $transactionId
     * @param float $amount
     * @return array
     */
    public function refund(string $transactionId, float $amount): array;

    /**
     * Get payment status
     *
     * @param string $transactionId
     * @return string
     */
    public function getStatus(string $transactionId): string;
}