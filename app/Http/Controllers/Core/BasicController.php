<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Setting;

use Request, Session;
class BasicController extends Controller 
{
    protected $data = array();
    protected $settings = array();
    protected $auth = null;
    
    protected $menu = '';
    protected $page = '';
    protected $pageTitle = '';
    protected $pageClass = '';
    protected $listRoute = '';
    
    public function __construct()
    {
         $this->settings = Setting::allBy();    

         $this->data['settings'] = $this->settings;
    }
    
    protected function view($view, $data = array())
    {
        $this->data['menu'] = $this->menu;
        $this->data['page'] = $this->page;
        
        $this->data['pageTitle'] = $this->pageTitle;
        $this->data['pageClass'] = $this->pageClass;
        
        return view($view, $data, $this->data);
    }
    
    protected function view2($view, $data = array())
    {
        $this->data['menu'] = $this->menu;
        $this->data['page'] = $this->page;
        
        $this->data['pageTitle'] = $this->pageTitle;
        $this->data['pageClass'] = $this->pageClass;
        
        return view($view, $data, $this->data);
    }
    
    protected function saveSorting($route='')
    {
        if(!Request::has('order')) return;
        
        $order      = Request::input('order');
        $orderby    = Request::input('orderby') == 'asc'? 'asc': 'desc';
        
        $data = ['order' => $order, 'orderby' => $orderby];
        
        $route = $route == '' ? $this->listRoute : $route;
        Session::set($route, $data);
        Session::save();
    }
    
    protected function listUrl()
    {
        return my_route($this->listRoute);
    }     	
}