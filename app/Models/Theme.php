<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ThemeColor;

class Theme extends Model
{
    protected $table = 'themes';
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             $row->colors()->delete();
             $row->pages()->delete();
        });
    }
    
    public function mainColor() {
        return ThemeColor::where('theme_id', $this->id)
            ->where('main', 1)
            ->first();
    }
    
    public function colors()
    {
        return $this->hasMany('App\Models\ThemeColor', 'theme_id');
    }
    
    public function pages()
    {
        return $this->hasMany('App\Models\ThemePage', 'theme_id');
    }
    
    public function status_lang($label=false)
    {
        $lang = 'Inctive';
        $class = 'warning';
        if($this->status == STATUS_ACTIVE)
        {
            $lang = 'Active';
            $class = 'success';
        }
        
        if($label == false) return $lang;
        
        return "<label class='text-$class'>$lang</label>";
    }
    
    public function path() 
    {
        return theme_url() . '/' . $this->path;
    }
    
    public function imgPath($uri='')
    {
        $suffix = '';
        if($uri != '') $suffix .= '/' . $uri;
        
        return $this->path() . '/preview' . $suffix;
    }
}
