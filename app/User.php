<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function plans()
    {
        return $this->hasMany('App\Plan')
          ->orderBy('created_at', 'desc');
    }

    public function spots()
    {
        return $this->hasMany('App\Spot')
          ->orderBy('created_at', 'desc');
    }

    public function followers()
    {
        return $this->hasMany('App\Follow', 'followed_user_id');
    }

    public function follows()
    {
        return $this->hasMany('App\Follow', 'follower_user_id');
    }

    public function favPlans()
    {
        return $this->belongsToMany('App\Plan', 'favoriteplans')
          ->orderBy('created_at', 'desc');
    }

    public function favSpots()
    {
        return $this->belongsToMany('App\Spot', 'favoritespots')
          ->orderBy('created_at', 'desc');
    }
}
