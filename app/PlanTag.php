<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTag extends Model
{
    protected $fillable = ['plan_id','tag_id'];
    protected $table = 'plan_tag';
    protected $primaryKey = null;

    public $incrementing = false;
}
