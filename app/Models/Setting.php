<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    public $timestamps  = false;
    
    /**
    * return result by index array
    * 
    * @param mixed $field
    * @param mixed $value
    * @param mixed $type
    * @return []
    */
    static function allBy($field=null, $value=null, $type='and')
    {
        // Setup the field/value check.
        $results = null;       
        if ($field != null && !is_array($field)) 
        {
            $field = array($field => $value);
            
            if ($type == 'or') 
            {
                $results = Setting::orWhere($field)->get();
            } 
            else 
            {
                $results = Setting::where($field)->get();
            }
        }
        else
        {
            $results = Setting::all();
        }
        
        $resultArray = array();
        foreach ($results as $record) 
        {
            $resultArray[$record->name] = $record->value;
        }

        return $resultArray;
    }
    
    /**
    * update batch rows
    *  
    * @param mixed $data
    */
    static function updateBatch($data)
    {
        if(!is_array($data) && empty($data)) return false;
        
        foreach($data as $key => $value)
        {
            $affectedRows = Setting::where('name', $key)->update(['value' => $value]);
            
            if($affectedRows === null) return false;
        }
        
        return true;
    }
}
