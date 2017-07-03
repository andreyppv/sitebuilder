<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Core\BasicController;
use AdminAuth;

class AdminController extends BasicController 
{
    protected $menu = '';
    protected $page = 'dashboard';
    
    protected $current_user = null;
    
    protected $list_route = '';
    protected $list_limit = 25;
     
    public function __construct()
    {
        parent::__construct();   
        
        // If user is logged in, then get customer
        $this->auth = new AdminAuth();
        $this->auth->restrict();
        
        $this->current_user = $this->auth->user();
        $this->data['current_user'] = $this->current_user;
        
        $this->data['curi'] = $this->controller_uri;
    }
      
    protected function view($view, $data = array())
    {              
        return parent::view("admin.{$view}", $data);
    } 
    
    protected function saveSorting()
    {
        if(!Request::has('order')) return;
        
        $order      = Request::input('order');
        $orderby    = Request::input('orderby') == 'asc'? 'asc': 'desc';
        
        $data = ['order' => $order, 'orderby' => $orderby];
        
        Session::set($this->list_route, $data);
        Session::save();
    }
    
    protected function listUrl()
    {
        return my_route($this->list_route);
    }   
    
    protected function uploadImage($file, $path='') {
        
        if($path != '')
        {
            $path = '/' . $path . '/';
        }
        
        if(Input::hasFile($file))
        {     
            $file = Input::file($file); 
            
            $fileName = strtolower($file->getClientOriginalName());
            $targetUri = UPLOADS_BASE . $path . substr($fileName, 0, 1) . '/' . substr($fileName, 1, 1);
            $targetPath = public_path($targetUri);
            
            if(!mkpath($targetPath))
            {
                return redirect()
                    ->back()
                    ->withInput();    
            }
            $file->move($targetPath, $fileName);
            
            return $targetUri . '/' . $fileName;  
        }          
        
        return '';
    } 
}