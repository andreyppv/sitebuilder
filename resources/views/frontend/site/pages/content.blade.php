@include('frontend.site.pages.left')

<div class="mainRightPanel pull-right span9">    
    <form id="page-form" method="post">
        {!! Form::hidden('page_id', $page->id, array('id' => 'page_id')) !!}
        
        <div id="domainv"></div>
        
        <div class="clearfix">
            <div class="subdomainLeft">Page Title</div>
            <div class="subdomainRight">
                {!! Form::text('page_title', $page->title, array('id' => 'page_title', 'class' => 'PageMainRightTxtBx')) !!}
            </div>
        </div>    
        
        <div class="pagTabClickOuter">
            <div class="pagTabClick subdomainLeft">Advance Settings</div>    
            
            <div class="pagTabShowHide">
                <div class="clearfix">
                    <div class="subdomainLeft">Page Url</div>
                    <div class="subdomainRight">
                        {!! Form::text('seo_url', $page->seo_url, array('id' => 'seo_url', 'class' => 'PageMainRightTxtBx', 'readonly')) !!}
                    </div>
                </div> 
                
                <div class="marTop10 clearfix">
                    <div class="subdomainLeft">Page Description</div>
                    <div class="subdomainRight">
                        {!! Form::textarea('page_description', $page->description, array('id' => 'page_description', 'class' => 'PageMainRightTxtBx', 'rows' => 2, 'style' => 'height:auto !important;')) !!}
                    </div>
                </div>  
                
                <div class="marTop10 clearfix">
                    <div class="subdomainLeft">Meta Keywords</div>
                    <div class="subdomainRight">
                        {!! Form::textarea('meta_keywords', $page->meta_keywords, array('id' => 'meta_keywords', 'class' => 'PageMainRightTxtBx', 'rows' => 1, 'style' => 'height:auto !important;')) !!}
                    </div>
                </div>  
            </div>
        </div>
        
        @if($page->type == 'static') 
        <label>{!! Form::checkbox('show_in_form', 1, $page->is_each_page, array('id' => 'show_in_form')) !!}&nbsp;&nbsp;Show in Each Form of the Page</label>
        <label>{!! Form::checkbox('terms_conditions', 1, $page->is_term_page, array('id' => 'terms_conditions', 'class' => 'static_type')) !!}&nbsp;&nbsp;Set This Page As Terms & Condiotions page</label>
        <label>{!! Form::checkbox('privacy_policy', 1, $page->is_privacy_page, array('id' => 'privacy_policy', 'class' => 'static_type')) !!}&nbsp;&nbsp;Set This Page As Privacy Policy page</label>
        <label>{!! Form::checkbox('contact_us', 1, $page->is_contact_page, array('id' => 'contact_us', 'class' => 'static_type')) !!}&nbsp;&nbsp;Set This Page As Cotact Us page</label>
        @endif
        
        <div class="marTop10 clearfix">
            <input type="button" value="Save &amp; Edit" class="btn btn-success" id="btn-page-save" name="save">    
            
            @if($page->type == 'static')
            <input type="button" value="Delete Page" class="btn btn-danger" id="btn-page-delete" name="delete">
            @endif
        </div>
    </form>
</div>