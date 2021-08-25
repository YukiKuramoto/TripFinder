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

    public function tags()
    {
      return $this->belongsToMany('App\Tag', 'plan_tag');
    }
}
