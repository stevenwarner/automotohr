<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="universal-form-style-v2">
                                <p class="upload-file-type">Upload a .pdf, .doc or .docx to distribute to your employees.</p>
                                <form id="form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                    <?php $field_id = 'sid'; ?>
                                    <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                    <?php if (isset($document_info[$field_id])) { ?>
                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document_info[$field_id]; ?>" />
                                        <input type="hidden" id="perform_action" name="perform_action" value="update_document" />
                                    <?php } else { ?>
                                        <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php $field_id = 'document_name'; ?>
                                            <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                            <?php echo form_label('Document Name<span class="staric">*</span>', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $stored_value), 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php $field_id = 'document_description'; ?>
                                            <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                            <?php echo form_label('Document Description', $field_id); ?>
                                            <?php echo form_textarea($field_id, set_value($field_id, $stored_value), 'class="invoice-fields autoheight" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php $field_id = 'document_file'; ?>
                                            <?php echo form_label('Browse Document', $field_id); ?>
                                            <div class="upload-file invoice-fields">
                                                <span class="selected-file" id="name_<?php echo $field_id; ?>">No file selected</span>
                                                <input name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" onchange="check_file('pictures')" type="file" accept="application/pdf" />
                                                <a href="javascript:void(0);">Choose File</a>
                                            </div>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php $field_id = 'action_required'; ?>
                                            <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                            <label for="<?php echo $field_id; ?>" class="control control--checkbox">
                                                Action Required
                                                <input class="" <?php echo $stored_value == 1 ? 'checked="checked"' : ''; ?> type="checkbox" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="1" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <hr />
                                    <?php $field_id = 'document_original_name'; ?>
                                    <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                    <?php if (!empty($stored_value)) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Current Document File</label>
                                                <?php echo form_input($field_id, set_value($field_id, $stored_value), 'class="invoice-fields" id="' . $field_id . '" readonly'); ?>
                                                <?php if ($document_info['document_type'] == 'pdf') { ?>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-success"><?php echo isset($document_info['sid']) ? 'Update' : 'Upload'; ?></button>
                                            <a href="<?php echo base_url('documents_management'); ?>" class="btn black-btn">Cancel</a>
                                        </div>
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

<script>
    $(document).ready(function () {
        $('input[type=file]').on('change', function () {
            var fileName = $(this).val();
            var id = $(this).attr('id');

            if (fileName.length > 0) {
                var ext = fileName.split('.').pop();

                if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "xlsx" && ext != "ppt" && ext != "pptx") {
                    $("#" + id).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + id).html('<p class="red">Only (.pdf, .doc, .docx, .xlsx, .ppt, .pptx) allowed!</p>');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + id).html(original_selected_file);
                }
            }
        });
    });
</script>