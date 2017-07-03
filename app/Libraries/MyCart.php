<?php

namespace App\Libraries;

use App\Models\Theme;
use App\Models\ThemeColor;

use App\Models\Site;
use App\Models\SitePage;
use App\Models\SitePageElement;

use Session;
class MyCart {
    private $auth = null;
    private $customer = null;
    private $error = '';
    
    /**
    * construct
    * 
    */
    public function __construct() {

    }
    
    /**
    * init cart
    * 
    * @param mixed $auth
    * @param mixed $customer
    * @return MyCart
    */
    public function init($auth, $customer)
    {
        $this->auth = $auth;
        $this->customer = $customer;
        
        return $this;
    }
    
    /**
    * get site from session
    * 
    */
    public function getSite()
    {
        //get cart_id
        $cartId = Session::get('cart_id');
        
        //get site from cart_id
        $site = Site::find($cartId);
        
        return $site;    
    }
    
    /**
    * set site id to session
    * 
    * @param mixed $siteId
    */
    public function setSite($siteId)
    {
        Session::set('cart_id', $siteId);
        Session::save();    
    }
    
    /**
    * add site to site table from theme and save site id to session
    * 
    * @param mixed $themeId
    * @param mixed $colorId
    */
    public function addSite($themeId, $colorId)
    {
        //format
        $this->format();
        
        //get theme & color row
        $themeRow = Theme::find($themeId);
        $colorRow = ThemeColor::find($colorId);
        
        //validation
        if(!isset($themeRow->id))
        {
            $this->error = 'Theme doesn\'t exist. Try again later.';
            return false;
        }        
        
        
        if(!isset($colorRow->id))
        {
            $this->error = 'Theme color option doesn\'t exist. Try again later.';
            return false;
        } 
        
        //add pages
        if($siteId = $this->_insertSite($themeId, $colorId, $themeRow->name)) {
            foreach($themeRow->pages as $page) {
                if($sitePageId = $this->_insertPage($siteId, $page)) {
                    foreach($page->elements as $ele) {
                        $this->_insertPageElement($siteId, $sitePageId, $ele);
                    }   
                }
            }
            
            $this->setSite($siteId);
            $this->setFirstFlag();
            
            return true;
        }   
        
        return false; 
    }
    
    /**
    * get page from session
    * 
    */
    public function getPage()
    {
        if(Session::has('cart_page_id'))
        {
            return SitePage::find(Session::get('cart_page_id'));
        }
        else
        {
            $page = $this->getSite()->pages[0];
            
            Session::set('cart_page_id', $page->id);
            Session::save();  
            
            return $page;
        }
        
        return false;
    }
    
    public function setPage($pageId)
    {
        $page = SitePage::find($pageId);
        if(isset($page->id))
        {
            Session::set('cart_page_id', $pageId);
            Session::save();
            
            return true;
        }
        
        return false;
    }
    
    public function getCurrentPage()
    {
        if(Session::has('cart_current_page_id'))
        {
            return SitePage::find(Session::get('cart_current_page_id'));
        }
        else
        {
            $page = $this->getSite()->pages[0];
            
            $this->setCurrentPage($page->id);
            
            return $page;
        }
        
        return false;
    }
    
    public function setCurrentPage($pageId)
    {
        Session::set('cart_current_page_id', $pageId);
        Session::save();        
    }
    
    /**
    * change color
    * 
    * @param mixed $colorId
    */
    public function changeColor($colorId)
    {
        $site = $this->getSite();
        
        $color = ThemeColor::where('id', $colorId)
            ->where('theme_id', $site->theme->id)
            ->first();
        if(isset($color->id))
        {
            $site->color_id = $colorId;
            $site->save();
            
            return true;
        }
        
        return false;
    }
    
    /**
    * change theme for current site
    * 
    * @param mixed $themeId
    * @param mixed $colorId
    */
    public function changeTheme($themeId, $colorId)
    {
        //get theme & color row
        $themeRow = Theme::find($themeId);
        $colorRow = ThemeColor::find($colorId);
        
        //validation
        if(!isset($themeRow->id))
        {
            $this->error = 'Theme doesn\'t exist. Try again later.';
            return false;
        }        
        
        if(!isset($colorRow->id))
        {
            $this->error = 'Theme color option doesn\'t exist. Try again later.';
            return false;
        }     
        
        //delete current pages and elements
        $site = $this->getSite();
        $site->deletePages();
        
        //set new ones
        $site->theme_id = $themeId;
        $site->color_id = $colorId;
        $updated = $site->save();
        
        if($updated)
        {
            foreach($themeRow->pages as $page) {
                if($sitePageId = $this->_insertPage($site->id, $page)) {
                    foreach($page->elements as $ele) {
                        $this->_insertPageElement($site->id, $sitePageId, $ele);
                    }   
                }
            }
            
            $this->format();
            $this->setSite($site->id);
            
            return true;    
        }
        
        return false;
    }
    
    public function isEmpty()
    {
        return !Session::has('cart_id');
    }
    
    public function hasPending()
    {
        if($this->isEmpty()) return false;
        
        $site = $this->getSite();
        if($site == null) 
        {
            $this->format();
            return false;
        }
        
        return true;
    }
    
    /**
    * get last error
    * 
    */
    public function getLastError() 
    {
        return $this->error;
    }
    
    /**
    * format cart
    * 
    */
    public function format()
    {
        if(Session::has('cart_id'))
        {
            Session::forget('cart_id');
            Session::forget('cart_page_id');
            Session::save();
        }
        
        return $this;    
    }
    
    /**
    * set first flag for url popup as first loading
    * 
    */
    public function setFirstFlag()
    {
        Session::set('created_first', true);
        Session::save();
        
        return $this;
    }
    
    /**
    * remove first flag from session
    * 
    */
    public function removeFirstFlag()
    {
        Session::forget('created_first');
        Session::save();
        
        return $this;
    }
    
    public function isFirstLoading()
    {
        if(Session::has('created_first'))
        {
            $this->removeFirstFlag();
            
            return true;
        }
        
        return false;
    }
    
    public function writeJSON($timestamp)
    {
        $result = new \stdClass();
        
        $site = $this->getSite();
        
        //site info
        $result->info = new \stdClass(); 
        $result->info->title = $site->title;
        $result->info->theme = (object)[
            'name' => $site->theme->name,
            'path' => $site->theme->path,
            'color'=> $site->color->name,
            'style'=> $site->color->style
        ];
        $result->info->favicon = $site->favicon;
        $result->info->meta_description = $site->meta_description;
        $result->info->meta_keywords = $site->meta_keywords;
        $result->info->google_analytics = $site->google_analytics;
        
        //pages
        $pages = array();
        foreach($site->pages as $page)
        {
            $pageItem = new \stdClass;
            
            $pageItem->info = new \stdClass;
            $pageItem->info->title        = $page->title;
            $pageItem->info->type         = $page->type;
            $pageItem->info->description  = $page->description ? $page->description : '';
            $pageItem->info->meta_keywords= $page->meta_keywords ? $page->meta_keywords : '';
            
            //original elements
            $pageItem->elements = array();
            foreach($page->elements as $el)
            {
                $eleItem = new \stdClass;
                $eleItem->id    = $el->tid;
                $eleItem->type  = $el->type;
                $eleItem->modified= $el->modified;
                $eleItem->content = $el->content;
                
                $pageItem->elements[$el->tid] = $eleItem;    
            }    
            $pages[$page->key] = $pageItem;
            
            $pageItem->dnd_elements = array();
        }
        
        $result->pages = $pages;
        
        $jsonPath = site_path($site->path);
        $jsonFileName = $timestamp .'.json';
        if(!mkpath($jsonPath)) {
            $this->error = 'Site path is invalid.';
            return false;
        }
        
        $file = $jsonPath . '/' . $jsonFileName;
        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        fclose($fp);
        
        return true;
    }    
    
    /**
    * insert site to table
    * 
    * @param mixed $themeId
    * @param mixed $colorId
    * @param mixed $themeName
    */
    private function _insertSite($themeId, $colorId, $themeName)
    {
        $folder = rand(10000000,99999999);
        
        $site = new Site;
        $site->customer_id  = $this->customer->id;
        $site->theme_id     = $themeId;
        $site->color_id     = $colorId;
        $site->title        = $themeName;
        $site->domain_type  = DOMAIN_TYPE_SUB;
        $site->subdomain_url= random_subdomain($folder);
        $site->path         = $folder;   
        $added = $site->save();
        
        if($added) 
        {
            //$this->_copyFiles2SubDomain($site->theme->path, $site->path);
            
            return $site->id;
        }
        
        return 0;
    }
    
    /**
    * insert page to table
    * 
    * @param mixed $siteId
    * @param mixed $themePage
    */
    private function _insertPage($siteId, $themePage)
    {
        $sitePage = new SitePage;
        $sitePage->site_id  = $siteId;
        $sitePage->key      = $themePage->key;
        $sitePage->type     = $themePage->type;
        $sitePage->title    = $themePage->name;
        $sitePage->form_id  = $themePage->form_id;
        $sitePage->form_name= $themePage->form_name;
        $sitePage->form_type= $themePage->form_type;
        $sitePage->form_title   = $themePage->form_title;
        $sitePage->form_submit  = $themePage->form_submit;
        $sitePage->form_privacy = $themePage->form_privacy;
        $sitePage->form_action  = $themePage->form_action;
        $pageAdded = $sitePage->save();
        
        if($pageAdded) return $sitePage->id;
        
        return 0;
    }
    
    private function _insertPageElement($siteId, $sitePageId, $element)
    {
        $sitePageElement = new SitePageElement;
        $sitePageElement->site_id   = $siteId;
        $sitePageElement->site_page_id = $sitePageId;
        $sitePageElement->tid       = $element->tid;
        $sitePageElement->name      = $element->name;
        $sitePageElement->type      = $element->type;
        $sitePageElement->content   = $element->content;
        $sitePageElement->data      = $element->data;
        $sitePageElement->wrapper   = $element->wrapper;
        $elementAdded = $sitePageElement->save();   
        
        if($elementAdded) return $sitePageElement->id;
        
        return 0;
    }
    
    /**
    * copy htmls to subdomain folder by .php file
    * 
    * @param mixed $themePath
    * @param mixed $subDomain
    */
    private function _copyFiles2SubDomain($themePath, $subDomain)
    {
        $sourcePath = public_path("templates/{$themePath}");
        $targetPath = public_path("sites/{$subDomain}");
        
        //check
        if(!file_exists($sourcePath))
        {
            return false;
        }
        
        if(!mkpath($targetPath))
        {
            return false;
        } 
                 
        //copy htmls
        foreach (glob("$sourcePath/*.html") as $fileName) 
        {
            $finfo = pathinfo($fileName);
            $newFileName = $targetPath . '/' . $finfo['filename'] . '.php';
            
            copy($fileName, $newFileName);
        }      
        
        return true;
    }
    
    /**
    * create subdomain folder
    * 
    * @param mixed $site
    */
    private function _createSubdomain($subFolder)
    {
        $targetPath = public_path("sites/$subFolder");  
        
        if(!mkpath($targetPath))
        {
            return false;
        }  
        
        return true;
    }
}