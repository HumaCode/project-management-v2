<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $fillable = [
        'ip_address',
        'event_type',
        'url',
        'user_agent',
        'status',
    ];
}
