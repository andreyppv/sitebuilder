<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referrals extends Model
{
    protected $table = 'referrals';

    public function status_lang()
    {
        if($this->status == STATUS_ACTIVE) return 'Active';
        else 'InActive';
    }
}
