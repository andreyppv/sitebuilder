<div class="mainLeftPanel overhidden fixeddiv span2">
    <div id="designleft">
        <div class="maindesignleftDiv">
            <div class="headerNav span12">
                <h3 class="mainTabNav active">
                    <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                    Design
                </h3>
            </div>
            <div class="selectTheme widAuto clearfix span12 offset0">
                <button id="change-theme-button" class="mainleftthemeAlign column4 span12 offset0 borderbox">
                    <i class="fa fa-copy"></i> <br />
                    - Change Theme
                </button>
                <button id="change-fonts-button" class="mainleftthemeAlign column4 span12 offset0 borderbox">
                    <i class="fa fa-font"></i>  <br />
                    - Change Fonts Options
                </button>
                <button id="change-back-button" data-toggle="modal" href="#backgroundchangePopup" class="mainleftthemeAlign column4 span12 offset0 borderbox">
                    <i class="fa fa-picture-o"></i>  <br />
                    - Change Background Image
                </button>
                <button id="change-banner-button" class="mainleftthemeAlign column4 span12 offset0 borderbox">
                    <i class="fa fa-picture-o"></i>  <br />
                    Change Banner Image
                </button>
            </div>
        </div>
        <div class="mainbackgroundleftChange" style="display:none;">
            <div class="selectTheme no-mar clearfix">
                <div class="selectlist clearfix">
                    <h3 class="fontlistdivHead marLftmin60 active">
                        <span class="arrow"></span>
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                        <br/>
                        Select Option <a class="fontback">BACK</a>
                    </h3>
                    
                </div>                        
            </div>
        </div>
        <div class="maindesignleftChange" style="display:none;">
            <div class="selectTheme no-mar clearfix">
                <input type="hidden" name="fontchange" id="fontchange" value="">
                <div class="selectlist clearfix">
                    <h3 class="fontlistdivHead marLftmin60 active span12">
                        <span class="arrow"></span>
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                         Select Option<a class="fontback">BACK</a>
                    </h3>
                    <ul class="nav span12 offset0">
                        <li id="site_title" class="width50 offset0">
                            <i class="fa fa-copy"></i> <br />
                            Site Title
                        </li>
                        <li id="nav_menu" class="width50 offset0">
                            <i class="fa fa-copy"></i> <br />
                            Navigation Menu
                        </li>
                        <li id="main_headline" class="width50 offset0">
                            <i class="fa fa-copy"></i> <br />
                            Headline
                        </li>
                        <li id="main_title" class="width50 offset0">
                            <i class="fa fa-copy"></i> <br />
                            Title
                        </li>
                        <li id="main_paragraph" class="width50 offset0">
                            <i class="fa fa-copy"></i> <br />
                            Paragraph
                        </li>
                        <li id="main_imagetitle" class="width50 offset0">    
                            <i class="fa fa-copy"></i> <br />
                            Image Captions
                        </li>
                    </ul>    
                </div>                        
                <div class="fontlistdiv fontlistdivSel" style="display:none;">
                    <h3 class="fontlistdivHead marLftmin60 active span12 fontsettingname">
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                        <span>Font Settings</span><a class="fontback">BACK</a>
                    </h3>
                    <div class="clearfix"></div>
                    <h3 class="fontlistdivHead fontsetting marLftmin60 active span12 offset0 marginTop10">
                        <span class="arrow"></span>
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                        <br/>
                        Select Font Family
                    </h3>
                    <div class="mainLeftPanelScrollNew span12 offset0">
                        <div class="nav fontfamily fontlistdivInn">
                            <ul class="nav fontfamily fontScrollUl">
                                
                            </ul>
                        </div>
                    </div>
                    <h3 class="fontlistdivHead fontsetting active span12 offset0 marginTop10">
                        <span class="arrow"></span>
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                        <br/>
                        Select Font Size
                    </h3>
                    <div class="fontSizeModify clearfix span12 offset0">
                        <span class="plusminusfont decreasefont marLeft20"><i class="fa fa-minus-circle"></i></span>
                        <input type="text" class="inputfont" value="20" />
                        <span class="plusminusfont increasefont"><i class="fa fa-plus-circle"></i></span>
                    </div>    
                    <h3 class="fontlistdivHead fontsetting active lineht span12 offset0 marginTop10">
                        <span class="arrow"></span>
                        <img src="{{ asset('images/basic.png')  }}" alt="basic" title="basic" />
                        <br/>
                        Select Line Height
                    </h3>
                    <div class="fontSizeModify clearfix lineht span12 offset0">
                        <span class="plusminusfont decreaselineht marLeft20"><i class="fa fa-minus-circle"></i></span>
                        <input type="text" class="inputlineht" value="20" />
                        <span class="plusminusfont increaselineht"><i class="fa fa-plus-circle"></i></span>
                    </div>                        
                </div>
            </div>
        </div>
        <div class="themecolorselect">
            <label class="colorlabel">Theme Color</label>
            <ul class="colorpick" id="theme-color-picker">
                @foreach($site->theme->colors as $cls)
                <li style="background-color:{{ $cls->value }}" class="themeclrselected @if($cls->id == $site->color_id) active @endif" data-index="{{ $cls->id }}"></li>
                @endforeach    
            </ul>
        </div>  
    </div>
    
    <a class="bulid-inst" id="btn-show-instruction">Click here to see Instruction</a>
</div>