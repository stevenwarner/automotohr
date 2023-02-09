<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
    <?php                       if ($company_has_job_approval_rights == 1) { ?>
                                    <div class="box-view reports-filtering">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <button id="btn-pending" class="page-heading pending-color no-margin"><span><?php echo count($all_unapproved_jobs); ?></span> Pending Approval</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button id="btn-approved" class="page-heading no-margin"><span><?php echo count($all_approved_jobs); ?></span> Approved</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button id="btn-rejected" class="page-heading reject-btn no-margin"><?php echo count($all_rejected_jobs); ?></span> Rejected</button>
                                            </div>
                                        </div>
                                    </div>
                                    <header id="pending" class="category-sec-header">
                                        <h2>All Job Listings Pending Approval</h2>
                                    </header>
    <?php                       if (!empty($all_unapproved_jobs)) { ?>
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp border-none mylistings-wrp">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-6">Job Title</th>
                                                        <th class="col-xs-1 text-center">Status</th>
                                                        <th class="col-xs-5 text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php                                               foreach ($all_unapproved_jobs as $job) { ?>
                                                        <tr>
                                                            <td><?php echo ucwords($job['Title']); ?>
                                                                <?php if($job['active']==0) {echo '<p class="red">  [Inactive]</p>'; } ?>
                                                                <?php if($job['active']==1) {echo '<p class="green"> [Active]</p>'; } ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo ucwords($job['approval_status']); ?>
                                                                <br />
                                                                <?php echo formatDate($job['activation_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('edit_listing') . '/' . $job['sid'] ?>">View / Edit</a>
                                                                <button onclick="fSetStatus('approved', <?php echo $job['sid'] ?>);" class="submit-btn" type="button">Approve</button>
                                                                <button onclick="fSetStatus('rejected', <?php echo $job['sid'] ?>);" class="submit-btn reject-btn" type="button">Reject</button>
                                                            </td>
                                                        </tr>
<?php                                               } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
    <?php                       } else { ?>
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp border-none mylistings-wrp">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-6">Job Title</th>
                                                        <th class="col-xs-1 text-center">Status</th>
                                                        <th class="col-xs-5 text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td colspan="3">No Job Listings</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
    <?php                       } ?>
                                    <header id="approved" class="category-sec-header">
                                        <h2>All Approved Job Listings</h2>
                                    </header>
    <?php                       if (!empty($all_approved_jobs)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Job Title</th>
                                                            <th class="col-xs-1 text-center">Status</th>
                                                            <th class="col-xs-2 text-center">Approved By</th>
                                                            <th class="col-xs-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($all_approved_jobs as $job) { ?>
                                                            <tr>
                                                                <td><?php echo ucwords($job['Title']); ?>
                                                                    <?php if($job['active']==0) {echo '<p class="red">  [Inactive]</p>'; } ?>
                                                                    <?php if($job['active']==1) {echo '<p class="green"> [Active]</p>'; } ?></td>
                                                                <td class="text-center">
                                                                    <?php echo ucwords($job['approval_status']); ?>
                                                                    <br />
                                                                    <?php echo formatDate($job['approval_status_change_datetime'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo ucwords($job['approval_status_by']); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('edit_listing') . '/' . $job['sid'] ?>">View / Edit</a>
                                                                    <button onclick="fSetStatus('rejected', <?php echo $job['sid'] ?>);" class="submit-btn reject-btn pull-left" type="button">Reject</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Job Title</th>
                                                            <th class="col-xs-1 text-center">Status</th>
                                                            <th class="col-xs-2 text-center">Approved By</th>
                                                            <th class="col-xs-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="4">No Job Listings</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <header id="rejected" class="category-sec-header">
                                        <h2>All Rejected Job Listings</h2>
                                    </header>
                                    <?php if (!empty($all_rejected_jobs)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Job Title</th>
                                                            <th class="col-xs-1 text-center">Status</th>
                                                            <th class="col-xs-2 text-center">Rejected By</th>
                                                            <th class="col-xs-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($all_rejected_jobs as $job) { ?>
                                                            <tr>
                                                                <td><?php echo ucwords($job['Title']); ?>
                                                                    <?php if($job['active']==0) {echo '<p class="red"> [Inactive]</p>'; } ?>
                                                                    <?php if($job['active']==1) {echo '<p class="green"> [Active]</p>'; } ?></td>
                                                                <td class="text-center">
                                                                    <p class="red"><?php echo ucwords($job['approval_status']); ?></p>
                                                                    <br />
                                                                    <?php echo formatDate($job['approval_status_change_datetime'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo ucwords($job['approval_status_by']); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('edit_listing') . '/' . $job['sid'] ?>">View / Edit</a>
                                                                    <button onclick="fSetStatus('approved', <?php echo $job['sid'] ?>);"  class="submit-btn pull-left" type="button">Approve</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Job Title</th>
                                                            <th class="col-xs-1 text-center">Status</th>
                                                            <th class="col-xs-2 text-center">Rejected By</th>
                                                            <th class="col-xs-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr><td colspan="4">No Job Listings</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="create-job-box">
                                        <div class="dash-box">
                                            <h2>You don't have Job Approvals Module Enabled!</h2>
                                            <span>Get it Enabled from Site Admin?</span>
                                            <div class="button-panel">
                                                <a class="site-btn" href="javascript:void(0);">Learn More</a>
                                            </div>
                                            <p>With this module you can Approve / Reject jobs created by employees</p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#btn-pending').on('click', function () {
            $('html, body').animate({scrollTop: $('#pending').offset().top}, 1000);
        });

        $('#btn-approved').on('click', function () {
            $('html, body').animate({scrollTop: $('#approved').offset().top}, 2000);
        });

        $('#btn-rejected').on('click', function () {
            $('html, body').animate({scrollTop: $('#rejected').offset().top}, 3000);
        });

    });

    function fSetStatus(status, jobId) {
        var myJobId = jobId;
        var myStatus = status;

        alertify.confirm('Are you sure?', 'Are you sure you want to mark this Job Listing as ' + status + '?',
                function () {
                    var myUrl = '<?php echo base_url("job_approval_management/ajax_responder") ?>';
                    var dataToPost = 'perform_action=update_job_approval_status&status=' + myStatus + '&jobid=' + myJobId;
                    var myRequest;
                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'POST',
                        data: dataToPost
                    });

                    myRequest.done(function (response) {
                        if (response == 'success') {
                            var myLocation = window.location.href;
                            window.location = myLocation;
                        } else {
                            alertify.notify('Failed to Update Approval Status');
                        }
                    });
                },
                function () {
                    //cancel
                }
        );
    }
</script>
