<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotTag extends Model
{
  /*
  |--------------------------------------------------------------------------
  | SpotTag Model
  |--------------------------------------------------------------------------
  |
  | スポットタグ情報保存用モデル
  | spotsテーブル対tagsテーブルの中間テーブルモデル
  |
  */

  protected $fillable = ['spot_id','tag_id'];
  protected $table = 'spot_tag';
  protected $primaryKey = null;

  public $incrementing = false;
}
