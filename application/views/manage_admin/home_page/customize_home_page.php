<div class="main">
    <div class="container-fluid">
        <div class="row">                   
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <?php if (in_array('full_access', $security_details) || in_array('customize_home_page', $security_details)) { ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?></h1>
                                        </div>
                                        <!-- Edit Invoice Start -->
                                        <div class="hr-edit-invoice">
                                            <form method="post" action="" enctype="multipart/form-data" id="customize_home_form">
                                                <div class="hr-complex" >
                                                    <ul>  
                                                        <li style="width:100%">
                                                            <label>Header Main Heading Text:</label>
                                                            <textarea style="padding:10px; height:150px; " class="hr-form-fileds"  required="" name="header_text"><?php
                                                                if (isset($home_page['header_text'])) {
                                                                    echo $home_page['header_text'];
                                                                }
                                                                ?></textarea>
                                                        </li>  <li style="width:100%">
                                                            <label>Header Sub-Heading Text:</label>
                                                            <textarea style="padding:10px; height:150px; " class="hr-form-fileds"  required="" name="header_sub_text"><?php
                                                                if (isset($home_page['header_sub_text'])) {
                                                                    echo $home_page['header_sub_text'];
                                                                }
                                                                ?></textarea>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="hr-complex" id="product_container">
                                                    <ul>  
                                                        <li>
                                                            <input type="radio" name="header_flag" value="header_video_flag" id="header_video_flag" <?php
                                                            if ($home_page['header_video_flag']) {
                                                                echo "checked";
                                                            }
                                                            ?>>&nbsp;<b>Header Video</b>
                                                            &nbsp;&nbsp;
                                                            <input type="radio" name="header_flag" value="header_banner_flag" id="header_banner_flag" <?php
                                                            if ($home_page['header_banner_flag']) {
                                                                echo "checked";
                                                            }
                                                            ?>>&nbsp;<b>Header Banner</b><br/><br/>      
                                                        </li>
                                                        <li style="width:100%;" id="header_video_link">
                                                            <label>Header Video Link:</label>
                                                            <textarea style="padding:10px; height:150px; " class="hr-form-fileds" id="header_video" name="header_video" required=""><?php if (isset($home_page['header_video'])) {
                                                                ?>https://www.youtube.com/watch?v=<?php
                                                                    echo $home_page['header_video'];
                                                                }
                                                                ?></textarea>
                                                        </li>
                                                        <?php if (isset($home_page['header_video'])) { ?>
                                                            <li style="display: none"  id="header_video_display" class="admin-video">
                                                                <div class="well well-sm">
                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $home_page['header_video']; ?>"  height="500px" width="100%"> </iframe>
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                        <li style="width:100%;  display: none;"  id="header_banner_link">
                                                            <label>Header Banner Image:</label>
                                                            <input type="file" name="header_banner" id="header_banner"  >
                                                        </li>
                                                        <?php if (isset($home_page['header_banner'])) { ?>
                                                            <input type="hidden" name="old_header_banner" value="<?php echo $home_page['header_banner']; ?>" >

                                                            <li style="display: none" id="header_banner_display" class="admin-video">
                                                                <div class="well well-sm home-page-img">
                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $home_page['header_banner']; ?>" >
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="hr-complex" >
                                                    <ul>  
                                                        <li style="width:100%">
                                                            <label>HR and Recruiting Video:</label>
                                                            <textarea style="padding:10px; height:150px; " class="hr-form-fileds" name="hr_video" required=""><?php if (isset($home_page['hr_video'])) {
                                                            ?>https://www.youtube.com/watch?v=<?php
                                                                    echo $home_page['hr_video'];
                                                                }
                                                                ?></textarea>
                                                        </li>
                                                        <?php if (isset($home_page['hr_video'])) { ?>
                                                            <li class="admin-video">
                                                                <div class="well well-sm">
                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $home_page['hr_video']; ?>" height="500px" width="100%"></iframe>
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="hr-complex" id="banner_1">
                                                    <div class="form-group edit_filter autoheight" style="display: inline-block;">
                                                        <div class="col-xs-12">
                                                        <label>So why should you use or switch to <?php echo STORE_NAME; ?>?</label>
                                                        </div>
                                                        <div class="col-xs-12">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo NO_VIDEO; ?>
                                                                <input <?= $home_page['banner_1_type'] == 'no_video' ? 'checked="checked"' : '' ?> class="video_source" type="radio" name="banner_1_type" value="no_video"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo YOUTUBE_VIDEO; ?>
                                                                <input <?= $home_page['banner_1_type'] == 'youtube' ? 'checked="checked"' : '' ?> class="video_source" type="radio" name="banner_1_type" value="youtube"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo VIMEO_VIDEO; ?>
                                                                <input <?= $home_page['banner_1_type'] == 'vimeo' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_1_type" value="vimeo"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo UPLOAD_VIDEO; ?>
                                                                <input <?= $home_page['banner_1_type'] == 'upload_video' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_1_type" value="upload_video" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                Image Banner
                                                                <input <?= $home_page['banner_1_type'] == 'upload_image' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_1_type" value="upload_image" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                                            <div class="form-group autoheight" id="yt_vm_video_container">
                                                                <label for="video_id">Video Url <span class="hr-required">*</span></label>
                                                                <input style="margin-bottom:10px;" type="text" name="video_url_1" value="<?= $home_page['banner_1_type'] == 'youtube' ? 'https://www.youtube.com/watch?v='.$home_page['why_us_banner_1'] : ($home_page['banner_1_type'] == 'vimeo'? 'https://vimeo.com/'.$home_page['why_us_banner_1'] : '')  ?> " class="form-control" id="video_id_1" data-rule-required="true">
                                                                <div style="display:block;max-width:100%;">
                                                                <?php if($home_page['banner_1_type'] == 'youtube'){ ?>    
                                                                    <iframe width="100%" height="480px" src="https://www.youtube.com/embed/<?= $home_page['why_us_banner_1'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                                <?php }else if($home_page['banner_1_type'] == 'vimeo'){ ?>
                                                                    <iframe src="https://player.vimeo.com/video/<?= $home_page['why_us_banner_1']; ?>" width="100%" height="480px" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group autoheight" id="up_video_container">
                                                                <label>Upload Video <span class="hr-required">*</span></label>
                                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                                    <span class="selected-file" id="name_video_upload_1"><?= $home_page['banner_1_type'] == 'upload_video' ? @end(explode("/",$home_page['why_us_banner_1'])) : '' ?></span>
                                                                    <input type="file" name="video_upload_1" id="video_upload_1" onchange="check_video_file('video_upload_1')">
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                                <div style="display:block;max-width:100%;">
                                                                    <?php if($home_page['banner_1_type'] == 'upload_video'){ ?>
                                                                        <video style="width:100%;" controls id="old_video_1" src="<?= base_url($home_page['why_us_banner_1']) ?>">
                                                                        </video>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group autoheight" id="up_image_container">
                                                                <label>Upload Image Banner <span class="hr-required">*</span></label>
                                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                                    <span class="selected-file" id="name_why_us_banner_1"><?= $home_page['banner_1_type'] == 'upload_image' ? $home_page['why_us_banner_1'] : '' ?></span>
                                                                    <input type="file" name="why_us_banner_1" id="why_us_banner_1" onchange="check_image_file('why_us_banner_1')">
                                                                    <a href="javascript:;">Choose Image</a>
                                                                </div>
                                                                <div style="display:block;max-width:100%;">
                                                                    <?php if($home_page['banner_1_type'] == 'upload_image'){ ?>
                                                                        <img id="old_img_1" style="width:100%;" src="<?php echo AWS_S3_BUCKET_URL . $home_page['why_us_banner_1']; ?>" >
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                                <div class="hr-complex" id="banner_2">
                                                <div class="form-group edit_filter autoheight" style="display: inline-block;">
                                                        <div class="col-xs-12">
                                                        <label>Get everything you need to target:</label>
                                                        </div>
                                                        <div class="col-xs-12">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo NO_VIDEO; ?>
                                                                <input <?= $home_page['banner_2_type'] == 'no_video' ? 'checked="checked"' : '' ?> class="video_source" type="radio" name="banner_2_type" value="no_video"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo YOUTUBE_VIDEO; ?>
                                                                <input <?= $home_page['banner_2_type'] == 'youtube' ? 'checked="checked"' : '' ?> class="video_source" type="radio" name="banner_2_type" value="youtube"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo VIMEO_VIDEO; ?>
                                                                <input <?= $home_page['banner_2_type'] == 'vimeo' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_2_type" value="vimeo"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo UPLOAD_VIDEO; ?>
                                                                <input <?= $home_page['banner_2_type'] == 'upload_video' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_2_type" value="upload_video" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">    
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                Image Banner
                                                                <input <?= $home_page['banner_2_type'] == 'upload_image' ? 'checked="checked"' : '' ?>  class="video_source" type="radio" name="banner_2_type" value="upload_image" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                                            <div class="form-group autoheight" id="yt_vm_video_container">
                                                                <label for="video_id_2">Video Url <span class="hr-required">*</span></label>
                                                                <input style="margin-bottom:10px;" type="text" name="video_url_2" value="<?= $home_page['banner_2_type'] == 'youtube' ? 'https://www.youtube.com/watch?v='.$home_page['why_us_banner_2'] : ($home_page['banner_2_type'] == 'vimeo'? 'https://vimeo.com/'.$home_page['why_us_banner_2'] : '')  ?> " class="form-control" id="video_id_2" data-rule-required="true">
                                                                <div style="display:block;max-width:100%;">
                                                                <?php if($home_page['banner_2_type'] == 'youtube'){ ?>    
                                                                    <iframe width="100%" height="480px" src="https://www.youtube.com/embed/<?= $home_page['why_us_banner_2'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                                <?php }else if($home_page['banner_2_type'] == 'vimeo'){ ?>
                                                                    <iframe src="https://player.vimeo.com/video/<?= $home_page['why_us_banner_2']; ?>" width="100%" height="480px" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group autoheight" id="up_video_container">
                                                                <label>Upload Video <span class="hr-required">*</span></label>
                                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                                    <span class="selected-file" id="name_video_upload_2"><?= $home_page['banner_2_type'] == 'upload_video' ? @end(explode("/",$home_page['why_us_banner_2'])) : '' ?></span>
                                                                    <input type="file" name="video_upload_2" id="video_upload_2" onchange="check_video_file('video_upload_2')">
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                                <div style="display:block;max-width:100%;">
                                                                    <?php if($home_page['banner_2_type'] == 'upload_video'){ ?>
                                                                        <video style="width:100%;" controls id="old_video_2" src="<?= base_url($home_page['why_us_banner_2']) ?>">
                                                                        </video>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group autoheight" id="up_image_container">
                                                                <label>Upload Image Banner <span class="hr-required">*</span></label>
                                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                                    <span class="selected-file" id="name_why_us_banner_2"><?= $home_page['banner_1_type'] == 'upload_image' ? $home_page['why_us_banner_2'] : '' ?></span>
                                                                    <input type="file" name="why_us_banner_2" id="why_us_banner_2" onchange="check_image_file('why_us_banner_2')">
                                                                    <a href="javascript:;">Choose Image</a>
                                                                </div>
                                                                <div style="display:block;max-width:100%;">
                                                                    <?php if($home_page['banner_2_type'] == 'upload_image'){ ?>
                                                                        <img id="old_img_2" style="width:100%;" src="<?php echo AWS_S3_BUCKET_URL . $home_page['why_us_banner_2']; ?>" >
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                </div>                        
                                                
                                                <div class="invoice-bottom">
                                                    <ul>
                                                        <li class="btns-row">
                                                            <!--<input type="submit" class="site-btn" value="Apply">-->
                                                            <input type="submit" class="site-btn" value="Save">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Edit Invoice End -->
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('input[name="header_flag"]').change(function (e) {
        var div_to_show = $(this).val();
        if (div_to_show == 'header_video_flag') {
            $('#header_video_link').show();
            $("#header_video").prop('required', true);
            $('#header_video_display').show();


            $('#header_banner_link').hide();
            $('#header_banner_display').hide();
            $("#header_banner").prop('required', false);
        } else {
            $('#header_banner_link').show();
//            $("#header_banner").prop('required', true);
            $('#header_banner_display').show();


            $('#header_video_link').hide();
            $('#header_video_display').hide();
            $("#header_video").prop('required', false);
        }
    });

    $(document).ready(function () {

        if ($('#header_video_flag').is(':checked') == true) {
            $('#header_video_link').show();
            $("#header_video").prop('required', true);
            $('#header_video_display').show();


            $('#header_banner_link').hide();
            $('#header_banner_display').hide();
            $("#header_banner").prop('required', false);
        } else if ($('#header_banner_flag').is(':checked') == true) {
            $('#header_banner_link').show();
//            $("#header_banner").prop('required', true);
            $('#header_banner_display').show();


            $('#header_video_link').hide();
            $('#header_video_display').hide();
            $("#header_video").prop('required', false);
        }
        var banner1 = '#banner_1';
        var banner2 = '#banner_2';
        choose_radio_buttons('<?= $home_page['banner_1_type'] ?>',banner1);
        choose_radio_buttons('<?= $home_page['banner_2_type'] ?>',banner2);
        $(banner1+' .video_source').on('click', function(){
            var selected = $(this).val();
            choose_radio_buttons(selected,banner1);
            
        });
        $(banner2+' .video_source').on('click', function(){
            var selected = $(this).val();
            choose_radio_buttons(selected,banner2);
            
        });
        $('#customize_home_form').submit(function(){
            var flag = 0;
            var message;
            if($('input[name="banner_1_type"]:checked').val() == 'youtube'){
                if($('#video_id_1').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#video_id_1').val().match(p)) {
                        message = 'Not a Valid Youtube URL';
                        flag = 1;
                    }
                } else {
                    message = 'Please provide a Valid Youtube URL';
                    flag = 1;
                }
            }
            if($('input[name="banner_2_type"]:checked').val() == 'youtube'){
                if($('#video_id_2').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#video_id_2').val().match(p)) {
                        message = 'Not a Valid Youtube URL';
                        flag = 1;
                    }
                } else {
                    message = 'Please provide a Valid Youtube URL';
                    flag = 1;
                }
            }

            if($('input[name="banner_1_type"]:checked').val() == 'vimeo'){
                if($('#video_id_1').val() != '') {
                    var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {url: $('#video_id_1').val()},
                        async : false,
                        success: function (data) {
                            if (data == false) {
                                message = 'Not a Valid Vimeo URL';
                                flag = 1;
                            }
                        },
                        error: function (data) {
                        }
                    });
                } else {
                    message = 'Please provide a Valid Vimeo URL';
                    flag = 1;
                }
            }
            if($('input[name="banner_2_type"]:checked').val() == 'vimeo'){
                if($('#video_id_2').val() != '') {
                    var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {url: $('#video_id_2').val()},
                        async : false,
                        success: function (data) {
                            if (data == false) {
                                message = 'Not a Valid Vimeo URL';
                                flag = 1;
                            }
                        },
                        error: function (data) {
                        }
                    });
                } else {
                    message = 'Please provide a Valid Vimeo URL';
                    flag = 1;
                }
            }
            
            if($('input[name="banner_1_type"]:checked').val() == 'upload_video'){
                var fileName  = $("#video_upload_1").val();
                if (fileName.length > 0) {
                    $('#name_video_upload_1').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#video_upload_1").val(null);
                        $('#name_video_upload_1').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        message = 'Please select a valid video format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#video_upload_1")[0].files[0].size/1024/1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#video_upload_1").val(null);
                            $('#name_video_upload_1').html('');
                            message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else if($("#old_video_1").length == 0) {
                    $('#name_video_upload_1').html('<p class="red">Please select video</p>');
                    message = 'Please select video to upload';
                    flag = 1;
                }
            }
            if($('input[name="banner_2_type"]:checked').val() == 'upload_video'){
                var fileName  = $("#video_upload_2").val();

                if (fileName.length > 0) {
                    $('#name_video_upload_2').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#video_upload_2").val(null);
                        $('#name_video_upload_2').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        message = 'Please select a valid video format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#video_upload_2")[0].files[0].size/1024/1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#video_upload_2").val(null);
                            $('#name_video_upload_2').html('');
                            message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else if($("#old_video_2").length == 0) {
                    $('#name_video_upload_2').html('<p class="red">Please select video</p>');
                    message = 'Please select video to upload';
                    flag = 1;
                }
            }
            if($('input[name="banner_1_type"]:checked').val() == 'upload_image'){
                var fileName  = $("#why_us_banner_1").val();

                if (fileName.length > 0) {
                    $('#name_why_us_banner_1').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                        $("#why_us_banner_1").val(null);
                        $('#name_why_us_banner_1').html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                        message = 'Please select a valid image format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#why_us_banner_1")[0].files[0].size/1024/1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#why_us_banner_1").val(null);
                            $('#name_why_us_banner_1').html('');
                            message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else  if($("#old_img_1").length == 0) {
                    $('#name_why_us_banner_1').html('<p class="red">Please select image</p>');
                    message = 'Please select image to upload';
                    flag = 1;
                }
            }
            if($('input[name="banner_2_type"]:checked').val() == 'upload_image'){
                var fileName  = $("#why_us_banner_2").val();

                if (fileName.length > 0) {
                    $('#name_why_us_banner_2').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                        $("#why_us_banner_2").val(null);
                        $('#name_why_us_banner_2').html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                        message = 'Please select a valid image format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#why_us_banner_2")[0].files[0].size/1024/1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#why_us_banner_2").val(null);
                            $('#name_why_us_banner_2').html('');
                            message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else  if($("#old_img_2").length == 0) {
                    $('#name_why_us_banner_2').html('<p class="red">Please select image</p>');
                    message = 'Please select image to upload';
                    flag = 1;
                }
            }
            if (flag == 1) {
                alertify.alert(message);
                return false;
            }
        });
    });
    function choose_radio_buttons(selected, banner){
        if(selected == 'no_video'){
            $(banner+' #yt_vm_video_container input').prop('disabled', false);
            $(banner+' #yt_vm_video_container').hide();

            $(banner+' #up_video_container input').prop('disabled', true);
            $(banner+' #up_video_container').hide();

            $(banner+' #up_image_container input').prop('disabled', true);
            $(banner+' #up_image_container').hide();
        }else if (selected == 'youtube') {
                $(banner+' #yt_vm_video_container input').prop('disabled', false);
                $(banner+' #yt_vm_video_container').show();

                $(banner+' #up_video_container input').prop('disabled', true);
                $(banner+' #up_video_container').hide();

                $(banner+' #up_image_container input').prop('disabled', true);
                $(banner+' #up_image_container').hide();

                $(banner+' #upload_audio_video').text('Save Video');

            } else if (selected == 'vimeo') {
                $(banner+' #yt_vm_video_container input').prop('disabled', false);
                $(banner+' #yt_vm_video_container').show();

                $(banner+' #up_video_container input').prop('disabled', true);
                $(banner+' #up_video_container').hide();

                $(banner+' #up_image_container input').prop('disabled', true);
                $(banner+' #up_image_container').hide();

                $(banner+' #upload_audio_video').text('Save Video');

            } else if (selected == 'upload_video') {
                $(banner+' #yt_vm_video_container input').prop('disabled', true);
                $(banner+' #yt_vm_video_container').hide();

                $(banner+' #up_video_container input').prop('disabled', false);
                $(banner+' #up_video_container').show();

                $(banner+' #up_image_container input').prop('disabled', true);
                $(banner+' #up_image_container').hide();

                $(banner+' #upload_audio_video').text('Save Video');

            } else if (selected == 'upload_image') {
                $(banner+' #yt_vm_video_container input').prop('disabled', true);
                $(banner+' #yt_vm_video_container').hide();

                $(banner+' #up_video_container input').prop('disabled', true);
                $(banner+' #up_video_container').hide();

                $(banner+' #up_image_container input').prop('disabled', false);
                $(banner+' #up_image_container').show();

            }
    }
    function check_video_file(val) {
        var fileName  = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                $("#" + val).val(null);
                alertify.alert("Please select a valid video format.");
                $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                return false;
            } else {
                var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                if (video_size_limit < file_size) {
                    $("#" + val).val(null);
                    alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                    $('#name_' + val).html('');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + val).html(original_selected_file);
                    return true;
                }

            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.alert("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;
        }
    }
    function check_image_file(val) {

        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
</script>
