<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use App\Models\Theme;
use App\Models\Site;

use Input;

class SiteController extends FrontAuthenticatedController {
    protected $menu = 'site';
    protected $pageTitle = 'My Sites';        
    protected $listRoute = 'site.list';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    ////////////////////////////////////////////////////////////////
    // Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        $rows = Site::paginate($this->list_limit);
        $pending = $this->cart->hasPending();
        
        return $this->view('site.index', compact('rows', 'pending'));   
    } 
    
    public function themes()
    {
        $themes = Theme::orderBy('name')->get();
        
        return $this->view('site.theme', compact('themes'));   
    }
    
    public function newSession()
    {
        $result = array('status' => false, 'msg' => '');
        
        $themeId = Input::get('theme_id');
        $colorId = Input::get('color_id');
        
        $status = $this->cart->addSite($themeId, $colorId);
        if(!$status)
        {
            $result['msg'] = $this->cart->getLastError();
            echo json_encode($result);
            exit;
        }
        
        $result['status'] = true;
        echo json_encode($result);
        exit;
    }
    
    public function editSession($id)
    {
        $site = Site::find($id);
        if(isset($site->id))
        {
            $this->cart->setSite($id);
            
            force_redirect(route('site.builder'));
        }
        
        return redirect($this->listUrl());
    }
    
    public function exitSession($target='')
    {
        $this->cart->format();
        
        if($target == 'domain')
        {
            return redirect(my_route('domain.list'));
        }        
        
        return redirect($this->listUrl());    
    }
    
    public function publish()
    {
        $result = array('status' => true, 'msg' => '', 'url' => '');
        
        $site = $this->cart->getSite();
        $site->publish = STATUS_ACTIVE;
        $site->json = time();
        $site->save();
        
        //generate new json file
        $this->cart->writeJSON($site->json);
        
        $result['url'] = $site->getRealUrl();
        
        echo json_encode($result);
        exit;    
    }
}