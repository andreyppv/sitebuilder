<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontController;
use Mail, Redirect;

class HomeController extends FrontController {

    public function index()
    {   
        if($this->auth->isLoggedIn())
        {
            return Redirect::route('site.list');
        }
        
        return $this->view('home.index');
    }
}