<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    function main(){
        return 1;
        //$data = MainModel::show();
        //return view('widget.body',['data' => $data]);
    }

    public function detail(Request $request){
        $id=$request->id;
        return view('Product.detail');
    }


}
