<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Libraries\DomainProcess;
use App\Models\State;
use App\Models\Domain;
use App\Models\Transaction;
use Input, Request, Validator, Redirect, URL, Session, Stripe;
use stdClass;

class PaymentController extends FrontAuthenticatedController {
    protected $menu = '';
    protected $pageTitle = '';        
    
    public function __construct()
    {
        parent::__construct();
        
        $this->data['states'] = State::lists('name', 'code');
    }
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function purchase()
    {
        $domain = Input::get('domain');
        $domain = str_replace(array('http://', 'www.', '/'), '', $domain);
        
        $domainName = $domain;
        $domainAvailable = 'No';
        $action = 'purchase';
        
        $objDomain = new DomainProcess;
        if($objDomain->valid_domain($domain)) 
        {    
            $response = $objDomain->check_domain($domain);
            
            if(!isset($response->response) || $response->response->domain->domainAvailable != 'Yes')
            {         
                //$temp = (array)$response->status->statusDescription;   
                Template::set_message($response->status->statusDescription->__toString(), 'danger');

                return Redirect::route('site.list');
            }    
            else
            {
               $domainName = $response->response->domain->domainName;
               $domainAvailable = $response->response->domain->domainAvailable;  
            }
        }
        
        $this->pageTitle = 'Register a New Domain';
        return $this->view('payment.purchase', compact('domainName', 'domainAvailable', 'action'));   
    }
    
    public function extend()
    {
        $productId = Input::get('prdid');
        $domainRow = Domain::where('product_id', $productId)->first();
        
        if(!isset($domainRow->id)) 
        {
            Template::set_message('Domain doesn\'t exist in database.', 'danger');
            
            return Redirect::route('domain.list');
        }
        
        /*$objDomain = new DomainProcess;
        $response = $objDomain->domain_get($domainRow->name);
        if($response->response->domainGet->domain->domainInfo->domainName == '')
        {
            Template::set_message('Domain doesn\'t exist in register.com.', 'danger');
            
            return Redirect::route('domain.list');
        }*/
        
        $domainName = $domainRow->name; 
        $domainAvailable = 'Yes';
                                                            
        $this->pageTitle = 'Extend Domain Registration';
        return $this->view('payment.extend', compact('domainName', 'domainAvailable', 'productId'));        
    }
    
    public function privacy()   
    {
        $productId = Input::get('prdid');
        $domainRow = Domain::where('product_id', $productId)->first();
        
        if(!isset($domainRow->id)) 
        {
            Template::set_message('Domain doesn\'t exist in database.', 'danger');
            
            return Redirect::route('domain.list');
        }
        
        /*$objDomain = new DomainProcess;
        $response = $objDomain->domain_get($domainRow->name);
        if($response->response->domainGet->domain->domainInfo->domainName == '')
        {
            Template::set_message('Domain doesn\'t exist in register.com.', 'danger');
            
            return Redirect::route('domain.list');
        }*/
        
        $domainName = $domainRow->name; 
        $domainAvailable = 'Yes';
        $formUrl = URL::route('payment.privacy.post');
                                                            
        $this->pageTitle = 'Purchase Privacy Information';
        return $this->view('payment.privacy', compact('domainName', 'domainAvailable', 'productId', 'formUrl'));   
    } 
    
    public function privacyExtend()   
    {
        $productId = Input::get('prdid');
        $domainRow = Domain::where('product_id', $productId)->first();
        
        if(!isset($domainRow->id)) 
        {
            Template::set_message('Domain doesn\'t exist in database.', 'danger');
            
            return Redirect::route('domain.list');
        }
        
        /*$objDomain = new DomainProcess;
        $response = $objDomain->domain_get($domainRow->name);
        if($response->response->domainGet->domain->domainInfo->domainName == '')
        {
            Template::set_message('Domain doesn\'t exist in register.com.', 'danger');
            
            return Redirect::route('domain.list');
        }*/
        
        $domainName = $domainRow->name; 
        $domainAvailable = 'Yes';
        $formUrl = URL::route('payment.privacy.extend.post');
                                                            
        $this->pageTitle = 'Renew Privacy Information';
        return $this->view('payment.privacy', compact('domainName', 'domainAvailable', 'productId', 'formUrl'));   
    } 
    
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////
    public function postPurchase()
    {
        //validate posts
        $rules = [
            'domain'        => 'required|valid_domain', 
            'priceplan'     => 'required|in:1,2,5,10', 
            'payment-type'  => 'required|in:' . PAYMENT_CREDITCARD . ',' . PAYMENT_PAYPAL,
            'first-name'    => 'required|max:50',
            'last-name'     => 'required|max:50',
            'state'         => 'required|exists:states,code',
            'country'       => 'required',
            'postal-code'   => 'required',
            'phone-no'      => 'required',
        ];
        
        if(Input::get('paymenttype') == PAYMENT_CREDITCARD)
        {
            $rules['cc-number'] = 'required';   
            $rules['cc-cvc']    = 'required';
            $rules['cc-expmon'] = 'required';
            $rules['cc-expyear']= 'required';
        }
        
        $messages = [
            'domain.valid_domain'   => 'Invalid Domain',
            'payment-type.in'       => 'Invalid Payment Type',
            'priceplan.in'          => 'Invalid Price Plan',
        ];
         
        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) 
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } 
            
        //get variables from Input
        $domain     = Input::get('domain');
        $paymentType= Input::get('payment-type');
        $firstName  = Input::get('first-name');
        $lastName   = Input::get('last-name');
        $state      = Input::get('state');
        $country    = Input::get('country');
        $postal     = Input::get('postal-code');
        $phone      = Input::get('phone-no');
        $privacyRegister = Input::get('private_register');
        
        $planYear   = Input::get('priceplan');
        $yearPrice  = 0.00;
        $privacyPrice = 0.00;
        $domainPrivacy= false;
                
        //start domain purchase process
        $objDomain = new DomainProcess();
        
        if ($domain != '' && $objDomain->valid_domain($domain) )
        {    
            $response = $objDomain->check_domain($domain);
             
            if( isset($response->response) && $response->response->domain->domainAvailable == 'Yes')
            {
                if($planYear == 1) $yearPrice = $this->settings['price_one_year'];
                else if($planYear == 2) $yearPrice = $this->settings['price_two_year'];
                else if($planYear == 5) $yearPrice = $this->settings['price_five_year'];
                else if($planYear == 10) $yearPrice = $this->settings['price_ten_year'];
                else $yearPrice = $this->settings['price_two_year'];
                
                if($privacyRegister)
                {
                    $privacyPrice = $this->settings['price_privacy'];
                    $domainPrivacy = true;
                } 
                else 
                {
                    $domainPrivacy = false;
                }                    
                
                //$amount = ( ($planYear * $yearPrice) + ( $planYear * $privacyPrice) ) * 100;
                $amount = ( ($planYear * $yearPrice) + ( $planYear * $privacyPrice) );
                $description = sprintf(trans('msg.domain_purchase_description'), $domain, $amount, $planYear);
                
                //check right price
                if(!is_numeric($amount) || $amount == 0)
                {
                    Template::set_message("Price is incorrect.", 'danger');
                    
                    return redirect()
                        ->back()
                        ->withInput();
                }
         
                //creditcard payment
                if($paymentType == PAYMENT_CREDITCARD)
                {
                    //get creditcard info
                    $cardNumber = Input::get('cc-number');
                    $cvcCode    = Input::get('cc-cvc');
                    $expMonth   = Input::get('cc-expmon');
                    $expYear    = Input::get('cc-expyear');    
                                        
                    //process payment
                    $payResult = $this->_stripePayment($amount, $cardNumber, $expMonth, $expYear, $cvcCode, $description);               
                    
                    //if payment is success
                    if($payResult->status == 1)
                    { 
                        if($this->_buyDomain($domain, $planYear, $domainPrivacy, $amount, PAYMENT_CREDITCARD,  
                            $payResult->transaction_id, $firstName, $lastName, $country, $state, $postal, '', $phone))
                        {
                            $msg = sprintf(trans('msg.domain_purchased'), $domain);
                            Template::set_message($msg, 'success');
                            
                            return Redirect::route('domain.list');
                        }
                        else
                        {
                            Template::set_message(trans('msg.something_wrong'), 'danger');
                        }
                    }
                    else                           
                    {
                        Template::set_message("Payment is failed.", 'danger');
                    }
                }
                else if($paymentType == PAYMENT_PAYPAL)
                {
                    $goBackUrl = URL::route('payment.callback.paypal.purchase');
                    $cancelUrl = URL::route('domain.list');
                    
                    Session::forget('paypal_purchase_payload'); 
                    
                    $payload = [
                        'first_name'    => $firstName,
                        'last_name'     => $lastName,
                        'state'         => $state,
                        'country'       => $country,
                        'postal'        => $postal,
                        'phone'         => $phone,
                        'plan_year'     => $planYear,
                        'domain_name'   => $domain,
                        'private'       => $domainPrivacy,
                        'amount'        => $amount
                    ];
                    Session::set('paypal_purchase_payload', $payload);
                    Session::save();

                    $this->_paypalPayment($amount, $goBackUrl, $cancelUrl, $description);
                    exit;
                }
            }
            else
            {
                Template::set_message($response->status->statusDescription->__toString(), 'danger');
            }
        }
        else
        {
            Template::set_message('Invalid domain name', 'danger');
        }
        
        return redirect()
                ->back()
                ->withInput();
    }
    
    public function postPrivacy()
    {
        //validate posts
        $rules = [
            'productId'     => 'required', 
            'payment-type'  => 'required|in:' . PAYMENT_CREDITCARD . ',' . PAYMENT_PAYPAL,
            'first-name'    => 'required|max:50',
            'last-name'     => 'required|max:50',
            'state'         => 'required|exists:states,code',
            'country'       => 'required',
            'postal-code'   => 'required',
            'phone-no'      => 'required',
        ];
        
        if(Input::get('paymenttype') == PAYMENT_CREDITCARD)
        {
            $rules['cc-number'] = 'required';   
            $rules['cc-cvc']    = 'required';
            $rules['cc-expmon'] = 'required';
            $rules['cc-expyear']= 'required';
        }
        
        $messages = [
            'payment-type.in'   => 'Invalid Payment Type',
        ];
         
        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) 
        {           
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } 
            
        //get variables from Input
        $productId  = Input::get('productId');
        $paymentType= Input::get('payment-type');
        $firstName  = Input::get('first-name');
        $lastName   = Input::get('last-name');
        $state      = Input::get('state');
        $country    = Input::get('country');
        $postal     = Input::get('postal-code');
        $phone      = Input::get('phone-no');        
        
        //check domain is exist in database
        $domainRow = Domain::where('product_id', $productId)->first();
        if(!isset($domainRow->id)) 
        {
            return Redirect::route('domain.list');
        }
        
        $amount      = $this->settings['price_privacy'];        
        $description = sprintf(trans('msg.domain_privacy_description'), $domainRow->name, $amount);
        
        //check right price
        if(!is_numeric($amount) || $amount == 0)
        {
            Template::set_message("Price is incorrect.", 'danger');
            
            return redirect()
                ->back()
                ->withInput();
        }  
        
        //creditcard payment
        if($paymentType == PAYMENT_CREDITCARD)
        {
            //get creditcard info
            $cardNumber = Input::get('cc-number');
            $cvcCode    = Input::get('cc-cvc');
            $expMonth   = Input::get('cc-expmon');
            $expYear    = Input::get('cc-expyear');
            
            //process payment
            $payResult = $this->_stripePayment($amount, $cardNumber, $expMonth, $expYear, $cvcCode, $description);               
            
            //if payment is success
            if($payResult->status == 1)
            { 
                if($this->_addDomainPrivacy($domainRow->id, $productId, PAYMENT_CREDITCARD, $amount, $payResult->transaction_id, $description))
                {
                    $msg = sprintf(trans('msg.domain_privacy_added'), $domainRow->name);
                    Template::set_message($msg, 'success');
                    
                    return Redirect::route('domain.list');
                }
                else
                {
                    Template::set_message(trans('msg.something_wrong'), 'danger');
                    
                    return redirect()
                        ->back()
                        ->withInput();
                }
            }
            else
            {
                Template::set_message("Payment is failed.", 'danger');
                
                return redirect()
                    ->back()
                    ->withInput();
            }
        }
        else if($paymentType == PAYMENT_PAYPAL)
        {
            $goBackUrl = URL::route('payment.callback.paypal.privacy');
            $cancelUrl = URL::route('domain.list');
            
            Session::forget('paypal_privacy_payload'); 
            
            $payload = [
                'domain_id'     => $domainRow->id,
                'domain_name'   => $domainRow->name,
                'product_id'    => $productId,
                'amount'        => $amount
            ];
            Session::set('paypal_privacy_payload', $payload);
            Session::save();

            $this->_paypalPayment($amount, $goBackUrl, $cancelUrl, $description);
            exit;
        }
        
        return Redirect::route('domain.list');
    }
    
    public function postPrivacyExtend()
    {
        //validate posts
        $rules = [
            'productId'     => 'required', 
            'payment-type'  => 'required|in:' . PAYMENT_CREDITCARD . ',' . PAYMENT_PAYPAL,
            'first-name'    => 'required|max:50',
            'last-name'     => 'required|max:50',
            'state'         => 'required|exists:states,code',
            'country'       => 'required',
            'postal-code'   => 'required',
            'phone-no'      => 'required',
        ];
        
        if(Input::get('paymenttype') == PAYMENT_CREDITCARD)
        {
            $rules['cc-number'] = 'required';   
            $rules['cc-cvc']    = 'required';
            $rules['cc-expmon'] = 'required';
            $rules['cc-expyear']= 'required';
        }
        
        $messages = [
            'payment-type.in'   => 'Invalid Payment Type',
        ];
         
        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) 
        {           
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } 
            
        //get variables from Input
        $productId  = Input::get('productId');
        $paymentType= Input::get('payment-type');
        $firstName  = Input::get('first-name');
        $lastName   = Input::get('last-name');
        $state      = Input::get('state');
        $country    = Input::get('country');
        $postal     = Input::get('postal-code');
        $phone      = Input::get('phone-no');        
        
        //check domain is exist in database
        $domainRow = Domain::where('product_id', $productId)->first();
        if(!isset($domainRow->id)) 
        {
            return Redirect::route('domain.list');
        }
        
        $amount      = $this->settings['price_privacy'];        
        $description = sprintf(trans('msg.domain_privacy_description'), $domainRow->name, $amount);
        
        //check right price
        if(!is_numeric($amount) || $amount == 0)
        {
            Template::set_message("Price is incorrect.", 'danger');
            
            return redirect()
                ->back()
                ->withInput();
        }  
        
        //creditcard payment
        if($paymentType == PAYMENT_CREDITCARD)
        {
            //get creditcard info
            $cardNumber = Input::get('cc-number');
            $cvcCode    = Input::get('cc-cvc');
            $expMonth   = Input::get('cc-expmon');
            $expYear    = Input::get('cc-expyear');
            
            //process payment
            $payResult = $this->_stripePayment($amount, $cardNumber, $expMonth, $expYear, $cvcCode, $description);               
            
            //if payment is success
            if($payResult->status == 1)
            { 
                if($this->_renewDomainPrivacy($domainRow->id, $productId, $domainRow->privacy_product_id, $domainRow->privacy_expire_time, PAYMENT_CREDITCARD, $amount, $payResult->transaction_id, $description))
                {
                    $msg = sprintf(trans('msg.domain_privacy_updated'), $domainRow->name);
                    Template::set_message($msg, 'success');
                    
                    return Redirect::route('domain.list');
                }
                else
                {
                    Template::set_message(trans('msg.something_wrong'), 'danger');
                    
                    return redirect()
                        ->back()
                        ->withInput();
                }
            }
            else
            {
                Template::set_message("Payment is failed.", 'danger');
                
                return redirect()
                    ->back()
                    ->withInput();
            }
        }
        else if($paymentType == PAYMENT_PAYPAL)
        {
            $goBackUrl = URL::route('payment.callback.paypal.privacy.extend');
            $cancelUrl = URL::route('domain.list');
            
            Session::forget('paypal_privacy_renew_payload'); 
            
            $payload = [
                'domain_id'     => $domainRow->id,
                'domain_name'   => $domainRow->name,
                'product_id'    => $productId,
                'privacy_product_id' => $domainRow->privacy_product_id,
                'privacy_expire_time'=> $domainRow->privacy_expire_time,
                'amount'        => $amount
            ];
            Session::set('paypal_privacy_renew_payload', $payload);
            Session::save();

            $this->_paypalPayment($amount, $goBackUrl, $cancelUrl, $description);
            exit;
        }
        
        return Redirect::route('domain.list');
    }
    
    public function postExtend()
    {
        //validate posts
        $rules = [
            'productId'     => 'required', 
            'priceplan'     => 'required|in:1,2,5,10', 
            'payment-type'  => 'required|in:' . PAYMENT_CREDITCARD . ',' . PAYMENT_PAYPAL,
            'first-name'    => 'required|max:50',
            'last-name'     => 'required|max:50',
            'state'         => 'required|exists:states,code',
            'country'       => 'required',
            'postal-code'   => 'required',
            'phone-no'      => 'required',
        ];
        
        if(Input::get('paymenttype') == PAYMENT_CREDITCARD)
        {
            $rules['cc-number'] = 'required';   
            $rules['cc-cvc']    = 'required';
            $rules['cc-expmon'] = 'required';
            $rules['cc-expyear']= 'required';
        }
        
        $messages = [
            'payment-type.in'   => 'Invalid Payment Type',
        ];
         
        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) 
        {           
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } 
            
        //get variables from Input
        $productId  = Input::get('productId');
        $paymentType= Input::get('payment-type');
        $firstName  = Input::get('first-name');
        $lastName   = Input::get('last-name');
        $state      = Input::get('state');
        $country    = Input::get('country');
        $postal     = Input::get('postal-code');
        $phone      = Input::get('phone-no');        
        
        $planYear   = Input::get('priceplan');
        $yearPrice  = 0.00;
        
        //check domain is exist in database
        $domainRow = Domain::where('product_id', $productId)->first();
        if(!isset($domainRow->id)) 
        {
            return Redirect::route('domain.list');
        }
        
        if($planYear == 1) $yearPrice = $this->settings['price_one_year'];
        else if($planYear == 2) $yearPrice = $this->settings['price_two_year'];
        else if($planYear == 5) $yearPrice = $this->settings['price_five_year'];
        else if($planYear == 10) $yearPrice = $this->settings['price_ten_year'];
        else $yearPrice = $this->settings['price_two_year'];
        
        //start domain purchase process
        $amount      = $planYear * $yearPrice;        
        $description = sprintf(trans('msg.domain_extend_description'), $domainRow->name, $amount, $planYear);
        
        //check right price
        if(!is_numeric($amount) || $amount == 0)
        {
            Template::set_message("Price is incorrect.", 'danger');
            
            return redirect()
                ->back()
                ->withInput();
        }  
        
        //creditcard payment
        if($paymentType == PAYMENT_CREDITCARD)
        {
            //get creditcard info
            $cardNumber = Input::get('cc-number');
            $cvcCode    = Input::get('cc-cvc');
            $expMonth   = Input::get('cc-expmon');
            $expYear    = Input::get('cc-expyear');
            
            //process payment
            $payResult = $this->_stripePayment($amount, $cardNumber, $expMonth, $expYear, $cvcCode, $description);               
            
            //if payment is success
            if($payResult->status == 1)
            { 
                /*$this->_extendDomain($domainRow->id, $productId, $domainRow->expire_date, 
                    $planYear, PAYMENT_CREDITCARD, $amount, $payResult->transaction_id, $description, 
                    $firstName, $lastName, $country, $state, $postal, '', $phone);*/
                    
                if($this->_extendDomain($domainRow->id, $productId, $domainRow->expire_date, 
                    $planYear, PAYMENT_CREDITCARD, $amount, $payResult->transaction_id, $description))
                {
                    $msg = sprintf(trans('msg.domain_extended'), $domainRow->name, $planYear);
                    Template::set_message($msg, 'success');
                    
                    return Redirect::route('domain.list');
                }
                else
                {
                    Template::set_message(trans('msg.something_wrong'), 'danger');
                    
                    return redirect()
                        ->back()
                        ->withInput();
                }
            }
            else
            {
                Template::set_message("Payment is failed.", 'danger');
                
                return redirect()
                    ->back()
                    ->withInput();
            }
        }
        else if($paymentType == PAYMENT_PAYPAL)
        {
            $goBackUrl = URL::route('payment.callback.paypal.extend');
            $cancelUrl = URL::route('domain.list');
            
            Session::forget('paypal_extend_payload'); 
            
            $payload = [
                'domain_id'     => $domainRow->id,
                'domain_name'   => $domainRow->name,
                'product_id'    => $productId,
                'expire_date'   => $domainRow->expire_date,
                'extend_year'   => $planYear,
                'amount'        => $amount
            ];
            Session::set('paypal_extend_payload', $payload);
            Session::save();

            $this->_paypalPayment($amount, $goBackUrl, $cancelUrl, $description);
            exit;
        }
        
        return Redirect::route('domain.list');
    }
    ////////////////////////////////////////////////////////////////
    //Callback Methods
    ////////////////////////////////////////////////////////////////
    public function paypalCallbackPurchase()
    {      
        if(!Session::has('paypal_purchase_payload'))
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
            
            return Redirect::route('payment.new');
        }
        
        $payLoad = Session::get('paypal_purchase_payload');

        //variables
        $email      = $this->current_customer->email;
        $domain     = $payLoad['domain_name'];
        $planYear   = $payLoad['plan_year'];
        $firstName  = $payLoad['first_name'];
        $lastName   = $payLoad['last_name'];
        $country    = $payLoad['country'];
        $state      = $payLoad['state'];
        $postal     = $payLoad['postal'];
        $phone      = $payLoad['phone'];
        $amount     = $payLoad['amount'];
        $domainPrivacy = $payLoad['private'];
        
        $transaction_id = Request::get('txn_id');
        $amount = Request::get('payment_gross');
        
        if($this->_buyDomain($domain, $planYear, $domainPrivacy, $amount, PAYMENT_PAYPAL, 
            $transaction_id, $firstName, $lastName, $country, $state, $postal, '', $phone))
        {
            Session::forget('paypal_purchase_payload');
            Session::save();
            
            $msg = sprintf(trans('msg.domain_purchased'), $domain);
            Template::set_message($msg, 'success');
        }
        else
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
        }        

        return Redirect::route('domain.list');
    }
    
    public function paypalCallbackPrivacy()
    {      
        if(!Session::has('paypal_privacy_payload'))
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
            
            return Redirect::route('payment.privacy');
        }
        
        $payLoad = Session::get('paypal_privacy_payload');

        //make a payment for domain 
        $domain_id  = $payLoad['domain_id'];
        $domain     = $payLoad['domain_name'];
        $productId  = $payLoad['product_id'];
        $amount     = $payLoad['amount'];
        
        $transaction_id = Request::get('txn_id');
        $amount         = Request::get('payment_gross');
        //$domainRow = Domain::where('product_id', $productId)->first();
        $description = sprintf(trans('msg.domain_privacy_description'), $domain, $amount);
        
        if($this->_addDomainPrivacy($domain_id, $productId, PAYMENT_PAYPAL, $amount, $transaction_id, $description))
        {
            Session::forget('paypal_privacy_payload');
            Session::save();
            
            $msg = sprintf(trans('msg.domain_privacy_added'), $domain);
            Template::set_message($msg, 'success');
        }
        else
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
        }
        
        return Redirect::route('domain.list');
    }
    
    public function paypalCallbackPrivacyExtend()
    {      
        if(!Session::has('paypal_privacy_renew_payload'))
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
            
            return Redirect::route('payment.privacy.extend');
        }
        
        $payLoad = Session::get('paypal_privacy_renew_payload');

        //make a payment for domain 
        $domain_id  = $payLoad['domain_id'];
        $domain     = $payLoad['domain_name'];
        $productId  = $payLoad['product_id'];
        $amount     = $payLoad['amount'];
        $privacyProductId = $payLoad['privacy_product_id'];
        $privacyExpireTime= $payLoad['privacy_expire_time'];
        
        $transaction_id = Request::get('txn_id');
        $amount         = Request::get('payment_gross');
        //$domainRow = Domain::where('product_id', $productId)->first();
        $description = sprintf(trans('msg.domain_privacy_description'), $domain, $amount);
        
        if($this->_renewDomainPrivacy($domain_id, $productId, $privacyProductId, $privacyExpireTime, PAYMENT_PAYPAL, $amount, $transaction_id, $description))
        {
            Session::forget('paypal_privacy_renew_payload');
            Session::save();
            
            $msg = sprintf(trans('msg.domain_privacy_updated'), $domain);
            Template::set_message($msg, 'success');
        }
        else
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
        }
        
        return Redirect::route('domain.list');
    }
    
    public function paypalCallbackExtend()
    {      
        if(!Session::has('paypal_extend_payload'))
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
            
            return Redirect::route('payment.privacy.extend');
        }
        
        $payLoad = Session::get('paypal_extend_payload');

        //make a payment for domain 
        $domain_id  = $payLoad['domain_id'];
        $domain     = $payLoad['domain_name'];
        $productId  = $payLoad['product_id'];
        $expireDate = $payLoad['expire_date'];
        $extendYear = $payLoad['extend_year'];
        $amount     = $payLoad['amount'];
        
        $transaction_id = Request::get('txn_id');
        $amount         = Request::get('payment_gross');
        //$domainRow = Domain::where('product_id', $productId)->first();
        $description = sprintf(trans('msg.domain_extend_description'), $domain, $amount, $extendYear);
        
        if($this->_extendDomain($domain_id, $productId, $expireDate, $extendYear, PAYMENT_PAYPAL, $amount, $transaction_id, $description))
        {
            Session::forget('paypal_extend_payload');
            Session::save();
            
            $msg = sprintf(trans('msg.domain_extended'), $domain, $extendYear);
            Template::set_message($msg, 'success');
        }
        else
        {
            Template::set_message(trans('msg.something_wrong'), 'danger');
        }
        
        return Redirect::route('domain.list');
    }
    
    ////////////////////////////////////////////////////////////////
    //Private Methods
    ////////////////////////////////////////////////////////////////
    
    /**
    * process stripe payment
    * 
    * @param mixed $amount
    * @param mixed $cardno
    * @param mixed $expmonth
    * @param mixed $expyear
    * @param mixed $cvccode
    * @param mixed $description
    * @return stdClass
    */
    private function _stripePayment($amount, $cardno, $expmonth, $expyear, $cvccode, $description)
    {
        $result = new stdClass();
        $result->status = false;
        $result->msg    = '';
        
        try {
            $stripeKey = $this->settings['stripe_secret_key'];
            
            $response = Stripe::make($stripeKey)->charges()->create([
                'currency'  => 'USD',
                'amount'    => $amount,
                'card'      => array(
                    'number'    => $cardno,
                    'exp_month' => $expmonth,
                    'exp_year'  => $expyear,
                    'cvc'       => $cvccode,
                ),
                'description'   => $description,
            ]);
            
            $result->status = true;
            $result->transaction_id = $response['id'];
            return $result;
            
        } catch (\Exception $e) {
            // Get the error message returned by Stripe
            $result->msg    = $e->getMessage();
            return $result;
        } 
    }    
    
    /**
    * process paypal payment
    * 
    * @param mixed $amount
    * @param mixed $goBackUrl
    * @param mixed $cancelUrl
    * @param mixed $description
    */
    private function _paypalPayment($amount, $goBackUrl, $cancelUrl, $description)
    {
        $server = 'www.paypal.com';
        if($this->settings['paypal_mode'] == 0) //test mode
        {
            $server = 'www.sandbox.paypal.com';
        }
        
        echo '<form name="paypal" action="https://' . $server . '/cgi-bin/webscr" method="post" id="paypal_form">';
        echo '<input type=hidden name=amount value="' . $amount . '" />';
        echo '<input type=hidden name=business value="'. $this->settings['paypal_account'] . '" />
                 <input type=hidden name=cmd value="_xclick" />
                 <input type=hidden name=currency_code value="USD" />
                 <input type=hidden name=item_name value="' . $description . '" />
                 <input type=hidden name=quantity value="1" />                                 
                    
                 <input type=hidden name=payer_id value=1 />
                 <input type=hidden name=payer_email value="" />
                 <input type=hidden name=custom value=10/>
                 <input type=hidden name=return value="' . $goBackUrl . '"/>
                 <input type=hidden name=cancel_return value="' . $cancelUrl . '"/>
                 <input type=hidden name=rm value="2" />
                 <input type="hidden" name="bn" value="Go to MyAccount Page" />
                 <input type="hidden" name="cbt" value="Return to The Site" />';
        echo '</form>';
        echo "<h4>Your payment is loading please wait...</h4>";
        echo '<script>document.paypal.submit();</script>';
        exit;
    }
    
    /**
    * process domain registration
    * 
    * @param mixed $domain
    * @param mixed $planYear
    * @param mixed $domainPrivacy
    * @param mixed $amount
    * @param mixed $payment
    * @param mixed $firstName
    * @param mixed $lastName
    * @param mixed $country
    * @param mixed $state
    * @param mixed $postal
    * @param mixed $address
    * @param mixed $phone
    * @return void
    */
    private function _buyDomain($domain, $planYear, $domainPrivacy, $amount, $payment, $transactionId, $firstName, $lastName, $country, $state, $postal, $address, $phone)
    {
        $objDomain = new DomainProcess;
        
        //make a payment for domain
        $buyResult  = $objDomain->buy_domain($domain, $planYear, $this->current_customer->email, $firstName, $lastName, $country, $state, $postal, $address, $phone); 
         
        //if buying is success
        if ($buyResult && $buyResult->status->statusCode == 1000) 
        {
            //add new domain to database      
            $objDomain->setCloudFlareInfo($this->settings['cldfr_email'], $this->settings['cldfr_key']);
            
            $productId = $buyResult->response->productId->__toString();
            $domainResult = $objDomain->addNewDomain($domain, $productId, $planYear, $transactionId, $amount, $payment);  #domain add                   
             
            #private registeration
            if($domainResult == true && $domainPrivacy == true)
            {                       
                #domain privacy add
                $addPrivacyResult = $objDomain->domainPrivacyAdd($productId);

                if ($addPrivacyResult && $addPrivacyResult->status->statusCode == 1000) 
                {
                    $exp_date = strtotime( date("d-m-Y", strtotime('+' . $planYear . 'years')) ) + 86399;  
                    $updateData = [
                        'privacy_product_id'    => $addPrivacyResult->response->productId,
                        'privacy_expire_time'   => $exp_date,
                        'privacy'               => 1,    
                    ];
                    
                    $updateResult = Domain::where('product_id', $productId)->update($updateData);
                }                            
            }
            
            return $domainResult;
        }
        else
        {
            Template::set_message($buyResult->status->statusDescription->__toString(), 'danger');
        }
        
        return false;     
    }
    
    //private function _extendDomain($domainId, $productId, $expireDate, $extendYear, $paymentType, $amount, $transactionId, $description='', $firstName, $lastName, $country, $state, $postal, $address, $phone)
    private function _extendDomain($domainId, $productId, $expireDate, $extendYear, $paymentType, $amount, $transactionId, $description='')
    {
        $objDomain = new DomainProcess();
        
        //$extendResult = $objDomain->renew_domain($extendYear, $productId);  
        $extendResult = new stdClass;
        $extendResult->status = new stdClass;
        $extendResult->response = new stdClass;
        $extendResult->status->statusCode = 1000;
        $extendResult->response->productId = $productId;   
        if ($extendResult && $extendResult->status->statusCode == 1000) 
        {
            $exp_date   =  ($extendYear * 31536000) + $expireDate;
            $updateResult = Domain::where('product_id', $productId)->update(['expire_date' => $exp_date]);
            if($updateResult) 
            {
                //insert transaction to database
                $newTransaction = new Transaction;
                $newTransaction->customer_id    = $this->current_customer->id;
                $newTransaction->domain_id      = $domainId;
                $newTransaction->description    = $description;
                $newTransaction->terms          = "$extendYear Year";
                $newTransaction->type           = 'Debit';
                $newTransaction->method         = $paymentType;
                $newTransaction->amount         = $amount;
                $newTransaction->transaction_id = $transactionId;
                $result = $newTransaction->save();                 
                
                return $result;
            }      
        }
        
        return false;
    }
    
    /**
    * process add domain privacy
    * 
    * @param mixed $domainId
    * @param mixed $productId
    * @param mixed $paymentType
    * @param mixed $amount
    * @param mixed $transactionId
    * @param mixed $description
    */
    private function _addDomainPrivacy($domainId, $productId, $paymentType, $amount, $transactionId, $description='')
    {
        $objDomain = new DomainProcess();
        
        #domain privacy add
        $addPrivacyResult = $objDomain->domainPrivacyAdd($productId);
        /*$addPrivacyResult = new stdClass;
        $addPrivacyResult->status = new stdClass;
        $addPrivacyResult->response = new stdClass;
        $addPrivacyResult->status->statusCode = 1000;
        $addPrivacyResult->response->productId = 3243434;*/
        if ($addPrivacyResult && $addPrivacyResult->status->statusCode == 1000) 
        {
            $exp_date = strtotime( date("d-m-Y", strtotime('+1 years')) ) + 86399;            
            $updateData = [
                'privacy_product_id'    => $addPrivacyResult->response->productId,
                'privacy_expire_time'   => $exp_date,
                'privacy'               => 1,
            ];
            
            $updateResult = Domain::where('product_id', $productId)->update($updateData);
            
            if($updateResult)
            {               
                //insert transaction to database
                $newTransaction = new Transaction;
                $newTransaction->customer_id    = $this->current_customer->id;
                $newTransaction->domain_id      = $domainId;
                $newTransaction->description    = $description;
                $newTransaction->terms          = "1 Year";
                $newTransaction->type           = 'Debit';
                $newTransaction->method         = $paymentType;
                $newTransaction->amount         = $amount;
                $newTransaction->transaction_id = $transactionId;
                $result = $newTransaction->save();                 
                
                return $result;
            }                            
        }
                          
        return false;
    }
    
    /**
    * process privacy extending
    * 
    * @param mixed $domainId
    * @param mixed $productId
    * @param mixed $privacyProductId
    * @param mixed $privacyExpireTime
    * @param mixed $paymentType
    * @param mixed $amount
    * @param mixed $transactionId
    * @param mixed $description
    */
    private function _renewDomainPrivacy($domainId, $productId, $privacyProductId, $privacyExpireTime, $paymentType, $amount, $transactionId, $description='')
    {
        $objDomain = new DomainProcess();
        
        #domain privacy add
        $renewPrivacyResult = $objDomain->domainPrivacyRenew($privacyProductId);
        /*$renewPrivacyResult = new stdClass;
        $renewPrivacyResult->status = new stdClass;
        $renewPrivacyResult->response = new stdClass;
        $renewPrivacyResult->status->statusCode = 1000;
        $renewPrivacyResult->response->productId = 3243434;*/
        if ($renewPrivacyResult && $renewPrivacyResult->status->statusCode == 1000) 
        {
            $privacyExpireTime = (int)$privacyExpireTime;
            $privacyExpireTime = $privacyExpireTime == 0 ? time() : $privacyExpireTime;
            
            $exp_date = strtotime('+1 years', $privacyExpireTime); 
            $updateData = [
                'privacy_expire_time'   => $exp_date,
                'privacy'               => 1,
            ];
            
            $updateResult = Domain::where('product_id', $productId)->update($updateData);
            
            if($updateResult)
            {               
                //insert transaction to database
                $newTransaction = new Transaction;
                $newTransaction->customer_id    = $this->current_customer->id;
                $newTransaction->domain_id      = $domainId;
                $newTransaction->description    = $description;
                $newTransaction->terms          = "1 Year";
                $newTransaction->type           = 'Debit';
                $newTransaction->method         = $paymentType;
                $newTransaction->amount         = $amount;
                $newTransaction->transaction_id = $transactionId;
                $result = $newTransaction->save();                 
                
                return $result;
            }                            
        }
                          
        return false;
    }
}