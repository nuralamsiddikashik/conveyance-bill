<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginRequest extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'request_ip',
        'request_user_agent',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function approver() {
        return $this->belongsTo( User::class, 'approved_by' );
    }

    public function rejecter() {
        return $this->belongsTo( User::class, 'rejected_by' );
    }
}
