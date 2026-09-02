<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_type',
        'user_name',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a new audit activity log entry.
     */
    public static function record(
        string $action,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?User $user = null
    ): self {
        $currentUser = $user ?? Auth::guard('web')->user();
        $currentNasabah = Auth::guard('nasabah')->user();

        $userType = 'system';
        $userName = 'System Engine';
        $userId = null;

        if ($currentUser) {
            $userType = 'petugas';
            $userName = $currentUser->name;
            $userId = $currentUser->id;
        } elseif ($currentNasabah) {
            $userType = 'nasabah';
            $userName = $currentNasabah->nama . ' (' . $currentNasabah->nomor_nasabah . ')';
        }

        return self::create([
            'user_id' => $userId,
            'user_type' => $userType,
            'user_name' => $userName,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'properties' => !empty($properties) ? $properties : null,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => substr(request()->userAgent() ?? 'N/A', 0, 250),
            'created_at' => now(),
        ]);
    }
}
