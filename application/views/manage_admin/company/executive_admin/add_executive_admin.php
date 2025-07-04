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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <span class="page-title"><i class="fa fa-users"></i>Manage Executive Admin</span>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title"><?php echo $page_title; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>First Name<span class="hr-required">*</span></label>
                                                        <?php echo form_input('first_name', set_value('first_name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Last Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('last_name', set_value('last_name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Username<span class="hr-required">*</span></label>
                                                        <?php echo form_input('username', set_value('username'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('username'); ?>
                                                    </div>
                                                </div>
<!--                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                                    <div class="field-row">-->
<!--                                                        <label>Password<span class="hr-required">*</span></label>-->
<!--                                                        --><?php //echo form_input('password', set_value('password'), 'class="hr-form-fileds"'); ?>
<!--                                                        --><?php //echo form_error('password'); ?>
<!--                                                    </div>-->
<!--                                                </div>-->
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Email<span class="hr-required">*</span></label>
                                                        <?php echo form_input('email', set_value('email'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Alternative Email</label>
                                                        <?php echo form_input('alternative_email', set_value('alternative_email'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('alternative_email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Job Title<span class="hr-required"></span></label>
                                                        <?php echo form_input('job_title', set_value('job_title'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('job_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Direct Business Number<span class="hr-required"></span></label>
                                                        <?php echo form_input('direct_business_number', set_value('direct_business_number'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('direct_business_number'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Cell Number<span class="hr-required"></span></label>
                                                        <?php echo form_input('cell_number', set_value('cell_number'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('cell_number'); ?>
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="profile_picture">Profile Picture:</label>
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_profile_picture">No file selected</span>
                                                            <input name="profile_picture" id="profile_picture" onchange="check_file_all('profile_picture')" accept="image/*" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <?php echo form_error('profile_picture'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="country">Access Level</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="access_level">
                                                                <?php if(!empty($access_levels)) { ?>
                                                                    <?php foreach($access_levels as $accessLevel) { ?>
                                                                        <option <?php echo set_select('access_level');?> value="<?php echo $accessLevel; ?>"><?php echo $accessLevel; ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('access_level'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row active-admin-status">
                                                        <?php echo form_checkbox('send', 1, TRUE); ?>
                                                        <label>Send Password Link<span class="hr-required"></span></label>
                                                        <?php echo form_error('cell_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" value="Register" name="submit">                        
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

<script type="text/javascript">

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

</script>