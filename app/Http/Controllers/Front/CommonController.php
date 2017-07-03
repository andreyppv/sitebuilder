<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class CommonController extends Controller 
{
    public function map()
    {
        return view('common.map');
    }
}