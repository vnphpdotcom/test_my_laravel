<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    //
    protected $table = 'config';
    protected $guarded = [];

    protected static function getValue($ascii,int $locked = 0)
    {
        return Config::select('value')->where(['ascii'=>$ascii,'locked'=>$locked])->first()->value;
    }
}
