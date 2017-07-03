<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\AdminController;
use App\Libraries\Template;
use App\Models\Admin;
use Input, Validator, Request, URL, Redirect;

class MemberController extends AdminController {

    protected $page = 'member';
    protected $page_title = 'Members';
    
    protected $list_route = 'admin.member.list';
     
    public function __construct()
    {
        parent::__construct();
    }
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        //get orders
        $this->saveSorting();
        
        $order = Request::input('order');
        $orderby = Request::input('orderby') == 'asc'? 'asc': 'desc';
        switch($order)
        {
            case 'name':
                $order = 'first_name';
                break;
            case 'email':
            case 'created_at':
                break;
            case 'status':
                $order = 'is_active';
                break;
            
            default:
                $order = 'created_at';
                $orderby = 'desc';
                break;
        }
        
        $rows = Admin::where('id', '!=', $this->current_user->id)
            ->orderBy($order, $orderby)
            ->paginate($this->list_limit);
        
        return $this->view('member.index', compact('rows'));   
    }
    
    public function create()
    {
        return $this->view('member.create');
    }
    
    public function edit($id)
    {
        $row = Admin::find($id);
        
        return $this->view('member.edit', compact('row'));
    }
    
    public function delete($id)
    {
        $row = Admin::find($id);
        
        Template::set_message("Member[ " . $row->full_name() . " ] has been removed.", 'warning');
        
        Admin::destroy($id);
        
        return redirect($this->listUrl());
    }
    
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////
    public function store()
    {                                    
        //validate posts
        $rules = [];
        
        if(!Input::has('user_id'))
        {
            $rules = [
                'email'     => 'required|email|unique:admins', 
                'password'  => 'required|min:4',
                'first_name' => 'required|max:32',
                'last_name'  => 'required|max:32',
            ];
        }
        else
        {
            $user_id = Input::get('user_id');
            
            $rules = [
                'email'     => "required|email|unique:admins,email,$user_id,id",
                'first_name' => 'required|max:32',
                'last_name'  => 'required|max:32',
            ];    
        }
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        if(!Input::has('user_id'))
        {            
            // Insert New User to Table
            $salt = str_random(8);

            $user = new Admin;
            $user->first_name   = Input::get('first_name');
            $user->last_name    = Input::get('last_name');
            $user->email        = Input::get('email');
            $user->passsalt     = $salt;
            $user->password     = md5($salt . Input::get('password'));
            $user->is_active    = Input::get('is_active') == '1' ? '1' : '0';
            $status = $user->save();
            
            // if creating is failed
            if(!$status)
            {
                Template::set_message(trans('msg.has_error'), 'danger');
                
                return redirect()
                    ->back()
                    ->withInput();
            }
            
            $newName = $user->full_name();
            Template::set_message("New member[$newName] is created.", 'success');
        }
        else
        {
            // Update row
            $user = Admin::find($user_id);
            $user->first_name   = Input::get('first_name');
            $user->last_name    = Input::get('last_name');
            $user->email        = Input::get('email');
            $user->is_active    = Input::get('is_active') == '1' ? '1' : '0';
            if(Input::get('password'))
            {
                $salt = str_random(8);
                $user->passsalt     = $salt;
                $user->password     = md5($salt . Input::get('password'));
            }
            $status = $user->save();
            
            // Set update message
            if($status)
            {
                Template::set_message("$user->first_name $user->last_name's information is updated.", 'success');
            }
            else
            {
                Template::set_message(trans('msg.has_error'), 'danger');
                
                return redirect()
                    ->back()
                    ->withInput();
            }
            
        }
        
        //return redirect($this->listUrl());
        return Redirect::route('admin.member.list');
    }  
     
    ////////////////////////////////////////////////////////////////
    //Ajax Methods
    ////////////////////////////////////////////////////////////////
    public function ajaxCheckEmail()
    {
        $email = Input::get('email');
        $user_id = Input::get('user_id');
        
        $user = Admin::where('email', $email)->first();
        if($user == null) return 'true';
        if($user->id == $user_id) return 'true';
        
        return 'false';        
    }
}