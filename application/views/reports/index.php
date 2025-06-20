<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate/applicants'); ?>
                                                    <figure><i class="fa fa-users"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicants</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Generate Advanced Reports Related to Job Applicants</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_monthly_filled_jobs_report'); ?>
                                                    <figure><i class="fa fa-users"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Jobs - Per Month</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Closed / Filed Jobs Per Month</small>
                                                    </div>

                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_interviews_scheduled_by_recruiters'); ?>
                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Interviews</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Interviews Scheduled by Employers</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/candidates_between_period'); ?>
                                                    <figure><i class="fa fa-users"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicants</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicants Between A Certain Period</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_time_to_fill'); ?>
                                                    <figure><i class="fa fa-clock-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Jobs</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Time to Fill a Posted Job</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_time_to_hire'); ?>
                                                    <figure><i class="fa fa-clock-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Jobs</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Time to Hire a Candidate for Job</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_active_new_hire_categories'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Job Categories</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>A list of Job Categories on which individuals have been recently hired</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_new_hires_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">New Hires</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>New Hires Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_new_hires_onboarding_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">New Hires On-Boarding</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>A List of Newly Hired Candidates Still in On-Boarding Process</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_job_views_applicants_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Job Views</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Job Views Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- applicant referral report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_applicant_referrals_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Reference</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Reference Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- applicant referral report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_applicant_status_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicant Status</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicant Status Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- candidate offers report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_candidate_offers_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Candidate Offers</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Candidate Offers Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- applicant origination tracker report -->
                                        <!--                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/applicant_origination_tracker_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicant Origination Tracker</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicant Origination Tracker Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <!-- applicant interview scores report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/applicant_interview_scores_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicant Interview Scores</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicant Interview Scores Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- applicant source report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/applicant_source_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicant Source Tracking</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicant Source Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- applicant origination statistics report -->
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_applicant_origination_statistics_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Applicant Origination Statistics</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Applicant Origination Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- job fair report -->
                                        <?php if ($job_fair_configuration) { ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <?php $url = base_url('reports/generate_job_fair_report'); ?>
                                                        <figure><i class="fa fa-list"></i></figure>
                                                        <h2 class="post-title">
                                                            <a href="<?php echo $url; ?>">Job Fair Track Report</a>
                                                        </h2>
                                                        <div class="count-box">
                                                            <small>Job Fair Report</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/generate_company_daily_activity_report'); ?>
                                                    <figure><i class="fa fa-list"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Company Daily Activity Report</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Daily Activity Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/driving_license'); ?>
                                                    <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Employee</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Driving License Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($companyDetailsForSMS)) { ?>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <?php $url = base_url('reports/company_sms_report'); ?>
                                                        <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                        <h2 class="post-title">
                                                            <a href="<?php echo $url; ?>">SMS Report</a>
                                                        </h2>
                                                        <div class="count-box">
                                                            <small>Company SMS Report</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!--
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php /*$url = base_url('reports/employee_monthly_attendance_report');*/ ?>
                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php /*echo $url; */ ?>">Attendance Report</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Monthly</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php /*echo $url; */ ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->

                                        <!--
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php /*$url = base_url('reports/employee_weekly_attendance_report');*/ ?>
                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php /*echo $url; */ ?>">Attendance Report</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Weekly</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php /*echo $url; */ ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->




                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/employee_document'); ?>
                                                    <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Employee</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Confidential and Authorized Document Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/employeeAssignedDocuments'); ?>
                                                    <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Employee</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Assigned Documents Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <?php $url = base_url('reports/employeeTerminationReport'); ?>
                                                    <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo $url; ?>">Employee</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Termination Report</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(PAYROLL)) { ?>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <?php $url = base_url('payrolls/ledger'); ?>
                                                        <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                        <h2 class="post-title">
                                                            <a href="<?php echo $url; ?>">Payroll Ledger</a>
                                                        </h2>
                                                        <div class="count-box">
                                                            <small>Payroll Ledger Report</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <?php $url = base_url('reports/applicantsAiScoreReport'); ?>
                                                        <figure><i class="fa fa-list" aria-hidden="true"></i></figure>
                                                        <h2 class="post-title">
                                                            <a href="<?php echo $url; ?>">Applicants AI Score Report</a>
                                                        </h2>
                                                        <div class="count-box">
                                                            <small>Applicants AI Score Report</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a class="site-btn" href="<?php echo $url; ?>">Generate</a>
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
</div>