<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Models\Transaction;

use Input, Redirect, Request;

class TransactionController extends FrontAuthenticatedController {
    protected $menu = 'transaction';
    protected $pageTitle = 'My Transactions';        
    
    public function __construct()
    {
        parent::__construct();
    }
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        //get orders
        $order      = Request::input('order');
        $orderby    = Request::input('orderby') == 'asc'? 'asc': 'desc';
        switch($order)
        {
            case 'created_at':
            case 'description':
            case 'method':
            case 'amount':
                break;
            case 'reference':
                $order = 'transaction_id';
                break;
            default:
                $order = 'created_at';
                $orderby = 'desc';
                break;
        }
        
        $rows = Transaction::orderBy($order, $orderby)->paginate($this->list_limit);
        
        return $this->view('transaction.index', compact('rows'));   
    } 
}