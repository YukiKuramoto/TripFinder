<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SpotComment extends Model
{
  /*
  |--------------------------------------------------------------------------
  | SpotComment Model
  |--------------------------------------------------------------------------
  |
  | スポットコメント情報保存用モデル
  |
  */

  protected $table = 'spot_comment';
  protected $guarded = array('id');

  public function spots()
  {
    return $this->belongsTo('App\Spot', 'spot_id');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

}
