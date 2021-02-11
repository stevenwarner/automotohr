<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>"><i class="fa fa-chevron-left"></i>Group Management Listing</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-users"></i>
                                            <?php echo !isset($group) ? 'Add a Document Group' : 'Modify a Document Group'; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily create document groups for your Employees</p>
                                    <div class="form-wrp">
                                        <form id="form_new_document_group" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="<?php echo $perform_action; ?>" />
                                            <input type="hidden" name="ip_address" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />

                                            <div class="form-group autoheight">
                                                <label for="name">Name<span class="staric">*</span></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo isset($group['name']) ? $group['name'] : ''; ?>">
                                                <?php echo form_error('name'); ?>
                                            </div>
                                            <div class="form-group autoheight">
                                                <?php $description = isset($group['description']) ? html_entity_decode($group['description']) : ''; ?>
                                                <label for="description">Description</label>
                                                <textarea name="description" cols="40" rows="10" class="form-control ckeditor" style="visibility: hidden; display: none;"><?php echo $description; ?></textarea>
                                                <?php echo form_error('description'); ?>
                                            </div>  
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <?php $status = isset($group['status']) ? $group['status'] : 1; ?>
                                                    <div class="col-lg-12 mb-2"><label>Status</label></div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Active
                                                            <input type="radio" name="status" value="1" <?php echo $status == 1 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Inactive
                                                            <input type="radio" name="status" value="0" <?php echo $status == 0 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group autoheight">
                                                <label>Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="<?php echo isset($group['sort_order']) ? $group['sort_order'] : ''; ?>">
                                            </div>
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();"><?php echo $submit_button_text; ?></button>
                                                        <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn black-btn">Cancel</a>
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
<script>
    function validate_form() {
        $("#form_new_document_group").validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Group name is required',
                }
            },
            submitHandler: function (form) {
                form.submit();   
            }
        });
    }
</script>
