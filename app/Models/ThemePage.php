<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemePage extends Model
{
    protected $table = 'theme_pages';
    public $timestamps = false;
    
    public function theme()
    {
        return $this->hasMany('App\Models\Theme', 'theme_id');
    }

	protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             $row->formElements()->delete();
             $row->elements()->delete();
        });
    }

	public function elements()
    {
        return $this->hasMany('App\Models\ThemePageElement', 'theme_page_id');
    }
}
