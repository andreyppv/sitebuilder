<?php

namespace App\Http\Controllers\Customer;
use App\Libraries\SiteJson;
use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SitePage;
use App\Models\Setting;

class WebSiteController extends Controller {
    private $site = null;
    private $data = array();  
    
    protected $settings = array();
    protected $jsonInstance = null;
    public function __construct()
    {
        //parent::__construct();
        $domain = my_domain();    
        $this->site = Site::where('subdomain_url', $domain)
            ->orWhere('pointdomain_url', $domain)
            ->first();
        
        if(!isset($this->site->id)) {
            force_redirect(route('website.error'));   
        }
            
        //load site json, if fail redirect to error page
        $this->jsonInstance = new SiteJson;
        if(!$this->jsonInstance->load($this->site->path, $this->site->json)) {
            force_redirect(route('website.error'));   
        }
        
        //check status
        if($this->site->status == STATUS_INACTIVE) {
            force_redirect(route('website.deactive'));   
        }
        
        //check status
        if($this->site->publish == STATUS_INACTIVE) {
            force_redirect(route('website.unpublish'));   
        }
            
        $this->data['site'] = $this->jsonInstance->getSiteInfo();
        $this->data['settings'] = Setting::allBy();
        $this->data['base_url'] = theme_url($this->site->theme->path) . '/';
        $this->data['primary_url'] = prep_url(env('SITEURL'));    
    }
    
    public function index()
    {        
        $page = $this->jsonInstance->getPageInfo('index');
        $params = $this->jsonInstance->getPageElements('index');
        $newElements = $this->jsonInstance->getPageDndElements('index');
        
        if(!$page) return force_redirect(route('website.error')); 
        
        $page->title = $this->data['site']->title;
        $view = $this->site->theme->path . ".index"; 
        return $this->view2($view, compact('page', 'params', 'newElements'));           
    }
    
    public function page($slug='')
    {         
        $page = $this->jsonInstance->getPageInfo($slug);
        $params = $this->jsonInstance->getPageElements($slug);
        $newElements = $this->jsonInstance->getPageDndElements($slug);
        
        if(!$page) return force_redirect(route('website.error')); 
        
        $view = '';
        if($page->type == 'basic')
        {
            $view = $this->site->theme->path . ".{$slug}"; 
        }
        else
        {
        }
        
        return $this->view2($view, compact('page', 'params', 'newElements'));              
    }
    
    public function error()
    {
        return $this->view('error');   
    }
    
    public function deactive()
    {
        return $this->view('deactive');   
    }
    
    public function unpublish()
    {
        return $this->view('unpublish');   
    }
    
    protected function view($view, $data=array())
    {
        return view("customer.{$view}", $data, $this->data);
    }
    
    protected function view2($view, $data=array())
    {
        return view($view, $data, $this->data);
    }
}