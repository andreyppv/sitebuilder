<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $table = 'store_products';
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($row) { // before delete() method call this
             $row->images()->delete();
        });
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\StoreProductImage', 'product_id');
    }
     
    public function category()
    {
        return $this->belongsTo('App\Models\StoreCategory', 'category_id');
    }
    
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id');
    }
    
    public function shipping()
    {
        return $this->belongsTo('App\Models\StoreShipping', 'shipping_id');
    }
}
