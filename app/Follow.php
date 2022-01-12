<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
  /*
  |--------------------------------------------------------------------------
  | Follow Model
  |--------------------------------------------------------------------------
  |
  | ユーザーのフォロー情報保存用モデル
  | usersテーブル対usersテーブルの中間テーブルモデル
  |
  */

    public function user()
    {
      return $this->belongsTo('App\User', 'id');
    }
}
