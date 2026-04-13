<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'approved_at' => 'datetime',
            'last_login_approved_at' => 'datetime',
        ];
    }

    public function conveyances() {
        return $this->hasMany( Conveyance::class );
    }

    public function activityLogs() {
        return $this->hasMany( UserActivityLog::class );
    }

    public function deleteRequests() {
        return $this->hasMany( ConveyanceDeleteRequest::class, 'requested_by' );
    }

    public function loginRequests() {
        return $this->hasMany( LoginRequest::class );
    }

    public function isApproved(): bool {
        if ( $this->is_admin ) {
            return true;
        }

        return $this->approved_at !== null;
    }
}
