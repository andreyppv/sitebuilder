<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;
use App\Libraries\DomainProcess;
use App\Models\Domain;
use App\Models\Site;
use App\Models\TempSite;

use Input, URL, Session;

class DomainController extends FrontAuthenticatedController {
    protected $menu = 'domain';
    protected $pageTitle = 'Domains';        
    
    public function __construct()
    {
        parent::__construct();
    }
    
    ////////////////////////////////////////////////////////////////
    //Action Methods
    ////////////////////////////////////////////////////////////////
    public function index()
    {
        $rows = Domain::where('customer_id', $this->current_customer->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return $this->view('domain.index', compact('rows'));   
    } 
    
    public function login($domainId)
    {
        $singleSignOnUrl = '';
        $terms      = 1; 
        $domainRow  = Domain::find($domainId);
        if($domainRow)
        {            
            //send mail add request
            $objDomain = new DomainProcess;    
            $response = $objDomain->mailLogin($domainRow->email_product_id);
            if($response 
                &&  $response->status->statusCode == 1000 
                && isset($response->response->productId)
                && $response->response->productId == $domainRow->email_product_id )
            {
                $singleSignOnUrl = $response->response->singleSignOnUrl; 
            }
        }
        
        return $this->view('domain.login', compact('singleSignOnUrl'));   
    }
    
    public function lock($domainId)
    {
        $domainRow  = Domain::find($domainId);
        return $this->view('domain.lock', compact('domainRow'));   
    }
    
    public function assign($domainId)
    {
        $domainRow  = Domain::find($domainId);
        
        $sites = array('GreenHome(tryrapidcms.com)', 'Testing(Testrapidcms.com)');
        
        return $this->view('domain.assign', compact('domainRow', 'sites'));   
    }
    ////////////////////////////////////////////////////////////////
    //Post Methods
    ////////////////////////////////////////////////////////////////
    
    ////////////////////////////////////////////////////////////////
    //Ajax Methods
    ////////////////////////////////////////////////////////////////
    
    /**
    * check new domain is available to register
    * 
    */
    public function ajaxCheck()
    {
        $domain = Input::get('domain');
        
        $objDomain = new DomainProcess;
        
        if(Input::has('action') && Input::get('action') == 'newdomain') {
            
            //this case will be from site/customization
            
            if($domain != '' && $objDomain->valid_domain($domain)) {  
                $response = $objDomain->check_domain($domain);
                
                if ($response && $response->status->statusCode == 1000) {
                    
                    if ($response->response->domain->domainAvailable == 'Yes') {
                        echo '<a class="saveButtonInput pull-right" id="setting_domain_submit1" href="' . URL::route('payment.new', 'domain='.$response->response->domain->domainName) . '">Continue</a>';
                    } else {
                        echo 'taken';    
                    }   
                } else {
                    echo 'try';
                }
            } else {
                echo 'try';
            } 
        } else {
            
            //this case will be from purchase domain
            
            if($domain != '' && $objDomain->valid_domain($domain)) {  
                $response = $objDomain->check_domain($domain);
                
                if ($response && $response->status->statusCode == 1000) {
                    
                    echo '<div class="buy_domain clearfix">';
                    echo '<p class="domaincls span6 bold"><i class="fa fa-hand-o-right pull-right"></i>' . $response->response->domain->domainName . '</p>';
                    if ($response->response->domain->domainAvailable == 'Yes') {
                        echo '<span class="bold avafont text-success mar-l-25">Available <i class="fa fa-check-circle"></i></span>';
                        echo '<a class="submitButton mar-l-25" href="' . URL::route('payment.new', 'domain='.$response->response->domain->domainName) . '">Buy</a>';
                    } else {
                        echo '<span class="bold avafont text-danger mar-l-25">Not Avaiable <i class="fa fa-times-circle"></i></span>'; 
                    }
                    echo '</div>';
                } else {
                    echo '<p class="info pull-left">' . $response->status->statusDescription . '</p>';
                }
            } else {
                echo '<p class="info pull-left"> Enter Valid domain Name</p>';
            }
        }
    
        exit;
    }
    
    /**
    * setup email for selected domain
    * 
    */
    public function ajaxEmailSetup()
    {
        $result = ['status' => false, 'msg' => ''];
        
        $terms      = 1;
        $domainId   = Input::get('domain_id');
        $domainRow  = Domain::find($domainId);
        if(!$domainRow)
        {
            $result['msg'] = trans('msg.something_wrong');
            echo json_encode($result);
            exit;       
        }
        
        //send mail add request
        $objDomain = new DomainProcess;    
        $response = $objDomain->mailAdd($domainRow->name, $terms);
        if($response &&  $response->status->statusCode == 1000 && isset($response->response->productId) )
        {
            //if success
            $responseMail = $objDomain->mailAutoRenew($response->response->productId);
            
            //save response info to database
            $domainRow->email_product_id    = $response->response->productId;
            $domainRow->email_password      = $response->response->password;
            $domainRow->email_server_name   = $response->response->serverName;
            $domainRow->save();                
            
            $result['status'] = true;
        }
        else
        {
            $result['msg'] = $response->status->statusDescription->__toString();
        }
        
        echo json_encode($result);
        exit;
    }   
    
    /**
    * email login
    * 
    */
    /*public function ajaxEmailLogin()
    {
        $singleSignOnUrl = '';
        $terms      = 1; 
        $domainId   = Input::get('domain_id'); 
        $domainRow  = Domain::find($domainId);
        if($domainRow)
        {            
            //send mail add request
            $objDomain = new DomainProcess;    
            $response = $objDomain->mailLogin($domainRow->email_product_id);
            if($response 
                &&  $response->status->statusCode == 1000 
                && isset($response->response->productId)
                && $response->response->productId == $domainRow->email_product_id )
            {
                $singleSignOnUrl = $response->response->singleSignOnUrl; 
            }
        }
        
        return $this->view('domain.login', compact('singleSignOnUrl'));   
    }*/
    
    /**
    * update resgier status
    * 
    */
    public function ajaxChangeRegisterStatus()
    {
        $result = ['status' => false, 'value' => '', 'msg' => ''];
        
        $productId      = Input::get('product_id');
        $registerStatus = Input::get('register_status');
        $registerStatusParameter = $registerStatus == ON ? 'True' : 'False';
        
        $objDomain = new DomainProcess();
        
        $response = $objDomain->registerLockProcess($productId, $registerStatusParameter);
        if($productId == $response->response->productId)
        {
            $upvalue = $registerStatus == ON ? ON : OFF;
            
            Domain::where('product_id', $productId)->update(['register_lock' => $upvalue]);
            
            $result['status'] = true;
            $result['value']  = ($registerStatus == ON) ? 'Locked' : 'Not Locked'; 
        } 
        else
        {
            $result['msg'] = $response->status->statusDescription->__toString();    
        }
        
        echo json_encode($result);
        exit;
    }
    
    /**
    * update site assign status
    * 
    */
    public function ajaxSiteAssign()
    {
        $result = ['status' => false, 'value' => '', 'msg' => ''];
        
        $domainId   = Input::get('domain_id');
        $assignId   = Input::get('assign_id');
        $domainRow  = Domain::find($domainId);
        
        //ToDo Here
        $result['status'] = true;
        
        echo json_encode($result);
        exit;
    }
    
    ////////////////////////////////////////////////////////////////
    //Private Methods
    ////////////////////////////////////////////////////////////////
    
}