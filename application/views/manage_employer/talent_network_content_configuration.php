<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <?php echo form_open_multipart('', array('id' => 'talent-network-config')); ?>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header-area">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back to Settings</a>
                                    Join Our Talent Network
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="universal-form-style-v2 talent-network-config">
                                <ul>
                                    <li class="form-col-100">
                                        <label>title <span class="hr-required">*</span></label>
                                        <input name="title" id="title" value="<?php echo isset($talent_data['title']) ? $talent_data['title'] : ''; ?>" class="invoice-fields" type="text">
                                        <?php echo form_error('title'); ?>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <label>Content <span class="hr-required">*</span></label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                        <textarea class="ckeditor" name="content" id="content" rows="8" cols="60" ><?php echo isset($talent_data['content']) ? $talent_data['content'] : ''; ?></textarea>
                                        <?php echo form_error('content'); ?>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    Show Image
                                                    <input type="radio" value="picture" name="picture_or_video" <?php if(isset($talent_data['picture_or_video']) && $talent_data['picture_or_video'] == 'picture'){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    Show Video
                                                    <input type="radio" value="video" name="picture_or_video" <?php if(isset($talent_data['picture_or_video']) && $talent_data['picture_or_video'] == 'video'){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    None
                                                    <input type="radio" value="none" name="picture_or_video" <?php if(isset($talent_data['picture_or_video']) && ($talent_data['picture_or_video'] == 'none')){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-12">
                                            	<?php echo form_error('picture_or_video'); ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-100" id="video_div">
                                        <label>YouTube Video&nbsp; <span class="example-link">e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX</span></label>
                                        <input name="youtube_link" id="youtube_link" value="<?php echo !empty($talent_data['youtube_link']) ? 'https://www.youtube.com/watch?v='.$talent_data['youtube_link'] : ''; ?>" class="invoice-fields" onblur="return youtube_check()" type="text">
                                        <?php echo form_error('youtube_link'); ?><br>
<!--                                        <p id="video_link_error"></p>-->
                                    </li>
                                    <?php if(isset($talent_data['picture']) && $talent_data['picture'] != '' && $talent_data['picture'] != NULL){ ?>
                                    <li class="form-col-100 autoheight no-margin" id="pic_display">
                                        <div class="well well-sm">
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $talent_data['picture']; ?>" alt="">
                                        </div>
                                    </li>
                                    <?php } ?>
                                    <li class="form-col-100" id="picture_div">
                                        <label>upload image</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                            <input name="pictures" id="pictures" onchange="check_file('pictures')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <input value="Save" class="submit-btn" type="submit">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe") {
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
    
    function display(value)
    {
        if (value == 'picture')
        {
            $('#picture_div').show();
            $('#pic_display').show();
            $('#video_div').hide();
        }
        else if (value == 'video')
        {
            $('#video_div').show();
            $('#picture_div').hide();
            $('#pic_display').hide();
        }
        else
        {
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
