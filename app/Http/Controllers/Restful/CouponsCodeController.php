<?php

namespace App\Http\Controllers\Restful;

use App\CouponsCode;
use App\CouponsUsed;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponsCodeController extends Controller
{
    //
    function getCoupons(Request $request)
    {
        $count = CouponsCode::select('id')->where('code',$request->code)->count();
        if($count)
        {
            $data = CouponsCode::select('code','type','value','status','limited_at','limit_used','expired_at')->where('code',$request->code)->get();
            foreach ($data as $value)
            {
                if($value->status === 'limit')
                {
                    return ($value->limited_at < $value->limit_used)?$data:response()->json(['error' => true,'data'=>''],400);
                }
                elseif ($value->status === 'expire')
                {
                    $current = strtotime(Carbon::now());
                    $expired = strtotime($value->expired_at);
                    return ($current < $expired)?$data:response()->json(['error' => true,'data'=>''],400);
                }
                elseif($value->status === 'no limit') return $data;
                else return response()->json(['error' => true,'data'=>''],400);
            }
        }
        else response()->json(['error' => true,'data'=>''],400);
    }
}
