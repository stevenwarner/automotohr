<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$field_country = '';
$field_state = '';
$field_city = '';
$field_zipcode = '';
$field_address = '';
$next_btn = '';
$center_btn = '';
$back_btn = 'Dashboard';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/eeoc_form/' . $unique_sid);
    $bank_details = $bank_details;
    $delete_post_url = current_url();
    $save_post_url = current_url();
    $next_btn = '<a href="javascript:;"class="btn btn-success btn-block" id="go_next"> Save And Proceed To Next <i class="fa fa-angle-right"></i></a>';
    $link = $enable_learbing_center ? base_url('onboarding/learning_center/' . $unique_sid) : base_url('onboarding/eeoc_form/' . $unique_sid);
    $center_btn = '<a href="'.$link.'" class="btn btn-warning btn-block mb-2"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $bank_details = $direct_deposit_information;
    $delete_post_url = current_url();
    $save_post_url = current_url();
} ?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>  
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h1 class="section-ttile">Direct Deposit Information</h1>
                        </div>
                        <p class="text-blue">Provide your U.S. or Canadian Bank deposit account type (checking or savings), account number and routing number, and other required information.</p>
                        <p class="text-blue"><b>Submit the completed direct deposit form to your employer.</b></p>
                        <div class="form-wrp">
                            <form id="form_update_bank_details" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="update_bank_details" />
                                <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'account_title'; ?>
                                            <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                            <?php echo form_label('Account Title', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'routing_transaction_number'; ?>
                                            <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                            <?php echo form_label('Routing/Transaction Number', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'account_number'; ?>
                                            <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                            <?php echo form_label('Checking/Saving Account Number', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'financial_institution_name'; ?>
                                            <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                            <?php echo form_label('Financial Institution (Bank) Name', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>

                                    <?php if(!empty($bank_details['voided_cheque']) && $bank_details['voided_cheque'] != NULL) { ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="well well-sm">
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL.$bank_details['voided_cheque']?>" alt="">
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Voided Check </label>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_picture">No file selected</span>
                                                <input name="picture" id="picture" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row">
                                            <?php $field_id = 'account_type'; ?>
                                            <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <label>Account Type</label>
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
                                <input type="hidden" value="<?= isset($enable_learbing_center) ? $enable_learbing_center : ''?>" name="enable_learbing_center_flag">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="btn-wrp full-width text-right">
                                        <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                                        <input class="btn btn-info" id="add_edit_submit" value="<?= empty($next_btn) ? 'Save' : 'Save And Proceed Next';?>" type="submit">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if($users_type != 'applicant') { ?>
                        <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                            <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
                        <!-- </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#go_next').click(function(){
            $('#add_edit_submit').click();
        });

        $('#picture').on('change',function() {
            var fileName = $(this).val();
            
            if (fileName.length > 0) {
                $('#name_picture').html(fileName.substring(0, 45));
            } else {
                $('#name_picture').html('No file selected');
            }
        });
    });
</script>