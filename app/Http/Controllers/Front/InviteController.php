<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Models\Referrals;

use Input, Validator, Redirect, Session, Request, Mail;

class InviteController extends FrontAuthenticatedController {
    protected $menu = 'invite';
    protected $pageTitle = 'Invites';        
    
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
        $order      = Request::input('order');
        $orderby    = Request::input('orderby') == 'asc'? 'asc': 'desc';
        switch($order)
        {
            case 'email':
            case 'created_at':
                break;
            
            default:
                $order = 'created_at';
                $orderby = 'desc';
                break;
        }
        
        $rows = Referrals::where('customer_id', $this->current_customer->id)
            ->orderBy($order, $orderby)
            ->paginate(10);
            
        return $this->view('invite.index', compact('rows'));   
    } 
    
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////
    public function postSend()
    {
        //validate posts
        $rules = [
            'from'  => "required",
            'to'    => 'required',
        ];     
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $emails = explode(',', Input::get('to'));
        foreach($emails as $email)
        {
            if($email == '') continue;
            
            //check email is already exist in referrals
            $count = Referrals::where('customer_id', $this->current_customer->id)
                ->where('email', $email)
                ->count();
            
            if($count > 0) continue;
            
            //insert invite email to database
            $insertResult = Referrals::insert([
                'domain_id'     => 0,
                'customer_id'   => $this->current_customer->id,
                'email'         => $email,
                'subject'       => "Regarding a Reference in " . $this->settings['site.title'],
                'content'       => 'Your friend refer you to create a new website or blog in the'. $this->settings['site.title'],                
            ]);
            
            // Send invite email to friends
            $this->to_email = $email;
            //$this->to_name  = $name;
            Mail::send('emails.inviteDetails', array('customer' => $this->current_customer, 'settings' => $this->settings), function($message) 
            {
                $message->from($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                    ->to($this->to_email)
                    ->subject("Regarding a Reference in " . $this->settings['site.title']);
            });
            
            // Send invite email to admin
            Mail::send('emails.inviteDetails', array('customer' => $this->current_customer, 'settings' => $this->settings), function($message) 
            {
                $message->from($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                    ->to($this->settings['email.sender_email'], $this->settings['email.sender_name'])
                    ->subject("Reference");
            });
            
            Template::set_message('Mail Sent Successfully', 'success');
        }
        
        return Redirect::route('invite.list');
    }
}