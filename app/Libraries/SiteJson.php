<?php

namespace App\Libraries;

class SiteJson {
    private $_path = '';
    private $_metaPath = '';
    private $_jsonData = null;
    
    private $_error = '';
    public function __construct($path = '', $json = '')
    {
        $this->load($path, $json);  
        
        return $this;  
    }
    
    public function load($path, $json)
    {
        $this->_path = site_path($path);
        
        //check path
        if(!file_exists($this->_path)) {
            $this->_error = 'Invalid site path.';
            return false;
        }
        
        //check meta path
        $this->_metaPath = "$this->_path/$json.json";
        if(!file_exists($this->_metaPath)) {
            $this->_error = 'Json file is missing.';
            return false;
        }
         
        //load json file
        $jsonContent = file_get_contents($this->_metaPath);
        $this->_jsonData = json_decode($jsonContent);
        
        //if has error return false
        if(json_last_error()) {
            $this->_jsonData = null;
            
            $this->_error = 'Invalid json file.';
            return false;
        }
        
        $this->_jsonData->pages = (array)$this->_jsonData->pages;
        
        return true;
    }
    
    public function getSiteInfo()
    {
        return isset($this->_jsonData->info) ? $this->_jsonData->info : false;
    }
    
    public function getPageInfo($key)
    {             
        $result = isset($this->_jsonData->pages[$key]) ? $this->_jsonData->pages[$key]->info : false;
        if($result == false && $key == 'index')
        {
            foreach($this->_jsonData->pages as $pg) {
                $result = $pg->info;
                break;
            }    
        }
        
        return $result;
    }    
    
    public function getPageElements($key)
    {
        $result = isset($this->_jsonData->pages[$key]) ? $this->_jsonData->pages[$key]->elements : false;
        if($result == false && $key == 'index')
        {
            foreach($this->_jsonData->pages as $pg) {
                $result = $pg->elements;
                break;
            }    
        }
        
        return (array)$result;
    } 
    
    public function getPageDndElements($key)
    {
        $result = isset($this->_jsonData->pages[$key]) ? $this->_jsonData->pages[$key]->dnd_elements : false;
        if($result == false && $key == 'index')
        {
            foreach($this->_jsonData->pages as $pg) {
                $result = $pg->dnd_elements;
                break;
            }    
        }
        
        return (array)$result;
    }   
    
    public function getLastError() {
        return $this->_error;
    }   
}