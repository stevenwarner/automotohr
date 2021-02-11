<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php //$this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-success"><i class="fa fa-angle-left"></i> Dashboard</a>
                    <!--<a href="<?php echo base_url('dashboard/login_credentials'); ?>" class="btn btn-success"><i class="fa fa-edit"></i> Change Credentials</a>-->
                    <a href="<?php echo $back_url; ?>/login_credentials" class="btn btn-success"><i class="fa fa-unlock-alt"></i> Login Credentials</a>
                </div>
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $page_title; ?></h2>
                </div>
                <div class="form-wrp">
                    <form id="form_applicant_information" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_profile" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">First Name: <span class="required">*</span></label>
                                    <input type="text" name="first_name" value="<?php echo set_value(first_name, $executive_user['first_name']); ?>" class="form-control valid" id="first_name" required="required">
                                    <?php echo form_error('first_name'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name: <span class="required">*</span></label>
                                    <input type="text" name="last_name" value="<?php echo set_value(last_name, $executive_user['last_name']); ?>" class="form-control valid" id="last_name" required="required">
                                    <?php echo form_error('last_name');?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email: <span class="required">*</span></label>
                                    <input type="email" name="email" value="<?php echo set_value(email, $executive_user['email']); ?>" class="form-control valid" id="email" required="required">
                                    <?php echo form_error('email'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="alternative_email">Alternative Email:</label>                                    
                                    <input type="email" name="alternative_email" value="<?php echo set_value(alternative_email, $executive_user['alternative_email']); ?>" class="form-control valid" id="alternative_email">
                                     <?php echo form_error('alternative_email'); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="job_title">Job Title:</label>                                    
                                    <input type="text" name="job_title" value="<?php echo set_value(job_title, $executive_user['job_title']); ?>" class="form-control valid" id="job_title">
                                    <?php echo form_error('job_title'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="direct_business_number">Direct Business Number:</label>                                    
                                    <input type="text" name="direct_business_number" value="<?php echo set_value(direct_business_number, $executive_user['direct_business_number']); ?>" class="form-control valid" id="direct_business_number">
                                    <?php echo form_error('direct_business_number'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="Cell Number">Cell Number:</label>                                    
                                    <input type="text" name="cell_number" value="<?php echo set_value(cell_number, $executive_user['cell_number']); ?>" class="form-control valid" id="cell_number">
                                    <?php echo form_error('cell_number'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="profile_picture">Profile Picture:</label>
                                    <div class="choose-file-wrp">
                                        <input name="profile_picture" id="profile_picture" onchange="check_file_all('profile_picture')" accept="image/*" type="file" class="choose-file">
                                    </div>
                                    <?php echo form_error('profile_picture'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="video">Video URL:</label>
                                    <input type="text" name="video" value="<?php echo set_value('video', $executive_user['video']); ?>" class="form-control" id="video">
                                    <?php echo form_error('video'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="btn-wrp full-width text-right">
                            <div class="form-group">
                                <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">Cancel</a>
                                <input class="btn btn-success" value="Update" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
//    $(document).ready(function(){
//        $('form').validate();
//    });

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#form_applicant_information").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true
                },
                video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                first_name:{
                    required: 'First Name is required'
                },
                last_name:{
                    required: 'Last Name is required'
                },
                email:{
                    required: 'Email is required'
                },
                video:{
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });


</script>