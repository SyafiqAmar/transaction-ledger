<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeCredential extends Model
{
    protected $fillable = [
        'user_id',
        'exchange', 
        'api_key', 
        'api_secret'
        ];
    protected $casts = [
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
