<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the wishlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the item in the wishlist.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Scope a query to check if item is in user's wishlist.
     */
    public function scopeIsWishlisted($query, $userId, $itemId)
    {
        return $query->where('user_id', $userId)
            ->where('item_id', $itemId)
            ->exists();
    }
}
