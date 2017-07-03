<div>
    <!--Domain Modal-->
    <div class="modal fade" id="modal-domain" tabindex="-1" role="dialog" aria-hidden="true">
         
         <!--start one-->
         <div class="domainChangeOne fullheightCont" id="domainChangeOne">
            <div class="domainOuterHead">
                <div>Choose Your Website Domain</div>
                <div class="youtubepopclose" data-dismiss="modal"></div>
            </div>    
            
            <div class="chooseDomainPop">
                <div id="err_msg"></div>
                <div id="suc_msg"></div>
                <div class="clearfix"> <span class="domain-info">This is the address where people will find your website online. Reserve your domain now.</span></div>
                
                <div class="subdomainRight clearfix">
                    <div class="chooseDomainInner">
                        <div id="sitedescrip">
                            <div class="chooseDomainInnerBg">
                                <form class="chooseDomainInnerForm" id="chooseDomainInnerForm" method="post">
                                    
                                    <!--subdoamin-->
                                    <div class="chooseDomainSubdomain domainTypeRow">
                                        <label class="row-fluid">
                                            <div class="span1 offset0">
                                                <input type="radio" name="domain" id="edit_subdomain_radio" value="subdomain"/>
                                            </div>
                                            <div for="edit_sub_domain" class="span9 border-leftcol">
                                                <div class="chooseDomainSubdomainTop">Use a Subdomain of {{ my_domain() }}</div>

                                                http://www.
                                                <input type="text" class="popsettTxtBx domainnamefilter" name="subdomainurl" id="subdomainurl"/>{{ my_domain() }}
                                            </div>
                                            
                                            <div style="display:none;" class="span2 available-mark img-mark">
                                                <img src="{{ asset('images/tick-avaliable.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 taken-mark img-mark">
                                                <img src="{{ asset('images/taken.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 loading-mark img-mark">
                                                <img src="{{ asset('images/loader_payment.gif') }}"/>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end subdoamin-->
                                    
                                    <!--register domain-->
                                    <div class="chooseDomainSubdomain domainTypeRow clearfix">
                                        <label class="row-fluid">
                                            <div class="span1 offset0">
                                                <input type="radio" name="domain" id="new_domain_radio" value="newdomain"/>
                                            </div>
                                            <div class="span9 border-leftcol">
                                                <div class="chooseDomainSubdomainTop">Register your new domain</div>
                                                <span class="contain">http://www. <input type="text" class="domainnamefilter popsettTxtBx" name="new_domain_name" id="new_domain_name" placeholder="example"/>
                                                    <select class="domainselect" id="domain_extension">
                                                        <option>.com</option>                                            
                                                        <option>.org</option>
                                                        <option>.net</option>
                                                        <option>.biz</option>
                                                    </select>
                                                </span> 
                                            </div>
                                            
                                            <div style="display:none;" class="span2 available-mark img-mark">
                                                <img src="{{ asset('images/tick-avaliable.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 taken-mark img-mark">
                                                <img src="{{ asset('images/taken.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 loading-mark img-mark">
                                                <img src="{{ asset('images/loader_payment.gif') }}"/>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end register domain-->
                                    
                                    <!--assign domain-->
                                    <div class="chooseDomainAlreaydomain domainTypeRow clearfix">
                                        <label class="row-fluid">
                                            <span class="span1">
                                                <input type="radio" name="domain" id="register_domain_radio" value="regdomain"/>
                                            </span>
                                            <div class="span9 border-leftcol">
                                                <div class="chooseDomainSubdomainTop">Assign a Domain You Already Own From rapid-CMS</div>
                                                <span class="contain">http://www.
                                                    {!! Form::select('newdomainlist', $domains, null, array('id' => 'newdomainlist')) !!}
                                                </span>
                                            </div>
                                            
                                            <div style="display:none;" class="span2 available-mark img-mark">
                                                <img src="{{ asset('images/tick-avaliable.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 taken-mark img-mark">
                                                <img src="{{ asset('images/taken.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 loading-mark img-mark">
                                                <img src="{{ asset('images/loader_payment.gif') }}"/>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end assign domain-->
                                    
                                    <!--connect domain-->
                                    <div class="chooseDomainAlreaydomain domainTypeRow clearfix">
                                        <label class="row-fluid">
                                            <span class="span1">
                                                <input type="radio" name="domain" id="point_domain_radio" value="pointdomain"/>
                                            </span>
                                            <div class="span9 border-leftcol">
                                                <div class="chooseDomainSubdomainTop">Connect a Domain You Already Own</div>
                                                <span class="contain">http://www. <input type="text" id="point_domain_name_url" class="domainnamefilter" name="point_domain_name_url"  value="" placeholder="example.com" /></span>
                                            </div>
                                            
                                            <div style="display:none;" class="span2 available-mark img-mark">
                                                <img src="{{ asset('images/tick-avaliable.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 taken-mark img-mark">
                                                <img src="{{ asset('images/taken.png') }}"/>
                                            </div>
                                            <div style="display:none;" class="span2 loading-mark img-mark">
                                                <img src="{{ asset('images/loader_payment.gif') }}"/>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="clearfix continue-btn-box" id="subdomain-btn-box" style="display:none;">
                                        <input type="button" class="saveButtonInput pull-right" name="domain-continue" id="btn-domain-continue" value="Continue" />
                                    </div>
                                    <div class="clearfix continue-btn-box" id="newdomain-btn-box" style="display:none;"></div>
                                    <!--end connect domain-->                                    
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <!--end one-->
         
         <!--start two-->
         <div class="domainChangeTwo"  id="domainChangeTwo" style="display:none;">
            <div class="domainOuterHead">
                <img src="{{ asset('images/Www.png') }}" alt="domain" title="domain" />
                <div>Choose Your Website Domain</div>
            </div>
            <div class="clearfix">
                <div class="chooseDomainPop">
                    <div class="chooseDomainInner chooseDomainInnerSec">
                        <div class="para">To set up your domain with old.rapidcms.com, your domain's DNS settings need to be updated.</div>
                        <div class="option"><b>Option A:</b> Email instructions to your domain registrar</div>
                        <div class="inst">Send these instructions to your domain registrar (ex. GoDaddy, 1and1, Yahoo, etc.)</div>
                        <div class="chooseDomainPopTxtarea">
                            <p>Hello, I have purchased my domain <span id="youdomainurl_set"></span>.  I have built my website on old.rapidcms.com.  I need you to point my domain to the following IP: 127.0.0.123</p>                                
                            <p>This is done by changing my domain's A-Records. I am not requesting that you transfer my domain, redirect my domain, or change my name servers.  I want to remain with you as my domain registrar.</p>
                        </div>
                        <div class="option"><b>Option B:</b> Make the DNS changes yourself see instructions </div>
                        <div class="para">Once the DNS changes are made, it may take up to 48 hours (although usually less) for the changes to propagate through the Internet</div>
                        <div class="dc">
                            <input type="button" class="btn btn-primary subdomainInput" value="Continue" id="btn-confirm-continue"/>
                        </div>
                    </div>            
                </div>
            </div>
         </div>
         <!--end two-->
         
    </div>
    <!--End Domain-->   
</div>    