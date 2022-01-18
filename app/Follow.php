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

  protected $fillable = ['followed_user_id','follower_user_id'];
  protected $table = 'follows';

}
