<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true]);
        }
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get notification type label.
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'item_approved' => 'Barang Disetujui',
            'item_rejected' => 'Barang Ditolak',
            'new_transaction' => 'Transaksi Baru',
            'payment_confirmed' => 'Pembayaran Dikonfirmasi',
            'delivery_confirmed' => 'Barang Diterima',
            'account_status' => 'Status Akun',
            'system' => 'Sistem',
        ];
        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute()
    {
        $icons = [
            'item_approved' => 'check-circle',
            'item_rejected' => 'x-circle',
            'new_transaction' => 'shopping-cart',
            'payment_confirmed' => 'credit-card',
            'delivery_confirmed' => 'truck',
            'account_status' => 'user',
            'system' => 'bell',
        ];
        return $icons[$this->type] ?? 'bell';
    }

    /**
     * Get notification color based on type.
     */
    public function getColorAttribute()
    {
        $colors = [
            'item_approved' => 'success',
            'item_rejected' => 'danger',
            'new_transaction' => 'info',
            'payment_confirmed' => 'primary',
            'delivery_confirmed' => 'success',
            'account_status' => 'warning',
            'system' => 'secondary',
        ];
        return $colors[$this->type] ?? 'secondary';
    }

    /**
     * Get time ago in human readable format.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
