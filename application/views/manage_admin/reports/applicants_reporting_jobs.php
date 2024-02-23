<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php //$this->load->view('templates/_parts/admin_column_left_view'); 
                ?>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Company</th>
                                                                <th>Job title</th>
                                                                <th>Applied Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($applicants as $applicant) { ?>
                                                                <tr>
                                                                    <td><?php echo $applicant['CompanyName']; ?></td>
                                                                    <td><?php echo $applicant['desired_job_title'] != '' ? $applicant['desired_job_title'] : $applicant['Title']; ?></td>
                                                                    <td><?php echo date_with_time($applicant['date_applied']); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
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