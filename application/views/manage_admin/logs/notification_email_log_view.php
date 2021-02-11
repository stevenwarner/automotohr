<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="20%"><b>Sender</b></td>
                                                        <td><?php echo $log["sender"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="20%"><b>Receiver</b></td>
                                                        <td><?php echo $log["receiver"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Date</b></td>
                                                        <td><?php echo date_with_time($log["sent_date"]); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Subject</b>
                                                        <td> <?php echo $log["subject"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Message</b>
                                                        <td> <?php echo $log["message"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="msg_setting">
                                                                <a class="hr-edit-btn" title="Back" href="<?php echo base_url('manage_admin/notification_email_log'); ?>">Back</a>
                                                            </p>
                                                        </td>
                                                    </tr>
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