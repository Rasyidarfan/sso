<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuthCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'client_id',
        'user_id',
        'expires_at',
        'used_at',
        'state'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a new authorization code
     */
    public static function generate($clientId, $userId, $state = null)
    {
        return self::create([
            'code' => Str::random(40),
            'client_id' => $clientId,
            'user_id' => $userId,
            'expires_at' => now()->addMinutes(10),
            'state' => $state
        ]);
    }

    /**
     * Check if the code is valid (not expired and not used)
     */
    public function isValid()
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }

    /**
     * Mark this code as used
     */
    public function markAsUsed()
    {
        $this->used_at = now();
        $this->save();
    }

    /**
     * Get the user associated with this code
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client app associated with this code
     */
    public function clientApp()
    {
        return $this->belongsTo(ClientApp::class, 'client_id', 'client_id');
    }
}
