<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-10">Title</th>
                        <th class="col-xs-2 text-center" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($getting_started_sections)) { ?>
                        <?php foreach ($getting_started_sections as $getting_started_section) { ?>
                            <tr>
                                <td>
                                    <?php echo $getting_started_section['section_title']; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-block" onclick="func_edit_getting_started_section(<?php echo $getting_started_section['sid']; ?>);">Edit</button>
                                </td>
                                <!--<td>
                                    <form id="form_delete_getting_started_section_<?php /*echo $getting_started_section['sid']; */?>" enctype="multipart/form-data" method="post" action="<?php /*echo current_url(); */?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_getting_started_section" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php /*echo $company_sid; */?>" />
                                        <input type="hidden" id="getting_started_section_sid" name="getting_started_section_sid" value="<?php /*echo $getting_started_section['sid']; */?>" />
                                        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_delete_getting_started_section(<?php /*echo $getting_started_section['sid']; */?>);">Delete</button>
                                    </form>
                                </td>-->
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Sections</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--
<hr />
<div class="row" id="add_new_location_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Getting Started Section
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_new_getting_started_section" enctype="multipart/form-data" method="post" action="<?php /*echo current_url(); */?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_getting_started_section" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php /*echo $company_sid; */?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <?php /*$field_id = 'section_title'; */?>
                                <?php /*echo form_label('Title:', $field_id); */?>
                                <?php /*echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); */?>
                                <?php /*echo form_error($field_id); */?>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php /*$field_id = 'section_content'; */?>
                                <?php /*echo form_label('Content:', $field_id); */?>
                                <?php /*echo form_textarea($field_id, '', 'class="invoice-fields autoheight ckeditor" id="' . $field_id . '"'); */?>
                                <?php /*echo form_error($field_id); */?>
                            </li>
                            <!--<li class="form-col-100 autoheight">
                                <div class="well well-sm">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/ePbKGoIGAXY"></iframe>
                                    </div>
                                </div>
                            </li>

                            <li class="form-col-50-left autoheight">
                                <label>Video Status</label>
                                <label for="section_video_status_enabled" class="control control--radio">
                                    Enabled&nbsp;
                                    <input id="section_video_status_enabled" name="section_video_status" value="1" type="radio">
                                    <div class="control__indicator"></div>
                                </label>
                                <label for="section_video_status_disabled" class="control control--radio">
                                    Disabled&nbsp;
                                    <input id="section_video_status_disabled" name="section_video_status" value="0" type="radio">
                                    <div class="control__indicator"></div>
                                </label>
                            </li>
                            <li class="form-col-50-right autoheight">
                                <label>Video Source</label>
                                <label for="section_video_source_youtube" class="control control--radio">
                                    Youtube&nbsp;
                                    <input id="section_video_source_youtube" name="section_video_source" value="youtube" type="radio" checked="checked">
                                    <div class="control__indicator"></div>
                                </label>
                                <label for="section_video_source_vimeo" class="control control--radio">
                                    Vimeo&nbsp;
                                    <input id="section_video_source_vimeo" name="section_video_source" value="vimeo" type="radio">
                                    <div class="control__indicator"></div>
                                </label>
                            </li>
                            <li class="form-col-100">
                                <?php /*$field_id = 'section_video'; */?>
                                <?php /*echo form_label('Video Url:', $field_id); */?>
                                <?php /*echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); */?>
                                <?php /*echo form_error($field_id); */?>
                            </li>
                            <!--<li class="form-col-100 autoheight">
                                <div class="well well-sm">
                                    <img class="img-responsive" src="" />
                                </div>
                            </li>

                            <li class="form-col-100 autoheight">
                                <label>Image Status</label>
                                <label for="section_image_status_enabled" class="control control--radio">
                                    Enabled&nbsp;
                                    <input id="section_image_status_enabled" name="section_image_status" value="1" type="radio">
                                    <div class="control__indicator"></div>
                                </label>
                                <label for="section_image_status_disabled" class="control control--radio">
                                    Disabled&nbsp;
                                    <input id="section_image_status_disabled" name="section_image_status" value="0" type="radio">
                                    <div class="control__indicator"></div>
                                </label>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php /*$field_id = 'section_image'; */?>
                                <?php /*echo form_label('Image:', $field_id); */?>
                                <input type="file" class="form-fields" id="section_image" name="section_image" />
                                <?php /*echo form_error($field_id); */?>
                            </li>
                            <li class="form-col-100">
                                <?php /*$field_id = 'section_sort_order'; */?>
                                <?php /*echo form_label('Sort Order:', $field_id); */?>
                                <input type="number" min="0" id="section_sort_order" name="section_sort_order" class="invoice-fields" value="0"/>
                                <?php /*echo form_error($field_id); */?>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-success">Add New Section</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
-->

<script>
    function func_edit_getting_started_section(section_sid) {
        var my_request;
        my_request = $.ajax({
            url: '<?php echo base_url('onboarding/ajax_responder'); ?>',
            type: 'POST',
            data: {'perform_action': 'edit_getting_started_section', 'section_sid': section_sid}
        });

        my_request.done(function (response) {
            $('#popupmodallabel').html('Edit Getting Started Section');
            $('#popupmodalbody').html(' ');
            $('#popupmodalbody').html(response);
            $('#popupmodal').modal('toggle');
            $('.modal-dialog').css('width', '75%');
            CKEDITOR.replace('section_content');
        });
    }

    function func_delete_getting_started_section(getting_started_section_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this Section?',
                function () {
                    $('#form_delete_getting_started_section_' + getting_started_section_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    $(document).ready(function () {
        //$('#add_new_location_form').hide();
    });
</script>