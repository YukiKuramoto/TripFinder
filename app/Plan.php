<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = array('plan_id');

    public function spots()
    {
      return $this->hasMany('App\Spot');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function tags()
    {
      return $this->belongsToMany('App\Tag', 'plan_tag');
    }

    public function favs()
    {
      return $this->belongsToMany('App\User', 'favoriteplans');
    }

    public function images()
    {
      return $this->hasManyThrough ('App\Image', 'App\Spot');
    }

}
