@if(isset($items))
    @foreach($items as $item)
        <?php $data = json_decode($item->data); ?>
        
        @if($item->type == ITEM_TITLE)     
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
                       
            <div id="buildTitle_{{ $item->id }}" 
                class="element themehead clickheretext contenttext contentedit main_title" 
                contenteditable="true" 
                data-ph="Edit Title Here" 
                data-rapidcms-engine="true" 
                data-index="{{ $item->id }}" 
                data-type="{{ $item->type }}">{!! $item->content !!}
            </div>
        </li>
        @elseif($item->type == ITEM_PARAGRAPH)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            <div id="buildPara_{{ $item->id }}" 
                class="element clickheretext contenttext contentedit main_paragraph" 
                style="" 
                contenteditable="true"  
                data-ph="Edit Paragraph Here" 
                data-rapidcms-engine="true" 
                data-index="{{ $item->id }}" 
                data-type="{{ $item->type }}">{!! $item->content !!}
            </div>  
        </li>
        @elseif($item->type == ITEM_DIVIDER)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            <div id="buildDivide_{{ $item->id }}"><hr></div>    
        </li>
        @elseif($item->type == ITEM_IMAGE)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            <div id="buildImg_{{ $item->id }}" class="row-fluid buildimage" style="">
                @if($item->content == '')
                <form id="imageform_{{ $item->id }}" class="form-horizontal sky-form" method="post" enctype="multipart/form-data" style="clear:both">
                    <label id='imageloadbutton_{{ $item->id }}' class="input input-file" style="display:block; height:135px;" for="file">
                        <div class="button">
                            <input type='button' name="photos[]" id="photoimg_{{ $item->id }}" class="inputfile element" data-index="{{ $item->id }}" data-type="{{ $item->type }}"/>
                        </div>
                    </label>
                </form>
                @else
                <div class="uploadImgBg" id="alignto{{ $item->id }}" style="text-align:{{ $data->text_align }}"> 
                    <a><img class="imagechange" src="{{ url($item->content) }}" width="{{ $data->width }}" height="{{ $data->height }}"/></a> 
                    <form class="clr form-horizontal sky-form uploadsecbg" style="display:none;" method="post" enctype="multipart/form-data">
                        <label id='imageloadbutton_{{ $item->id }}' class="input input-file" style="display:block" for="file">
                            <div class="button">
                                <input type='button' name="photos[]" id="photoimg_{{ $item->id }}" class="inputfile element" data-index="{{ $item->id }}" data-type="{{ $item->type }}"/>
                            </div>
                        </label>
                        <input type='hidden' name="status" value="single"/>
                    </form>
                </div>
                @endif
            </div>
        </li>
        @elseif($item->type == ITEM_MAP)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}" data-latitude="{{ $data->latitude }}" data-longitude="{{ $data->longitude }}" data-zoom="{{ $data->zoom }}" data-address="{{ isset($data->address) ? $data->address : '' }}">
            <div class="form_element_control">
                <div class="controlMidOuter"><div class="controlMidBg"></div></div>
                <div class="settingControl"><div class="settingControlBg btn-map-setting-popup"><img src="{{ asset('images/setting.png') }}"/></div></div>
                <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            
            <div class="moveicon"></div>
            <div class="map_resize"> 
                <iframe name="ifrm" id="ifrm" frameborder="0" allowfullscreen="" src="{{ route('common.map', (array)$data) }}" width="100%" height="250"></iframe>
            </div>
        </li>
        @elseif($item->type == ITEM_SOCIAL_ICONS)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
                <div class="controlMidOuter"><div class="controlMidBg"></div></div>
                <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            
            <div id="buildSocial_{{ $item->id }}" class="buildSocialIcon" style="text-align:{{ $data->align }}">
                <input type="button" class="fbicon" value="" />
                <input type="button" class="twittericon" value="" />
                <input type="button" class="linkedicon" value="" />
                <input type="button" class="mailicon" value="" />
            </div>
        </li>
        @elseif($item->type == ITEM_YOUTUBE)
        <?php 
            $margin = 'margin:0px;'; 
            if($data->space == 'Small') $margin = 'margin:5px 0;';
            else if($data->space == 'Medium') $margin = 'margin:10px 0;';            
            else if($data->space == 'Large') $margin = 'margin:15px 0;';            
            
            $width = 'width:50%; height:250px; margin-left:auto; margin-right:auto;';
            if($data->width == 'Medium') $width = 'width:70%;';
            else if($data->width == 'Large') $width = 'width:100%;';
            
            $style = $margin . $width;
            
            $url = ($item->content == '') ? 'https://www.youtube.com/embed/S4HpNGtnkts' : $item->content;
        ?>
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}" data-space="{{ $data->space }}" data-width="{{ $data->width }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="settingControl"><div class="settingControlBg btn-youtube-setting-popup"><img src="{{ asset('images/setting.png') }}"/></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            
            <div class="youtubeDiv clearfix" id="youtubeDiv_{{ $item->id }}" style="display:block; {{ $style }}">
                <iframe name="ifrm" id="ifrm{{ $item->id }}" frameborder="0" allowfullscreen="" src="{{ $url }}" width="100%" height="200"></iframe>
            </div>
        </li>
        @elseif($item->type == ITEM_FILE)
        <li id="page_{{ $item->id }}" class="activemove theme2bginn" data-index="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="form_element_control">
               <div class="controlMidOuter"><div class="controlMidBg"></div></div>
               <div class="deleteOuter"><div class="deleteBg" title="Delete"><i class="fa fa-trash-o"></i></div></div>
            </div>
            
            @if($item->hasFile())            
            <?php $file = $item->downloadFile(); ?>
            <div class="width100 block fileTool btn-download-file" style="background:url(<?php echo file_icon($file->name); ?>) no-repeat left top">
                <div class="filename">{{ $file->name }}</div>
               <a>Downlod File</a> 
            </div>
            @else
            <div class="width100 block fileTool btn-download-file" style="background:url({{ asset('images/download.png') }}) no-repeat left top">
               <a>Click here to upload file</a> 
            </div>
            @endif
        </li>
        @endif
    @endforeach    
@endif