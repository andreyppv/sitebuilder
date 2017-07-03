<div>
    <!--bulidinst_popup-->
    <div id="bulidinst_popup" style="display:none;" class="popupproduct publishPopBgOuter"> 
        <h2>Predefined values</h2>
        <div class="instpopclose">X</div>
        <ul class="instListing">
            <li><b>[PRODUCTNAME] </b>- replace a Product name.</li>
            <li><b>[PRODUCTPRICE]</b> - replace a product price.</li>
            <li><b>[TRIALDAYS]</b> - replace a TRIAL days of product.</li>
            <li><b>[fullCorporateAddress]</b> - replace a full corporate address.</li>
            <li><b>[fullReturnsAddress]</b> - replace a full return address.</li>
            <li><b>[corporationName]</b> - Replace a corporation name</li>
            <li><b>[returnsPhone]</b> - replace a return phone number</li>
            <li><b>[returnsEmail]</b> - replace a return email id</li>
            <li><b>[csHours]</b> - replace hours of Operation</li>
            <li><b>[corporatePhone]</b> - replace a corporate phone number</li>
            <li><b>[corporateEmail]</b> - replace a corporate email id</li>
            <li><b>[shippingPrice]</b> - replace a shipping price</li>
            <li><b>[returnsState]</b> - replace return state</li>
            <li><b>[returnsCity]</b> - replace a return city</li>
        </ul>
    </div>
    <!--end bulidinst_popup-->
    
    <!--toolbar-->
    <div id="toolbar" style="display:none;">
        <div id="buttomtoolbar" style="display:none;">
            <div class="btmtoolbar">
                <select id="short-codes" class="margin0">
                    <option value="[PRODUCTNAME]">[PRODUCTNAME] - Product name</option>
                    <option value="[PRODUCTPRICE]">PRODUCTPRICE]- Product Price</option>
                    <option value="[TRIALDAYS]">[TRIALDAYS]- Trial days of Product</option>
                    <option value="[fullCorporateAddress]">[fullCorporateAddress]- Full Corporate Address</option>
                    <option value="[fullReturnsAddress]">[fullReturnsAddress]- Full Return Address</option>
                    <option value="[corporationName]">[corporationName]- Corporate Name</option>
                    <option value="[csHours]">[csHours]-Hours of Operation</option>
                    <option value="[corporatePhone]">[corporatePhone]-Corporate Phone Number</option>
                    <option value="[corporateEmail]">[corporateEmail]-Corporate Email id</option>
                    <option value="[shippingPrice]">[shippingPrice]-Shipping Price</option>
                    <option value="[returnsState]">[returnsState]-Return City</option>
                    <option value="[returnsCity]">[returnsCity]-Return City</option>
                    <option value="[[returnsPhone]">[returnsPhone]-Return Phone Number</option>
                    <option value="[returnsEmail]">[returnsEmail]- Return Email Id</option>
                </select>
            </div>
        </div>     
    </div> 
    <!--end toolbar-->  
    
    <!--urlpopup-->
    <div class="modal fade" id="modal-link" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="marbtm15">
            <div data-dismiss="modal" class="close PopDeleteButt"></div>
        </div>
        <div>
            <div class="row-fluid">
                <h1>Link to :</h1>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>{!! Form::radio('link', 'url', true, array('class' => 'link-radio')) !!}</td>
                        <td>
                            <label class="bold">
                                Website URL <span class="pull-right">
                                <label id="link-url-label" class="checkbox inline small">{!! Form::checkbox('target', '_blank', false, array('id' => 'open-target')) !!} Open link in a new window</label></span>
                            </label><br />
                            {!! Form::text('target', null, array('id' => 'link-url', 'class' => 'url span10', 'placeholder' => 'url')) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>{!! Form::radio('link', 'email', false, array('class' => 'link-radio')) !!}</td>
                        <td>
                            <label id="emaillabel" class="bold">E-Mail Address</label>
                            {!! Form::text('target', null, array('id' => 'link-mail', 'class' => 'url span10', 'placeholder' => 'Email', 'style' => 'display:none;')) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="block center">
                                <a class="urlselect btn btn-success btn-large" id="btn-submit-urlform">Submit</a>
                                <a class="urlcancel btn btn-danger btn-large" data-dismiss="modal">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div> 
        </div>
    </div>
    <!--end urlpopup-->
    
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
    
    <!-----Delete Form Popup--->
    <div id="deletePopup" style="display:none;">
        <form name="delpopup" id="delpopup">
            <input type="hidden" name="element-id" id="element-id" value="">
            <input type="hidden" name="element-type" id="element-type" value="">
            <div class="deletePopupTxt">Are You sure</div>
            <input type="button" id="btnDeletePopupButton" class="deletePopupButton" name="submit" value="Delete">
        </form>
        <div class="topArrow"></div>
    </div>
    <!--Delete Form Popup---->
    
    <!--Single Image Modal-->
    <div class="modal fade" id="modal-single-image" tabindex="-1" role="dialog" aria-hidden="true">
 
    </div>
    <!--End Single Image Modal-->
    
    <!--Map Image Modal-->
    <div class="modal fade mappop" id="modal-map-setting" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="leftside"> <img src="{{ asset('images/map_marker.png') }}" alt="map image"/>
            <label>MAP</label>
       </div>
       <div class="rightside">
            <form id="map-setting-form" class="no-mar" method="post">
                <input type="hidden" name="map_element_id" id="map_element_id"/>
                
                <label>Address</label>
                <input class="mapinputTxt" type="text" id="map_address" name="map_address">
                
                <label>Zoom</label>
                <input class="mapinputTxt" type="number" min="1" max="17" id="map_zoom" name="map_zoom">
                
                <label>Latitude</label>
                <input class="mapinputTxt" type="text" id="map_latitude" name="map_latitude">
                
                <label>Longtitude</label>
                <input class="mapinputTxt" type="text" id="map_longitude" name="map_longitude">
                
                <div class="mapButton clearfix">
                    <input type="button" value="save" class="btn btn-success pull-left" id="btn-save-map-setting"/>
                    <input type="button" value="cancel" class="btn btn-danger pull-right mapcancel" data-dismiss="modal"/>
                </div>
            </form>
       </div>
    </div>
    <!--End Map Image Modal-->
    
    <!--File Popup-->
    <div class="modal fade all_popup" id="modal-download-file" tabindex="-1" role="dialog" aria-hidden="true">
        <div id="image-chooser-nav">
            <div id="image-chooser-nav-label">
                <div></div>
            </div>
            <div id="image-chooser-tab-computer" class="image-chooser-tab image-chooser-tab-selected">
                 <div class="colWhite"> <span></span> My Computer </div>
            </div>
            <div class="youtubepopclose" data-dismiss="modal" style="top:5px; right:5px;"></div>
       </div>
       <div id="browsebutton" class="popBrowseInner">
            <div id="imageloadstatuslogo" style="display:none;">
                <div class="laodImgChange"><img alt="Uploading...." src="{{ asset('images/gifload.gif') }}"></div>
            </div>
                                        
            <form id="fileform" class="form-horizontal sky-form no-mar" method="post" enctype="multipart/form-data" style="clear:both">
                 <label for="file" class="input input-file no-mar" style="display:block" name="imageloadbutton" id="imageloadbutton">
                    <div class="uploadTxtPop">Click here to upload File</div>
                 
                    <div class="button">
                        <input type="file" name="files" id="file-element"/>
                    </div>
                 </label>
            </form>
       </div>
    </div>
    <!--End File Popup-->
    
    <!--Youtube Modal-->
    <div class="modal fade" id="modal-youtube-setting" tabindex="-1" role="dialog" aria-hidden="true" style="width:320px;">
        <div class="youtubepopclose" data-dismiss="modal"></div>
            <form name="youtubefrm" id="youtube-setting-form" method="post">
                <input type="hidden" id="youtube_element_id" name="youtube_element_id"/>
            
                <div id="error_youtube"></div>
                <div class="contactlabelsPopupLeft">
                     <label>Youtube Video Url</label>
                     <input type="text" class="videoUrl" name="youtube_url" id="youtube_url" value=""/>
                </div>
                <div class="contactlabelsPopupRight">
                     <div class="contactlabelsPopupRightInner">
                          <label>Spacing</label>
                          <select class="spacingOption" name="youtube_space" id="youtube_spacing">
                               <option value="None">None</option>
                               <option value="Small">Small</option>
                               <option value="Medium">Medium</option>
                               <option value="Large">Large</option>
                          </select>
                          <label>Width</label>
                          <select class="widthOption" name="youtube_width" id="youtube_width">
                               <option value="Small">Small</option>
                               <option value="Medium">Medium</option>
                               <option value="Large">Large</option>
                          </select>
                     </div>
                     <div>
                          <input type="button" value="save" class="videosubmit" id="btn-save-youtube-setting"/>
                     </div>
                </div>
            </form>    
    </div>
    <!--End Youtube Modal-->
    
    <!--Social Modal-->
    <div class="modal fade galleryimagepop socialPop" id="modal-social-setting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="leftside"> 
            <i class="fa fa-users fontSize42"></i>
            <label>Social Link</label>
        </div>
        <div class="rightside">
            <form class="no-mar" id="social-setting-form" name="socialplugin" method="post">
                 <div class="socialInn clearfix"> <span><i class="fa fa-facebook"></i></span>
                      <input type="text" name="fb" id="social_fb" placeholder="http://facebook.com/example">
                 </div>
                 <div class="socialInn clearfix"><span><i class="fa fa fa-tumblr"></i></span>
                      <input type="text" name="tw" id="social_tw" placeholder="http://twitter.com/example">
                 </div>
                 <div class="socialInn clearfix"> <span><i class="fa fa-linkedin"></i></span>
                      <input type="text" name="ln" id="social_ln" placeholder="http://linkedin.com/in/example">
                 </div>
                 <div class="socialInn clearfix"> <span><i class="fa fa fa-envelope-o"></i></span>
                      <input type="text" name="mail" id="social_mail" placeholder="example@example.com">
                 </div>
                 <label>Alignment</label>
                 <select id="social_align" class="socialignOption">
                      <option value="left">Left</option>
                      <option value="center">Center</option>
                      <option value="right">Right</option>
                 </select>
                 <div class="mapButton clearfix">
                      <input type="button" class="btn btn-success pull-left" id="btn-save-social-setting" name="submit" value="Save">
                      <input type="button" class="btn btn-danger pull-right addGoogCancel" name="cancel" value="Cancel">
                 </div>
            </form>
        </div>
    </div>
    <!--End Social Modal-->
</div>