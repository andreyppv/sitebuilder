<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Models\Domain;
use App\Models\SitePageElement;
use App\Models\SitePageDndElement;
use App\Models\SitePageFile;
use Input, ImageManager;

class SiteBuilderController extends FrontAuthenticatedController {
    protected $menu = 'create';
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
    public function builder()
    {
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        $elements = SitePageElement::select('tid', 'type')
            ->where('site_id', $site->id)
            ->where('site_page_id', $page->id)
            ->get()
            ->toJson();
        $firstLoading = $this->cart->isFirstLoading();                       
        
        return $this->view('site.builder.content', compact('site', 'page', 'elements', 'firstLoading'));   
    } 
    
    public function loadPage()
    {
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        $base_url = theme_url($site->theme->path) . '/';
        $params = $page->elementsIndex();
        $items = $page->dndElements;
        
        $view = $site->theme->path . '.' . $page->key;
        return $this->view2($view, compact('site', 'page', 'base_url', 'params', 'items')); 
    }
    
    public function changePage()
    {
        $result = ['status' => false, 'error' => ''];
        
        $pageId = Input::get('page_id');
        $result['status'] = $this->cart->setPage($pageId);
        
        echo json_encode($result);
        exit;
    }
    
    public function updateItem()
    {
        $result = ['status' => false, 'error' => ''];
        
        $objectId = Input::get('objectId');
        $content = Input::get('content');
        
        $page = $this->cart->getPage();
        
        $updated = SitePageElement::where('site_page_id', $page->id)
            ->where('tid', $objectId)
            ->update(['content' => trim($content), 'modified' => 1]);
        if($updated) $result['status'] = true;
        
        echo json_encode($result);
        exit;
    }
    
    public function uploadImageSrc()
    {
        $objectId = Input::get('ObjectID');
        
        //get site from session
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        
        //get element
        $element = SitePageElement::where('site_id', $site->id)
            ->where('site_page_id', $page->id)
            ->where('tid', $objectId)
            ->first();
        
        $result = array('status' => false, 'msg' => '');    
        if(isset($element->id))
        {
            $result = $this->uploadImage(UPLOADS_BASE . '/site/images/');
            if($result['status']) 
            {
                //resize
                $data = json_decode($element->data);
                
                $width = $height = 200;
                if(isset($data->width)) $width = $data->width;
                if(isset($data->height)) $height = $data->height;
                $result['src'] = ImageManager::getImagePath($result['src'], $width, $height, 'fit-x');
                $result['path']= url($result['src']);
                    
                //save            
                $element->content = $result['src'];
                $element->modified = 1;
                $updated = $element->save(); 
                if(!$updated)
                {
                    $result['status'] = false;
                }
            }  
        }  
        else
        {
            $result['msg'] = 'Can\'t find element in system.';
        }    
        
        echo json_encode($result);
        exit;
    }
    
    public function sortElements()
    {
        $page = $this->cart->getPage();
        
        if(Input::get('pages'))
        {
            parse_str(Input::get('pages'), $pageOrder);
            foreach($pageOrder['page'] as $key => $val)
            {
                $this->_setSortValue($val, $key);
            }
        }
        
        exit;
    }
    
    public function updateElementSection()
    {
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        $type = Input::get('item_type');
        
        $elementIds = Input::get('element_ids');
        $index = 0;
        foreach($elementIds as $val)
        {                      
            if(($val == '000') && $this->_validObjectType($type))
            {                
                //insert new item
                $row = new SitePageDndElement;
                $row->site_id = $site->id;
                $row->site_page_id = $page->id;
                $row->type = $type;
                $row->data = $this->_getDefaulParamsByType($type);
                $row->save();
                
                $this->_setSortValue($row->id, $index);
            }
            else
            {        
                $val = preg_replace("/[^0-9]/", '', $val);                                                   
                $this->_setSortValue($val, $index);
            }  
            
            $index++;
        }
        
        $items = $page->dndElements;
        return $this->view('site.element.items', compact('items'));  
    }
    
    public function refreshElementSection()
    {
        $page = $this->cart->getPage();
        $items = $page->dndElements;
        return $this->view('site.element.items', compact('items'));  
    }
    
    public function deleteElement()
    {
        //remove element item
        $elementId = Input::get('element-id');
        SitePageDndElement::destroy($elementId);
        
        $page = $this->cart->getPage();
        $items = $page->dndElements;
        return $this->view('site.element.items', compact('items'));  
    }
    
    public function updateElement()
    {
        $objectType = Input::get('objectType');    
        $objectId   = Input::get('objectId');
        $objectContent = Input::get('content');
        
        $result = ['status' => false, 'error' => ''];
        $element = SitePageDndElement::find($objectId);
        
        if($objectType == ITEM_TITLE || $objectType == ITEM_PARAGRAPH)
        {
            $element->content = $objectContent;
            $element->save();
            
            $result['status'] = true;
        }
        
        echo json_encode($result);
        exit;
    }
    
    public function updateMapElement()
    {
        $result = ['status' => true, 'error' => ''];
        
        $elementId  = Input::get('map_element_id');
        $address    = Input::get('map_address');
        $latitude   = Input::get('map_latitude');
        $longitude  = Input::get('map_longitude');
        $zoom       = Input::get('map_zoom');
        
        $element = SitePageDndElement::find($elementId);
        $element->data = json_encode(['address' => $address, 'latitude' => $latitude, 'longitude' => $longitude, 'zoom' => $zoom]);
        $element->save();
        
        return json_encode($result);
        exit;
    }
    
    public function updateYoutubeElement()
    {
        $result = ['status' => true, 'error' => ''];
        
        $elementId = Input::get('youtube_element_id');
        $url    = Input::get('youtube_url');
        $space  = Input::get('youtube_space');
        $width  = Input::get('youtube_width');
        
        $element = SitePageDndElement::find($elementId);
        $element->content = $url;
        $element->data = json_encode(['space' => $space, 'width' => $width]);
        $element->save();
        
        return json_encode($result);
        exit;
    }
    
    public function updateFileElement()
    {
        $objectId = Input::get('ObjectID');
        
        //get site from session
        $site = $this->cart->getSite();
        $page = $this->cart->getPage();
        
        //get element
        $element = SitePageDndElement::find($objectId);
        
        $result = array('status' => false, 'msg' => '');    
        if(isset($element->id))
        {
            $result = $this->uploadFile(UPLOADS_BASE . '/site/files/');
            if($result['status']) 
            {
                $file = SitePageFile::where('site_id', $site->id)
                    ->where('page_id', $page->id)
                    ->where('element_id', $element->id)
                    ->first();
                if(!isset($file->id)) $file = new SitePageFile; 
                
                $file->site_id = $site->id;
                $file->page_id = $page->id;
                $file->element_id = $element->id;
                $file->name = $result['name'];
                $file->path = $result['src'];
                $file->save();
            }  
        }  
        else
        {
            $result['msg'] = 'Can\'t find element in system.';
        }    
        
        echo json_encode($result);
        exit;    
    }
    
    private function _setSortValue($elementId, $sortValue)
    {
        $row = SitePageDndElement::find($elementId);
        if(!isset($row->id)) return;
        
        $row->sort = $sortValue;
        $row->save();    
    }
    
    private function _getDefaulParamsByType($type)
    {
        $data = [];
        
        if($type == ITEM_IMAGE)
        {
            $data['text_align'] = 'center';
            $data['width'] = 200;
            $data['height'] = 200;
        }
        else if($type == ITEM_MAP)
        {
            $data['address'] = '';
            $data['latitude'] = 38.657633;
            $data['longitude'] = 36.936035;                
            $data['zoom'] = 6;
        }
        else if($type == ITEM_YOUTUBE)
        {
            $data['space'] = 'None';
            $data['width'] = 'Large';
        }
        else if($type == ITEM_SOCIAL_ICONS)
        {
            $data['align'] = 'center';
        }
        
        return json_encode($data);
    }
    
    private function _validObjectType($type)
    {
        if($type > 0 && $type <= 15)
        {
            return true;
        }
        
        return false;
    }
}