<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Section Three <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
        <div class="panel-body">
            <div>
                <h2>Textual Content</h2>
                <div class="universal-form-style-v2">
                    <ul>
                        <form id="form_save_config_section_03" enctype="multipart/form-data" method="post" >

                            <!-- Required Hidden Fields Start -->
                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                            <input type="hidden" name="perform_action" id="perform_action" value="save_config_section_03"/>
                            <input type="hidden" id="page_name" name="page_name" value="home" />
                            <!-- Required Hidden Fields end -->


                            <li class="form-col-100 autoheight">
                                <label for="txt_about_us_heading">Title <span class="staric">*</span></label>
                                <input class="invoice-fields" type="text" name="title_section_03"  id="title_section_03" value="<?php echo (isset($section_03_meta['title']) && $section_03_meta['title'] != '' ? $section_03_meta['title'] : '' ); ?>"/>
                            </li>
                            <li class="form-col-100 autoheight">
                                <label for="txt_about_us_text">Content <span class="staric">*</span></label>
                                <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                <textarea class="invoice-fields-textarea" type="text" name="content_section_03" id="content_section_03" rows="4"><?php echo (isset($section_03_meta['content']) && $section_03_meta['content'] != '' ? $section_03_meta['content'] : '' ); ?></textarea>
                            </li>
                            <li class="form-col-100 autoheight">
                                <div>
                                    <label for="status_section_03">Status</label>
                                    <input type="hidden" id="status_section_03" name="status_section_03" value=""  />
                                </div>
                                <div class="questionair_radio_container">
                                    <input type="radio" name="status_section_03" id="status_section_03_enabled"  value="1" <?php echo ( isset($section_03_meta['status']) && intval($section_03_meta['status']) == 1 ? 'checked="checked"' : '');?> />
                                    <label for="status_section_03_enabled">
                                        Enabled
                                    </label>
                                </div>
                                <div class="questionair_radio_container">
                                    <input type="radio" name="status_section_03" id="status_section_03_disabled"  value="0" <?php echo ( isset($section_03_meta['status']) && intval($section_03_meta['status']) == 0 ? 'checked="checked"' : '');?> />
                                    <label for="status_section_03_disabled">
                                        Disabled
                                    </label>
                                </div>

                            </li>
                            <li class="form-col-100 autoheight">
                                <input id="btn_about_us_save" type="submit" class="submit-btn" value="Save" onclick="fValidateConfigsection03();" />
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
            <div>
                <h2>Image</h2>
                <div class="well well-sm">
                    <img class="img-responsive"
                         src="<?php echo AWS_S3_BUCKET_URL; echo (isset($section_03_meta['image']) ? $section_03_meta['image'] : '' ); ?>"
                         alt="<?php echo (isset($section_03_meta['image']) ? $section_03_meta['image'] : '' ); ?>"/>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="universal-form-style-v2">
                            <form method="post" id="form_save_image_section_03" enctype="multipart/form-data">
                                <!-- Required Hidden Fields Start -->
                                <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                <input type="hidden" name="perform_action" id="perform_action" value="save_image_section_03"/>
                                <input type="hidden" id="page_name" name="page_name" value="home" />
                                <!-- Required Hidden Fields end -->


                                <div class="form-group">
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file" id="selected_file_image_section_03" name="selected_file_image_section_03">No file selected</span>
                                        <input onchange="fUpdateOnChangeStatic(this);" type="file" name="image_section_03" />
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                    <p class="help-block text-right">Image Dimensions : W 1400px X H 900px.</p>
                                </div>
                                <div class="btn-panel">
                                    <button type="button"
                                            class="delete-all-btn active-btn"
                                            onclick="fSaveImagesection03();">
                                        <i class="fa fa-refresh"></i>&nbsp;Update
                                    </button>
                                    <button
                                        data-pageid=""
                                        data-banner="about_us"
                                        data-page="home"
                                        data-def_image="tab1-0Atpg.jpg"
                                        data-theme="<?php echo $theme['theme_name']; ?>"
                                        type="button" class="delete-all-btn active-btn" onclick="fRestoreDefaultImageForSection(this, 3);" ><i class="fa fa-reply"></i>&nbsp;Restore Default Banner</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        CKEDITOR.replace('content_section_03');
    });
    
    function fValidateConfigsection03(){
        $('#form_save_config_section_03').validate({
            ignore: [],
            rules : {
                title_section_03 : {
                    required : true
                },
                content_section_03 : {
                    required : true
                }
            },
            messages: {
                title_section_03: {
                    required: 'Title is required',
                },
                content_section_03: {
                    required: 'Section content is required',
                }
            }
        });
    }

    function fValidateImagesection03(){
        $('#form_save_image_section_03').validate({
           rules : {
               image_section_03: {
                   extension: 'jpg|jpeg|jpe|png'
               }
           }
        });
    }
    function fSaveImagesection03(){
        fValidateImagesection03();

        if($('#form_save_image_section_03').valid()){
            $('#form_save_image_section_03').submit();
        }
    }
</script>
