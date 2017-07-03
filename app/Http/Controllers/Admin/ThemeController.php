<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Libraries\ThemeParser;
use App\Models\Theme;
use App\Models\ThemeColor;
use App\Models\ThemePage;
use App\Models\ThemePageElement;

use Input, URL;

class ThemeController extends FrontAuthenticatedController {
    protected $pageTitle = 'Themes';
    
    public function __construct()
    { 
        parent::__construct();   
    }
    
    public function index()
    { 
        exit;
    }
    
    //extract
    public function extractTemplate()
    {
        $templateName = 'rapidcms_template_v8';
        $themeParser = new ThemeParser($templateName);
        $templateData = $themeParser->getThemeInfo();
        if($templateData == false) {
            echo $themeParser->getLastError();
            exit;
        }
        
        //insert new theme
        $theme = new Theme;
        $theme->tid     = $templateData->tid;  
        $theme->name    = $templateData->name;
        $theme->mobile  = $templateData->mobile;
        $theme->version = $templateData->version;
        $theme->path    = $templateName;
        $themeAdded     = $theme->save();
        
        if($themeAdded)
        {        
            $this->_insertThemeColors($theme->id, $templateData->colors);
            $this->_insertThemePages($theme->id, $templateData->pages);
        }
        
        exit;
    }
    
    /**
    * insert theme color data for theme
    * 
    * @param mixed $themeId
    * @param mixed $colorArray
    */
    private function _insertThemeColors($themeId, $colorArray)
    {
        foreach($colorArray as $i => $color)
        {
            $themeColor = new ThemeColor;
            $themeColor->theme_id   = $themeId;   
            $themeColor->name       = $color->name;
            $themeColor->image      = $color->thumb_main;
            $themeColor->value      = $color->hex;
            $themeColor->main       = ($i == 0) ? 1 : 0;
            $themeColor->sort       = $i;
            $themeColor->style      = $color->name . '.css';
            $added = $themeColor->save();
            
            if(!$added) return false;
        }
        
        return true;
    }
    
    private function _insertThemePages($themeId, $pageArray)
    {
        foreach($pageArray as $key => $page)
        {
            $themePage = new ThemePage;
            $themePage->theme_id = $themeId;
            $themePage->key      = $key;
            $themePage->name     = $page->name;   
            $added = $themePage->save();
            
            if($added)
            {
                if(isset($page->form))
                {
                    $themePage->form_id     = $page->form->id;
                    $themePage->form_name   = $page->form->name;
                    $themePage->form_type   = $page->form->type;
                    $themePage->form_title  = $page->form->title;
                    $themePage->form_submit = $page->form->submit;
                    $themePage->form_privacy= $page->form->privacy;
                    $themePage->form_action = $page->form->action;
                    $themePage->save();
                    
                    $this->_insertThemePageElements($themeId, $themePage->id, $page->form->form_elements, 'form'); 
                }
                
                $this->_insertThemePageElements($themeId, $themePage->id, $page->elements, 'basic'); 
            }
        }
        
        return true;
    }
    
    private function _insertThemePageElements($themeId, $pageId, $elements, $wrapper)
    {
        foreach($elements as $el)
        {
            $pageElement = new ThemePageElement;
            $pageElement->theme_id = $themeId;
            $pageElement->theme_page_id = $pageId;
            $pageElement->tid  = $el->id;
            $pageElement->name = $el->name;
            $pageElement->type = $el->type;
            $pageElement->content = isset($el->content) ? $el->content : null;
            $pageElement->wrapper = $wrapper;
            if(isset($el->data))
            {
                $pageElement->data = json_encode($el->data);
            }
            
            $pageElement->save();
        }    
        
        return true;
    }
}