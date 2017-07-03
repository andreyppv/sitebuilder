<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Core\FrontController;
use App\Libraries\Template;
use App\Models\Customer;
use App\Models\SocialProfile;
use Input, Validator, Redirect, URL, Mail, DB, Session, Socialize;
class AccountController extends FrontController {
    protected $menu =  'account';
    protected $pageTitle = 'My Account';
    
    public function __construct()
    {
        parent::__construct();
        
        // Check customer is logged in
        $this->auth->restrict();
    }
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        return $this->view('account.index');   
    }
    
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////   
    
    
    ////////////////////////////////////////////////////////////////
    // Private Functions
    ////////////////////////////////////////////////////////////////
      
    
    ////////////////////////////////////////////////////////////////
    // Ajax Functions
    ////////////////////////////////////////////////////////////////
    public function ajaxUpdatePassword()
    {
        $user = $this->current_customer;
        
        //result
        $result = array('status' => false, 'msg' => '');
                                    
        //validate posts
        $rules = [
            'current_password'  => "required|check_current_password:customers,id,$user->id",
            'password'          => 'required',
            'password_confirm'  => 'same:password'
        ];     
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            $result['msg'] = $validator->errors()->first();
        }
        else
        {
            // Save new account information        
            $salt = str_random(8);
            $this->current_customer->passsalt = $salt;
            $this->current_customer->password = md5($salt . Input::get('password'));
            $this->current_customer->save();
                 
            // Set update message
            $result['status'] = true;
            $result['msg'] = 'Your password is updated successfully';
        }
        
        echo json_encode($result);
        exit;
    }
    
    public function ajaxUpdateEmail()
    {
        $user = $this->current_customer;
        
        //result
        $result = array('status' => false, 'msg' => '');
                                    
        //validate posts
        $rules = [
            'email' => "required|email|unique:customers,email,$user->id,id", 
        ];     
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            $result['msg'] = $validator->errors()->first();
        }
        else
        {
            // Save new account information        
            $this->current_customer->email = Input::get('email');
            $this->current_customer->save();
                 
            // Set update message
            $result['status'] = true;
            $result['msg'] = 'Your email is updated successfully';
        }
        
        echo json_encode($result);
        exit;
    }
    
    public function ajaxUpdateName()
    {
        $user = $this->current_customer;
        
        //result
        $result = array('status' => false, 'msg' => '');
                                    
        //validate posts
        $rules = [
            'full_name' => 'required|max:128',
        ];     
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            $result['msg'] = $validator->errors()->first();
        }
        else
        {
            // Save new account information        
            $this->current_customer->name = Input::get('full_name');
            $this->current_customer->save();
                 
            // Set update message
            $result['status'] = true;
            $result['msg'] = 'Your name is updated successfully';
        }
        
        echo json_encode($result);
        exit;
    }
}