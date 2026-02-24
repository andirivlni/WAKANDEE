<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    /**
     * Handle payment gateway webhook (Midtrans, etc)
     */
    public function payment(Request $request)
    {
        Log::info('Payment webhook received', $request->all());

        try {
            // Verifikasi signature (sesuaikan dengan payment gateway yang digunakan)
            $payload = $request->all();

            // Contoh untuk Midtrans
            if (isset($payload['transaction_status']) && isset($payload['order_id'])) {
                $orderId = $payload['order_id'];
                $transactionStatus = $payload['transaction_status'];

                // Cari transaksi berdasarkan order_id (transaction_code)
                $transaction = Transaction::where('transaction_code', $orderId)->first();

                if (!$transaction) {
                    Log::error('Transaction not found for webhook', ['order_id' => $orderId]);
                    return response()->json(['error' => 'Transaction not found'], 404);
                }

                DB::beginTransaction();

                // Update status berdasarkan response dari payment gateway
                switch ($transactionStatus) {
                    case 'settlement':
                    case 'capture':
                        // Pembayaran sukses
                        $transaction->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                        ]);

                        // Notifikasi ke penjual
                        Notification::create([
                            'user_id' => $transaction->seller_id,
                            'type' => 'payment_success',
                            'title' => 'Pembayaran Berhasil',
                            'message' => 'Pembayaran untuk ' . $transaction->item->name . ' telah diterima',
                        ]);
                        break;

                    case 'pending':
                        // Menunggu pembayaran
                        $transaction->update([
                            'payment_status' => 'pending',
                        ]);
                        break;

                    case 'deny':
                    case 'cancel':
                    case 'expire':
                        // Pembayaran gagal/dibatalkan
                        $transaction->update([
                            'payment_status' => 'cancelled',
                        ]);

                        // Kembalikan status item
                        $transaction->item->update(['status' => 'approved']);

                        // Notifikasi ke pembeli
                        Notification::create([
                            'user_id' => $transaction->buyer_id,
                            'type' => 'payment_failed',
                            'title' => 'Pembayaran Gagal',
                            'message' => 'Pembayaran untuk ' . $transaction->item->name . ' gagal',
                        ]);
                        break;
                }

                DB::commit();

                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Invalid payload'], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle QRIS payment webhook
     */
    public function qris(Request $request)
    {
        Log::info('QRIS webhook received', $request->all());

        try {
            // Verifikasi signature
            $payload = $request->all();

            if (isset($payload['qr_code']) && isset($payload['status'])) {
                $qrCode = $payload['qr_code'];
                $status = $payload['status'];

                // Cari transaksi berdasarkan qris_code
                $transaction = Transaction::where('qris_code', $qrCode)
                    ->where('payment_status', 'pending')
                    ->first();

                if (!$transaction) {
                    Log::error('Transaction not found for QRIS webhook', ['qr_code' => $qrCode]);
                    return response()->json(['error' => 'Transaction not found'], 404);
                }

                DB::beginTransaction();

                if ($status === 'success') {
                    $transaction->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    Notification::create([
                        'user_id' => $transaction->seller_id,
                        'type' => 'payment_success',
                        'title' => 'Pembayaran QRIS Berhasil',
                        'message' => 'Pembayaran QRIS untuk ' . $transaction->item->name . ' telah diterima',
                    ]);
                } elseif ($status === 'failed') {
                    $transaction->update(['payment_status' => 'cancelled']);
                    $transaction->item->update(['status' => 'approved']);
                }

                DB::commit();

                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Invalid payload'], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('QRIS webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle WhatsApp webhook (for notifications)
     */
    public function whatsapp(Request $request)
    {
        Log::info('WhatsApp webhook received', $request->all());

        try {
            // Verifikasi token
            $token = $request->header('Authorization');

            if (!$token || $token !== 'Bearer ' . env('WHATSAPP_WEBHOOK_TOKEN')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $payload = $request->all();

            // Proses pesan masuk dari WhatsApp
            if (isset($payload['messages'][0])) {
                $message = $payload['messages'][0];
                $from = $message['from'];
                $text = $message['text']['body'] ?? '';

                // Log pesan untuk debugging
                Log::info('WhatsApp message received', [
                    'from' => $from,
                    'text' => $text
                ]);

                // Di sini bisa ditambahkan logika untuk membalas pesan otomatis
                // Misal: konfirmasi transaksi, cek status, dll
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle email webhook (for bounced emails)
     */
    public function email(Request $request)
    {
        Log::info('Email webhook received', $request->all());

        try {
            $payload = $request->all();

            // Proses email bounce, complaint, dll
            if (isset($payload['event']) && isset($payload['email'])) {
                $event = $payload['event'];
                $email = $payload['email'];

                Log::warning('Email event received', [
                    'event' => $event,
                    'email' => $email
                ]);

                // Di sini bisa ditambahkan logika untuk menangani email bounce
                // Misal: menonaktifkan user dengan email tidak valid
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Email webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle SMS webhook
     */
    public function sms(Request $request)
    {
        Log::info('SMS webhook received', $request->all());

        try {
            $payload = $request->all();

            // Proses SMS delivery report
            if (isset($payload['message_id']) && isset($payload['status'])) {
                $messageId = $payload['message_id'];
                $status = $payload['status'];

                Log::info('SMS status update', [
                    'message_id' => $messageId,
                    'status' => $status
                ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('SMS webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Verify webhook endpoints (for GET requests)
     */
    public function verify(Request $request)
    {
        // Untuk verifikasi endpoint dari payment gateway
        $token = $request->query('token');

        if ($token === env('WEBHOOK_VERIFY_TOKEN')) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Invalid token'], 401);
    }
}
