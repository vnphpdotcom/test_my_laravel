<?php

namespace App\Http\Controllers\Restful;

use App\Config;
use App\CouponsCode;
use App\CouponsUsed;
use App\Document;
use App\Http\Controllers\Controller;
use App\Purchase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    //
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function checkPurchase(Request $request)
    {
        if(Auth::check())
        {
            if(!Document::mySelf(Auth('api')->user()->id,$request->id)) {
                return Purchase::select('id')->where([
                    ['document', $request->id],
                    ['method', 'buy'],
                    ['requested_by', Auth('api')->user()->id],
                    ['status', 1]
                ])->get();
            }else return response()->json([['error' => '']],200);
        }
        else return response()->json(['error' => 'Not authorized'],403);
    }

    function checkout(Request $request)
    {
        if(Auth::check())
        {
            if($request->input('data'))
            {
                $coupons_value = 0;
                $purchase = null;
                $data = json_decode($request->input('data'));
                $coupons = ($request->input('coupons'))?CouponsCode::getValue($request->input('coupons')):0;
                if($coupons)
                {
                    if(CouponsUsed::getUsed($coupons->id,Auth('api')->user()->id)) return response()->json(['error' => 100],200);
                }
                foreach ($data as $value)
                {
                    $count = Purchase::select('id','price')->where([
                        ['document', $value->id],
                        ['requested_by', Auth('api')->user()->id],
                        ['status', 1]
                    ])->count();
                    if(!$count)
                    {
                        $document = Document::select('id','price','created_by')->where([
                            ['id', $value->id],
                            ['status', 1],
                            ['error', 0]
                        ])->first();
                        if($document->created_by !== Auth('api')->user()->id)
                        {
                            $purchase_before = $document->price + $document->price*Config::getValue('vat')/100;
                            $commission = $document->price*Config::getValue('commission')/100;
                            if($coupons)
                            {
                                $purchase_after = ($coupons->type==='percent')?(($purchase_before > $purchase_before*$coupons->value/100)?($purchase_before - $purchase_before*$coupons->value/100):0):(($purchase_before > $coupons->value)?($purchase_before - $coupons_value):0);
                            }
                            else $purchase_after = $purchase_before;
                            if(Auth('api')->user()->price >= $purchase_after)
                            {
                               $purchase = Purchase::addPurchase(['user_id'=>Auth('api')->user()->id,'document_id'=>$document->id,'total_amount'=>$purchase_after], ['user_id'=>$document->created_by,'document_id'=>$document->id,'total_amount'=>$commission]);
                               if($purchase)
                               {
                                   User::updatePrice(User::getPrice(Auth('api')->user()->id) - $purchase_after,Auth('api')->user()->id);
                                   User::updatePrice(User::getPrice($document->created_by) + $commission, $document->created_by);
                               }
                               else return response()->json(['error' => 401],200);
                            }
                        }
                        else return response()->json(['error' => 80],200);
                    }
                }
                if($purchase)
                {
                    ($coupons)?CouponsUsed::addUsed($coupons->id,Auth('api')->user()->id):null;
                    return response()->json(['error' => 0],200);
                }else return response()->json(['error' => 'Not action'],401);

            }
            else return response()->json(['error' => 'Not authorized'],403);
        }
        else return response()->json(['error' => 'Not authorized'],403);
    }
}
