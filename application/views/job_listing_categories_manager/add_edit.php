<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- manage_employer/profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?></span>
                    </div>
                    <div class="compose-message">
                        <div class="universal-form-style-v2">
                            <ul>
                                <?php echo form_open('', array('enctype' => 'multipart/form-data', 'method' => 'post', 'id' => 'job_listing_category')); ?>
                                <input type="hidden" id="action" name="action" />
                                <input type="hidden" id="sid" name="sid"  value="<?php echo (!empty($categoryInfo) ? $categoryInfo['sid'] : '' )?>"/>
                                <li class="form-col-100 autoheight">
                                    <?php $categoryName = (!empty($categoryInfo) ? $categoryInfo['value'] : '' ); ?>
                                    <?php echo form_label('Category Name', 'category');?>
                                    <?php echo form_input(array('class' => 'invoice-fields', 'id' => 'category', 'name' => 'category'), set_value('category', $categoryName)); ?>
                                    <?php echo form_error('category'); ?>
                                </li>                                
                                <li class="form-col-100 autoheight">
                                    <?php   $this->load->helper('url');
                                            $buttonText = '';
                                            if($this->uri->segment(2) == 'add'){
                                                $buttonText = 'Create New Category';
                                            } else if($this->uri->segment(2) == 'edit'){
                                                $buttonText = 'Update Category';
                                            } ?>
                                    <input type="submit" value="<?php echo $buttonText; ?>" onclick="return validate_form()"class="submit-btn">
                                    <input type="button" value="Cancel" onClick="document.location.href = '<?= base_url('job_listing_categories') ?>'" class="submit-btn btn-cancel"/>
                                </li>
                                <?php echo form_close();?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function validate_form() {
        $("#job_listing_category").validate({
            ignore: ":hidden:not(select)",
            rules: {
                category: {
                    required: true,
                    lettersonly: true
                }
            },
            messages: {
                category: {
                    required: 'Category Name is required!'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
      
    $(document).ready(function(){
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Digits not allowed!");
    });
</script>