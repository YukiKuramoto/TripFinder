<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SpotComment extends Model
{
  protected $table = 'spot_comment';
  protected $guarded = array('id');

  public function spots()
  {
    return $this->belongsTo('App\Spot', 'spot_id');
  }

}
