<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    protected $table = 'theme_colors';
    public $timestamps  = false;
    
    public function theme()
    {
        return $this->hasMany('App\Models\Theme', 'theme_id');
    }
}
