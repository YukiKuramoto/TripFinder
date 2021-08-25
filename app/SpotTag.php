<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotTag extends Model
{
  protected $fillable = ['spot_id','tag_id'];
  protected $table = 'spot_tag';
}
