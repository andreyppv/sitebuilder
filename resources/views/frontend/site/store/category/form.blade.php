<div class="storeDahboradHead clearfix">
    Product Type 
    <a id="btn-category-form-back">Back</a>
</div>

{!! Form::open(array('url' => URL::route("site.store.category.save"), 'method' => 'post', 'id' => 'aform', 'autocomplete' => 'off')) !!}
<div class="span7 offset0 marTop10">
    {!! Form::hidden('category_id', isset($category->id) ? $category->id : null, array('id' => 'category_id')) !!}
    
    <div class="store_addProduct">
        <label>Name</label>
        {!! Form::text('category_name', isset($category->name) ? $category->name : null, array('id' => 'category_name', 'class' => 'widt49per')) !!}
        <div class="clr"></div>
        
        <div class="storeFooter marTop20">
            <input type="submit" value="Save" name="save" class="btn btn-primary" id="btn-category-save">
            <input type="button" value="Cancel" name="cancel" class="btn btn-danger" id="btn-category-cancel">
        </div>
    </div>
</div>
{!! Form::close() !!}