<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavPlan extends Model
{
    protected $fillable = ['user_id','plan_id'];
    protected $table = 'favoriteplans';
}
