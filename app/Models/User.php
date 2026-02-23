<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school',
        'grade',
        'phone',
        'role',
        'is_active',
        'profile_photo',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the items for the user.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the transactions where user is seller.
     */
    public function soldTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    /**
     * Get the transactions where user is buyer.
     */
    public function boughtTransactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    /**
     * Get the wishlist items for the user.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the wishlisted items.
     */
    public function wishlistedItems()
    {
        return $this->belongsToMany(Item::class, 'wishlists')->withTimestamps();
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the approvals made by the user as admin.
     */
    public function approvals()
    {
        return $this->hasMany(ApprovalLog::class, 'admin_id');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return asset('images/default-avatar.png');
    }
}
