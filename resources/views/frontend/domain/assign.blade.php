<div class="sliderPopupHead">Choose a Domain Destination</div>
<div class="row-fluid">
    <div class="span12 slidePopRhtScroll offset0">
        <div class="clearfix library_content">
            <div class="libouterbx">
                <span class="libouterleft"><input type="radio" id="assign" name="assign_domain" value="assign" @if($domainRow->assigned_id != 0) checked="checked" @endif/></span>
                <span class="libouterright">
                    Assign to one of my anomcrm sites
                
                    @if($domainRow->assigned_id != 0)
                        {!! Form::select('unassigndomains', $sites, $domainRow->assigned_id, array('id' => 'unassigndomains')) !!}
                    @else
                        {!! Form::select('unassigndomains', $sites, $domainRow->assigned_id, array('id' => 'unassigndomains', 'style' => "display:none;")) !!}
                    @endif
              </span>
            </div>
        </div>
        <div class="clearfix library_content">
            <div class="libouterbx">
                <span class="libouterleft"><input type="radio" id="noasign" name="assign_domain" value="noassign" @if($domainRow->assigned_id == 0) checked="checked" @endif/></span>
                <span class="libouterright">Don't assign a destination</span>
            </div>
        </div>
        <div class="clearfix library_content">
            <input type="button" value="Save" class="success-btn pull-right" id="btn-save-assign" data-index="{{ $domainRow->id }}"/>
        </div>
    </div>
</div>