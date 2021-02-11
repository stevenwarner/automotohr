<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$first_name = '';
$last_name = '';
$email = '';
$job_role = '';
$contact_number = '';
$company_name = '';
$company_size = '';
$street = '';
$city = '';
$state = '';
$zip_code = '';
$country = '';
$client_message = '';
$btn_text = 'Save';

if (isset($reffer_user_info)) {
    $reffer_user_sid = isset($reffer_user_info['sid']) ? $reffer_user_info['sid'] : '';
    $first_name = isset($reffer_user_info['first_name']) ? $reffer_user_info['first_name'] : '';
    $last_name = isset($reffer_user_info['last_name']) ? $reffer_user_info['last_name'] : '';
    $email = isset($reffer_user_info['email']) ? $reffer_user_info['email'] : '';
    $job_role = isset($reffer_user_info['job_role']) ? $reffer_user_info['job_role'] : '';
    $contact_number = isset($reffer_user_info['phone_number']) ? $reffer_user_info['phone_number'] : '';
    $company_name = isset($reffer_user_info['company_name']) ? $reffer_user_info['company_name'] : '';
    $company_size = isset($reffer_user_info['company_size']) ? $reffer_user_info['company_size'] : '';
    $street = isset($reffer_user_info['street']) ? $reffer_user_info['street'] : '';
    $city = isset($reffer_user_info['city']) ? $reffer_user_info['city'] : '';
    $state = isset($reffer_user_info['state']) ? $reffer_user_info['state'] : '';
    $zip_code = isset($reffer_user_info['zip_code']) ? $reffer_user_info['zip_code'] : '';
    $reffer_user_country = isset($reffer_user_info['country']) ? $reffer_user_info['country'] : '';
    $client_message = isset($reffer_user_info['client_message']) ? $reffer_user_info['client_message'] : '';
    $newsletter_status = isset($reffer_user_info['newsletter_subscribe']) && $reffer_user_info['newsletter_subscribe'] == 1 ? 'yes' : 'no';
    $btn_text = 'Update';
} else {
    $newsletter_status = 'yes';
}
?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><?= $title; ?></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>dashboard" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="form-wrp">
                    <form id="form_save_video" method="post" enctype="multipart/form-data" autocomplete="off">
                        <?php if (isset($reffer_user_info)) { ?>
                            <input type="hidden" id="perform_action" name="perform_action" value="edit_reffer_user" />
                            <input type="hidden" id="reffer_user_sid" name="reffer_user_sid" value="<?php echo $reffer_user_sid; ?>" />
                        <?php } else { ?>
                            <input type="hidden" id="perform_action" name="perform_action" value="add_new_reffer_user" />
                            <input type="hidden" id="referred_by" name="referred_by" value="<?= $user_id; ?>" />
                        <?php } ?>  
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>First Name<span class="required" aria-required="true">*</span></label>
                                    <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Last Name<span class="required" aria-required="true">*</span></label>
                                    <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Email<span class="required" aria-required="true">*</span></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="company_name"  value="<?php echo $company_name; ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <?php $control_id = 'company_size'; ?>
                                    <?php echo form_label('Company Size', $control_id); ?>
                                    <div class="select">
                                        <select class="form-control" name="company_size" class="form-control">
                                            <option value="" selected="selected">Select Company Size</option>
                                            <option value="1-5" <?php echo isset($reffer_user_info) && $company_size == '1-5' ? 'selected="selected"' : ''?>>1 - 5</option>
                                            <option value="6-25" <?php echo isset($reffer_user_info) && $company_size == '6-25' ? 'selected="selected"' : ''?>>6 - 25</option>
                                            <option value="26-50" <?php echo isset($reffer_user_info) && $company_size == '26-50' ? 'selected="selected"' : ''?>>26 - 50</option>
                                            <option value="51-100" <?php echo isset($reffer_user_info) && $company_size == '51-100' ? 'selected="selected"' : ''?>>51 - 100</option>
                                            <option value="101-250" <?php echo isset($reffer_user_info) && $company_size == '101-250' ? 'selected="selected"' : ''?>>101 - 250</option>
                                            <option value="251-500" <?php echo isset($reffer_user_info) && $company_size == '251-500' ? 'selected="selected"' : ''?>>251 - 500</option>
                                            <option value="501+" <?php echo isset($reffer_user_info) && $company_size == '501+' ? 'selected="selected"' : ''?>>501+</option>
                                        </select>
                                    </div>
                                    <?php echo form_error($control_id); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Job Role</label>
                                    <input type="text" class="form-control" name="job_role"  value="<?php echo $job_role; ?>">
                                    <?php echo form_error('job_role'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number"  value="<?php echo $contact_number; ?>">
                                </div>
                            </div>
                             <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <div class="select">
                                        <select class="form-control" id="country" name="country" onchange="getStates(this.value)">
                                            <option value="" selected="selected">Select Country</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <option value="<?php echo $active_country["sid"]; ?>" <?php echo isset($reffer_user_info) && $reffer_user_country == $active_country["country_name"] ? 'selected="selected"' : ''?>>
                                                    <?php echo $active_country["country_name"]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>State/Province</label>
                                    <select class="form-control" id="state" name="state_province">
                                        <?php if(isset($reffer_user_info) && $state != '') { ?>
                                            <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
                                        <?php } ?>    
                                        <option value="">Select State</option>
                                        <option value="">Please select your Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city"  value="<?php echo $city; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Street</label>
                                    <input type="text" class="form-control" name="street"  value="<?php echo $street; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Zip Code/Postal Code</label>
                                    <input type="text" class="form-control" name="zip_postal_code"  value="<?php echo $zip_code; ?>">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Your Message </label>
                                    <textarea name="client_message" cols="40" rows="10" class="form-control autoheight" id="location_address"><?php echo $client_message; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Subscribe to our weekly newsletter! </label>
                                    <label class="control control--radio" style="margin-top: 20px;">
                                        Yes
                                        <input class="newsletter_status" type="radio" name="newsletter_subscribe" value="1" <?php echo $newsletter_status == 'yes' ? 'checked="checked"' : ''; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-top: 20px; margin-left: 15px;">
                                        No
                                        <input class="newsletter_status" type="radio" name="newsletter_subscribe" value="0" <?php echo $newsletter_status == 'no' ? 'checked="checked"' : ''; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                <button id="add_edit_submit" class="btn btn-primary" type="submit"><?php echo $btn_text; ?></button>
                                <a href="<?php echo isset($reffer_user_info) ? base_url('view-referral-clients') : base_url('dashboard'); ?>" class="btn btn-danger" type="submit">Cancel</a>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="terms_and_conditions_apply_now" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading">Affiliate Program - Terms of Service</h4>
            </div>
            <div class="term-condition-content">
                <?php $this->load->view('refer_potential_clients/terms_of_service'); ?>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="privay_policy_apply_now" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading">Affiliate Program - Privacy Policy</h4>
            </div>
            <div class="term-condition-content">
                <?php $this->load->view('refer_potential_clients/privacy_policy'); ?>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
    $('#add_edit_submit').click(function () {
       $("#form_save_video").validate({
            ignore: [],
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email:{
                    required: true,
                }
            },
            messages: {
                first_name: {
                    required: 'Please enter first name',
                },
                last_name: {
                    required: 'Please enter last name',
                },
                email: {
                    required: 'Please enter valid email',
                }
            },
            submitHandler: function (form) { 
                form.submit();
            }
        });           
    });
    
    $(document).ready(function () {
        $('.file-style').filestyle({
            text: 'Upload File',
            btnClass: 'btn-info',
            placeholder: "No file selected"
        });
    }); 

    function getStates(val) {

        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please select your Country</option>');
        } else {
            var myurl = "<?= base_url() ?>refer_client_potential/getnewstates/"+val;
            $.ajax({
                type: "GET",
                url: myurl,
                async : false,
                success: function (data) {
                   var obj = jQuery.parseJSON(data);
                   $('#state').html(obj);
                }
            });
        }
    }  
</script>