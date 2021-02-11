<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">Section Six <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseSix" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Textual Content</h2>
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_save_config_section_06" enctype="multipart/form-data" method="post" >

                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_config_section_06"/>
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->


                                    <li class="form-col-100 autoheight">
                                        <label for="title_section_06">Title <span class="staric">*</span></label>
                                        <input class="invoice-fields" type="text" name="title_section_06"  id="title_section_06" value="<?php echo (isset($section_06_meta['title']) && $section_06_meta['title'] != '' ? $section_06_meta['title'] : '' ); ?>"/>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <label for="tag_line_section_06">Tag Line</label>
                                        <input class="invoice-fields" type="text" name="tag_line_section_06"  id="tag_line_section_06" value="<?php echo (isset($section_06_meta['tag_line']) && $section_06_meta['tag_line'] != '' ? $section_06_meta['tag_line'] : '' ); ?>"/>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <div>
                                            <label for="status_section_06">Status</label>
                                            <input type="hidden" id="status_section_06" name="status_section_06" value=""  />
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_06" id="status_section_06_enabled"  value="1" <?php echo ( isset($section_06_meta['status']) && intval($section_06_meta['status']) == 1 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_06_enabled">
                                                Enabled
                                            </label>
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_06" id="status_section_06_disabled"  value="0" <?php echo ( isset($section_06_meta['status']) && intval($section_06_meta['status']) == 0 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_06_disabled">
                                                Disabled
                                            </label>
                                        </div>

                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <input id="btn_about_us_save" type="button" class="submit-btn" value="Save" onclick="fSaveConfigsection06();" />
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fValidateConfigsection06(){
        $('#form_save_config_section_06').validate({
            rules : {
                title_section_06 : {
                    required : true
                }

            }
        });
    }

    function fSaveConfigsection06(){
        fValidateConfigsection06();
        if($('#form_save_config_section_06').valid()){
            $('#form_save_config_section_06').submit();
        }
    }
</script>