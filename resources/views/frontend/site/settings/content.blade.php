@extends('frontend.layouts.inner')

@section('content')

    @include('frontend.site.settings.left')

    <div class="mainRightPanel pull-right span9"><div class="mainRightSideToggle">
        <!--start general setting-->
        <div id="general-setting-box" class="setting-box">
            <div id="errormsg"></div>
            
            <div class="row marTop10">
                <div class="subdomainLeft">Site Address</div>
                <div class="subdomainRight clearfix">
                    <div class="subdomainformLeft">
                        <div class="leftinput">{{ $site->getPrefix() }}</div>
                        {!! Form::text('subdomain_url', $site->getRealUrlString(), array('id' => 'subdomain_url', 'class' => 'PageMainRightTxtBx rightinput', 'readonly')) !!}
                    </div>
                    <div class="subdomainformRight">
                        <button class="btn btn-primary" id="btn-change-address" name="change-address">Change</button>
                    </div>
                </div>
            </div>
            
            <div class="row marTop10">
                <div class="subdomainLeft">Site Title</div>
                <div class="subdomainRight clearfix">
                    <div class="subdomainformLeft">
                        {!! Form::text('site_title', $site->title, array('id' => 'site_title', 'class' => 'PageMainRightTxtBx', 'placeholder' => 'Enter your site title here')) !!}
                    </div>
                    <div class="subdomainformRight">
                        <button class="btn btn-primary" id="btn-save-title" name="save-title">Save</button>
                    </div>
                </div>
            </div>
            
            <div class="row marTop10">
                <div class="span8 subdomainLeft  offset0">
                    Domain Status
                </div>
                <div class="span4">  
                    <button class="btn btn-primary pull-right" id="btn-save-status" name="save-status" value="{{ $site->status }}">
                        @if($site->status == STATUS_ACTIVE) Deactive
                        @else Activate
                        @endif  
                    </button>  
                </div>
            </div>
                     
            <div class="row">
                <div class="subdomainRight clearfix"> 
                    @if($site->status == STATUS_ACTIVE)
                        <span id="sitestatus" class="text-success">Domain is Active</span>    
                    @else
                        <span id="sitestatus" class="text-danger">Domain is Deactive</span>
                    @endif    
                </div>
            </div>   
            
            <div class="row marTop10">
                <div class="subdomainLeft">Fav Icon</div>
                <div class="subdomainRight clearfix">
                    <form style="clear:both" class="form-horizontal sky-form" id="favIconfrm" method="post">
                        <label class="input input-file" style="display:block; height:135px;" for="fav_icon">
                            <div class="button">
                                  <input type='file' name="favicon_image" id="fav_icon" class="inputfile"/>
                                  @if($site->favicon != '')
                                  <img height="70" id="favicon"  width="70" src="{{ asset($site->favicon) }}" />
                                  @else
                                  <img height="70" id="favicon"  width="70" src="{{ asset('images/no-image.jpg') }}" />
                                  @endif
                             </div>
                            <input type='hidden' name="status" value="favimageupload"/>
                        </label>    
                    </form>
                </div>
            </div>
            
        </div>
        <!--end general setting-->
        
        <!--start seo setting-->
        <div id="seo-setting-box" style="display: none;" class="setting-box">
            <div class="row marTop10">
                <div class="subdomainLeft">Meta Description</div>
                <div class="subdomainLeft">
                    <div class="subdomainformLeft">
                        {!! Form::text('meta_description', $site->meta_description, array('id' => 'meta_description', 'class' => 'PageMainRightTxtBx', 'placeholder' => 'Enter your description here')) !!}
                    </div>
                </div>
            </div>
            
            <div class="row marTop10">
                <div class="subdomainLeft">Meta Keywords</div>
                <div class="subdomainRight clearfix">
                    <div class="subdomainformLeft">
                        {!! Form::text('meta_keywords', $site->meta_keywords, array('id' => 'meta_keywords', 'class' => 'PageMainRightTxtBx', 'placeholder' => 'Enter your meta keywords here')) !!}
                        <div class="seoformComment">Separate each keyword with a comma.</div>
                    </div>
                </div>
            </div>
            
            <div class="row marTop10">
                <div class="subdomainLeft">Google Analytics</div>
                <div class="subdomainRight clearfix">
                    <div class="subdomainformLeft">
                        {!! Form::textarea('google_analytics', $site->google_analytics, array('id' => 'google_analytics', 'class' => 'PageMainRightTxtArea', 'placeholder' => 'Enter your analytic code', 'rows' => 3)) !!}
                    </div>
                </div>
            </div> 
            
            <div class="row marTop10">
                <button class="btn btn-primary" id="btn-save-seo" name="save-save-seo">Save</button>
            </div>
               
        </div>
        <!--end seo setting-->
    </div></div>

@stop

@section('additional')
@include('frontend.site.settings.additional')                                              
@stop

@section('styles')
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('vendor/build-plugins.js') }}"></script>
<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/settings.js') }}"></script>
@stop