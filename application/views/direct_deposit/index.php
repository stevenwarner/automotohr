<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}
//
//$uri_segment_01 = strtolower($this->uri->segment(1));
//
//if($uri_segment_01 == 'my_profile' ||
//    $uri_segment_01 == 'login_password' ||
//    $uri_segment_01 == 'incident_reporting_system' ||
//    $uri_segment_01 == 'my_events' ||
//    $uri_segment_01 == 'direct_deposit'
//) {
//    $load_view = 'new';
//}

?>
<?php if (!$load_view) { ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                        <div class="dashboard-conetnt-wrp">
                            <form id="form_update_bank_details" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="update_bank_details" />
                                <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                <div class="universal-form-style-v2">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-col-100">
                                            <?php $field_id = 'account_title'; ?>
                                            <?php $temp = isset($direct_deposit_information[$field_id]) && !empty($direct_deposit_information[$field_id]) ? $direct_deposit_information[$field_id] : ''; ?>
                                            <?php echo form_label('Account Title', $field_id); ?> <span class="staric">*</span>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '" required'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-col-100">
                                            <?php $field_id = 'routing_transaction_number'; ?>
                                            <?php $temp = isset($direct_deposit_information[$field_id]) && !empty($direct_deposit_information[$field_id]) ? $direct_deposit_information[$field_id] : ''; ?>
                                            <?php echo form_label('Routing/Transaction Number', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-col-100">
                                            <?php $field_id = 'account_number'; ?>
                                            <?php $temp = isset($direct_deposit_information[$field_id]) && !empty($direct_deposit_information[$field_id]) ? $direct_deposit_information[$field_id] : ''; ?>
                                            <?php echo form_label('Checking/Saving Account Number', $field_id); ?> <span class="staric">*</span>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '" required'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-col-100">
                                            <?php $field_id = 'financial_institution_name'; ?>
                                            <?php $temp = isset($direct_deposit_information[$field_id]) && !empty($direct_deposit_information[$field_id]) ? $direct_deposit_information[$field_id] : ''; ?>
                                            <?php echo form_label('Financial Institution (Bank) Name', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row">
                                            <?php $field_id = 'account_type'; ?>
                                            <?php $temp = isset($direct_deposit_information[$field_id]) && !empty($direct_deposit_information[$field_id]) ? $direct_deposit_information[$field_id] : ''; ?>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <label>Account Type</label> <span class="staric">*</span>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-2">
                                                <?php $default_checked = $temp == 'checking'? true : false; ?>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        Checking
                                                        <input <?php echo set_radio($field_id, 'checking', $default_checked); ?> name="<?php echo $field_id; ?>" id="account_type_checking" type="radio" value="checking" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-2">
                                                <?php $default_checked = $temp == 'savings'? true : false; ?>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        Savings
                                                        <input <?php echo set_radio($field_id, 'savings', $default_checked); ?> name="<?php echo $field_id; ?>" id="account_type_savings" type="radio" value="savings" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrp full-width text-right">
                                    <input class="submit-btn" value="Save" type="submit">
                                    <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'" />
                                </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<?php } else { ?>
    <?php $this->load->view('onboarding/bank_details'); ?>
<?php } ?>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $("#form_update_bank_details").validate({
        ignore: ":hidden:not(select)",
        rules: {
            account_title: {
                required: true
            },
            account_number: {
                required: true
            }
        },
        messages: {
            account_title: {
                required: 'Account title is required'
            },
            account_number: {
                required: 'Account number is required'
            }
        },
        submitHandler: function (form) {

            var checked = $("input[name='account_type']:checked").length;
            if(!checked){
                alertify.error('Account Type is required');
                return false;
            }
            else{
                form.submit();
            }
        }
    });
</script>