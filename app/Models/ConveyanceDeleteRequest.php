<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConveyanceDeleteRequest extends Model {
    use HasFactory;

    protected $fillable = [
        'conveyance_id',
        'requested_by',
        'approved_by',
        'rejected_by',
        'status',
        'approved_at',
        'rejected_at',
        'conveyance_date',
        'conveyance_total_amount',
        'conveyance_owner_id',
        'request_ip',
        'request_user_agent',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'conveyance_date' => 'date',
    ];

    public function conveyance() {
        return $this->belongsTo( Conveyance::class );
    }

    public function requester() {
        return $this->belongsTo( User::class, 'requested_by' );
    }

    public function approver() {
        return $this->belongsTo( User::class, 'approved_by' );
    }

    public function rejecter() {
        return $this->belongsTo( User::class, 'rejected_by' );
    }

    public function conveyanceOwner() {
        return $this->belongsTo( User::class, 'conveyance_owner_id' );
    }
}
