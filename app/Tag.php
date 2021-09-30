<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = array('tag_id');

    public static $rules = array(
        'tag_name' => 'required',
        );

    public function plans()
    {
      return $this->belongsToMany('App\Plan', 'plan_tag');
    }

    public function spots()
    {
      return $this->belongsToMany('App\Spot', 'spot_tag');
    }
}
