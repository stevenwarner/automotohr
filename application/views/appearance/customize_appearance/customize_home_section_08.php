<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseEight">Advanced Font Customization <span class="glyphicon glyphicon-plus"></span></a>
        </h4>
    </div>
    <div id="collapseEight" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="universal-form-style-v2">
                        <form id="form_save_font_family" method="post" name="form_save_font_family" enctype="multipart/form-data">
                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                            <input type="hidden" name="page_name" id="page_name" value="home"/>
                            <input type="hidden" name="section" id="section" value="collapseEight"/>
                            <input type="hidden" name="perform_action" id="perform_action" value="save_font_configurations"/>
                            
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label for="font_customization">Font Configurations</label>
                                    <input type="hidden" id="font_customization" name="font_customization" value=""  /><!--this is for error reporting-->
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                Use Default Fonts
                                                <input type="radio" name="font_customization" id="font_customization_disabled"  value="0" <?php echo ( isset($theme['font_customization']) && intval($theme['font_customization']) == 0 ? 'checked="checked"' : '');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                Use Google Fonts
                                                <input type="radio" name="font_customization" id="font_customization_enabled"  value="1" <?php echo ( isset($theme['font_customization']) && intval($theme['font_customization']) == 1 ? 'checked="checked"' : '');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                Use Web Fonts
                                                <input type="radio" name="font_customization" id="font_customization_enabled"  value="2" <?php echo ( isset($theme['font_customization']) && intval($theme['font_customization']) == 2 ? 'checked="checked"' : '');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Google Fonts</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label for="google_fonts">Select Font Family</label>
                                            <div class="Category_chosen single-select-dropdown">                           
                                                <select data-placeholder="Please Select" name="google_fonts" id="google_fonts"  class="chosen-select">
                                                        <option value="0" >Please Select</option>
                                                <?php       foreach ($google_fonts as $google_font) { ?>
                                                            <option value="<?php echo $google_font['sid']; ?>" <?php if($theme['google_fonts_sid'] == $google_font['sid']){ echo 'selected';}?>><?php echo $google_font['font_family']; ?></option>
                                                <?php       } ?>
                                                </select>
                                            </div> 
                                            <div class="video-link" style='font-style: italic; font-weight: normal;'><b></b>
                                                Please select Google Font if you want to use it.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Web Fonts</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label for="web_fonts">Select Font Family</label>
                                            <div class="Category_chosen single-select-dropdown">                           
                                                <select data-placeholder="Please Select" name="web_fonts" id="web_fonts"  class="chosen-select">
                                                            <option value="0" >Please Select</option>
                                                <?php       foreach ($web_fonts as $web_font) { ?>
                                                                <option value="<?php echo $web_font['sid']; ?>" <?php if($theme['web_fonts_sid'] == $web_font['sid']){ echo 'selected';}?>><?php echo $web_font['web_fonts']; ?></option>
                                                <?php       } ?>
                                                </select>
                                            </div> 
                                            <div class="video-link" style='font-style: italic; font-weight: normal;'><b></b>
                                                Please select Web Font if you want to use it.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Button and Heading Title Customizations</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="video-link" style='font-style: italic; font-weight: normal; padding-bottom: 10px;'><b></b>
                                                Please leave it blank if you want to use default colors
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label for="theme4_btn_bgcolor">Button Background Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_btn_bgcolor" value="<?php echo $theme['theme4_btn_bgcolor']; ?>" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label for="theme4_btn_bgcolor">Button Font Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_btn_txtcolor" value="<?php echo $theme['theme4_btn_txtcolor']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label for="theme4_heading_color_span">Heading Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_heading_color_span" value="<?php echo $theme['theme4_heading_color_span']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label for="theme4_heading_color">Heading First Word Color </label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_heading_color" value="<?php echo $theme['theme4_heading_color']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Job Search Box Customizations</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="video-link" style='font-style: italic; font-weight: normal; padding-bottom: 10px;'><b></b>
                                                Please leave it blank if you want to use default colors
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <label for="theme4_search_container_bgcolor">Search Container Background Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_search_container_bgcolor" value="<?php echo $theme['theme4_search_container_bgcolor']; ?>" >
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <label for="theme4_search_btn_bgcolor">Search Button Background Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_search_btn_bgcolor" value="<?php echo $theme['theme4_search_btn_bgcolor']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                    <label for="theme4_search_btn_color">Search Button Font Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_search_btn_color" value="<?php echo $theme['theme4_search_btn_color']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Heading Banner Text Color</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="video-link" style='font-style: italic; font-weight: normal; padding-bottom: 10px;'><b></b>
                                                Please leave it blank if you want to use default colors
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <label for="theme4_banner_text_l1_color">First Line Font Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_banner_text_l1_color" value="<?php echo $theme['theme4_banner_text_l1_color']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <label for="theme4_banner_text_l2_color">Second Line Font Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_banner_text_l2_color" value="<?php echo $theme['theme4_banner_text_l2_color']; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label>Job Details Page</label>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="video-link" style='font-style: italic; font-weight: normal; padding-bottom: 10px;'><b></b>
                                                Please leave it blank if you want to use default colors
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <label for="theme4_job_title_color">Job Title Font Color</label>
                                                    <div class="input-group colorcustompicker"> 
                                                        <input type="text" class="form-control" name="theme4_job_title_color" value="<?php echo isset($theme['theme4_job_title_color']) ? $theme['theme4_job_title_color'] : '#0000ff'; ?>">
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                              
                            <div class="btn-panel text-center">
                                <input type="submit" class="btn btn-success" value="Save Settings" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();
        $('.colorcustompicker').colorpicker();
    });
</script>