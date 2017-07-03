<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontController;
use App\Libraries\Template;
use App\Models\Customer;
use App\Models\CustomerPasswordReset;
use App\Models\SocialProfile;
use Input, Validator, Redirect, URL, Mail, DB, Session, Socialize;

class AuthController extends FrontController {
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function login()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        return $this->view('auth.login');
    }
    
    public function register()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        return $this->view('auth.register');
    }
    
    public function registerSuccess()
    {
        if(!$this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        $this->pageTitle = 'Register Success';
        return $this->view('auth.registerSuccess');
    }
    
    public function registerComplete()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        if(!Session::has('profile_id'))
        {
            return URL::route('register');
        }
        
        $profile_id = Session::get('profile_id');
        $profile = SocialProfile::find($profile_id);
        
        $this->pageTitle = 'Register';
        return $this->view('auth.registerComplete', compact('profile'));
    }
    
    public function logout()
    {
        $this->auth->logout();
        
        return redirect('/');;
    }
    
    public function forgotPassword()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');;
        }
        
        $this->pageTitle = 'Forgot password';     
        return $this->view('auth.forgotPassword');
    }
    
    public function resetPassword($token = null)
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        $this->pageTitle = 'Reset Password';
        return $this->view('auth.resetPassword')->with('token', $token);
    }
    
    //facebook signup
    public function registerWithSocial($driver = 'facebook')
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        return Socialize::with($driver)->redirect();
    }
    
    public function registerCallback($driver = 'facebook')
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        if($driver == 'facebook')
        {
            if($user = Socialize::with($driver)->user())
            {
                $customer = Customer::where('email', $user->email)->first();
                        
                //if email is already registered, 
                //then redirect to register page
                if(isset($customer->id))
                {
                    Template::set_message('Your email is already registered in our system.', 'danger');
                    return Redirect::route('register');
                }
                
                //insert social_profiles
                //we collected customer's social profile information for future
                $profile = SocialProfile::firstOrCreate(['social_name' => 'facebook', 'social_id' => $user->id]);
                $profile->email     = $user->email;
                $profile->first_name= $user->user['first_name'];
                $profile->last_name = $user->user['last_name'];
                $profile->avatar    = $user->avatar_original;
                $profile->save();
                
                Session::set('profile_id', $profile->id);
                Session::save();
                
                return Redirect::route('register.complete');
            }
            else
            {
                //can't get facebook user
                Template::set_message('Something went wrong. Try again.', 'danger');
                return Redirect::route('register');
            }  
        }      
    }
    //end facebook signup
    
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////
    public function postLogin()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        //validate posts
        $rules = [
            'email' => 'required|email', 
            'password'   => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        //check user row by email
        $email = Input::get('email');
        $pass  = Input::get('password'); 
        $remember = Input::get('remember') ? true : false;
        if(!$this->auth->login($email, $pass, $remember))
        {
            return Redirect::route('login');
        }
        
        return redirect('/');
    }   
    
    public function postRegister()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        //validate posts
        $rules = [
            'email'     => 'required|email|unique:customers', 
            'password'  => 'required|min:4',
            'password_confirm' => 'required|same:password',
            'first_name' => 'required|max:32',
            'last_name'  => 'required|max:32',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        //check user row by email
        if(!$this->_registerCustomer())
        {
            //Template::set_message(trans('auth.registeration_fail'), 'danger');
            return Redirect::route('register');
        }
        
        //Template::set_message(trans('auth.registeration_success'), 'success');
        //return Redirect::route('login');       
        
        //save registered email to session
        Session::set('registered_email', Input::get('email'));
        Session::save();
         
        return Redirect::route('register.success');        
    } 
    
    public function postForgotPassword()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
          
        //validate posts
        $rules = ['email' => 'required|email'];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get Customer Row by email
        $email = Input::get('email');
        $customer = Customer::where('email', $email)->first();
        
        // If we can't find
        if($customer === null)
        {
            Template::set_message(trans('auth.email_not_found'), 'danger');
            
            return redirect()->back()->withInput();
        }
        
        // Save the reset_code to the db for later retrieval
        $token = sha1(str_random(40) . $email);
        $expire = strtotime('+1 hours');
        CustomerPasswordReset::insert([
            'email' => $email,
            'token' => $token,
            'expire'=> $expire
        ]);
        
        // Send email to customer
        $this->to_email = $email;
        $this->to_name = "$customer->first_name $customer->last_name";
        Mail::send('emails.forgotPassword', array('token' => $token, 'settings' => $this->settings), function($message)
        {
            $message->from($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                ->to($this->to_email, $this->to_name)
                ->subject('Reset your password');
        });
        
        // Set message
        Template::set_message(trans('auth.reset_pass_message'), 'success');
        
        return Redirect::route('forgot');
    } 
    
    public function postResetPassword()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        //validate posts
        $rules = [
            'email' => 'required|email',
            'password'  => 'required|min:4',
            'password_confirm' => 'required|same:password',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Remove expired reset password request in customer_password_resets
        CustomerPasswordReset::where('expire', '<', time())->delete();
        
        // Check if there is token row in customer_password_resets
        $token = Input::get('token');
        $email = Input::get('email');
        $password = Input::get('password');
        $token_row = CustomerPasswordReset::where('email', $email)
            //->where('token', $token)
            ->first();
         
        if($token_row === null)
        {
            Template::set_message(trans('auth.invalid_expired_link'), 'danger');
            
            return Redirect::route('forgot');
        }
        
        // Get customer row
        $customer = Customer::where('email', $email)->first();
        if($customer === null)
        {
            Template::set_message(trans('auth.email_not_found'), 'danger');
            
            return Redirect::route('forgot');
        }
        
        $salt = str_random(8);
        $customer->passsalt = $salt;
        $customer->password = md5($salt . $password);
        $customer->save();
        
        // Remove reset request 
        CustomerPasswordReset::where('email', $email)
            ->where('token', $token)
            ->delete();
         
        Template::set_message(trans('auth.reset_password_success'), 'success');
        //return Redirect::route('login');
        return redirect('/');
    } 
    
    public function postRegisterComplete()
    {
        if($this->auth->isLoggedIn())
        {
            return redirect('/');
        }
        
        //validate posts
        $rules = [
            'password'  => 'required|min:4',
            'password_confirm' => 'required|same:password',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        //check user row by email
        if(!($customer = $this->_registerCustomerFromSocial()))
        {
            //Template::set_message(trans('auth.registeration_fail'), 'danger');
            return Redirect::route('register');
        }
        
        //Template::set_message(trans('auth.registeration_success'), 'success');
        //return Redirect::route('login');       
        
        //save registered email to session
        Session::forget('profile_id');
        Session::set('registered_email', $customer->email);
        Session::save();
         
        return Redirect::route('register.success');        
    } 
    ////////////////////////////////////////////////////////////////
    // Private Functions
    ////////////////////////////////////////////////////////////////
    private function _registerCustomer()
    {
        $email  = Input::get('email');
        $pass   = Input::get('password'); 
        $name   = Input::get('full_name');
        
        $salt = str_random(8);
        $customer = new Customer;
        $customer->email = $email;
        $customer->passsalt = $salt;
        $customer->password = md5($salt . $pass);
        $customer->name = $name;
        $customer->is_active = 1;
        $customer->save();
        
        // Send register email to customer
        $this->to_email = $email;
        $this->to_name  = $name;
        Mail::send('emails.register', array('customer' => $customer, 'settings' => $this->settings), function($message) 
        {
            $message->from($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                ->to($this->to_email, $this->to_name)
                ->subject('Thanks for your registering!');
        });
        
        return isset($customer->id) ? true : false;
    }
    
    private function _registerCustomerFromSocial()
    {
        $profile_id = Session::get('profile_id');
        $profile = SocialProfile::find($profile_id);
         
        if(!isset($profile->id)) 
        {
            return Redirect::route('register');
        }
        
        $pass = Input::get('password'); 
        $salt = str_random(8);
        $customer = new Customer;
        $customer->email = $email;
        $customer->passsalt = $salt;
        $customer->password = md5($salt . $pass);
        $customer->name = $name;
        $customer->is_active = 1;
        $customer->save();
        
        // Send register email to customer
        $this->to_email = $profile->email;
        $this->to_name = "$profile->first_name $profile->last_name";
        Mail::send('emails.register', array('customer' => $customer, 'settings' => $this->settings), function($message) 
        {
            $message->from($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                ->to($this->to_email, $this->to_name)
                ->subject('Thanks for your registering!');
        });
        
        return isset($customer->id) ? $customer : false;
    }
    
    ////////////////////////////////////////////////////////////////
    // Ajax Functions
    ////////////////////////////////////////////////////////////////
    public function ajaxLogin()
    {
        $result = array('status' => false, 'msg' => '');
        
        if($this->auth->isLoggedIn())
        {
            $result['status'] = true;
        }
        else
        {            
            //check user row by email
            $email = Input::get('email');
            $pass  = Input::get('password'); 
            $remember = Input::get('remember') ? true : false;
            if($this->auth->login($email, $pass, $remember))
            {
                $result['status'] = true;
            }
            else
            {
                $messages = Template::get_messages();
                $result['msg'] = $messages[0]['message'];
            }            
        }
        
        echo json_encode($result);
        exit;
    } 
    
    public function ajaxRegister()
    {
        if($this->auth->isLoggedIn())
        {
            exit;
        }
        
        //result
        $result = array('status' => false, 'msg' => '');
        
        //validate posts
        $rules = [
            'email'     => 'required|email|unique:customers', 
            'password'  => 'required|min:4',
            'full_name' => 'required|max:32',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            $result['msg'] = $validator->errors()->first();
            echo json_encode($result);
            exit;
        }
        
        //check user row by email
        if(!$this->_registerCustomer())
        {
            //Template::set_message(trans('auth.registeration_fail'), 'danger');
            $result['msg'] = trans('auth.registeration_fail');
        }
        else
        {
            //$result['status'] = true;
            
            //login
            //check user row by email
            $email = Input::get('email');
            $pass  = Input::get('password'); 
            if($this->auth->login($email, $pass))
            {
                $result['status'] = true;
            }
            else
            {
                $messages = Template::get_messages();
                $result['msg'] = $messages[0]['message'];
            }            
        }
        
        echo json_encode($result);
        exit;
    }  
}