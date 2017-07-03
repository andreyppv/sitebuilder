<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use App\Models\Site;
use App\Models\SitePage;

use Input;

class SitePageController extends FrontAuthenticatedController {
    protected $menu = 'pages';
    protected $pageTitle = 'My Sites';        
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->cart->isEmpty()) force_redirect(my_route('site.list'));
    }
    
    ////////////////////////////////////////////////////////////////
    // Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        $site = $this->cart->getSite();
        $page = $this->cart->getCurrentPage();
        
        return $this->view('site.pages.wrapper', compact('site', 'page'));   
    } 
    
    /**
    * return empty page view
    * 
    */
    public function create()
    {
        //get site from session
        $site = $this->cart->getSite();
        
        //add new page
        $page = new SitePage;
        $page->site_id  = $site->id;
        $page->title    = 'Untitled';
        $page->type     = 'static';
        $page->save();        
        
        return $this->view('site.pages.content', compact('site', 'page'));         
    }
    
    /**
    * return page view by pageId
    * 
    */
    public function edit()
    {
        //get site from session
        $site = $this->cart->getSite();
        
        //get pages
        $page = SitePage::find(Input::get('page_id'));
        if(!isset($page->id)) $page = $site->pages;
        
        return $this->view('site.pages.content', compact('site', 'page'));       
    }
    
    /**
    * delete page 
    * 
    */
    public function delete()
    {
        $pageId = Input::get('page_id');
        SitePage::destroy($pageId);
        
        $site = $this->cart->getSite();
        $page = $this->cart->getCurrentPage();
        return $this->view('site.pages.content', compact('site', 'page')); 
    }
    
    /**
    * save page content
    * 
    */
    public function store()
    {
        //get site from session
        $site = $this->cart->getSite();
        
        //add new page
        $pageId = Input::get('page_id');
        $page = SitePage::find($pageId);
        $page->title        = Input::get('page_title');
        $page->description  = Input::get('page_description');
        $page->meta_keywords= Input::get('meta_keywords');
        $page->is_each_page = Input::has('show_in_form') ? 1 : 0;
        $page->is_term_page = Input::has('terms_conditions') ? 1 : 0;
        $page->is_privacy_page = Input::has('privacy_policy') ? 1 : 0;
        $page->is_contact_page = Input::has('contact_us') ? 1 : 0;
        
        if(Input::has('terms_conditions')) SitePage::where('site_id', $site->id)->update(['is_term_page' => 0]);    
        if(Input::has('privacy_policy')) SitePage::where('site_id', $site->id)->update(['is_privacy_page' => 0]);    
        if(Input::has('contact_us')) SitePage::where('site_id', $site->id)->update(['is_contact_page' => 0]);    
        
        $page->save();
        
        return $this->view('site.pages.content', compact('site', 'page'));
    }
}