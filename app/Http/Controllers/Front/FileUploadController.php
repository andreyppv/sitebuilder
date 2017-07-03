<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class FileUploadController extends Controller 
{
    public function slider()
    {
        $result = $this->uploadImage(UPLOADS_BASE . '/tmp/slider/');
        
        echo json_encode($result);
        exit;
    }
}