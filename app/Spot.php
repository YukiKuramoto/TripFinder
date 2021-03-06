<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
  /*
  |--------------------------------------------------------------------------
  | Spot Model
  |--------------------------------------------------------------------------
  |
  | Spot情報保存用モデル
  |
  */

    protected $guarded = array('spot_id');

    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function plans()
    {
      return $this->belongsTo('App\Plan', 'plan_id');
    }

    public function tags()
    {
      return $this->belongsToMany('App\Tag', 'spot_tag');
    }

    public function images()
    {
      return $this->hasMany('App\Image');
    }

    public function links()
    {
      return $this->hasMany('App\Link');
    }

    public function comments()
    {
      return $this->hasMany('App\SpotComment');
    }

    public function favs()
    {
      return $this->belongsToMany('App\User', 'favoritespots');
    }

    public function commentUsers()
    {
      return $this->belongsToMany('App\User', 'spot_comment');
    }

}
