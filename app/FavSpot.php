<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavSpot extends Model
{
    protected $fillable = ['user_id','spot_id'];
    protected $table = 'favoritespots';
}
