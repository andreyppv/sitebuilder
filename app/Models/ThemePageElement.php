<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemePageElement extends Model
{
    protected $table = 'theme_page_elements';
    public $timestamps = false;
    
    public function theme()
    {
        return $this->hasMany('App\Models\Theme', 'theme_id');
    }

	public function page()
    {
        return $this->hasMany('App\Models\ThemePage', 'theme_page_id');
    }
}
