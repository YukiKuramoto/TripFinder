<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $guarded = array('spot_id');

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
}
