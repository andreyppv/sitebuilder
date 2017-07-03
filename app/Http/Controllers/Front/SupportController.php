<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use Input, Validator, Redirect, Session;

class SupportController extends FrontAuthenticatedController {
    protected $menu = 'features';
    protected $pageTitle = 'Features';        
    
    public function __construct()
    {
        parent::__construct();
    }
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        return $this->view('support.index');   
    } 
}