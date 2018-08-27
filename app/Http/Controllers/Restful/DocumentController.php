<?php

namespace App\Http\Controllers\Restful;

use App\MyLibrary\Ase;
use App\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Document;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    //

    function getList(Request $request)
    {
        switch ($request->action)
        {
            case 'hot': return Document::select('id','name', 'ascii', 'description', 'author', 'thumbnail', 'price', 'viewed', 'score', 'downloaded')->where('status', 1)->limit(6)->orderBy('downloaded')->get();
            case 'new': return Document::select('id','name', 'ascii', 'description', 'author', 'thumbnail', 'price', 'viewed', 'score', 'downloaded')->where('status', 1)->limit(18)->orderBy('created_at')->get();
        }

    }

    function getDetails(Request $request)
    {
        return Document::select('id','name', 'ascii', 'description', 'author', 'pages', 'thumbnail', 'preprice', 'price', 'viewed', 'score', 'downloaded', 'tags')->where([
            ['id', $request->id],
            ['ascii', $request->name]
        ])->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function checkCart(Request $request)
    {
        $data = $request->input('data');
        if($data)
        {
            $data = json_decode($data);
            $res = Array();
            foreach ($data as $key => $value)
            {
                $res[$key] = Document::select('id','name','ascii','thumbnail','preprice','price','author')->where('id',$value->id)->get();
            }
            return ($res)?response()->json(['error'=>false,'data'=>$res],200):response()->json(['error' => true,'data'=>''],200);
        }
        else
        {
            return response()->json(['error' => true,'data'=>''],200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function checkUserCart(Request $request)
    {
        $data = $request->input('data');
        if(Auth::check() && $data)
        {
            $data = json_decode($data);
            $res = Array();
            $j = 0;
            foreach ($data as $key => $value)
            {
                $check = Purchase::select('id')->where([
                    ['document', $value->id],
                    ['method', 'buy'],
                    ['requested_by', Auth('api')->user()->id],
                    ['status', 1]
                ])->count();
                if(!$check && !Document::mySelf(Auth('api')->user()->id,$value->id))
                {
                    $res[$j] = Document::select('id','name','ascii','thumbnail','preprice','price','author')->where('id',$value->id)->get();
                    $j++;
                }
            }
            return ($res)?response()->json(['error'=>false,'data'=>$res],200):response()->json(['error' => true,'data'=>''],200);
        }
        else
        {
            return response()->json(['error' => true,'data'=>''],200);
        }
    }
    
}
