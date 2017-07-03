<?php

namespace App\Libraries;

use App\Libraries\ThemePageParser;
class ThemeParser {
    private $_path = '';
    private $_metaPath = '';
    private $_jsonData = null;
    
    private $_error = '';
    public function __construct($path = '')
    {
        $this->load($path);  
        
        return $this;  
    }
    
    /**
    * check theme package
    * 
    */
    public function checkInfo()
    {
        //check path
        if(!file_exists($this->_path)) {
            $this->_error = 'Invalid theme path.';
            return false;
        }
        
        //check meta path
        $this->_metaPath = $this->_path . '/meta/template.json';
        if(!file_exists($this->_metaPath)) {
            $this->_error = 'Json file is missing.';
            return false;
        }
        
        return true;
    }
    
    public function load($path)
    {
        $this->_path = theme_path($path);
         
        if(!$this->checkInfo()) return false;
        
        //load json file
        $jsonContent = file_get_contents($this->_metaPath);
        $this->_jsonData = json_decode($jsonContent);
        
        //if has error return false
        if(json_last_error()) {
            $this->_jsonData = null;
            
            $this->_error = 'Invalid json file.';
            return false;
        }
        
        return true;
    }
    
    /**
    * extract json file
    * 
    */
    public function getThemeInfo()
    {
        if($this->_jsonData == null) {
            $this->_error = 'No json data exist.';
            return null;
        }
        if(!isset($this->_jsonData->id)        
            || !isset($this->_jsonData->name)
            || !isset($this->_jsonData->mobile)
            || !isset($this->_jsonData->version)
            || !isset($this->_jsonData->pages)
            || !isset($this->_jsonData->color_variations)) {
            $this->_error = 'Missing one of id, name, mobile, version, color_variations, pages.';
            return false;        
        }
        
        $result = new \stdClass;
        $result->tid    = $this->_jsonData->id;
        $result->name   = $this->_jsonData->name;
        $result->mobile = $this->_jsonData->mobile;
        $result->version = $this->_jsonData->version;
        $result->path   = $this->_path;
        $result->colors = $this->_jsonData->color_variations;
        $result->pages = $this->_jsonData->pages;
        foreach($result->pages as $key => $page)
        {
            $pagePath = $this->_path . "/$key.html";
            $pageParser = new ThemePageParser(); 
            if(!$pageParser->load($pagePath)) {
                $this->_error = $pageParser->getLastError();
                return false;
            } 
            
            if(!$pageParser->checkElements($page->elements)) {
                $this->_error = $pageParser->getLastError();
                return false;
            }
            
            $page->elements = $pageParser->getElements();
            
            $pageParser->saveHtml();
        }
        
        return $result;
    }
    
    public function getLastError() {
        return $this->_error;
    }
}