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
                                        <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/notification_emails') . '/' . $company_sid; ?>"><i class="fa fa-long-arrow-left"></i> Notification Emails Management</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <h1 class="page-title"><?php echo $company_name; ?></h1>
                                                    <a href="<?php echo base_url('manage_admin/notification_emails/add_contact') . '/' . $notification_type . '/' . $company_sid; ?>" class="site-btn pull-right"> + Add New Notification Email</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                <div class="pull-left">

                                                                    <h4 style="margin-top: 6px; margin-bottom: 0; font-size: 18px;" ><strong>Send Notification Emails: </strong> <span class="<?php echo ($current_notification_status == 1 ? 'Paid' : 'Unpaid'); ?>" style="font-size: 18px;"><?php echo ($current_notification_status == 1 ? 'Enabled' : 'Disabled'); ?></span></h4>

                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <form action="<?php echo current_url(); ?>" id="form_set_notifications_status" method="post" enctype="multipart/form-data">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="set_notifications_status" />
                                                                    <input type="hidden" id="notifications_status" name="notifications_status" value="<?php echo ($current_notification_status == 1 ? 0 : 1); ?>" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <?php if($current_notification_status == 1) { ?>
                                                                        <button type="button" onclick="func_set_notifications_status('Disable', '<?php echo $title_for_js_dialog; ?>'); " class="site-btn btn-danger btn-block">Disable Notifications</button>
                                                                    <?php } else { ?>
                                                                        <button type="button" onclick="func_set_notifications_status('Enable', '<?php echo $title_for_js_dialog; ?>');" class="site-btn btn-block">Enable Notifications</button>
                                                                    <?php } ?>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <h1 class="hr-registered">Notification Contacts</h1>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th>Contact Name</th>
                                                                    <th>Short Description</th>
                                                                    <th>Email Address</th>
                                                                    <!--<th>Phone Number</th>-->
                                                                    <th class="text-center">Status</th>
                                                                    <th colspan="2" class="text-center">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if($emails_count > 0) { ?>
                                                                    <?php foreach ($emails as $email) { ?>
                                                                        <tr id="<?php echo $email['sid']; ?>">
                                                                            <td><?php echo $email['contact_name']; ?></td>
                                                                            <td><?php echo $email['short_description']; ?></td>
                                                                            <td><?php echo $email['email']; ?></td>
                                                                            <!--<td><?php echo $email['contact_no']; ?></td>-->
                                                                            <td class="text-center"><?php echo ($email['status'] == 'active' ? '<span class="Paid">Active</span>' : '<span class="Unpaid">In-Active</span>'); ?></td>
                                                                            <td class="text-center">
                                                                                <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo base_url('manage_admin/notification_emails/edit_contact') . '/' . $email['sid'] . '/' . $notification_type . '/' . $company_sid; ?>" class="btn btn-success btn-sm" title="Edit Contact">
                                                                                    <i class="fa fa-pencil"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm" title="Remove Contact" onclick="remove_contact(<?php echo $email['sid'] ?>)"><i class="fa fa-times"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr><td colspan="5">No emails found.</td></tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
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
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function func_set_notifications_status(status, notifications) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to "' + status + '" "' + notifications + '" ?',
            function () {
                $('#form_set_notifications_status').submit();
            }, function () {
                alertify.warning('Cancelled!');
            });
    }

    function remove_contact(id){
        url = "<?= base_url() ?>manage_admin/notification_emails/ajax_responder";
        alertify.confirm('Confirmation', "Are you sure you want to remove this contact?",
                function () {
                    $.post(url, {perform_action: 'delete_contact', sid: id})
                            .done(function (data) {
                                $('#' + id).hide();
                                alertify.success('Contact removed successfully');
                            });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }


    $(document).ready(function () {
        $('[data-toggle=tooltip]').tooltip();
    });
</script>