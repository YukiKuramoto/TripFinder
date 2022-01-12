<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavPlan extends Model
{
  /*
  |--------------------------------------------------------------------------
  | FavPlan Model
  |--------------------------------------------------------------------------
  |
  | ユーザーのお気に入り情報保存用モデル
  | usersテーブル対plansテーブルの中間テーブルモデル
  |
  */

    protected $fillable = ['user_id','plan_id'];
    protected $table = 'favoriteplans';
}
