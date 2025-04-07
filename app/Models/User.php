<?php

namespace App\Models;
/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Event[] $events
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

        // 🔸 Événements organisés par l'utilisateur
    public function event()
    {
        return $this->hasMany(Event::class, 'user_id');
    }
    /**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user', 'user_id','event_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
