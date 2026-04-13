<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event',
        'method',
        'path',
        'route_name',
        'ip_address',
        'user_agent',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }
}
