<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ClientApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'redirect_uri',
        'redirect_uri_dev',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Generate a new client ID and secret
     */
    public static function generateCredentials()
    {
        return [
            'client_id' => Str::slug(Str::random(8)),
            'client_secret' => Str::random(32),
        ];
    }

    /**
     * Get redirect URI based on environment
     */
    public function getRedirectUri()
    {
        if (config('app.env') === 'local' && !empty($this->redirect_uri_dev)) {
            return $this->redirect_uri_dev;
        }
        
        return $this->redirect_uri;
    }

    /**
     * The user who created this app
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
