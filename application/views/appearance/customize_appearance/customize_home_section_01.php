<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Section One <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Textual Content</h2>

                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" id="form_config_section_01_test">
                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_config_section_01"/>
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->

                                    <!-- Section related Hidden Fields Start -->
                                    <!-- Section related Hidden Fields Start -->

                                    <li class="form-col-100 autoheight">
                                        <label for="title_section_01">Title</label>
                                        <input type="text" name="title_section_01" id="title_section_01" class="invoice-fields" placeholder="Title Goes Here" value="<?php echo (isset($section_01_meta['title']) && trim($section_01_meta['title'])!='' ? $section_01_meta['title'] : ''); ?>" />
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <label for="section_01_tagline">Tag Line</label>
                                        <input type="text" name="tag_line_section_01" id="tag_line_section_01" class="invoice-fields" placeholder="Tag Line Goes Here" value="<?php echo (isset($section_01_meta['tag_line']) && trim($section_01_meta['tag_line'])!='' ? $section_01_meta['tag_line'] : ''); ?>" />
                                    </li>

                                    <!-- Capitalize option section -->
                                    <li class="form-col-100 autoheight">
                                        <div class="questionair_radio_container">
                                            <input 
                                            type = "checkbox" 
                                            name = "show_capitalize_section_01" 
                                            id = "show_capitalize_section_01" 
                                            <?=(isset($section_01_meta['do_capitalize']) && $section_01_meta['do_capitalize'] == 1) || (!isset($section_01_meta['do_capitalize'])) ? 'checked="true"' : '';?>
                                            />
                                            <label for="show_capitalize_section_01">Capitalize text</label>
                                        </div>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <div class="questionair_radio_container">
                                            <label>Banner Type</label>
                                            <input type="hidden" name="show_img_vdo_section_01" value=""  />
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="show_img_vdo_section_01" id="show_img_vdo_section_01_video"  value="video" <?php echo ( isset($section_01_meta['show_video_or_image']) && $section_01_meta['show_video_or_image'] == 'video' ? 'checked="checked"' : ''); ?> />
                                            <label for="show_img_vdo_section_01_video">
                                                Video
                                            </label>
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="show_img_vdo_section_01" id="show_img_vdo_section_01_image"  value="image" <?php echo ( isset($section_01_meta['show_video_or_image']) && $section_01_meta['show_video_or_image'] == 'image' ? 'checked="checked"' : ''); ?> />
                                            <label for="show_img_vdo_section_01_image">
                                                Image
                                            </label>
                                        </div>

                                    </li>



                                    <div class="btn-panel">
                                        <ul>
                                            <li><button type="button" class="delete-all-btn active-btn" onclick="fSaveConfigSection01();"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save Configuration</button></li>
                                        </ul>
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Banner Image</h2>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="well well-sm">
                                    <img class="img-responsive"
                                         src="<?php echo (isset($section_01_meta['image']) ? AWS_S3_BUCKET_URL . $section_01_meta['image'] : ''); ?>"
                                         alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="universal-form-style-v2">
                                    <form method="post" id="form_save_image_section_01" enctype="multipart/form-data">
                                        <!-- Required Hidden Fields Start -->
                                        <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                        <input type="hidden" name="perform_action" id="perform_action" value="save_image_section_01"/>
                                        <input type="hidden" id="page_name" name="page_name" value="home" />
                                        <!-- Required Hidden Fields end -->

                                        <div class="form-group">
                                            <div class="upload-file invoice-fields">
                                                <span class="selected-file" id="selected_file_image_section_01" name="selected_file_image_section_01">No file selected</span>
                                                <input onchange="fUpdateOnChangeStatic(this)" class="invoice-fields" type="file"  name="image_section_01" />
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                            <p class="help-block text-right">Image Dimensions : W 1400px X H 900px.</p>
                                        </div>
                                        <div class="btn-panel">
                                            <button type="button"
                                                    class="delete-all-btn active-btn"
                                                    onclick="fSaveImageSection01();">
                                                <i class="fa fa-refresh"></i>&nbsp;Save Image
                                            </button>
                                            &nbsp;&nbsp;
                                            <button
                                                data-pageid=""
                                                data-banner="main"
                                                data-page="home"
                                                data-def_image="1-e1qhe.jpg"
                                                data-theme="<?php echo $theme['theme_name']; ?>"
                                                type="button"
                                                class="delete-all-btn active-btn"
                                                onclick="fRestoreDefaultImageForSection(this, 1);" ><i class="fa fa-reply"></i>&nbsp;Restore Default Banner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Video</h2>
                        <div class="well well-sm">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo ( isset($section_01_meta['video']) && $section_01_meta['video'] != '' ? $section_01_meta['video'] : ''); ?>"></iframe>
                            </div>
                        </div>
                        <br />
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_video_section_01" enctype="multipart/form-data" method="post">
                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_section_01_video"/>
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->

                                    <li class="form-col-100 autoheight">
                                        <label for="video_section_01">Video Url <span class="staric">*</span></label>
                                        <input class="invoice-fields" type="text" id="video_section_01" name="video_section_01" placeholder="Video Url" value="<?php echo ( isset($section_01_meta['video']) && $section_01_meta['video'] != '' ? 'https://www.youtube.com/watch?v=' . $section_01_meta['video'] : ''); ?>" />
                                    </li>
                                </form>
                            </ul>
                        </div>
                        <div class="btn-panel">
                            <button type="button" class="delete-all-btn active-btn" onclick="fSaveVideoSection01();" ><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save Video</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fValidateConfigSection01() {
        $('#form_config_section_01_test').validate({
            rules: {
                // title_section_01: {
                //     required: true
                // },
                // tag_line_section_01: {
                //     required: true
                // },
                show_img_vdo_section_01: {
                    required: true
                }
            }
        });
    }
    $(document).ready(function(){
        fValidateConfigSection01();
        fValidateVideoSection01();
        fValidateImageSection01();
    });
    function fSaveConfigSection01() {
//        fValidateConfigSection01();
        if ($('#form_config_section_01_test').valid()) {
            $('#form_config_section_01_test').submit();
        }
    }

    function fValidateVideoSection01() {
        $('#form_video_section_01').validate({
            rules: {
                video_section_01: {
                    required: true,
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            }
        });
    }
    function fSaveVideoSection01() {
//        fValidateVideoSection01();

        if ($('#form_video_section_01').valid()) {
            $('#form_video_section_01').submit();
        }
    }

    function fValidateImageSection01() {
        $('#form_save_image_section_01').validate({
            rules: {
                image_section_01: {
                    extension: 'jpg|jpeg|jpe|png'
                }
            }
        });
    }
    function fSaveImageSection01() {
//        fValidateImageSection01();

        if ($('#form_save_image_section_01').valid()) {
            $('#form_save_image_section_01').submit();
        }
    }




</script>
