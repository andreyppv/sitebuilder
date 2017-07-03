<div class="sliderPopupHead">Registrar Lock</div>
<div class="span12 slidePopRhtScroll offset0">
    <div class="clearfix library_content">
        <label class="libouterbx" for="reglock">
            <span class="libouterleft">
                <input type="radio" id="reglock" name="reg_lock" value="{{ ON }}" @if($domainRow->register_lock == ON) checked="checked" @endif/>
            </span> 
            <span class="libouterright">Lock Domain</span> Lock your domain to prevent it from being transferred without your permission. It is recommended to keep your domain locked.
        </label>
    </div>
    
    <div class="clearfix library_content">
        <label class="libouterbx" for="regunlock">
            <span class="libouterleft">
                <input type="radio" id="regunlock" name="reg_lock" value="{{ OFF }}" @if($domainRow->register_lock != ON) checked="checked" @endif/>
            </span>
            <span class="libouterright">Unlock Domain</span> Unlock your domain if you need to transfer it to another registrar. This will remove your privacy protection.
        </label>
    </div>
    <div class="clearfix library_content">
        <input type="button" value="Save" class="success-btn pull-right" id="btn-save-register-status" data-product-id="{{ $domainRow->product_id }}"/>
    </div>
</div>