<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Core\FrontController;
use App\Models\Customer;
use App\Libraries\MyCart;

class FrontAuthenticatedController extends FrontController {
    
    protected $list_limit = 25;
    protected $cart = null;
    
    public function __construct()
    {
        parent::__construct(); 
        
        // Check customer is logged in
        $this->auth->restrict();   
        
        $this->cart = new MyCart;
        $this->cart->init($this->auth, $this->current_customer);
    } 
    
    //for site customize, move to other later
    protected function elementToJSON($elements)
    {
        $result = array();
        foreach($elements as $el)
        {
            $result[] = [
                'id'    => $el->tid,
                'name'  => $el->name,
                'type'  => $el->type,
                'content' => $el->content,
                //'data'  => json_decode($el->data),
            ];
        }
        
        return json_encode($result);
    }
}