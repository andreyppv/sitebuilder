<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $table = 'site_pages';
    public $timestamps = false;
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             $row->elements()->delete();
             $row->dndElements()->delete();
        });
    }
    
    public function site()
    {
        return $this->belongsTo('App\Models\Site', 'site_id');
    }
    
    public function elements()
    {
        return $this->hasMany('App\Models\SitePageElement', 'site_page_id');
    }
    
    public function dndElements()
    {
        return $this->hasMany('App\Models\SitePageDndElement', 'site_page_id')->orderBy('sort');
    }
    
    public function elementsIndex()
    {
        $result = array();
        
        foreach($this->elements as $ele)
        {
            $result[$ele->tid] = $ele;
        }
        
        return $result;
    }
    
    public function save(array $options = [])
    {
        $this->seo_url = $this->getNewSlug(gen_slug($this->title), $this->id?$this->id:0);
        if($this->type != 'basic')
        {
            $this->key = $this->seo_url;
        }
        
        return parent::save();
    }
    
    public function getNewSlug($slug, $id=0)
    {
        $result = self::where('seo_url', 'REGEXP', '^'.$slug.'-[0-9]*$')
            ->where('id', '!=', $id)
            ->max('seo_url');
        
        $result = str_replace($slug.'-', '', $result);
        if($result == '') $result = 0;
        
        $result = (int)$result + 1;
        return $slug . '-' . $result;
    }
}
