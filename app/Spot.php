<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $guarded = array('spot_id');
    
    public static $rules = array(
        'user_id' => 'required',
        'plan_id' => 'required',
        'spot_name' => 'required',
        'stay_time' => 'required',
        'stay_address' => 'required',
        'spot_image' => 'required',
        'spot_information' => 'required',
        );
    
}
