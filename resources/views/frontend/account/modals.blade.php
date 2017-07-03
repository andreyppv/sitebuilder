<div>

    <!--Password Modal-->
    <div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="deleteHead marbtm15">
            Change Password 
            <div data-dismiss="modal" class="close PopDeleteButt"></div>
        </div>
        <div id="passcoulmn" class="myAccTabCont">
            <form name="changePassForm" class="no-mar" id="changePassForm" method="post">
                <div class="cst_errormsg"></div>
                <div class="row-fluid">
                    <div class="">
                        <div class="row-fluid">
                            <div class="accontLeftLabel span4">Current Password</div>
                            <input type="password" name="current_pass" class="myaccTctBx" id="current_pass" value="" >
                        </div>
                        <div class="row-fluid marTop20">
                            <div class="accontLeftLabel span4">New Password</div>
                            <input type="password" name="new_pass" class="myaccTctBx" id="new_pass" value="" >
                        </div>
                        <div class="row-fluid marTop20">
                            <div class="accontLeftLabel span4">Confirm New Password</div>
                            <input type="password" name="confirm_newpass" class="myaccTctBx" id="confirm_newpass" value="" class="span4 myaccTctBx">
                        </div>
                        <div class="row-fluid marTop20">
                            <div class="span4">&nbsp;</div>
                            <input type="submit" class="submitButton" name="passsubmit" id="passsubmit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>    
        </div>
    </div>
    <!--End Pass Modal-->
    
    <!--Email Modal-->
    <div class="modal fade" id="modal-email" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="deleteHead marbtm15">
            Change Email 
            <div data-dismiss="modal" class="close PopDeleteButt"></div>
        </div>
        <div id="emailcoulmn" class="myAccTabCont">
            <form name="emailcolumn" class="no-mar" id="emailcolumn" method="post">
                <div class="cst_errormsg"></div>
                <div class="row-fluid">
                    <div class="">
                        <div class="row-fluid">
                            <div class="span4 accontLeftLabel">Email</div>
                            <input type="text" name="email" class="myaccTctBx" id="email" value="{{ $current_customer->email }}">
                        </div>
                        <div class="row-fluid marTop20">
                            <div class="span4">&nbsp;</div>
                            <input type="submit" class="submitButton" name="emailsubmit" id="emailsubmit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>    
        </div>
    </div>
    <!--End Email Modal-->
    
    <!--Name Modal-->
    <div class="modal fade" id="modal-fullname" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="deleteHead marbtm15">
            Change Name
            <div data-dismiss="modal" class="close PopDeleteButt"></div>
        </div>
        <div id="namecoulmn" class="myAccTabCont" >
            <form name="fullnameForm" class="no-mar" id="fullnameForm" method="post">
                <div class="cst_errormsg"></div>
                <div class="row-fluid">
                    <div class="">
                        <div class="row-fluid">
                            <div class="accontLeftLabel span4">Full Name</div>
                            <input type="text" name="fullname" class="myaccTctBx" id="fullname" value="{{ $current_customer->name }}">
                        </div>
                        <div class="row-fluid marTop20">
                            <div class="span4">&nbsp;</div>
                            <input type="submit" class="submitButton" name="fullsubmit" id="fullsubmit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>        
        </div>
    </div>
    <!--End Name Modal-->
    
</div>