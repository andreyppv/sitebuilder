<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use App\Models\Theme;
use App\Models\ThemeColor;
use App\Models\Site;
use App\Models\SitePageElement;

use Input;

class SiteCustomizeController extends FrontAuthenticatedController {
    protected $menu = 'customize';
    protected $pageTitle = 'My Sites';        
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->cart->isEmpty()) force_redirect(my_route('site.list'));
    }
    
    ////////////////////////////////////////////////////////////////
    // Action Methods
    ////////////////////////////////////////////////////////////////
    
    /**
    * display customize page
    * 
    */
    public function index()
    {
        $themes = Theme::orderBy('name')->get();
        
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        $elements = SitePageElement::select('tid', 'type')
            ->where('site_id', $site->id)
            ->where('site_page_id', $page->id)
            ->get()
            ->toJson();
        
        return $this->view('site.customize.content', compact('site', 'page', 'elements', 'themes'));
    } 
    
    /**
    * update theme color
    * 
    */
    public function updateColor()
    {
        $result = array('status' => false, 'msg' => '');
        
        //get color row
        $colorId = Input::get('color_id');
        if($this->cart->changeColor($colorId))
        {
            $result['status'] = true;
        }
        else
        {
            $result['msg'] = $this->cart->getLastError();
        }
        
        echo json_encode($result);
        exit;    
    }
    
    /**
    * update theme for current site
    * 
    */
    public function updateTheme()
    {
        $result = array('status' => false, 'msg' => '');
        
        $themeId = Input::get('theme_id');
        $colorId = Input::get('color_id');
        
        $site = $this->cart->getSite();
        //if same thme, don't delete pages and elements
        if($site->theme_id == $themeId) 
        {
            if($this->cart->changeColor($colorId)) 
            {
                $result['status'] = true;
                
                Template::set_message('Color is changed.', 'success');
            } 
            else 
            {
                $result['msg'] = $this->cart->getLastError();
            }
        } 
        else 
        {
            if($this->cart->changeTheme($themeId, $colorId)) 
            {
                $result['status'] = true;
                
                Template::set_message('Theme is changed.', 'success');
            } 
            else 
            {
                $result['msg'] = $this->cart->getLastError();
            }
        }
        
        echo json_encode($result);
        exit;
    }
}