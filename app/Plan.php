<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = array('plan_id');
    
    public static $rules = array(
        'user_id' => 'required',
        'plan_name' => 'required',
        'stay_time' => 'required',
        'plan_information'=> 'required',
        );
    
    public function spots(){
        return $this->hasMany('App\Spot');
    }
}
