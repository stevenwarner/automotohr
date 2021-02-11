<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">Section Five <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Textual Content</h2>
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_save_config_section_05" enctype="multipart/form-data" method="post" >

                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_config_section_05"/>
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->


                                    <li class="form-col-100 autoheight">
                                        <label for="title_section_05">Title <span class="staric">*</span></label>
                                        <input class="invoice-fields" type="text" name="title_section_05"  id="title_section_05" value="<?php echo (isset($section_05_meta['title']) && $section_05_meta['title'] != '' ? $section_05_meta['title'] : '' ); ?>"/>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <div>
                                            <label for="status_section_05">Status</label>
                                            <input type="hidden" id="status_section_05" name="status_section_05" value=""  />
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_05" id="status_section_05_enabled"  value="1" <?php echo ( isset($section_05_meta['status']) && intval($section_05_meta['status']) == 1 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_05_enabled">
                                                Enabled
                                            </label>
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_05" id="status_section_05_disabled"  value="0" <?php echo ( isset($section_05_meta['status']) && intval($section_05_meta['status']) == 0 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_05_disabled">
                                                Disabled
                                            </label>
                                        </div>

                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <input id="btn_about_us_save" type="button" class="submit-btn" value="Save" onclick="fSaveConfigsection05();" />
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <legend>Partners</legend>
                        <div class="col-lg-12"><h2 class="add-new-partner">Add New Partner</h2></div>
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_add_new_partner"
                                      method="post" id="form_add_dialog"
                                      enctype="multipart/form-data">

                                    <input type="hidden"
                                           name="theme_name"
                                           id="theme_name"
                                           value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden"
                                           name="page_name"
                                           id="page_name" value="home"/>

                                    <input type="hidden"
                                           name="perform_action"
                                           id="perform_action"
                                           value="save_partner"/>

                                    <li class="form-col-100 autoheight">
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                            <label
                                                for="txt_about_us_heading">Partner Title<span class="staric">*</span></label>
                                        </div>
                                        <div class="col-6g-7 col-md-7 col-xs-12 col-sm-12">
                                            <div class="form-group demo2">

                                                <input
                                                    class="invoice-fields"
                                                    id="txt_partner_name"
                                                    name="txt_partner_name"
                                                    type="text"/>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                            <label
                                                for="txt_about_us_heading">Partner Url</label>
                                        </div>
                                        <div class="col-6g-7 col-md-7 col-xs-12 col-sm-12">
                                            <div class="form-group demo2">

                                                <input
                                                    class="invoice-fields"
                                                    id="txt_partner_url"
                                                    name="txt_partner_url"
                                                    type="text"/>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                            <label for="txt_about_us_text">Partner Logo<span class="staric">*</span></label>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                            <div class="upload-file invoice-fields">
                                                <span class="selected-file" id="selected_file_file_partner_logo" name="selected_file_file_partner_logo">No file selected</span>
                                                <input onchange="fUpdateOnChangeStatic(this);" id="file_partner_logo" name="file_partner_logo" type="file" />
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                            <p class="help-block  text-right">Image Dimensions 000 x 000.</p>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12"></div>
                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                            <div class="btn-panel">
                                                <button type="button" class="submit-btn" onclick="fSaveAddNewPartnerForm();">
                                                    <i class="fa fa-plus"></i>&nbsp;Add
                                                    New
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </form>
                            </ul>
                        </div>

                        <form id="form-trash">
                            <input type="hidden" name="theme_name"
                                   id="theme_name"
                                   value="<?php echo $theme['theme_name']; ?>"/>
                            <input type="hidden" name="page_name"
                                   id="page_name" value="home"/>
                            <input type="hidden"
                                   name="perform_action"
                                   id="perform_action"
                                   value="delete_partner"/>
                        </form>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th class="col-xs-1">Sr. #</th>
                                        <th class="col-xs-2">Name</th>
                                        <th class="col-xs-6">Url</th>
                                        <th class="col-xs-2">Logo</th>
                                        <th class="col-xs-1"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($partners as $partner): ?>
                                        <tr id="row-<?php echo $count; ?>">
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $partner['txt_partner_name']; ?></td>
                                            <td><?php echo $partner['txt_partner_url']; ?></td>
                                            <td class="partner-logo"><img
                                                    src="<?php echo AWS_S3_BUCKET_URL . $partner['file_partner_logo']; ?>"
                                                    alt="<?php echo $partner['file_partner_logo']; ?>"/>
                                            </td>
                                            <td class="partner-table-btn">


                                                <a href="javascript:;" class="action-btn remove clone-job" id="trash-<?php echo $count; ?>"
                                                   onclick="fDeletePartner(<?php echo $count - 1; ?>, this);"
                                                   data-row="<?php echo $count; ?>">
                                                    <i class="fa fa-remove"></i>
                                                    <span class="btn-tooltip">Delete</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fValidateConfigsection05(){
        $('#form_save_config_section_05').validate({
            rules : {
                title_section_05 : {
                    required : true
                }

            }
        });
    }

    function fSaveConfigsection05(){
        fValidateConfigsection05();
        if($('#form_save_config_section_05').valid()){
            $('#form_save_config_section_05').submit();
        }
    }

</script>