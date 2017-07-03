@extends('frontend.layouts.default')

@section('content')

<div id="domainprocessdiv">
    <h4 class="myhomeheading">Purchase a Domain Name</h4> 
    <div class="clr"></div>
    <div id="errormsg"></div>
        
    <div class="pointTabCont clearfix" id="new_domain_show">
        <div class="subdomainRight bgNongRight">
            <input type="text" autocomplete="off" class="textAreaBoxInputs domainnamefilter" id="suggested_names" name="suggested_names" placeholder="Ex:xxxx">     
            <select class="selectbxdoamin" id="doamin_ext">
                <option value="com">.com</option>
                <option value="org">.org</option>
                <option value="net">.net</option>
                <option value="biz">.biz</option>
                <option value="info">.info</option>
                <option value="mobi">.mobi</option>
                <option value="tv">.tv</option>                    
                <option value="work">.work</option>
            </select>    
            <i></i>                        
            <a class="vpb_button" id="btn-check-domain">Continue</a></div>
        <div class="row-fluid">
            <!--<div id="vpb_search_status" class="span7 offset0"></div>-->
            <div id="vpb_search_status" class="span7"></div>
        </div>
    </div>
    <small class="domaininfo">After purchasing a domain, create a new site or assign it to an existing site.</small>
    
    <div class="clearfix">
        <h4 class="myhomeheading">My domains</h4> 
            @foreach($rows as $r)
                <div class="HeadingUser">{{ $r->name }}<button class="success-btn pull-right btn-domain-manage" data-index="{{ $r->id }}">Manage</button></div>
                <div class="myAccTabCont">
                    <table class="userhometablecont" width="100%">
                        <thead>
                            <tr>    
                                <th height="30"><b>Expiration Date</b></th>
                                <th height="30"><b>Privacy Protection</b></th>
                                <th height="30"><b>Email</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>    
                                <td height="30">
                                    {{ date('d, M Y', $r->expire_date) }}
                                    <a href="{{ URL::route('payment.extend', 'prdid='.$r->product_id) }}"><span class="success-btn">Extend</span></a></td>
                                <td height="30">
                                    @if($r->privacy == 0 || $r->privacy_expire_time < time())
                                        Off <a href="{{ URL::route('payment.privacy', 'prdid='.$r->product_id) }}"><span class="success-btn">Add privacy</span></a>
                                    @elseif($r->privacy_product_id != '')
                                        On <a href="{{ URL::route('payment.privacy.extend', 'prdid='.$r->product_id) }}"><span class="success-btn">Renew privacy</span></a>
                                    @endif
                                </td>
                                <td height="30">
                                    @if($r->email_product_id == '')
                                        Not Setup<span class="success-btn curpointer margin_left_5 btn-email-setup" data-index="{{ $r->id }}">Edit</span>
                                    @else
                                        <span class="success-btn curpointer btn-email-login" data-toggle="modal" data-target="#emailloginurl" href="{{ URL::route('domain.login', $r->id) }}">Login</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
            
            @if($rows->isEmpty())
                <span class="text-danger text-center span12 offet0">There is No Domain Purchased</span>
            @endif
        </div>
</div>

<div id="domaininfodiv">
    <button class="success-btn pull-right" id="btn-show-domains"> My Domains</button>
    @foreach($rows as $r)
    <div class="clearfix indivdomaindetails" style="display:none;" id="domaininfodiv_{{ $r->id }}">
        <h4 class="myhomeheading">{{ $r->name }}</h4> 
        <div class="row-fluid">
            <div class="span8">
                <ul class="nav nav-tabs topheadul">
                    <li><a class="active" data-toggle="tab" href="#overview">Overview</a></li>
                </ul>
                <div class="tab-content">
                    <div id="overview" class="tab-pane fade in active">
                        <div class="row-fluid">
                            <div class="overviewrow">
                                <div class="span5 cols">Destination</div>
                                <div class="span4 cols" id="destnat_txt_17">No destination</div>
                                <div class="span3 cols" id="editbutton_17"><button class="success-btn" data-toggle="modal" data-target="#commonpopupdiv" href="{{ URL::route('domain.assign', $r->id) }}">Edit</button></div>
                            </div>
                            <div class="overviewrow">
                                <div class="span5 cols">Expiration Date</div>
                                <div class="span4 cols">{{ date('d, M Y', $r->expire_date) }}</div>
                                <div class="span3 cols"><a href="{{ URL::route('payment.extend', 'prdid='.$r->product_id) }}">
                                <span class="success-btn">Extend</span></a></div>
                            </div>
                            <div class="overviewrow">
                                <div class="span5 cols">Privacy Protection</div>
                                
                                @if($r->privacy == ON)
                                <div class="span4 cols">Protected</div>
                                <div class="span3 cols"><a href="{{ URL::route('payment.privacy.extend', 'prdid='.$r->product_id) }}"><span class="success-btn">Renew Privacy</span></a></div>
                                @else
                                <div class="span4 cols">Not Protected</div>
                                <div class="span3 cols"><a href="{{ URL::route('payment.privacy', 'prdid='.$r->product_id) }}"><span class="success-btn">Enable</span></a></div>
                                @endif
                            </div>
                            <div class="overviewrow">
                                <div class="span5 cols">Email</div>
                                <div class="span4 cols">Register.com</div>
                                <div class="span3 cols"><span class="success-btn curpointer btn-email-login" data-toggle="modal" data-target="#emailloginurl" href="{{ URL::route('domain.login', $r->id) }}">Login</span></div>
                            </div>
                            <div class="overviewrow">
                                <div class="span5 cols">Registrar Lock</div>
                                <div class="span4 cols" id="registlock_{{ $r->product_id }}">@if($r->register_lock == ON) Locked @else Not Locked @endif</div>
                                <div class="span3 cols"><button class="success-btn" data-toggle="modal" data-target="#commonpopupdiv" href="{{ URL::route('domain.lock', $r->id) }}">Edit</button></div>
                            </div>
                        </div>
                    </div>
                    <div id="dns" class="tab-pane"></div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@stop

@section('additional')

@include('frontend/domain/additional')

@stop

@section('scripts')
<script>
ajaxCheckURL                = '{{ URL::route("domain.ajax.check") }}';
ajaxEmailSetupURL           = '{{ URL::route("domain.ajax.email.setup") }}';
ajaxSiteAssignURL           = '{{ URL::route("domain.ajax.site.assign") }}';
ajaxChangeRegisterStatusURL = '{{ URL::route("domain.ajax.change.register.status") }}';
</script>
<script src="{{ asset('scripts/pages/domain.js') }}"></script>
@stop