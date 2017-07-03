<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePageElement extends Model
{
    protected $table = 'site_page_elements';
    public $timestamps = false;
    
    public function site()
    {
        return $this->hasMany('App\Models\Site', 'site_id');
    }

	public function page()
    {
        return $this->hasMany('App\Models\SitePage', 'site_page_id');
    }
}
