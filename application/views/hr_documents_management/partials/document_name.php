<div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
    <div class="upload-new-doc-heading">
        <i class="fa fa-file-text-o"></i>
        <?php echo $title; ?>
    </div>
    <p class="upload-file-type">Upload a .pdf, .pptx, .ppt, .doc or .docx to distribute to your Employees or Onboarding Candidates.</p>
    <form id="form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
        <input type="hidden" name="document_url" id="add_specific_doc_url" />
        <input type="hidden" name="document_name" id="add_specific_doc_name" />
        <input type="hidden" name="document_extension" id="add_specific_doc_extension" />
        <?php $field_id = 'sid'; ?>
        <?php $stored_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
        <?php if (isset($document_info[$field_id])) { ?>
            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document_info[$field_id]; ?>" />
            <input type="hidden" id="perform_action" name="perform_action" value="update_document" />
            <input type="hidden" id="type" name="type" value="uploaded" />
        <?php } else { ?>
            <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <label>Document Name<span class="staric">*</span></label>
                <input type="text" name="document_title" value="<?php
                                                                if (isset($document_info['document_title'])) {
                                                                    echo set_value('document_title', $document_info['document_title']);
                                                                } else {
                                                                    echo set_value('document_title');
                                                                } ?>" class="invoice-fields">
                <?php echo form_error('document_title'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <label>Instructions / Guidance </label>
                <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                <textarea class="invoice-fields autoheight ckeditor" name="document_description" onkeyup="check_length()" id="document_description" cols="54" rows="6">
                                                <?php
                                                if (isset($document_info['document_description'])) {
                                                    $desc = strip_tags(html_entity_decode($document_info['document_description']));
                                                    echo set_value('document_description', $desc);
                                                } else {
                                                    echo set_value('document_description');
                                                } ?>
                                            </textarea>
            </div>
        </div>