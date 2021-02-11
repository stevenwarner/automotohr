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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/dashboard'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'add_new_system_notification_email')) { ?>
                                            <div class="add-new-promotions">
                                                <p><strong>All System Notification Emails will go to the active email addresses added in this module.</strong></p>
                                                <a class="site-btn" href="<?php echo site_url('manage_admin/system_notification_emails/add_new_system_notification_email'); ?>">Add a New System Notification Email</a>
                                            </div>
                                    <?php } ?>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">

                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-4 text-left">Full Name</th>
                                                        <th class="col-xs-6 text-left">Email Address</th>
                                                        <?php if(check_access_permissions_for_view($security_details, 'add_new_system_notification_email')) { ?>
                                                            <th class="col-xs-2 text-center" colspan="3">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($data)) { ?>
                                                        <?php foreach ($data as $key => $value) { ?>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <?php echo $value['full_name']; ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php echo $value['email']; ?>
                                                                </td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'add_new_system_notification_email')) { ?>
                                                                    <td class="text-center">
                                                                        <form id="form_delete_email_<?php echo $value['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_email" />
                                                                            <input type="hidden" id="sid" name="sid" value="<?php echo $value['sid']; ?>" />
                                                                            <button type="button" onclick="func_delete_email(<?php echo $value['sid']; ?>);" class="btn btn-danger btn-sm">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php if($value['status'] == 1) { ?>
                                                                            <form id="form_deactivate_email_address_<?php echo $value['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="deactivate_email" />
                                                                                <input type="hidden" id="sid" name="sid" value="<?php echo $value['sid']; ?>" />
                                                                                <button type="button" onclick="func_deactivate_email(<?php echo $value['sid']; ?>);" class="btn btn-danger btn-sm">Deactivate</button>
                                                                            </form>
                                                                        <?php } else { ?>
                                                                            <form id="form_activate_email_address_<?php echo $value['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="activate_email" />
                                                                                <input type="hidden" id="sid" name="sid" value="<?php echo $value['sid']; ?>" />
                                                                                <button type="button" onclick="func_activate_email(<?php echo $value['sid']; ?>);" class="btn btn-success btn-sm">Activate</button>
                                                                            </form>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?php echo base_url('manage_admin/system_notification_emails/set_system_notification_email_configuration') . '/' . $value['sid']; ?>"  class="btn btn-success btn-sm" >Set Email Notifications</a>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">
                                                                <span class="no-data">No Email Address Added</span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
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
    </div>
</div>
<?php if(check_access_permissions_for_view($security_details, 'add_new_system_notification_email')) { ?>
<script type="text/javascript">


    function func_delete_email(sid){
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to Delete this email address?',
            function () {
                $('#form_delete_email_' + sid).submit();
            }, function () {
                alertify.error('Cancelled');
            });
    }

    function func_activate_email(sid) {
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to activate this email address?',
            function () {
                $('#form_activate_email_address_' + sid).submit();
            }, function () {
                alertify.error('Cancelled');
            });
    }

    function func_deactivate_email(sid) {
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to activate this email address?',
            function () {
                $('#form_deactivate_email_address_' + sid).submit();
            }, function () {
                alertify.error('Cancelled');
            });
    }
</script>
<?php } 