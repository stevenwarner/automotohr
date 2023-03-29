<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="card_div">
                        <div class="dashboard-conetnt-wrp">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('notification_emails'); ?>"><i class="fa fa-chevron-left"></i>Notification Email Management</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="pull-left">
                                        <h4>
                                            <?php echo PRIVATE_MESSAGE; ?>
                                        </h4>
                                    </div>    
                                </div>    
                            </div>
                            <div class="btn-wrp">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="panel panel-success">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                        <div class="pull-left">
                                                            <h4 style="margin-top: 6px; margin-bottom: 0;" class="text-success" style="font-size: 18px;"><strong>Send Notification Emails: </strong> <span class="<?php echo ($current_notification_status == 1 ? 'Paid' : 'Unpaid'); ?>" style="font-size: 18px;"><?php echo ($current_notification_status == 1 ? 'Enabled' : 'Disabled'); ?></span></h4>                                                          
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <form action="<?php echo current_url(); ?>" id="form_set_notifications_status" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="set_notifications_status" />
                                                            <input type="hidden" id="notifications_status" name="notifications_status" value="<?php echo ($current_notification_status == 1 ? 0 : 1); ?>" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                            <?php if($current_notification_status == 1) { ?>
                                                                <button type="button" onclick="func_set_notifications_status('Disable', '<?php echo $title_for_js_dialog; ?>'); " class="btn btn-danger btn-block">Disable Notifications</button>
                                                            <?php } else { ?>
                                                                <button type="button" onclick="func_set_notifications_status('Enable', '<?php echo $title_for_js_dialog; ?>');" class="btn btn-success btn-block">Enable Notifications</button>
                                                            <?php } ?>
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
                alertify.warning('Canceled!');
            });
    }


</script>
