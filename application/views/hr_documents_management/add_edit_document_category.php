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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_category_management'); ?>"><i class="fa fa-chevron-left"></i>Category Management Listing</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-users"></i>
                                            <?php echo !isset($category) ? 'Add Document Category' : 'Modify Document Category'; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily create document categories for your Employees</p>
                                    <div class="form-wrp">
                                        <form id="form_new_document_category" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="<?php echo $perform_action; ?>" />
                                            <input type="hidden" name="ip_address" value="<?php echo getUserIP(); ?>" />
                                            <?php 
                                                $is_default = "";
                                                //
                                                if ($category['default_category_sid'] != 0 || $category['is_default'] == 1 ) {
                                                    $is_default = "readonly";
                                                }
                                            ?>

                                            <div class="form-group autoheight">
                                                <label for="name">Name<span class="staric">*</span></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo isset($category['name']) ? $category['name'] : ''; ?>" <?php echo $is_default; ?>>
                                                <?php echo form_error('name'); ?>
                                            </div>
                                            <div class="form-group autoheight">
                                                <?php $description = isset($category['description']) ? html_entity_decode($category['description']) : ''; ?>
                                                <label for="description">Description</label>
                                                <textarea name="description" cols="40" rows="10" class="form-control ckeditor" style="visibility: hidden; display: none;" <?php echo $is_default; ?>><?php echo $description; ?></textarea>
                                                <?php echo form_error('description'); ?>
                                            </div>  
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <?php $status = isset($category['status']) ? $category['status'] : 1; ?>
                                                    <div class="col-lg-12 mb-2"><label>Status</label></div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Active
                                                            <input type="radio" name="status" value="1" <?php echo $status == 1 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4">
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
                                                <input type="number" name="sort_order" class="form-control" value="<?php echo isset($category['sort_order']) ? $category['sort_order'] : $categories_count; ?>" <?php echo $is_default; ?>>
                                            </div>
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();"><?php echo $submit_button_text; ?></button>
                                                        <a href="<?php echo base_url('hr_documents_management/documents_category_management'); ?>" class="btn black-btn">Cancel</a>
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
        $("#form_new_document_category").validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Category name is required',
                }
            },
            submitHandler: function (form) {
                form.submit();   
            }
        });
    }
</script>