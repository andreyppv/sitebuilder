<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePageDndElement extends Model
{
    protected $table = 'site_page_dnd_elements';
    public $timestamps = false;
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             SitePageFile::where('site_id', $row->site_id)
                ->where('page_id', $row->site_page_id)
                ->where('element_id', $row->id)
                ->delete();
        });
    }
     
    public function site()
    {
        return $this->hasMany('App\Models\Site', 'site_id');
    }

    public function page()
    {
        return $this->hasMany('App\Models\SitePage', 'site_page_id');
    }
    
    public function hasFile()
    {
        if($this->type != ITEM_FILE) return false;
        
        $row = SitePageFile::where('site_id', $this->site_id)
            ->where('page_id', $this->site_page_id)
            ->where('element_id', $this->id)
            ->first();
        if(!isset($row->id))
        {
            return false;
        }
        
        return true;
    }
    
    public function downloadFile()
    {
        return SitePageFile::where('site_id', $this->site_id)
            ->where('page_id', $this->site_page_id)
            ->where('element_id', $this->id)
            ->first();
    }
    
}
