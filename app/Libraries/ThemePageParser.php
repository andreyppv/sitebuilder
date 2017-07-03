<?php

namespace App\Libraries;
class ThemePageParser {
    private $_path = '';
    private $_dom = null;
    private $_head = null;
    
    private $_validTypes = array('html', 'text', 'image', 'picture', 'blankbox');
    private $_error = '';
    
    private $_elements = array();
    public function __construct($path = '')
    {
        $this->load($path);    
    }
    
    public function load($path)
    {
        //check path
        if(!file_exists($path)) {
            $this->_error = 'Invalid page file path.';
            return false;
        }
        
        libxml_use_internal_errors(true);
        
        $this->_path = $path;
        $html = file_get_contents($path);
        
        $this->_dom = new \DOMDocument(); 
        $this->_dom->formatOutput = true;
        $this->_dom->encoding = 'utf-8';
        $this->_dom->loadHTML(utf8_decode($html)); 
        $this->_dom->preserveWhiteSpace = false;

        libxml_clear_errors();
        
        $head = $this->_dom->getElementsByTagName('head');
        if(count($this->_head)) {
            $this->_error = 'No header in html.';
            return false;
        } else {
            $this->_head = $head[0];
        }
        
        return true;
    }
    
    /**
    * check all elements in html and grab content from html
    * and return it
    * 
    * @param mixed $elements
    */
    public function checkElements($elements)
    {
        if(!is_array($elements)) return false;
        
        foreach($elements as $e)
        {
            if(!isset($e->id) || !isset($e->name) || !isset($e->type)) {
                $this->_error = 'Missing item one of id,name,type for ' . json_encode($e);
                return false;                                                             
            }
            if(!in_array($e->type, $this->_validTypes)) {
                $this->_error = 'Wrong type for ' . $e->id;
                return false;                                                             
            }
            
            $domElement = $this->_dom->getElementById($e->id);               
            if($domElement == null) {
                $this->_error = 'Can\'t fine element in html for ' . $e->id . ' in ' . $this->_path;
                return false;                                                             
            }
            
            $e->content = '';
            if($e->type == 'html') {
                if($domElement->hasChildNodes()) {
                    $e->content = $this->_getInnerHtml($domElement);
                }
            } else if($e->type == 'text') {
                if($domElement->tagName != 'input') {
                    $this->_error = 'Wrong type for ' . $e->id . '. Type must be html for this.';
                    return false;
                }
                
                $e->content = trim($domElement->getAttribute('value'));   
            } else if($e->type == 'image') {
                if($domElement->tagName != 'img') {
                    $this->_error = 'Wrong type for ' . $e->id . '. Type must be image for this.';
                    return false;
                }
                
                $e->content = $domElement->getAttribute('src');
            } else if($e->type == 'picture') {
                
            }    
        }
        
        $this->_elements = $elements;
        
        return true;
    }
    
    public function getElements()
    {
        return $this->_elements;
    }
    
    /**
    * save html to blade file with insert something
    * 
    */
    public function saveHtml() {
        
        //replace title
        $titleElement = $this->_dom->getElementsByTagName('title');
        if(count($titleElement))
        {
            $titleElement[0]->nodeValue = '{{ isset($page->title) ? $page->title : "" }}';
        }
        else
        {
            $titleElement = $this->_dom->createElement('title', '{{ isset($page->title) ? $page->title : "" }}');
            $this->_head->appendChild($titleElement);
        }
        
        //append <base href=""/> to head
        $baseElement = $this->_dom->createElement('base', '');
        $baseElement->setAttribute('href', '{{ $base_url }}');        
        
        if($this->_head->hasChildNodes())
        {
            $firstChild = $this->_head->childNodes[0];
            $this->_head->insertBefore($baseElement, $firstChild);
        }
        else
        {
            $this->_head->appendChild($baseElement);    
        }
        
        //modify element values for laravel variables
        foreach($this->_elements as $el)
        {
            $domElement = $this->_dom->getElementById($el->id);
            
             if($el->type == 'html') {
                $domElement->nodeValue = '{!! isset($params["' . $el->id .'"]) ? $params["' . $el->id .'"]->content : "" !!}';
            } else if($el->type == 'text') {
                $domElement->setAttribute('value', '{{ isset($params["' . $el->id .'"]) ? $params["' . $el->id .'"]->content : "" }}');
            } else if($el->type == 'image') {
                $domElement->setAttribute('src', '{{ (isset($params["' . $el->id .'"]) && ($params["' . $el->id .'"]->modified == 1)) ? url($params["' . $el->id .'"]->content) : "' . $el->content . '" }}');
            } else if($el->type == 'picture') {   
                $html = '
                    @if($params["' . $el->id .'"]->content != "")
                    <img src="{{url($params["' . $el->id . '"]->content)}}" width="100%" height="100%"/>
                    @else'
                    . $this->_getInnerHtml($domElement)
                    . '@endif
                ';
                $domElement->nodeValue = $html;
            } else if($el->type == 'blankbox') {
                $domElement->nodeValue = $this->_getBlankBoxContent();
            }
        }
        
        //save to file
        $fileInfo = pathinfo($this->_path);  
        $newFileName = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '.blade.php';
        
        $this->_dom->saveHTMLFile($newFileName);
        
        //reload new file and replace &gt;
        $data = file_get_contents($newFileName);
        $data = html_entity_decode($data, ENT_QUOTES);
        $data = str_replace(array("%7B%7B", "%7D%7D", "%24", "%20", "%22", "%5B", "%5D"), array("{{", "}}", "$", " ", "'", "[", "]"), $data);
        file_put_contents($newFileName, $data); 
        //
        
        return true;
    }
    
    public function getLastError() {
        return $this->_error;
    }
    
    private function _getBlankBoxContent() {
        $html = "@include('frontend.site.element.dropbox')";
        
        return $html;
    }
    
    private function _getInnerHtml( $node )   
    {  
        if($node->hasChildNodes())
        {
            $innerHTML= '';  
            $children = $node->childNodes;     
            foreach ($children as $child)  
            {  
                $innerHTML .= $child->ownerDocument->saveXML( $child );  
            }     
            return str_replace(array('&#13; ', '&#13;'), '', preg_replace('/\s+/', ' ', trim($innerHTML)));  
        }
        else
        {
            return trim($node->nodeValue);
        }
    }  
}