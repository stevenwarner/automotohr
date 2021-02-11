<?php
$full_name = '';
$email = '';
$contact_number = '';
$address = '';
$paypal_email = '';
$company_name = '';
$zip_code = '';
$method_of_promotion = '';
$list_of_emails = '';
$notes = '';
$profile_picture_url = 'assets/images/default_pic.jpg';

if (isset($affiliate_user) && !empty($affiliate_user)) {
    $full_name = (!empty($affiliate_user['full_name'])) ? $affiliate_user['full_name'] : '';
    $email = (!empty($affiliate_user['email'])) ? $affiliate_user['email'] : '';
    $contact_number = (!empty($affiliate_user['contact_number'])) ? $affiliate_user['contact_number'] : '';
    $address = (!empty($affiliate_user['address'])) ? $affiliate_user['address'] : '';
    $paypal_email = (!empty($affiliate_user['paypal_email'])) ? $affiliate_user['paypal_email'] : '';
    $company_name = (!empty($affiliate_user['company_name'])) ? $affiliate_user['company_name'] : '';
    $zip_code = (!empty($affiliate_user['zip_code'])) ? $affiliate_user['zip_code'] : '';
    $method_of_promotion = (!empty($affiliate_user['method_of_promotion'])) ? $affiliate_user['method_of_promotion'] : '';
    $list_of_emails = (!empty($affiliate_user['list_of_emails'])) ? $affiliate_user['list_of_emails'] : '';;
    $notes = (!empty($affiliate_user['notes'])) ? $affiliate_user['notes'] : '';
    $profile_picture_url = (!empty($affiliate_user['profile_picture'])) ? 'assets/uploaded_affiliate_profile_picture/'.$affiliate_user['profile_picture'] : 'assets/images/default_pic.jpg';
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
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 pull-right">
                    <div  class="img-thumbnail emply-picture pull-right">
                        <img style="height: 140px; width: 140px;" src="<?php echo $profile_picture_url; ?>">
                    </div>
                </div>
                <div class="form-wrp">
                    <form id="form_update_affiliate_user" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_profile" />
                        <input type="hidden" id="referred_affiliate" name="referred_affiliate" value="<?= $user_id; ?>" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">Full Name<span class="required" aria-required="true">*</span></label>
                                    <input type="text" class="form-control" name="full_name" value="<?php echo $full_name; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">Email<span class="required" aria-required="true">*</span></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" value="<?php echo $contact_number; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                                </div>
                            </div>
                            <?php if($session['affiliate_users']['parent_sid']==0){?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>PayPal E-Mail Address</label>
                                    <input type="email" class="form-control" name="paypal_email" value="<?php echo $paypal_email; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="company_name" value="<?php echo $company_name; ?>">
                                </div>
                            </div>
                            <?php }?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Zip Code</label>
                                    <input type="text" class="form-control" name="zipcode" value="<?php echo $zip_code; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Method Of Promotion (How will you get the word out to your Network?)</label>
                                    <input type="text" class="form-control" name="mathod_of_promotion" value="<?php echo $method_of_promotion; ?>">
                                </div>
                            </div>
                            <?php if($session['affiliate_users']['parent_sid']==0){?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Do you have an email list? If so, how many names?</label>
                                    <input type="number" class="form-control" name="list_of_emails" value="<?php echo $list_of_emails; ?>">
                                </div>
                            </div>
                            <?php }?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="profile_picture">Profile Picture</label>
                                    <div class="choose-file-wrp">
                                        <input name="profile_picture" id="profile_picture" onchange="check_profile_picture('profile_picture')" type="file" class="choose-file">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Notes</label>
                                    <textarea name="notes" cols="40" rows="10" class="form-control autoheight"><?php echo $notes; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                <button id="profile_update_submit" class="btn btn-primary" type="submit">Update</button>
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-danger" type="submit">Cancel</a>
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
    $('#profile_update_submit').click(function () {
       $("#form_update_affiliate_user").validate({
            ignore: [],
            rules: {
                full_name: {
                    required: true,
                },
                email:{
                    required: true,
                }
            },
            messages: {
                full_name: {
                    required: 'Please enter full name',
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

    function check_profile_picture(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format jpg/png/jpe.");
                    return false;
                } else {
                    return true;
                }    
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
</script>
