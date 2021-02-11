<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/blocked_applicants'); ?>" class="btn black-btn pull-right">Back</a>
                                    </div>
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                    <form id="form_add_blocked_applicant" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_applicant" />
                                        <div class="field-row field-row-autoheight">
                                            <label for="applicant_email">Applicant Email</label>
                                            <input data-rule-email="true" data-rule-required="true" id="applicant_email" name="applicant_email" value="" class="hr-form-fileds" type="email">
                                        </div>

                                        <div class="field-row field-row-autoheight">
                                            <input type="submit" value="Block E-Mail" onclick="return validate_form()" class="btn btn-success">
                                            <a href="<?php echo base_url('manage_admin/blocked_applicants'); ?>" class="btn black-btn">Cancel</a>
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
<script>
    
    function validate_form() {
        $("#form_add_blocked_applicant").validate({
            ignore: ":hidden:not(select)",
            rules: {
                applicant_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                applicant_email: {
                    required: 'Email address is required!',
                    email: 'Email is not valid'
                }
            },
            submitHandler: function (form) {
                form.submit();

            }
        });
    }
</script>
