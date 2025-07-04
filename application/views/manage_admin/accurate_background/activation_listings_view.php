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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <form name="users_form" method="post">
                                        <div class="hr-box-header">
                                            <div class="hr-items-count">
                                                <strong><?php echo count($activation_orders); ?></strong> Activation Requests
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive table-outer">
                                        <div class="hr-displayResultsTable">
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th><a>Company Name</a></th>
                                                            <th><a>Activated By</a></th>
                                                            <th><a>Date Applied</a></th>
                                                            <th  colspan="3"><a>Action</a></th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <!--All records-->
                                                        <?php foreach ($activation_orders as $key => $value) { ?>
                                                            <tr>
                                                                <td><a><b><?php echo $value['CompanyName'] ?></b></a></td>
                                                                <td><a><b><?php echo $value['first_name'] ?> <?php echo $value['last_name'] ?></b></a></td>
                                                                <td><a><b><?php echo date_with_time($value['date_applied']) ?></b></a></td>
                                                                <td colspan="3">
                                                                    <a href="<?php echo base_url('manage_admin/accurate_background/manage_document/'. $value['order_sid']); ?>" class="hr-edit-btn" title="Manage Your Document">
                                                                       <i class="fa fa-eye"></i>
                                                                    </a>
                                                                    <?php if ($value['background_check'] == 0) { ?>
                                                                        <input class="hr-edit-btn" type="button"  id="<?= $value["parent_sid"] ?>" onclick="return todo('activate', this.id)"  value="Activate">
                                                                    <?php } else { ?>
                                                                        <input class="hr-delete-btn" type="button"  id="<?= $value["parent_sid"] ?>" onclick="return todo('deactivate', this.id)"  value="Deactivate">
                                                                    <?php } ?>

                                                                    <?php if ($value['document_request'] == 0) { ?>
                                                                        <input class="hr-edit-btn" type="button"  id="<?= $value["order_sid"] ?>" onclick="return request_document('request_document', this.id)"  value="Request Document">
                                                                    <?php } else { ?>
                                                                        <input class="hr-delete-btn" type="button"  id="<?= $value["order_sid"] ?>" onclick="return cancel_request_document('cancel_request_document', this.id)"  value="Cancel Document Request">
                                                                    <?php } ?>
                                                                    <input class="hr-delete-btn" type="button"  id="<?= $value["order_sid"] ?>" onclick="return send_to_AB('send_to_AB',this.id)"  value="Send To AB">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="execute" value="multiple_action">
                                                <input type="hidden" id="type" name="type" value="company">
                                            </form>
                                        </div>
                                    </div>
                                    <form name="users_form" method="post">
                                        <div class="hr-box-header hr-box-footer">
                                            <div class="hr-items-count">
                                                <strong><?php echo count($activation_orders); ?></strong> Orders
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
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/accurate_background/accurate_background_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Company for Accurate Background Checks?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                location.reload();
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }


    function request_document(action, id) {
        url = "<?= base_url() ?>manage_admin/accurate_background/accurate_background_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to Request document from this Company?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                location.reload();
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }

    function cancel_request_document(action, id) {
        url = "<?= base_url() ?>manage_admin/accurate_background/accurate_background_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to cancel document  from this Company?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                location.reload();
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }

    function send_to_AB(action, id) {
        url = "<?= base_url() ?>manage_admin/accurate_background/accurate_background_tasks";
        alertify.confirm('Confirmation', "Are you sure you want to send this Company's document to Accurate Background Team?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                location.reload();
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>