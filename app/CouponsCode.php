<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsCode extends Model
{
    //
    protected $table = 'coupons_code';
    protected $guarded = [];

    protected static function getValue($code)
    {
        $count = CouponsCode::select('id')->where('code',$code)->count();
        if($count)
        {
            $data = CouponsCode::select('id','code','type','value','status','limited_at','limit_used','expired_at')->where('code',$code)->first();
            if($data->status === 'limit')
            {
                return ($data->limited_at < $data->limit_used)?$data:null;
            }
            elseif ($data->status === 'expire')
            {
                    $current = strtotime(Carbon::now());
                    $expired = strtotime($data->expired_at);
                    return ($current < $expired)?$data:null;
            }
            elseif($data->status === 'no limit') return $data;
            else return null;
        }
    }
}
