@extends('frontend.layouts.default')

@section('content')

<div class="row-fluid">
    <div class="span4"><img src="{{ asset('images/invite-friends.jpg') }}" /></div>
    <div class="span8">
        <div class="InviteFriendTopHead">Share Your Link To Your Friends</div>
    </div>
</div>

<div class="staticPageInner InviteFriend marTop20">
    <div class="row-fluid">
        <div class="span6">
            <div class="InviteFriendHead">Share this link with friends</div>
            <div class="row-fluid">
                <input type="text" class="InviteFriendTxtbx span5" name="link" id="link" value="{{ url('/') }}"/>
                <div class="clr"></div>
                <div class="marTop20">
                    <a href="https://www.facebook.com/sharer.php?s=100&p[title]=inviteslink&p[url]={{ url('/') }}&p[images][0]={{ asset('images/mobile_support.jpg') }}" class="socialLink colorblack">
                        <img src="{{ asset('images/fb.png') }}" /> <span>Share on facebook</span>
                    </a>        
                    <a href="https://twitter.com/home?status={{ url('/') }}" class="socialLink colorblack" target="_blank"/>
                        <img src="{{ asset('images/tw.png') }}" /> <span>Share on twitter</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="span6">
            <div class="InviteFriendHead">Invite friends by entering their emails</div>
            <div id="errormsg"></div>
            <form name="Invite" id="Invite" method="post" class="no-mar" action="{{ URL::route('invite.send.post') }}">
                <input type="hidden" name="fromemail" id="fromemail" value="{{ $current_customer->email }}">    
                <div class="row-fluid">
                    <div class="span3 txtAlignRht">From :</div>
                    <div class="span9">
                        <input type="text" name="from" id="from" value="{{ $current_customer->name }}">
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3 txtAlignRht">To :</div>
                    <div class="span9">
                        <textarea name="to" id="to"></textarea>
                    </div>
                </div>
                <div class="row-fluid">
                    <input type="submit" name="invitefriends" class="btn btn-warning pull-right" id="invitefriends" value="Send Invites">
                </div>
            </form>  
        </div>
    </div>
    
    <div class="row-fluid" id="reralstable">
        <div class="InviteFriendHead">My Referrals</div>
        
        <table class="table table-bordered table-striped table-hover dataTable" id="data-table">
        <colgroup>
            <col width=""/>
            <col width="150"/>
            <col width="100"/>
        </colgroup>
        <thead>
            <tr>
                {!! sort_th('invite.list', 'Email', 'email', 'left') !!}  
                {!! sort_th('invite.list', 'Created', 'created_at', 'center') !!}                          
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $base_index = ($rows->currentPage() - 1) * $rows->count(); ?>
            @foreach($rows as $r)
            <tr>
                <td>{{ $r->email }}</td>
                <td>{{ date('Y-m-d, H:i', strtotime($r->created_at)) }}</td>
                <td>{{ $r->status_lang() }}</td>
            </tr>
            @endforeach
            
            @if($rows->isEmpty())
            <tr>
                <td colspan="10">There is no data.</td>
            </tr>
            @endif
        </tbody>
        </table>
    </div>
    
    <div class="row-fluid">
        <div class="span6">
            @if($rows->total() > 0) 
            <div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">
                Showing <b>{{ $base_index + 1 }} to {{ $rows->currentPage() * $rows->count() }}</b> of <b>{{ $rows->total() }}</b> entries    
            </div>
            @endif
        </div>
        <div class="span6">
            <div class="dataTables_paginate paging_simple_numbers">
                {!! paginate_links($rows); !!}
            </div>    
        </div>
    </div>
</div>
          
@stop

@section('styles')
<link href="{{ asset('fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop

@section('scripts')
<script>
$("form#Invite").submit(function()
{
    var to = $('#to');
    var msg_box = $("#errormsg");
        
    if (to.val() == '')
    {
        msg_box.addClass('errormsg').html("Please enter atleast one email id ");
        to.focus();
        return false;
    }
    else if(!validEmails(to.val()))
    {
        msg_box.addClass('errormsg').html("Please enter valid emails ");
        to.focus();
        return false;
    }
});
</script>
@stop