<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_code',
        'item_id',
        'seller_id',
        'buyer_id',
        'amount',
        'admin_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'delivery_method',
        'dropoff_point',
        'qris_code',
        'notes',
        'paid_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Generate unique transaction code
            $transaction->transaction_code = 'TRX-' . strtoupper(uniqid()) . '-' . date('Ymd');
        });
    }

    /**
     * Get the item associated with the transaction.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the seller.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the buyer.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if transaction is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if transaction is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->payment_status === 'cancelled';
    }

    /**
     * Get payment status label with color.
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'warning'],
            'paid' => ['label' => 'Sudah Dibayar', 'color' => 'info'],
            'completed' => ['label' => 'Selesai', 'color' => 'success'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'danger'],
        ];
        return $labels[$this->payment_status] ?? ['label' => $this->payment_status, 'color' => 'secondary'];
    }

    /**
     * Get payment method label.
     */
    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'qris' => 'QRIS',
            'cod' => 'COD / Tunai',
        ];
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get delivery method label.
     */
    public function getDeliveryMethodLabelAttribute()
    {
        $labels = [
            'dropoff' => 'Drop-off Point',
            'cod' => 'COD / Serah Terima',
        ];
        return $labels[$this->delivery_method] ?? $this->delivery_method;
    }

    /**
     * Get formatted total amount.
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted admin fee.
     */
    public function getFormattedAdminFeeAttribute()
    {
        return 'Rp ' . number_format($this->admin_fee, 0, ',', '.');
    }
}
