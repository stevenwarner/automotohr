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
                                            <?php if (check_access_permissions_for_view($security_details, 'pending_jobs_feed')) { ?>
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <a href="<?php echo base_url('manage_admin/job_feeds_management'); ?>" class="btn btn-warning btn-block">Pending Jobs To Feeds</a>
                                                </div>
                                            <?php } ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'active_jobs_feed')) { ?>
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <a href="<?php echo base_url('manage_admin/job_feeds_management/jobs_active_on_feeds'); ?>" class="btn btn-success btn-block">Active Jobs To Feeds</a>
                                                </div>
                                            <?php } ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'refunded_jobs_feed')) { ?>
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <a href="<?php echo base_url('manage_admin/job_feeds_management/refunded_requests'); ?>" class="btn btn-info btn-block">Refunded Jobs To Feeds</a>
                                                </div>
                                            <?php } ?>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <a href="<?=base_url("indeed/authorize")?>" class="btn btn-success btn-block">Generate Indeed Access Token</a>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <strong style="color: #ffffff;">All Pending Jobs</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-1">Request Date</th>
                                                                        <th class="col-xs-2">Company</th>
                                                                        <th class="col-xs-1">Request By</th>
                                                                        <th class="col-xs-2">Job Title</th>
                                                                        <th class="col-xs-2">Product</th>
                                                                        <th class="col-xs-1">Days</th>
                                                                        <th class="col-xs-3 text-center" colspan="3">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($pending_jobs)) { ?>
                                                                        <?php foreach ($pending_jobs as $pending_job) { ?>
                                                                            <tr>
                                                                                <td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pending_job['purchased_date'])->format('m-d-Y \<\b\r\> h:i A'); ?></td>
                                                                                <td><?php echo ucwords($pending_job['CompanyName']); ?></td>
                                                                                <td><?php echo ucwords($pending_job['first_name'] . ' ' . $pending_job['last_name']); ?></td>
                                                                                <td><?php echo ucwords($pending_job['Title']); ?></td>
                                                                                <td><?php echo ucwords($pending_job['product_name']); ?></td>
                                                                                <td><?php echo $pending_job['no_of_days']; ?></td>
                                                                                <td class="col-xs-1">
                                                                                    <button type="button" onclick="func_activate_job_on_feed(<?php echo $pending_job['sid']; ?>);" id="btn_activate_job_feed" class="btn btn-success btn-block btn-sm">Activate
                                                                                    </button>
                                                                                    <form id="form_activate_job_on_feed_<?php echo $pending_job['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="activate_job_on_feed" />
                                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $pending_job['company_sid']; ?>" />
                                                                                        <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $pending_job['job_sid']; ?>" />
                                                                                        <input type="hidden" id="product_sid" name="product_sid" value="<?php echo $pending_job['product_sid']; ?>" />
                                                                                        <input type="hidden" id="no_of_days" name="no_of_days" value="<?php echo $pending_job['no_of_days']; ?>" />
                                                                                        <input type="hidden" id="jobs_to_feed_sid" name="jobs_to_feed_sid" value="<?php echo $pending_job['sid']; ?>" />
                                                                                    </form>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <button type="button" id="btn_refund_product" onclick="func_refund_product(<?php echo $pending_job['sid']; ?>);" class="btn btn-info btn-block btn-sm">Refund</button>
                                                                                    <form id="form_refund_product_<?php echo $pending_job['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="refund_product" />
                                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $pending_job['company_sid']; ?>" />
                                                                                        <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $pending_job['job_sid']; ?>" />
                                                                                        <input type="hidden" id="product_sid" name="product_sid" value="<?php echo $pending_job['product_sid']; ?>" />
                                                                                        <input type="hidden" id="no_of_days" name="no_of_days" value="<?php echo $pending_job['no_of_days']; ?>" />
                                                                                        <input type="hidden" id="jobs_to_feed_sid" name="jobs_to_feed_sid" value="<?php echo $pending_job['sid']; ?>" />
                                                                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $pending_job['employer_sid']; ?>" />
                                                                                    </form>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if ($pending_job['read_status'] == 0) { ?>
                                                                                        <form id="form_mark_as_read" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_as_read" />
                                                                                            <input type="hidden" id="pending_job_sid" name="pending_job_sid" value="<?php echo $pending_job['sid']; ?>" />

                                                                                            <button type="submit" id="btn_mark_as_read" class="btn btn-success btn-sm btn-block">Mark As Read</button>
                                                                                        </form>
                                                                                    <?php } else if ($pending_job['read_status'] == 1) { ?>
                                                                                        <form id="form_mark_as_unread" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_as_unread" />
                                                                                            <input type="hidden" id="pending_job_sid" name="pending_job_sid" value="<?php echo $pending_job['sid']; ?>" />

                                                                                            <button type="submit" id="btn_mark_as_unread" class="btn btn-warning btn-sm btn-block">Mark As Un-Read</button>
                                                                                        </form>
                                                                                    <?php } ?>
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

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <strong style="color: #ffffff;">Feeds</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Site</th>
                                                                        <th>Title</th>
                                                                        <th>Type</th>
                                                                        <th>URL</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>AutomotoSocial</td>
                                                                        <td>-</td>
                                                                        <td>-</td>
                                                                        <td><a href="http://automotosocial.com/listing-feeds/?feedId=6" target="_blank">http://automotosocial.com/listing-feeds/?feedId=6</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>ZipRecruiter</td>
                                                                        <td>Paid</td>
                                                                        <td><a href="https://www.automotohr.com/Zip_recruiter_feed" target="_blank">https://www.automotohr.com/Zip_recruiter_feed</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>ZipRecruiter</td>
                                                                        <td>Organic</td>
                                                                        <td><a href="https://www.automotohr.com/Zip_recruiter_organic" target="_blank">https://www.automotohr.com/Zip_recruiter_organic</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>Career Builder</td>
                                                                        <td>Paid</td>
                                                                        <td><a href="https://www.automotohr.com/Career_feed" target="_blank">https://www.automotohr.com/Career_feed</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>Career Builder</td>
                                                                        <td>Organic</td>
                                                                        <td><a href="https://www.automotohr.com/Career_feed_organic" target="_blank">https://www.automotohr.com/Career_feed_organic</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>Indeed</td>
                                                                        <td>Organic/Paid</td>
                                                                        <td><a href="https://www.automotohr.com/indeed_feed_new/new" target="_blank">https://www.automotohr.com/indeed_feed_new/new</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR (OLD)</td>
                                                                        <td>Indeed</td>
                                                                        <td>Organic</td>
                                                                        <td><a href="https://www.automotohr.com/indeed_feed_organic" target="_blank">https://www.automotohr.com/indeed_feed_organic</a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR (OLD)</td>
                                                                        <td>Indeed</td>
                                                                        <td>Paid</td>
                                                                        <td><a href="https://www.automotohr.com/indeed_feed" target="_blank">https://www.automotohr.com/indeed_feed</a></td>
                                                                    </tr>
                                                                    <tr class="bg-success">
                                                                        <td colspan="4">Applicant Accept URLs</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>Indeed</td>
                                                                        <td>-</td>
                                                                        <td>https://www.automotohr.com/indeed_feed/indeedPostUrl</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>AutomotoHR</td>
                                                                        <td>Indeed</td>
                                                                        <td>-</td>
                                                                        <td>https://www.automotohr.com/Zip_recruiter_organic/zipPostUrl</td>
                                                                    </tr>
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
<script>
    function func_refund_product(pending_job_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to refund this product?',
            function() {
                $('#form_refund_product_' + pending_job_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_activate_job_on_feed(pending_job_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this Job on Feed?',
            function() {
                $('#form_activate_job_on_feed_' + pending_job_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }
</script>