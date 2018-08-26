<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CouponsUsed extends Model
{
    //
    protected $table = 'coupons_used';
    protected $guarded = [];

    protected static function getUsed($coupon_id, $user_id)
    {
        return CouponsUsed::select('id')->where([
            'coupon_id' => $coupon_id,
            'used_by' => $user_id
        ])->first();
    }

    protected static function addUsed($coupon_id, $user_id)
    {
        return CouponsUsed::insertGetId([
            'coupon_id' => $coupon_id,
            'used_by' => $user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
