<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conveyance extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function items() {
        return $this->hasMany( ConveyanceItem::class );
    }

    public function user() {
        return $this->belongsTo( User::class );
    }
}
