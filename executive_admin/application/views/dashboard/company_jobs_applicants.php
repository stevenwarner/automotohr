<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">            
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i>Company Job Applicants</h1>
                    
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
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Email</th>
                                    <th>Job</th>
                                    <th class="text-center">Applied On</th>
                                </tr> 
                            </thead>
                            <tbody>
                                <?php if (empty($job_applicants)) { ?>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="no-data">No Job Applicants found.</div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($job_applicants as $job_applicant) { ?>
                                        <tr>
                                            <td><?php echo $job_applicant['first_name'] . ' ' . $job_applicant['last_name']; ?></td>
                                            <td><?php echo $job_applicant['email']; ?></td>
                                            <td><?php echo get_job_title($job_applicant['job_sid']); ?></td>
                                            <td class="text-center">
<!--                                                --><?php //echo my_date_format($job_applicant['date_applied']); ?>
                                                <?php echo reset_datetime(array(
                                                    'datetime' => $job_applicant['date_applied'],
                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                    // 'format' => 'h:iA', //
                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                    'from_timezone' => $executive_user['timezone'], //
                                                    '_this' => $this
                                                )); ?>
                                            </td>
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