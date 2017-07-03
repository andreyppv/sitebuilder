<?php 

if(!function_exists('format_currency')) 
{
    function format_currency($number)
    {
        $prefix = '$ ';
        return $prefix . number_format($number, 2);
    }
}

if ( ! function_exists('force_redirect'))
{
    function force_redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        if ( ! preg_match('#^https?://#i', $uri))
        {
            $uri = url($uri);
        }

        switch($method)
        {
            case 'refresh': 
                header("Refresh:0;url=".$uri);
                break;
            default: 
                header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }
}

if ( ! function_exists('my_array_column'))
{
    function my_array_column($array, $column_key, $index_key='', $has_empty=false, $empty_text='Select')
    {
        $result = array();
        
        if($has_empty == true)
        {
            $result[''] = $empty_text;
        }
        
        if((!is_array($array))) 
        {
            return $result;
        }
        
        foreach($array as $a)
        {
            if(!array_key_exists($column_key, $a)) continue;
            if($index_key != '' && !array_key_exists($index_key, $a)) continue;
            
            if($index_key != '') $result[$a[$index_key]] = $a[$column_key];
            else $result[] = $a[$column_key];
        }
        
        return $result;
    }
}

if(!function_exists('mkpath')) 
{
    function mkpath($path)
    {
        if(@mkdir($path) or file_exists($path)) return true;
        return (mkpath(dirname($path)) and mkdir($path));
    }
}

if(!function_exists('file_icon')) 
{
    function file_icon($name)
    {
        $temp = explode('.', $name);
        $ext = end($temp);
        
        $icon = "$ext-icon.png";
        if(!file_exists(public_path("images/icons/$icon")))
        {
            $icon = "txt-icon.png";
        }
        
        return url("images/icons/$icon");
    }   
}

if(!function_exists('sort_th')) 
{
    function sort_th($base_uri, $col_label, $order_field, $text_align='center')
    {
        $order = Request::input('order');
        $order_by = Request::input('orderby');
        
        $class = 'sorting';
        if($order_field == $order)
        {
            if($order_by == 'asc') 
            {
                $class = 'sorting_asc';
            }
            else 
            {
                $class = 'sorting_desc';
            }
        }
        
        $link = URL::route($base_uri, sort_link($order_field)); 
        $result = "<th class='sort_th text-$text_align $class' data-href='$link'>$col_label</th>";
        return $result;        
    } 
}

if ( !function_exists('sort_link') )
{
    function sort_link($order_field='')
    {     
        $order = Request::input('order');
        $order_by = Request::input('orderby');
        
        $stack = array();
        $stack[] = "order=" . $order_field;  
        if($order == $order_field) $stack[] = 'orderby=' . ($order_by == 'asc' ? 'desc' : 'asc');         
        else $stack[] = 'orderby=asc';
        
        /*$query = '';
        if(!empty($stack))
        {
            $query .= '?' . join('&', $stack);
        }*/
        
        return join('&', $stack);
    }
}

if ( !function_exists('paginate_links') )
{
    function paginate_links($rows)
    {
        if(empty($rows)) return '';
        
        $order = Request::input('order');
        $order_by = Request::input('orderby');
        
        if($order != '' && $order_by != '')
        {
            $rows->appends(['order' => $order])
                ->appends(['orderby' => $order_by]);
        }
        
        return $rows->render();    
    }
}

if ( !function_exists('paginate_links_with_params') )
{
    function paginate_links_with_params($rows)
    {
        if(empty($rows)) return '';
        
        foreach(Request::all() as $key => $value)
        {
            $rows->appends([$key => $value]);
        }
        
        return $rows->render(new App\Libraries\Pagination($rows));    
    }
}

if ( !function_exists('creditcard_expire_months') )
{
    function creditcard_expire_months()
    {
        $result = array();
        for($i=1; $i<=12; $i++)
        {
            $result[$i] = $i;
        }
        
        return $result;
    }
}

if ( !function_exists('creditcard_expire_years') )
{
    function creditcard_expire_years($years=5)
    {
        $result = array();
        $from = date('Y');
        for($i=$from; $i<=$from+$years; $i++)
        {
            $result[$i] = $i;
        }
        
        return $result;
    }
}


if ( !function_exists('prettyPrint') )
{
    function prettyPrint( $json )
    {
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = NULL;
        $json_length = strlen( $json );

        for( $i = 0; $i < $json_length; $i++ ) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if( $ends_line_level !== NULL ) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ( $in_escape ) {
                $in_escape = false;
            } else if( $char === '"' ) {
                $in_quotes = !$in_quotes;
            } else if( ! $in_quotes ) {
                switch( $char ) {
                    case '}': case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;

                    case '{': case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            } else if ( $char === '\\' ) {
                $in_escape = true;
            }
            if( $new_line_level !== NULL ) {
                $result .= "\n".str_repeat( "\t", $new_line_level );
            }
            $result .= $char.$post;
        }

        return $result;
    }
}