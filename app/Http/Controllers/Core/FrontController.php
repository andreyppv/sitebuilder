<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Core\BasicController;
use CustomerAuth;  

class FrontController extends BasicController 
{
    protected $current_customer = null;
    
    public function __construct()
    {
        parent::__construct();    
        
        // If customer is logged in, then get customer
        $this->auth = new CustomerAuth();
        if($this->auth->isLoggedIn())
        {
            $this->current_customer = $this->auth->customer();
            $this->data['current_customer'] = $this->current_customer;
        }        
    }
    
    protected function view($view, $data = array())
    {    
        return parent::view("frontend.{$view}", $data);
    }   
}