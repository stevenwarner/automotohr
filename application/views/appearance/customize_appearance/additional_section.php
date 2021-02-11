<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title);?>"><?php echo $panel_title; ?> <span class="glyphicon glyphicon-plus"></span></a>
        </h4>
    </div>
    <div id="collapse-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title);?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="universal-form-style-v2">
                        <form id="box-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title);?>" method="post" enctype="multipart/form-data">
                            <ul>
                                <li class="form-col-50-left">
                                    <label>Title<!--<span class="staric">*</span>--></label>
                                    <?php echo form_input('title', set_value('title',$box['title']), 'class="invoice-fields"'); ?>
                                    <?php echo form_error('title'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Status</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="status">
                                            <option value="0" <?php echo  !$box['status'] ? 'selected="selected"' : '';?>>In Active</option>
                                            <option value="1" <?php echo  $box['status'] ? 'selected="selected"' : '';?>>Active</option>
                                        </select>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label for="footer_content">Content<!--<span class="staric">*</span>--></label>
                                    <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                    <textarea class="ckeditor" id="<?php echo $box['sid'] ?>" name="content" rows="8" cols="60"><?php echo set_value('content',$box['content']);?></textarea>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Banner Image</label>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php if(isset($box['image']) && $box['image']!=''){?>
                                            <div class="well well-sm">
                                                <img style="width: 100%;" class="img-responsive" src="<?php echo (isset($box['image']) ? AWS_S3_BUCKET_URL . $box['image'] : ''); ?>" alt=""/>
                                            </div>
                                            <?php }
                                            else{
                                                echo '<h3>No Banner Uploaded</h3>';
                                            }?>
                                        </div>
                                    </div>
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
                                            <option value="left_right" <?php echo $box['column_type'] == "left_right" ? "selected='selected'" : ''?>>Left Right</option>
                                            <option value="right_left" <?php echo $box['column_type'] == "right_left" ? "selected='selected'" : ''?>>Right Left</option>
                                            <option value="top_down" <?php echo $box['column_type'] == "top_down" ? "selected='selected'" : ''?>>Top Down</option>
                                        </select>
                                    </div>
                                </li>
                                <?php $video_url = isset($box['video']) && !empty($box['video']) ? 'https://www.youtube.com/embed/' . $box['video'] : ''; ?>
                                <?php if(!empty($video_url)) { ?>
                                <li class="form-col-100 autoheight">
                                    <h2>Video</h2>
                                    <div class="well well-sm">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="<?php echo $video_url; ?>"></iframe>
                                        </div>
                                    </div>
                                    <br />
                                </li>
                                <?php } ?>
                                <li class="form-col-100">
                                    <label>Video URL </label>
                                    <?php $video_url = isset($box['video']) && !empty($box['video']) ? 'https://www.youtube.com/watch?v=' . $box['video'] : ''; ?>
                                    <?php echo form_input('video', set_value('video', $video_url), 'class="invoice-fields video-url"'); ?>
                                    <?php echo form_error('video'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Show Image/Video</label>
                                    <div class="hr-box-body hr-innerpadding">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label class="control control--radio">
                                                    Video
                                                    <input type="radio" name="show_video_or_image" id="show_video"  value="video" <?php echo $box['show_video_or_image'] == 'video' ? 'checked="checked"' : '';?> >
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label class="control control--radio">
                                                    Image
                                                    <input type="radio" name="show_video_or_image" id="show_image"  value="image" <?php echo $box['show_video_or_image'] == 'image' ? 'checked="checked"' : '';?> >
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="btn-panel text-right">
                                <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                <input type="hidden" name="box-sid" value="<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title);?>">
                                <input type="hidden" id="page_name" name="page_name" value="home" />
                                <input type="hidden" id="perform_action" name="perform_action" value="<?php echo $perform_action; ?>" />
                                <input type="hidden" id="section_id" name="section_id" value="<?php echo $section_id; ?>" />
                                <button type="submit" class="btn btn-success">Save Section</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

//        $(function () {
//            $.validator.setDefaults({
//                debug: true,
//                success: "valid"
//            });
            $("#box-" + "<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title);?>").validate({
                ignore: ":hidden:not(select)",
                rules: {
                    status: {
                        required: true
                    },
//                    title: {
//                        required: true
//                    },
                    video: {
                        pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                    }
                },
                messages: {
                    status: {
                        required: 'Status is required'
                    },
//                    title: {
//                        required: 'Title is required'
//                    },
                    video:{
                        pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                    }
                },
            submitHandler: function (form) {
//                var instances = $.trim(CKEDITOR.instances[<?=$box['sid']?>].getData());
//                if (instances.length === 0) {
//                    alertify.error('Error! Content Missing', "Content cannot be Empty");
//                    return false;
//                }
                form.submit();
            }
            });
//        });
    });


</script>