<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'type',
        'price',
        'condition',
        'images',
        'legacy_message',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'views_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'approved_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'rejection_reason',
    ];

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the item.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the transaction for this item.
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Get the approval logs for this item.
     */
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    /**
     * Get the wishlists for this item.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the users who wishlisted this item.
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Check if item is available (approved and not in transaction).
     */
    public function isAvailable(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if item is free/gift.
     */
    public function isFree(): bool
    {
        return $this->type === 'gift';
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->isFree()) {
            return 'Gratis';
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get first image URL.
     */
    public function getFirstImageAttribute()
    {
        $images = $this->images ?? [];
        if (!empty($images)) {
            return asset('storage/' . $images[0]);
        }
        return asset('images/default-item.png');
    }

    /**
     * Get all image URLs.
     */
    public function getImageUrlsAttribute()
    {
        $images = $this->images ?? [];
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $images);
    }

    /**
     * Get condition label.
     */
    public function getConditionLabelAttribute()
    {
        $labels = [
            'baru' => 'Baru',
            'sangat_baik' => 'Sangat Baik',
            'baik' => 'Baik',
            'cukup' => 'Cukup',
        ];
        return $labels[$this->condition] ?? $this->condition;
    }

    /**
     * Get category label.
     */
    public function getCategoryLabelAttribute()
    {
        $labels = [
            'buku' => 'Buku',
            'seragam' => 'Seragam',
            'alat_praktikum' => 'Alat Praktikum',
            'lainnya' => 'Lainnya',
        ];
        return $labels[$this->category] ?? $this->category;
    }

    /**
     * Get status label with color (including pending_transaction).
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['label' => 'Menunggu', 'color' => 'warning'],
            'pending_transaction' => ['label' => 'Dalam Transaksi', 'color' => 'info'],
            'approved' => ['label' => 'Disetujui', 'color' => 'success'],
            'rejected' => ['label' => 'Ditolak', 'color' => 'danger'],
            'sold' => ['label' => 'Terjual', 'color' => 'secondary'],
        ];
        return $labels[$this->status] ?? ['label' => ucfirst($this->status), 'color' => 'secondary'];
    }
}
