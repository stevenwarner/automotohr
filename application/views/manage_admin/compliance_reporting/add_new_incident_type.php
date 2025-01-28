<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/reports/compliance_reporting/incident_list') ?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" id="add_type">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><?= $form == 'add' ? 'New Incident Type' : $name; ?></h1>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Incident Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('incident_name', set_value('incident_name', $name), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('incident_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds">
                                                            <option value="0" <?= !$status ? 'selected="selected"' : '' ?>>In Active</option>
                                                            <option value="1" <?= $status ? 'selected="selected"' : '' ?>>Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Code</label>
                                                        <?php echo form_input('code', set_value('code', $code), 'class="hr-form-fileds"'); ?>

                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Priority </label>
                                                        <?php echo form_input('priority', set_value('priority', $priority), 'class="hr-form-fileds"'); ?>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="description-editor">
                                                        <label>Description /label>
                                                            <div id="editor1">
                                                                <textarea class="editor" id="editor" name="description" rows="8" cols="60" required>
                                                                <?php echo set_value('description', $ins); ?>
                                                            </textarea>
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <input type="hidden" value="<?= $form ?>">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" value="<?= $form == 'add' ? 'Add' : 'Update' ?>" name="form-submit">

                                                </div>
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
</div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: '<?= base_url('manage_admin/reports/compliance_reporting/ckImageUpload') ?>',
            },
            toolbar: [
                'heading', '|',
                'bulletedList', 'numberedList', 'blockQuote', 'insertTable',
                'image', 'horizontalLine',
                'video', 'audio', 'imageUpload', 'mediaEmbed',
                'outdent', 'indent',
                'subscript', 'superscript',
                'bold', 'italic', 'horizontalLine', 'link', 'undo', 'redo'
            ],
        })
        .catch(error => {
            console.error(error);
        });
</script>