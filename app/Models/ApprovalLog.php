<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approvals_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'admin_id',
        'action',
        'reason',
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
     * Get the item that was moderated.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the admin who performed the moderation.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get action label with color.
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'approved' => ['label' => 'Disetujui', 'color' => 'success'],
            'rejected' => ['label' => 'Ditolak', 'color' => 'danger'],
        ];
        return $labels[$this->action] ?? ['label' => $this->action, 'color' => 'secondary'];
    }

    /**
     * Scope a query to only include approved logs.
     */
    public function scopeApproved($query)
    {
        return $query->where('action', 'approved');
    }

    /**
     * Scope a query to only include rejected logs.
     */
    public function scopeRejected($query)
    {
        return $query->where('action', 'rejected');
    }
}
