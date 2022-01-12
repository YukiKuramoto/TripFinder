<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTag extends Model
{
  /*
  |--------------------------------------------------------------------------
  | PlanTag Model
  |--------------------------------------------------------------------------
  |
  | プランタグ情報保存用モデル
  | plansテーブル対tagsテーブルの中間テーブルモデル
  |
  */

    protected $fillable = ['plan_id','tag_id'];
    protected $table = 'plan_tag';
    protected $primaryKey = null;

    public $incrementing = false;
}
