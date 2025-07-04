<form id="func_insert_new_getting_started_section" enctype="multipart/form-data" method="post" action="<?php echo base_url('onboarding/configuration'); ?>">
    <input type="hidden" id="perform_action" name="perform_action" value="update_new_getting_started_section" />
    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
    <input type="hidden" id="section_sid" name="section_sid" value="<?php echo (isset($section['sid']) ? $section['sid'] : ''); ?>" />
    <div class="universal-form-style-v2">
        <ul>
            <li class="form-col-100">
                <?php $field_id = 'section_title'; ?>
                <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
                <?php echo form_label('Title:', $field_id); ?>
                <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '"'); ?>
                <?php echo form_error($field_id); ?>
            </li>
            <li class="form-col-100 autoheight">
                <?php $field_id = 'section_content'; ?>
                <?php $temp = (isset($section[$field_id]) ? html_entity_decode($section[$field_id]) : ''); ?>
                <?php echo form_label('Content:', $field_id); ?>
                <?php echo form_textarea($field_id, set_value($field_id, $temp, false), 'class="invoice-fields autoheight ckeditor" id="' . $field_id . '"'); ?>
                <?php echo form_error($field_id); ?>
            </li>
            <?php $field_id = 'section_video'; ?>
            <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
            <?php if($temp != '') { ?>
                <li class="form-col-100 autoheight">
                    <div class="well well-sm">
                        <?php if($section['section_video_source'] == 'youtube') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['section_video']; ?>"></iframe>
                            </div>
                        <?php } else { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe src="https://player.vimeo.com/video/<?php echo $section['section_video']; ?>" frameborder="0"></iframe>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>

            <li class="form-col-50-left autoheight">
                <?php $field_id = 'section_video_status'; ?>
                <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
                <label>Video Status</label>
                <label for="section_video_status_enabled" class="control control--radio">
                    <?php $default_selected = $temp == 1 ? true : false; ?>
                    Enabled
                    <input <?php echo set_radio($field_id, 1, $default_selected); ?> id="section_video_status_enabled" name="section_video_status" value="1" type="radio">
                    <div class="control__indicator"></div>
                </label>
                <label for="section_video_status_disabled" class="control control--radio">
                    <?php $default_selected = $temp == 0 ? true : false; ?>
                    Disabled
                    <input <?php echo set_radio($field_id, 0, $default_selected); ?> id="section_video_status_disabled" name="section_video_status" value="0" type="radio">
                    <div class="control__indicator"></div>
                </label>
                <label for="section_video_status_remove" class="control control--radio">
                    <?php $default_selected = $temp == 2 ? true : false; ?>
                    Remove
                    <input <?php echo set_radio($field_id, 2, $default_selected); ?> id="section_video_status_remove" name="section_video_status" value="2" type="radio">
                    <div class="control__indicator"></div>
                </label>
            </li>
            <li class="form-col-50-right autoheight">
                <?php $field_id = 'section_video_source'; ?>
                <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
                <label>Video Source</label>
                <label for="section_video_source_youtube" class="control control--radio">
                    <?php $default_selected = $temp == 'youtube' ? true : false; ?>
                    Youtube
                    <input <?php echo set_radio($field_id, 'youtube', $default_selected); ?> id="section_video_source_youtube" name="section_video_source" value="youtube" type="radio">
                    <div class="control__indicator"></div>
                </label>
                <label for="section_video_source_vimeo" class="control control--radio">
                    <?php $default_selected = $temp == 'vimeo' ? true : false; ?>
                    Vimeo
                    <input <?php echo set_radio($field_id, 'vimeo', $default_selected); ?> id="section_video_source_vimeo" name="section_video_source" value="vimeo" type="radio">
                    <div class="control__indicator"></div>
                </label>
            </li>

            <?php $field_id = 'section_video'; ?>
            <?php $temp = (isset($section[$field_id]) ? html_entity_decode($section[$field_id]) : ''); ?>
            <li class="form-col-100">
                <?php if($section['section_video_source'] == 'youtube') { ?>
                    <?php $temp = empty($temp) ? '' : 'https://www.youtube.com/watch?v=' . $temp; ?>
                <?php } else { ?>
                    <?php $temp = empty($temp) ? '' : 'https://vimeo.com/' . $temp; ?>
                <?php } ?>
                <?php $field_id = 'section_video'; ?>
                <?php echo form_label('Video:', $field_id); ?>
                <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '"'); ?>
                <?php echo form_error($field_id); ?>
            </li>


            <?php $field_id = 'section_image'; ?>
            <?php $sec_img = $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
            <?php if(!empty($temp)) { ?>
                <li class="form-col-100 autoheight">
                    <div class="well well-sm">
                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" />
                    </div>
                </li>
            <?php } ?>

            <li class="form-col-100 autoheight">
                <?php $field_id = 'section_image_status'; ?>
                <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : ''); ?>
                <label>Image Status</label>
                <label for="section_image_status_enabled" class="control control--radio">
                    <?php $default_selected = $temp == 1 ? true : false; ?>
                    Enabled
                    <input <?php echo set_radio($field_id, 1, $default_selected); ?> id="section_image_status_enabled" name="section_image_status" value="1" type="radio">
                    <div class="control__indicator"></div>
                </label>
                <label for="section_image_status_disabled" class="control control--radio">
                    <?php $default_selected = $temp == 0 ? true : false; ?>
                    Disabled
                    <input <?php echo set_radio($field_id, 0, $default_selected); ?> id="section_image_status_disabled" name="section_image_status" value="0" type="radio">
                    <div class="control__indicator"></div>
                </label>
                <label for="section_image_status_remove" class="control control--radio">
                    <?php $default_selected = $temp == 2 ? true : false; ?>
                    Remove
                    <input <?php echo set_radio($field_id, 2, $default_selected); ?> id="section_image_status_remove" name="section_image_status" value="2" type="radio">
                    <div class="control__indicator"></div>
                </label>
            </li>
            <li class="form-col-100 autoheight">
                <?php $field_id = 'section_image'; ?>
                <?php echo form_label('Image File:', $field_id); ?>
                <input type="file" class="form-fields" id="section_image" name="section_image" />
                <?php echo form_error($field_id); ?>
            </li>

            <li class="form-col-100">
                <?php $field_id = 'section_sort_order'; ?>
                <?php $temp = (isset($section[$field_id]) ? $section[$field_id] : 0); ?>
                <?php echo form_label('Sort Order:', $field_id); ?>
                <input type="number" min="0" id="section_sort_order" name="section_sort_order" class="invoice-fields" value="<?php echo set_value($field_id, $temp); ?>"  />
                <?php echo form_error($field_id); ?>
            </li>
        </ul>
        <button type="submit" class="btn btn-success">Update Section</button>
    </div>

</form>

<script type="text/javascript">
    $("#func_insert_new_getting_started_section").validate({
        ignore: ":hidden:not(select)",
        rules: {
            content: {
                required: function()
                {
                    CKEDITOR.instances.content.updateElement();
                }
            },
            section_title: {
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
            section_title: {
                required: 'Title is required'
            },
            video:{
                pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
            }
        },
        submitHandler: function (form) {
            var video_status = $('input[name="section_video_status"]:checked').val();
            var image_status = $('input[name="section_image_status"]:checked').val();
            var pre_img = '<?= $sec_img?>';
            if(video_status == 1){
                if($('#section_video').val() == ''){
                    alertify.error('Video URL is required');
                    return false;
                }else{
                    if($('input[name="section_video_source"]:checked').val() == 'youtube'){
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                        if(!$('#section_video').val().match(p)){
                            alertify.error('Not a Valid Youtube URL');
                            return false;
                        }
                    }else{

                        var myurl = "<?= base_url() ?>onboarding/validate_vimeo";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {url: $('#section_video').val()},
                            success: function (data) {
                                console.log(data);
                                if(data == 'false'){
                                    return false;
                                }
                            },
                            error: function(data) {

                            }
                        });
                    }
                }
            }
            if(image_status == 1){
                if(pre_img == '' && $('#section_image').val() == ''){
                    alertify.error('Provide Image');
                    return false;
                }
            }
            form.submit();
        }
    });
</script>