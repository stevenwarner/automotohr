<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$first_name = '';
$last_name = '';
$email = '';
$company = '';
$street = '';
$city = '';
$state_province = '';
$zip_postal_code = '';
$website = '';
$contact_number = '';
$btn_text = 'Save';

if (isset($affiliate_user_info)) {
    $affiliate_user_sid = isset($affiliate_user_info['sid']) ? $affiliate_user_info['sid'] : '';
    $first_name = isset($affiliate_user_info['first_name']) ? $affiliate_user_info['first_name'] : '';
    $last_name = isset($affiliate_user_info['last_name']) ? $affiliate_user_info['last_name'] : '';
    $email = isset($affiliate_user_info['email']) ? $affiliate_user_info['email'] : '';
    $company = isset($affiliate_user_info['company']) ? $affiliate_user_info['company'] : '';
    $street = isset($affiliate_user_info['street']) ? $affiliate_user_info['street'] : '';
    $city = isset($affiliate_user_info['city']) ? $affiliate_user_info['city'] : '';
    $state_province = isset($affiliate_user_info['state']) ? $affiliate_user_info['state'] : '';
    $zip_postal_code = isset($affiliate_user_info['zip_code']) ? $affiliate_user_info['zip_code'] : '';
    $affiliate_user_country = isset($affiliate_user_info['country']) ? $affiliate_user_info['country'] : '';
    $contact_number = isset($affiliate_user_info['contact_number']) ? $affiliate_user_info['contact_number'] : '';
    $website = isset($affiliate_user_info['website']) ? $affiliate_user_info['website'] : '';
    $btn_text = 'Update';
} ?>

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
                        <?php if (isset($affiliate_user_info)) { ?>
                            <input type="hidden" id="perform_action" name="perform_action" value="edit_affiliate_user" />
                            <input type="hidden" id="affiliate_user_sid" name="affiliate_user_sid" value="<?php echo $affiliate_user_sid; ?>" />
                        <?php } else { ?>
                            <input type="hidden" id="perform_action" name="perform_action" value="add_new_affiliate_user" />
                            <input type="hidden" id="referred_by" name="referred_by" value="<?php echo $user_id; ?>" />
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
                                    <label>Company</label>
                                    <input type="text" class="form-control" name="company" value="<?php echo $company; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Street</label>
                                    <input type="text" class="form-control" name="street" value="<?php echo $street; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo $city; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>State/Province</label>
                                    <input type="text" class="form-control" name="state_province" value="<?php echo $state_province; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Zip Code/Postal Code</label>
                                    <input type="text" class="form-control" name="zip_postal_code" value="<?php echo $zip_postal_code; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <div class="select">
                                        <select data-placeholder="Please Select Country" name="country" id="country" class="form-control">
                                            <option class="ats_search_filter_inactive" value="">Please Select Country</option>
                                            <?php foreach($countries as $country) {?>
                                                <option <?php echo isset($affiliate_user_info) && $affiliate_user_country == $country['country_name'] ? 'selected="selected"' : ''; ?> class="ats_search_filter_inactive" value="<?php echo $country['country_name']?>" >
                                                    <?php echo $country['country_name']?>
                                                </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control" name="website" value="<?php echo $website; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number" value="<?php echo $contact_number; ?>">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                <button id="add_edit_submit" class="btn btn-primary" type="submit"><?php echo $btn_text; ?></button>
                                <a href="<?php echo isset($affiliate_user_info) ? base_url('view-referral-affiliates') : base_url('dashboard'); ?>" class="btn btn-danger" type="submit">Cancel</a>
                            </div>
                        </div>
                    </form>    
                </div>
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
</script>