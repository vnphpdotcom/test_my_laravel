<?php

namespace App\Http\Controllers\Restful;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class CategoryController extends Controller
{
    //
    function getList()
    {
       return Category::select('id','name', 'ascii', 'desc')->get();
    }
}
