<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConveyanceItem extends Model {
    use HasFactory;

    protected $fillable = [
        'conveyance_id',
        'from_place',
        'to_place',
        'amount',
        'remarks',
    ];

    public function conveyance() {
        return $this->belongsTo( Conveyance::class );
    }
}
