<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';
    
    public function theme()
    {
        return $this->belongsTo('App\Models\Theme', 'theme_id');
    }
    
    public function color()
    {
        return $this->belongsTo('App\Models\ThemeColor', 'color_id');
    }
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             $row->pages()->delete();
        });
    }
    
    public function pages()
    {
        return $this->hasMany('App\Models\SitePage', 'site_id');
    }
    
    public function deletePages()
    {
        foreach($this->pages as $page)
        {
            $page->delete();
        }
    }
    
    public function store()
    {
        return Store::findOrNew($this->store_id);
    }
    
    public function getRealUrl()
    {
        $prefix = $this->getPrefix();
        $domain = $this->getRealUrlString();
        
        return $prefix . $domain;
    } 
    
    public function getPrefix()
    {
        return $this->ssl_option == STATUS_ACTIVE ? 'https://' : 'http://';
    }
    
    public function getRealUrlString()
    {
        return $this->pointdomain_url != '' ? $this->pointdomain_url : $this->subdomain_url;
    }       
}
