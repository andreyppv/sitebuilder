<?php

if(!function_exists('admin_url')) 
{
    function admin_url($uri='')
    {
        return URL::to("admin/$uri");
    }
}

if(!function_exists('upload_url')) 
{
    function upload_url($uri='')
    {
        return URL::to(UPLOADS_BASE . "/$uri");
    }
}

if(!function_exists('upload_path')) 
{
    function upload_path($uri='')
    {
        return public_path(UPLOADS_BASE . "/$uri");
    }
}

if(!function_exists('upload_uri')) 
{
    function upload_uri($uri='')
    {
        return UPLOADS_BASE . "/$uri";
    }
}

if(!function_exists('theme_url')) 
{
    function theme_url($uri='')
    {
        return URL::to("templates/$uri");
    }
}

if(!function_exists('theme_path')) 
{
    function theme_path($uri='')
    {
        return public_path('templates/' . $uri);
    }
}

if(!function_exists('site_path')) 
{
    function site_path($uri='')
    {
        return public_path("sites/$uri");
    }
}

if(!function_exists('current_url')) 
{
    function current_url()
    {
        $url = Request::url();
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    }
}

if ( ! function_exists('prep_url'))
{
    /**
     * Prep URL
     *
     * Simply adds the http:// part if no scheme is included
     *
     * @param   string  the URL
     * @return  string
     */
    function prep_url($str = '')
    {
        if ($str === 'http://' OR $str === '')
        {
            return '';
        }
        if($str === '#')
        {
            return $str;
        }
        
        $url = parse_url($str);
        if ( ! $url OR ! isset($url['scheme']))
        {
            return 'http://'.$str;
        }
        return $str;
    }
}

if(!function_exists('my_route')) 
{
    function my_route($route)
    {
        if(!Session::has($route)) return route($route);
        
        $params = Session::get($route);
        return route($route, $params);
    }
}

if ( ! function_exists('random_subdomain'))
{
    function random_subdomain($folder='')
    {
        $http_host  = str_replace("www.",'',$_SERVER['HTTP_HOST']);     
        $http_host  = str_replace("http://",'',$http_host);      
        $http_host  = str_replace("https://",'',$http_host);     
        $randdomain = $folder == '' ? rand(10000000,99999999) : $folder;
        $randdomain = $randdomain.".".$http_host;
        
        return $randdomain;
    }
}

if ( ! function_exists('my_domain'))
{
    function my_domain()
    {
        return $_SERVER['SERVER_NAME'];
    }
}

if ( ! function_exists('valid_domain'))
{
    function valid_domain($domain)
    {
        if (preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $domain)) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('gen_slug'))
{
    function gen_slug($str)
    {
        # special accents
        $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
        $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
    }
}