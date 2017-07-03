<div class="span12">                            
                    
    <div class="AccInfo">
        <div class="AccInfoHead">General Setting</div>
        <div class="AccInfoCont">
            <div class="AccInfoRhtInner clearfix">
                <div class="AccInfoRhtInnerLft">Password</div>
                <div class="AccInfoRhtInnerMiddle">......</div>
                <button class="btn btn-large btn-success AccInfoRhtInnerButton" data-target="#modal-password" data-toggle="modal"><i class="fa fa-edit"></i></button>    
            </div>
            <div class="AccInfoRhtInner clearfix">
                <div class="AccInfoRhtInnerLft">Email</div>
                <div class="AccInfoRhtInnerMiddle">{{ $current_customer->email }}</div>
                <button class="btn btn-large btn-success AccInfoRhtInnerButton" data-target="#modal-email" data-toggle="modal"><i class="fa fa-edit"></i></button>    
            </div>
            <div class="AccInfoRhtInner borNone clearfix">
                <div class="AccInfoRhtInnerLft">Full Name</div>
                <div class="AccInfoRhtInnerMiddle">{{ $current_customer->name }}</div>
                <button class="btn btn-large btn-success AccInfoRhtInnerButton" data-target="#modal-fullname" data-toggle="modal"><i class="fa fa-edit"></i></button>    
            </div>
        </div>
    </div>
    
</div>