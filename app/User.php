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

    // 対象ユーザーをフォローしているユーザーを取得
    public function followers()
    {
        // 第一引数：使用するモデル名称
        // 第二引数：使用するテーブル名称
        // 第三引数：リレーション定義しているモデルの外部キー（キーとなるカラム名）
        // 第四引数：結合するモデルの外部キー（参照するカラム名）
        // フォローされている人のIDを使ってフォローしている人を取得する
        return $this->belongsToMany('App\User', 'follows', 'followed_user_id', 'follower_user_id');
    }

    // 対象ユーザーがフォローしているユーザーを取得する
    public function follows()
    {
        // フォローしている人のIDを使ってフォローされている人を取得する
        return $this->belongsToMany('App\User', 'follows', 'follower_user_id', 'followed_user_id');
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
