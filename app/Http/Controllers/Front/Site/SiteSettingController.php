<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use App\Models\Domain;
use App\Models\Site;
use App\Models\SitePage;

use Input;

class SiteSettingController extends FrontAuthenticatedController {
    protected $menu = 'settings';
    protected $pageTitle = 'My Sites';        
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->cart->isEmpty()) force_redirect(my_route('site.list'));
        
        //get purchased domains
        $domains = Domain::where('customer_id', $this->current_customer->id)
            ->orderBy('name')
            ->lists('name', 'id')
            ->toArray();
        $this->data['domains'] = array('' => 'Please Select') + $domains;
    }
    
    ////////////////////////////////////////////////////////////////
    // Action Methods
    ////////////////////////////////////////////////////////////////
    
    /**
    * get all site settings and display them
    * 
    */
    public function index()
    {
        $site = $this->cart->getSite();
        
        return $this->view('site.settings.content', compact('site'));   
    } 
    
    /**
    * update site title
    * 
    */
    public function updateTitle()
    {
        $result = array('status' => true, 'error' => '');
        
        //get site from session
        $site = $this->cart->getSite();
        
        $site->title = Input::get('site_title');
        $site->save();
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * update site status
    * 
    */
    public function updateStatus()
    {
        $result = array('status' => true, 'error' => '', 'msg' => '');
        
        //get site from session
        $site = $this->cart->getSite();
        
        $action = Input::get('action');
        if($action == 'activate')
        {
            $site->status = STATUS_ACTIVE;
            
            $result['msg'] = 'Site is activated.';
        }
        else
        {
            $site->status = STATUS_INACTIVE;
            
            $result['msg'] = 'Site is deactivated.';
        }
        $site->save();
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * upload favicon for site
    * 
    */
    public function uploadFavicon()
    {
        //get site from session
        $site = $this->cart->getSite();
        
        $result = $this->uploadImage(UPLOADS_BASE . '/site/favicon/');
        if($result['status']) 
        {
            $site->favicon = $result['src'];
            $site->save();
        }        
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * update meta information
    * 
    */
    public function updateMetaInfo()
    {
        $result = array('status' => true, 'error' => '', 'msg' => '');
        
        //get site from session
        $site = $this->cart->getSite();
        
        $site->meta_description = Input::get('meta_description');
        $site->meta_keywords    = Input::get('meta_keywords');
        $site->google_analytics = Input::get('google_analytics');   
        $site->save();
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * update site url address
    * 
    */
    public function updateUrl()
    {
        $result = array('status' => true, 'error' => '');
        
        //get site from session
        $site = $this->cart->getSite();
        
        $action     = Input::get('action');
        $domainUrl  = Input::get('subdomain_url');
        $type       = Input::get('type');
        if($action == 'subdomain')
        {
            if($this->_isValidSubDomain($domainUrl))
            {
                $site->subdomain_url = $domainUrl;
                $site->domain_type   = $type;
                $site->pointdomain_url = '';
                $site->save();
            }
            else
            {
                $result['status'] = false;
                $result['error']  = 'Subdomain is already taken.';  
            }
        }
        else if($action == 'pointdomain')
        {
            if($this->_isValidPointDomain($domainUrl))
            {
                $site->pointdomain_url  = $domainUrl;
                $site->domain_type      = $type;
                $site->save();
            }
            else
            {
                $result['status'] = false;
                $result['error']  = 'Url is already taken.';  
            }
        }
        else if($action == 'regdomain')
        {
            $newDomainId = Input::get('newdomainid');
            $assignVal   = Input::get('assign_val');
            
            if($assignVal == 'assign')
            {
                $domainRow = Domain::find($newDomainId);
                
                if(isset($domainRow->id))
                {
                    $data = [
                        'domain_id'         => 0,
                        'domain_type'       => 'SD',
                        'pointdomain_url'   => '',
                    ];
                    
                    //remove domain url from existing sites
                    Site::where('customer_id', $this->current_customer->id)
                        ->where('domain_id', $domainRow->id)
                        ->update($data);
                    
                    //set domain url to current one
                    $site->pointdomain_url  = $domainRow->name;
                    $site->domain_type      = 'PD';
                    $site->domain_id        = $domainRow->id;
                    $site->save();
                }
                else
                {
                    $result['status'] = false;
                    $result['error']  = trans('msg.has_error');
                }
            }
            else if($assignVal == 'noassign')
            {
            }
        }
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * check if subdomain is valid
    * 
    */
    public function checkSubDomain()
    {
        $subdomain = Input::get('subdomain');
        if($this->_isValidSubDomain($subdomain))
            echo 'valid';
        else
            echo 'invalid';

        exit;
    }
    
    /**
    * check if pointdomain is valid
    * 
    */
    public function checkPointDomain()
    {
        $pointdomain = Input::get('pointdomain');
        if($this->_isValidPointDomain($pointdomain))
            echo 'valid';
        else
            echo 'invalid';

        exit;
    }
    ////////////////////////////////////////////////////////////////
    // Private Methods
    ////////////////////////////////////////////////////////////////      
    
    /**
    * check if subdomain is already taken
    * 
    * @param mixed $domain
    */
    private function _isValidSubDomain($domain)
    {
        //get site
        $site = $this->cart->getSite();        
        
        //check from site list
        $result = Site::where('subdomain_url', $domain)
            ->where('id', '!=', $site->id)
            ->count();
        
        if($result > 0)
        {
            return false;
        }
        
        return true;    
    }
    
    /**
    * check if pointdomain is already taken
    * 
    * @param mixed $domain
    */
    private function _isValidPointDomain($domain)
    {
        //get site
        $site = $this->cart->getSite();
        
        //check from site list
        $result = Site::where('pointdomain_url', $domain)
            ->where('id', '!=', $site->id)
            ->count();
            
        if($result > 0)
        {
            return false;
        }
        
        return true;    
    }
}