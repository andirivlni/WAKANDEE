<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment for transaction.
     *
     * @param Transaction $transaction
     * @param array $paymentData
     * @return bool
     */
    public function processPayment(Transaction $transaction, array $paymentData): bool
    {
        try {
            DB::beginTransaction();

            // Update transaction status
            $transaction->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_proof' => $paymentData['payment_proof'] ?? null,
            ]);

            // Update item status
            $transaction->item->update([
                'status' => 'sold'
            ]);

            DB::commit();

            // Send notifications
            app(NotificationService::class)->paymentConfirmed($transaction);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process refund for cancelled transaction.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function processRefund(Transaction $transaction): bool
    {
        try {
            DB::beginTransaction();

            $transaction->update([
                'payment_status' => 'cancelled',
                'notes' => ($transaction->notes ? $transaction->notes . ' ' : '') . '[Refund processed at ' . now() . ']'
            ]);

            // Return item to available status
            $transaction->item->update([
                'status' => 'approved'
            ]);

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate admin fee for transaction.
     *
     * @param float $amount
     * @param string $type
     * @return float
     */
    public function calculateAdminFee(float $amount, string $type): float
    {
        if ($type === 'gift') {
            return 0;
        }

        // Fixed admin fee Rp 1.000 for sale items
        return 1000;
    }

    /**
     * Validate payment proof.
     *
     * @param array $paymentData
     * @return bool
     */
    public function validatePaymentProof(array $paymentData): bool
    {
        if (!isset($paymentData['payment_proof'])) {
            return false;
        }

        $proof = $paymentData['payment_proof'];

        // Check if file is image
        if (!in_array($proof->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            return false;
        }

        // Check file size (max 2MB)
        if ($proof->getSize() > 2048000) {
            return false;
        }

        return true;
    }

    /**
     * Generate invoice number.
     *
     * @param Transaction $transaction
     * @return string
     */
    public function generateInvoiceNumber(Transaction $transaction): string
    {
        return 'INV-' . $transaction->transaction_code . '-' . date('Ymd');
    }
}
