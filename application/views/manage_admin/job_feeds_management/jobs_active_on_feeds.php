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
                                                        <strong style="color: #ffffff;">All Active Jobs</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th rowspan="2" class="col-xs-2">Company</th>
                                                                    <th rowspan="2" class="col-xs-1">Request By</th>
                                                                    <th rowspan="2" class="col-xs-2">Job Title</th>
                                                                    <th rowspan="2" class="col-xs-2">Product</th>
                                                                    <th rowspan="2" class="col-xs-1 text-center">Days</th>
                                                                    <th colspan="4" class="text-center">Date</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Request</th>
                                                                    <th class="col-xs-1 text-center">Activation</th>
                                                                    <th class="col-xs-1 text-center">Expiration</th>
                                                                    <th class="col-xs-1 text-center">Status</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $today = new DateTime();?>
                                                                <?php if (!empty($pending_jobs)) { ?>
                                                                    <?php foreach ($pending_jobs as $pending_job) { ?>
                                                                        <tr>
                                                                            <td><?php echo ucwords($pending_job['CompanyName']); ?></td>
                                                                            <td><?php echo ucwords($pending_job['first_name'] . ' ' . $pending_job['last_name']); ?></td>
                                                                            <td><?php echo ucwords($pending_job['Title']); ?></td>
                                                                            <td><?php echo ucwords($pending_job['product_name']); ?></td>
                                                                            <td class="text-center"><?php echo $pending_job['no_of_days']; ?></td>
                                                                            <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pending_job['purchased_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                            <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pending_job['activation_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                            <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pending_job['expiry_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                            <td class="text-center"><?php echo $today->getTimestamp() > DateTime::createFromFormat('Y-m-d H:i:s', $pending_job['expiry_date'])->getTimestamp() ? '<span class="text-danger">Expired</span>' : '<span class="text-success">Active</span>'; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">
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