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
                                        <h1 class="page-title"><i class="fa fa-list"></i>Job Feeds Management</h1>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <div style="min-height: 790px;">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <a href="<?php echo base_url('manage_admin/job_feeds_management'); ?>" class="btn btn-warning btn-block">Pending Jobs To Feeds</a>
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <a href="<?php echo base_url('manage_admin/job_feeds_management/jobs_active_on_feeds'); ?>" class="btn btn-success btn-block">Active Jobs To Feeds</a>
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <a href="<?php echo base_url('manage_admin/job_feeds_management/refunded_requests'); ?>" class="btn btn-info btn-block">Refunded Jobs To Feeds</a>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <strong style="color: #ffffff;">All Refunded Requests</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th rowspan="2" class="col-xs-1">Purchase Date</th>
                                                                    <th rowspan="2" class="col-xs-2">Company</th>
                                                                    <th rowspan="2" class="col-xs-1">Request By</th>
                                                                    <th rowspan="2" class="col-xs-2">Job Title</th>
                                                                    <th rowspan="2" class="col-xs-2">Product</th>
                                                                    <th rowspan="2" class="col-xs-1 text-center">Days</th>
                                                                    <th class="col-xs-1 text-center" colspan="2">Refund Information</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Date</th>
                                                                    <th class="col-xs-1 text-center">Invoice Number</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if (!empty($refunded_requests)) { ?>
                                                                    <?php foreach ($refunded_requests as $refunded_request) { ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $refunded_request['purchased_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                            <td><?php echo ucwords($refunded_request['CompanyName']); ?></td>
                                                                            <td><?php echo ucwords($refunded_request['first_name'] . ' ' . $refunded_request['last_name']); ?></td>
                                                                            <td><?php echo ucwords($refunded_request['Title']); ?></td>
                                                                            <td><?php echo ucwords($refunded_request['product_name']); ?></td>
                                                                            <td class="text-center"><?php echo $refunded_request['no_of_days']; ?></td>
                                                                            <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $refunded_request['refund_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                            <td class="text-center">
                                                                                <a target="_blank" href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $refunded_request['refund_invoice_sid']); ?>"><?php echo 'MP-' . str_pad($refunded_request['refund_invoice_sid'],6,0,STR_PAD_LEFT);?></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <span class="no-data">No Pending Request</span>
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
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">

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