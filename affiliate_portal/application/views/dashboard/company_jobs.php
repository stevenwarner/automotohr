<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">            
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i>Company Jobs</h1>
                    
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/manage_admin_companies/' . $company['sid']); ?>"><i class="fa fa-long-arrow-left"></i> Back to Company Dashboard</a>
                    
                </div>
                <div class="flash-message">
                    <?php $this->load->view('flashmessage/flash_message'); ?>
                </div>
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left"><?php echo $company['CompanyName']; ?></h1>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Type</th>
                                    <th class="text-center">Location</th>
                                    <!--<th>Approval Status</th>-->
                                    <th>Job Views</th>
                                    <th class="text-center">No. of Applicants</th>
                                </tr> 
                            </thead>
                            <tbody>
                                <?php if (empty($jobs)) { ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="no-data">No Jobs found.</div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php
                                    foreach ($jobs as $job) {
                                        $location_info = db_get_state_name($job['Location_State']);
                                        ?>
                                        <tr>
                                            <td><a><?php echo $job['Title']; ?></a></td>
                                            <td><?php echo $job['JobType']; ?></td>
                                            <td class="text-center">
                                                <?php echo isset($job['Location']) ? $job['Location'] . ', ' : ''; ?>
                                                <?php echo isset($job['Location_City']) ? $job['Location_City'] . ', ' : ''; ?>
                                                <?php echo isset($location_info['state_name']) ? $location_info['state_name'] . ', ' : ''; ?>
                                                <?php echo isset($location_info['country_name']) ? $location_info['country_name'] : ''; ?>
                                            </td>
                                            <!--<td><?php echo ucwords($job['approval_status']); ?></td>-->
                                            <td><?php echo $job['views']; ?></td>
                                            <td class="text-center"><?php echo get_no_of_applicants($job['sid'], $company['sid']); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>