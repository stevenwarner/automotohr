<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Dashboard
                    </a>
                </div>
                <!-- reports list table -->
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left"><span class="text-success">Job Report</span></h1>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-stripped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left col-xs-10">Title</th>
                                    <th class="text-center col-xs-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Applicants Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicants_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Status Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicant_status_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Referrals Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicants_referrals_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Source Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicant_source_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Interviews Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/interviews_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Jobs Per Month Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/jobs_per_month_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicants Between Period Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicants_between_period_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Time To Fill A Posted Job Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/time_to_fill_job_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Time to Hire a Candidate for Job Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/time_to_hire_job_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Recently Hired from Job Categories Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/job_categories_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>New Hires Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/new_hires_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>New Hires On-Boarding Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/new_hires_onboarding_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Job Views Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/job_views_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Company Daily Activity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/company_daily_activity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Employer Login Duration Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/employer_login_duration/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Company Weekly Activity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/company_weekly_activity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Daily Activity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/daily_activity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <!-- *** -->
                                <tr>
                                    <td>Weekly Activity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/weekly_activity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Daily Inactivity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/daily_inactivity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Weekly Inactivity Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/weekly_inactivity_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Daily Activity Overview Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/daily_activity_overview_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Weekly Activity Overview Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/weekly_activity_overview_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Daily Activity Detailed Overview Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/daily_activity_detailed_overview_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Offers Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicant_offers_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Origination Tracker Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicant_origination_tracker_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Interview Scores Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/applicant_interview_scores_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Job Products Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block btn-block" href="<?php echo base_url() . 'reports/job_products_report/' . $company_sid; ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Applicant Origination Statistics</td>
                                    <td class="text-center"><a class="btn btn-success btn-block btn-block" href="<?php echo base_url() . 'reports/applicant_origination_statistics_report/' . $company_sid; ?>">View</a></td>
                                </tr>

                                <tr>
                                    <td>EEO Report</td>
                                    <td class="text-center"><a class="btn btn-success btn-block" href="<?php echo base_url() . 'eeo/' . $company_sid; ?>">View</a></td>
                                </tr>

                                <!-- *** -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- reports list table -->
            </div>               					
        </div>
    </div>
</div>