<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'ip_address', 'profile_image', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attribute that holds last activity of a user.
     *
     * @var array
     */
    protected $appends = ['lastSeen'];

    /**
     * Return user last activity.
     *
     * @return \String
     */
    public function getLastSeenAttribute() {
        $redis = Redis::connection();
        return $redis->get('last_seen_' . $this->id);
    }
}
