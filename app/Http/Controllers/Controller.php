<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Input;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function uploadImage($uriPath)
    {
        $tempFile = $_FILES['Filedata']['tmp_name'];
        //$uriPath = 'upload/tmp/slides/';
        
        $fileName = strtolower($_FILES['Filedata']['name']);
        //$uriPath .= substr($fileName, 0, 1) . '/' . substr($fileName, 1, 1) . '/';
        $uriPath .= strtolower(str_random(1) . '/' . str_random(1) . '/');
        $targetPath = rtrim(public_path($uriPath), '/');
        
        $targetFile = $targetPath . '/' . str_replace(' ', '', $fileName);
        if(!mkpath($targetPath)) 
        {
            $result = array('msg' => 'Invalid file path', 'path' => '');
            echo json_encode($result);
            exit;
        }
        
        // Validate the file type
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
        $fileParts = pathinfo($fileName);
         
        $result = array('status' => false, 'msg' => '', 'path' => '', 'src' => '');  
        if (in_array($fileParts['extension'], $fileTypes)) 
        {
            move_uploaded_file($tempFile, $targetFile);
            $result['path'] = url($uriPath . $fileName);
            $result['src'] = $uriPath . $fileName;
            $result['status'] = true;
        } 
        else 
        {
            $result['msg'] = 'Invalid file type.';
        }
        
        return $result;
    }
    
    protected function uploadFile($uriPath)
    {
        $tempFile = $_FILES['Filedata']['tmp_name'];
        //$uriPath = 'upload/tmp/slides/';
        
        $fileName = strtolower($_FILES['Filedata']['name']);
        //$uriPath .= substr($fileName, 0, 1) . '/' . substr($fileName, 1, 1) . '/';
        $uriPath .= strtolower(str_random(1) . '/' . str_random(1) . '/');
        $targetPath = rtrim(public_path($uriPath), '/');
        
        $targetFile = $targetPath . '/' . str_replace(' ', '', $fileName);
        if(!mkpath($targetPath)) 
        {
            $result = array('msg' => 'Invalid file path', 'path' => '');
            echo json_encode($result);
            exit;
        }
        
        // Validate the file type
        $fileParts = pathinfo($fileName);
         
        $result = array('status' => false, 'msg' => '', 'path' => '', 'src' => '');  
        
        move_uploaded_file($tempFile, $targetFile);
        $result['name'] = $_FILES['Filedata']['name'];
        $result['path'] = url($uriPath . $fileName);
        $result['src']  = $uriPath . $fileName;
        $result['status'] = true;
        
        return $result;
    }
}
