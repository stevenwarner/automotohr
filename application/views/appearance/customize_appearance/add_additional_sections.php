<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('customize_appearance/'.$theme) ?>"><i class="fa fa-chevron-left"></i>Back</a>Add Additional Section</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabs-wrp paid-theme">
                                    <div class="tab_container">
                                        <div class="panel-group-wrp">
                                            <div class="panel-group">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="universal-form-style-v2">
                                                                <form id="box" method="post" enctype="multipart/form-data">
                                                                    <ul>
                                                                        <li class="form-col-50-left">
                                                                            <label>Title<!--<span class="staric">*</span>--></label>
                                                                            <?php echo form_input('title', set_value('title'), 'class="invoice-fields"'); ?>
                                                                            <?php echo form_error('title'); ?>
                                                                        </li>
                                                                        <li class="form-col-50-right">
                                                                            <label>Status</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="status">
                                                                                    <option value="0" >In Active</option>
                                                                                    <option value="1" selected="selected">Active</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                        <li class="form-col-100 autoheight">
                                                                            <label for="footer_content">Content<!--<span class="staric">*</span>--></label>
                                                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                                            <textarea class="ckeditor" name="content" rows="8" cols="60"><?php echo set_value('content');?></textarea>
                                                                        </li>
                                                                        <li class="form-col-50-left">
                                                                            <label>Image</label>
                                                                            <div class="upload-file invoice-fields">
                                                                                <span class="selected-file" class="name_image" >No file selected</span>
                                                                                <input type="file" name="image" id="image" class="image">
                                                                                <a href="javascript:;">Choose File</a>
                                                                            </div>
                                                                        </li>

                                                                        <li class="form-col-50-right">
                                                                            <label>Column Type</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="column_type">
                                                                                    <option value="left_right" selected='selected'>Left Right</option>
                                                                                    <option value="right_left" selected='selected'>Right Left</option>
                                                                                    <option value="top_down" >Top Down</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                        <li class="form-col-100">
                                                                            <label>Video URL </label>
                                                                            <?php echo form_input('video', set_value('video'), 'class="invoice-fields video-url"'); ?>
                                                                            <?php echo form_error('video'); ?>
                                                                        </li>
                                                                        <li class="form-col-50-left">
                                                                            <label>Show Image/Video</label>
                                                                            <div class="hr-box-body hr-innerpadding">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                        <label class="control control--radio">
                                                                                            Video
                                                                                            <input type="radio" name="show_video_or_image" id="show_video"  value="video" >
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                        <label class="control control--radio">
                                                                                            Image
                                                                                            <input type="radio" name="show_video_or_image" id="show_image"  value="image" checked="checked" >
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="btn-panel text-right">
                                                                        <input type="submit" class="btn btn-success" value="Save Section" name="perform_action"/>
                                                                    </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Modal -->
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/additional-methods.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $('.image').on('change',function(){
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });
    });

    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#box").validate({
            ignore: ":hidden:not(select)",
            rules: {
                content: {
                    required: function()
                    {
                        CKEDITOR.instances.content.updateElement();
                    }
                },
                status: {
                    required: true
                },
                video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                content: {
                    required: 'Content is required'
                },
                status: {
                    required: 'Status is required'
                },
                video:{
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                }
            },
            submitHandler: function (form) {
//                var instances = $.trim(CKEDITOR.instances.content.getData());
//                if (instances.length === 0) {
//                    alertify.alert('Error! Content Missing', "Content cannot be Empty");
//                    return false;
//                }
                form.submit();
            }
        });
    });
</script>