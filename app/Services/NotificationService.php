<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send notification for new item pending approval.
     *
     * @param Item $item
     * @return void
     */
    public function itemPending(Item $item): void
    {
        // Notify all admins
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'system',
                'title' => 'Barang Baru Menunggu Moderasi',
                'message' => $item->user->name . ' mengupload "' . $item->name . '" dan menunggu persetujuan.',
            ]);
        }
    }

    /**
     * Send notification for approved item.
     *
     * @param Item $item
     * @return void
     */
    public function itemApproved(Item $item): void
    {
        Notification::create([
            'user_id' => $item->user_id,
            'type' => 'item_approved',
            'title' => 'Barang Disetujui',
            'message' => 'Barang "' . $item->name . '" Anda telah disetujui dan sekarang tampil di katalog.',
        ]);
    }

    /**
     * Send notification for rejected item.
     *
     * @param Item $item
     * @param string $reason
     * @return void
     */
    public function itemRejected(Item $item, string $reason): void
    {
        Notification::create([
            'user_id' => $item->user_id,
            'type' => 'item_rejected',
            'title' => 'Barang Ditolak',
            'message' => 'Barang "' . $item->name . '" Anda ditolak. Alasan: ' . $reason,
        ]);
    }

    /**
     * Send notification for new transaction.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function newTransaction(Transaction $transaction): void
    {
        // Notify seller
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'new_transaction',
            'title' => 'Ada Transaksi Baru',
            'message' => $transaction->buyer->name . ' ingin membeli "' . $transaction->item->name . '".',
        ]);

        // Notify buyer (confirmation)
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'new_transaction',
            'title' => 'Transaksi Berhasil Dibuat',
            'message' => 'Transaksi untuk "' . $transaction->item->name . '" berhasil dibuat. Silakan selesaikan pembayaran.',
        ]);
    }

    /**
     * Send notification for payment confirmation.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function paymentConfirmed(Transaction $transaction): void
    {
        // Notify seller
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'payment_confirmed',
            'title' => 'Pembayaran Dikonfirmasi',
            'message' => 'Pembayaran untuk "' . $transaction->item->name . '" telah diterima. Siapkan barang untuk diserahkan.',
        ]);

        // Notify buyer
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'payment_confirmed',
            'title' => 'Pembayaran Berhasil',
            'message' => 'Pembayaran untuk "' . $transaction->item->name . '" telah dikonfirmasi. Silakan lakukan serah terima barang.',
        ]);
    }

    /**
     * Send notification for delivery confirmation.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function deliveryConfirmed(Transaction $transaction): void
    {
        // Notify seller
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'delivery_confirmed',
            'title' => 'Barang Telah Diterima',
            'message' => $transaction->buyer->name . ' telah menerima "' . $transaction->item->name . '". Transaksi selesai.',
        ]);

        // Notify buyer
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'delivery_confirmed',
            'title' => 'Transaksi Selesai',
            'message' => 'Terima kasih! Transaksi "' . $transaction->item->name . '" telah selesai.',
        ]);
    }

    /**
     * Send notification for cancelled transaction.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function transactionCancelled(Transaction $transaction): void
    {
        // Notify seller
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'system',
            'title' => 'Transaksi Dibatalkan',
            'message' => 'Transaksi "' . $transaction->item->name . '" telah dibatalkan.',
        ]);

        // Notify buyer
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'system',
            'title' => 'Transaksi Dibatalkan',
            'message' => 'Transaksi "' . $transaction->item->name . '" telah dibatalkan.',
        ]);
    }

    /**
     * Send notification for account status change.
     *
     * @param User $user
     * @param string $status
     * @return void
     */
    public function accountStatusChanged(User $user, string $status): void
    {
        $statusText = $status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_status',
            'title' => 'Status Akun Diubah',
            'message' => 'Akun Anda telah ' . $statusText . ' oleh admin.',
        ]);
    }

    /**
     * Send bulk notifications.
     *
     * @param array $userIds
     * @param string $title
     * @param string $message
     * @param string $type
     * @return void
     */
    public function sendBulk(array $userIds, string $title, string $message, string $type = 'system'): void
    {
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
            ]);
        }
    }

    /**
     * Mark notification as read.
     *
     * @param int $notificationId
     * @param int $userId
     * @return void
     */
    public function markAsRead(int $notificationId, int $userId): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true]);
    }

    /**
     * Mark all notifications as read for user.
     *
     * @param int $userId
     * @return void
     */
    public function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get unread count for user.
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Delete old notifications.
     *
     * @param int $days
     * @return void
     */
    public function deleteOldNotifications(int $days = 30): void
    {
        Notification::where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}
