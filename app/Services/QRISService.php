<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QRISService
{
    /**
     * Generate QRIS code for transaction.
     *
     * @param Transaction $transaction
     * @return string
     */
    public function generateQRCode(Transaction $transaction): string
    {
        try {
            // This is a simulation - in production, integrate with actual payment gateway
            $qrData = $this->buildQRISData($transaction);

            // Generate unique QR code string
            $qrCode = 'QRIS-' . strtoupper(Str::random(20)) . '-' . $transaction->id;

            // In production: call payment gateway API to generate actual QR code
            // $response = $this->paymentGateway->createQRCode($qrData);
            // $qrCode = $response['qr_string'];

            return $qrCode;

        } catch (\Exception $e) {
            Log::error('QRIS generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check payment status from QRIS.
     *
     * @param Transaction $transaction
     * @return string
     */
    public function checkPaymentStatus(Transaction $transaction): string
    {
        try {
            // This is a simulation - in production, check with actual payment gateway
            // $response = $this->paymentGateway->checkStatus($transaction->qris_code);
            // return $response['status'];

            // Simulate status check
            if ($transaction->payment_status === 'paid') {
                return 'PAID';
            }

            return 'PENDING';

        } catch (\Exception $e) {
            Log::error('QRIS status check failed: ' . $e->getMessage());
            return 'ERROR';
        }
    }

    /**
     * Build QRIS data payload.
     *
     * @param Transaction $transaction
     * @return array
     */
    private function buildQRISData(Transaction $transaction): array
    {
        return [
            'merchant_id' => config('payment.qris.merchant_id'),
            'merchant_name' => 'WAKANDE',
            'transaction_id' => $transaction->transaction_code,
            'amount' => $transaction->total_amount,
            'currency' => 'IDR',
            'description' => 'Pembayaran ' . $transaction->item->name,
            'buyer_email' => $transaction->buyer->email,
            'buyer_name' => $transaction->buyer->name,
            'expiry_time' => now()->addHours(24)->timestamp,
        ];
    }

    /**
     * Cancel QRIS payment.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function cancelQRCode(Transaction $transaction): bool
    {
        try {
            // In production: call payment gateway API to cancel QR code
            // $this->paymentGateway->cancelQRCode($transaction->qris_code);

            return true;

        } catch (\Exception $e) {
            Log::error('QRIS cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get QRIS payment instructions.
     *
     * @return array
     */
    public function getPaymentInstructions(): array
    {
        return [
            'Buka aplikasi mobile banking/QRIS',
            'Pilih menu Scan QR atau Bayar QRIS',
            'Scan kode QR yang ditampilkan',
            'Periksa detail pembayaran',
            'Masukkan PIN/autentikasi',
            'Simpan bukti pembayaran',
            'Upload bukti pembayaran di halaman transaksi',
        ];
    }
}
