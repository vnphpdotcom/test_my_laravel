<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    protected $table = 'purchase';
    protected $guarded = [];

    protected static function getPurchase(int $user_id, int $document_id)
    {
        return Purchase::select('id')->where([
            ['document', $document_id],
            ['method', 'buy'],
            ['requested_by', $user_id],
            ['status', 1]
        ])->first();
    }

    /**
     * @param array $buy
     * @param array $sell
     * @param int $status
     */
    protected static function addPurchase(array $buy, array $sell, int $status = 1)
    {
        $current = Carbon::now();
        $transaction_code_buy = md5($current.$buy['user_id'].$buy['document_id'].$buy['total_amount'].'TLYH@2018@BUY'.$current.$sell['user_id'].$sell['document_id'].$sell['total_amount'].'TLYH@2018@SELL');
        $transaction_code_sell = md5($current.$sell['user_id'].$sell['document_id'].$sell['total_amount'].'TLYH@2018@SELL'.$current.$buy['user_id'].$buy['document_id'].$buy['total_amount'].'TLYH@2018@BUY');
        $buy_id = Purchase::insertGetId([
            'transaction_code' => $transaction_code_buy,
            'method' => 'buy',
            'status' => $status,
            'total_amount' => $buy['total_amount'],
            'document' => $buy['document_id'],
            'requested_by' => $buy['user_id'],
            'accepted_by' => $buy['user_id'],
            'requested_at' => $current,
            'accepted_at' => $current
        ]);
        $sell_id = Purchase::insertGetId([
            'transaction_code' => $transaction_code_sell,
            'method' => 'sell',
            'status' => $status,
            'total_amount' => $sell['total_amount'],
            'document' => $sell['document_id'],
            'requested_by' => $sell['user_id'],
            'accepted_by' => $sell['user_id'],
            'requested_at' => $current,
            'accepted_at' => $current
        ]);
        if($buy_id&&$sell_id) return response()->json(['buy_id' => $buy_id,'sell_id' => $sell_id]);
        else return null;
    }
}
