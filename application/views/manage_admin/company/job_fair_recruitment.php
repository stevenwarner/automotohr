<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>
                                    <div class="edit-template-from-main">
                                        <div class="add-new-company">
                                            <div class="heading-title page-title">
                                                <h2 class="page-title">Company Name: <?php echo ucwords($company_name); ?></h2>
                                            </div>
                                                <?php echo form_open_multipart('', array('class' => 'form-horizontal')); ?>
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <ul>
                                                    <li>
                                                        <?php echo form_label('Title <span class="hr-required">*</span>', 'page_title'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('title', set_value('page_title', $job_fair_data['title']), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('title'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Content <span class="hr-required">*</span>', 'page_content'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_textarea('content', set_value('page_content', $job_fair_data['content'], false), 'class="ckeditor"'); ?>
                                                            <?php echo form_error('content'); ?>
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <?php echo form_label('Banner or Video <span class="hr-required"></span>', 'picture_or_video'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label class="control control--radio">
                                                                        Show Image
                                                                        <input type="radio" value="picture" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'picture'){ echo 'checked'; } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label class="control control--radio">
                                                                        Show Video
                                                                        <input type="radio" value="video" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'video'){ echo 'checked'; } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label class="control control--radio">
                                                                        None
                                                                        <input type="radio" value="none" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && ($job_fair_data['picture_or_video'] == 'none')){ echo 'checked'; } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <?php echo form_error('picture_or_video'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>                                                   
                                                    <li class="form-col-100" id="video_div">
                                                        <?php echo form_label('YouTube Video <span class="hr-required"></span>', 'video_id'); ?>
                                                        <div class="hr-fields-wrap">                                                 
                                                        <input name="video_id" id="youtubevideo" value="<?php echo !empty($job_fair_data['video_id']) ? 'https://www.youtube.com/watch?v='.$job_fair_data['video_id'] : ''; ?>" class="invoice-fields" onblur="return youtube_check()" type="text">
                                                        <?php echo form_error('video_id'); ?><br>
                                                        <p id="video_link_error"></p>
                                                        </div>
                                                    </li>
                                                    
                                            <?php   if(isset($job_fair_data['banner_image']) && $job_fair_data['banner_image'] != '' && $job_fair_data['banner_image'] != NULL){ ?>
                                                        <li class="form-col-100 autoheight no-margin" id="pic_display">
                                                            <div class="hr-fields-wrap">
                                                                <div class="well well-sm">
                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $job_fair_data['banner_image']; ?>" alt="">
                                                                </div>
                                                            </div>
                                                        </li>
                                            <?php   } ?>
                                                    
                                                    <li class="form-col-100" id="picture_div">
                                                        <label>upload image</label>
                                                        <div class="hr-fields-wrap"> 
                                                            <div class="upload-file invoice-fields">
                                                                <span class="selected-file" id="name_pictures">No file selected</span>
                                                                <input name="banner_image" id="pictures" onchange="check_file('pictures')" type="file">
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                    <li class="form-col-100 autoheight">
                                                        <?php echo form_label('Status <span class="hr-required"></span>', 'status'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label class="control control--radio">
                                                                        Enable
                                                                        <input type="radio" value="1" name="status" <?php if($job_fair_data['status'] == 1){ echo 'checked'; } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label class="control control--radio">
                                                                        Disable
                                                                        <input type="radio" value="0" name="status" <?php if($job_fair_data['status'] == 0){ echo 'checked'; } ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <?php echo form_error('status'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                    <li>
                                                        <input type="submit" name="submit" value="Save" class="search-btn">
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function youtube_check() {
        var matches = $('#youtubevideo').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
        data = $('#youtubevideo').val();
        
        if (matches || data == '') {
            $("#video_link_error").html("");
            $(':input[type="submit"]').prop('disabled', false);
            $(".search-btn").css("background","#81b431");
            return true;
        } else {
            $("#video_link_error").html("Please enter a Valid Youtube Link");
            $(':input[type="submit"]').prop('disabled', true);
            $(".search-btn").css("background","#ccc");
            return false;
        }
    }
        
    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
    
    function display(value) {
        if (value == 'picture') {
            $('#picture_div').show();
            $('#pic_display').show();
            $('#video_div').hide();
        } else if (value == 'video') {
            $('#video_div').show();
            $('#picture_div').hide();
            $('#pic_display').hide();
        } else {
            $('#video_div').hide();
            $('#picture_div').hide();
            $('#pic_display').hide();
        }
    }
    
    $(document).ready(function () {
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);
    });
    
    $("input[type='radio']").click(function(){
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);
    });
</script>