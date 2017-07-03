<div class="storeDahboradHead clearfix">
    Products 
    <a id="btn-product-form-back">Back</a>
</div>

{!! Form::open(array('url' => URL::route("site.store.product.save"), 'method' => 'post', 'id' => 'aform', 'autocomplete' => 'off')) !!}
<div class="row-fluid store_addProduct">
    <div class="span6 marTop10">
        <form id="product-form">    
            <input type="hidden" name="product_id" value="{{ $product->id }}" />
            
            <div class="row-fluid">
                <label class="span5">Product Name</label>
                {!! Form::text('product_name', $product->name, array('id' => 'product_name')) !!}
            </div>
            
            <div class="row-fluid">
                <label class="span5">Product Type</label>
                {!! Form::select('product_category', $categories, $product->category_id, array('id' => 'product_category', 'class' => 'selectpicker no-margin full-width bs-select-hidden')) !!}
            </div>
            
            <div class="row-fluid">
                <label class="span5">Short Description</label>
                {!! Form::textarea('product_description', $product->description, array('id' => 'product_description')) !!}
            </div>
            
            <div class="row-fluid">                      
                <div class="span4 offset0">
                    <label class="span12">Product Price</label>
                    {!! Form::text('product_price', $product->price ? $product->price : 0.00, array('id' => 'product_price', 'class' => 'pricevalidate')) !!}
                </div>
                <div class="span4">
                    <label class="span12">Trial</label>
                    {!! Form::radio('is_trial', 1, true, array('id' => 'is_trial_yes', 'style' => 'display:none')) !!}
                    <label class="trial" for="is_trial_yes">Yes</label>
                    {!! Form::radio('is_trial', 0, true, array('id' => 'is_trial_no', 'style' => 'display:none')) !!}
                    <label class="trial" for="is_trial_no">No</label>
                </div>
                 <div style="display:block" class="span4" id="triallendiv">
                    <label class="span12">Trial Length</label>
                    {!! Form::text('trial_length', $product->trial_length ? $product->trial_length : 0, array('id' => 'trial_length', 'class' => 'number')) !!}
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6 offset0">
                    <label class="span5">Campaign</label>
                    {!! Form::select('product_campaign', $campaigns, $product->campaign_id, array('id' => 'product_campaign', 'class' => 'selectpicker no-margin full-width bs-select-hidden')) !!}
                </div>
                
                <div class="span6">
                    <label class="span5">Rebill Price</label>
                    {!! Form::text('rebill_price', $product->rebill_price ? $product->rebill_price : 0.00, array('id' => 'rebill_price', 'class' => 'pricevalidate')) !!}
                </div>
            </div>
            
            <div class="row-fluid">
                <label class="span5">Shipping ID</label>
                {!! Form::select('shipping', $shippings, $product->shipping_id, array('id' => 'shipping', 'class' => 'selectpicker no-margin full-width bs-select-hidden')) !!}
            </div>
            
            <div class="row-fluid">
              <label class="span5">Payment Name</label>
              {!! Form::textarea('payment_name', $product->limelight_payment_name, array('id' => 'payment_name', 'rows' =>3)) !!}
              <label>Note:If more than payment name you can use by separator (,)</label>
            </div>
            
            <div class="row-fluid">
                <input type="submit" value="Save" name="save" class="btn btn-primary" id="btn-product-save">
                <input type="button" value="Cancel" name="cancel" class="btn btn-danger" id="btn-product-cancel">
            </div>
        </form>
    </div>
        
    <div class="span6 marTop10">
        {!! Form::open(array('url' => URL::route('site.store.product.upload.image'), 'method' => 'post', 'id' => 'prodimageform', 'class' => 'form-horizontal sky-form', 'file' => true)) !!}            
            <span class="uploadprodTit">Featured</span>
            <ul class="productimages storeAddProstoredImg clearfix" id="sortableImg">
                @include('frontend.site.store.product.images')
            </ul>
            
            <h1 class="storeUploadHead">Upload your images</h1> 
            <div id="preview"></div>
            <div id='imageloadstatus' style='display:none'></div>
            <label for="file" class="input input-file" id="imageloadbutton">
                <div class="button">
                    <span class="col-sm-3" id="dropUploaddocument" > 
                        <input type="file" name="photos[]"  id="prodimg" multiple="true" />
                    </span>   
                </div>
                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="product_key" id="product_key" value="{{ str_random(30) }}" />
            </label>
            <div style="display:none;" class="progress">
                <div class="bar"></div>
                <div class="percent">0%</div>
            </div>   
        {!! Form::close() !!}
    </div>
</div>
{!! Form::close() !!}