<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavSpot extends Model
{
  /*
  |--------------------------------------------------------------------------
  | FavSpot Model
  |--------------------------------------------------------------------------
  |
  | ユーザーの行きたいスポット情報保存用モデル
  | usersテーブル対spotsテーブルの中間テーブルモデル
  |
  */

    protected $fillable = ['user_id','spot_id'];
    protected $table = 'favoritespots';
}
