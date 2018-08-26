<?php

namespace App\Http\Controllers\Restful;

use App\Http\Controllers\Controller;
use App\Config;

class ConfigController extends Controller
{
    //
    function getList()
    {
        $list = Config::select('ascii', 'value')->where('locked', 0)->get();
        $result = array();
        if($list) {
            foreach ($list as $value) {
                $result[$value->ascii] = $value->value;
            }
        }
        return json_encode($result);
    }
}
