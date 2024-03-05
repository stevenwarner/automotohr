<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php if (!empty($session['company_detail']['Logo'])) { ?>
                                <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo'] ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
                            <?php } ?>
                            <?php if (!empty($session['company_detail']['CompanyName'])) { ?>
                                <br><?php echo $session['company_detail']['CompanyName']; ?>
                                <?php if (isCompanyClosed() && isPayrollOrPlus(true)) { ?>
                                    <label class="label label-danger" title="The store is closed." placement="top">
                                        Closed
                                    </label>
                                <?php } ?>
                            <?php } ?><br>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="applicant-filter">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Search Job Posting</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 custom-col">
                                                <div class="form-group full-width">
                                                    <div class="Category_chosen">
                                                        <select data-placeholder="- All Jobs -" multiple="multiple" name="jobs_list[]" id="jobs_list" class="chosen-select">
                                                            <?php
                                                            foreach ($all_jobs as $job) {
                                                                //
                                                                $jobSid = $job['sid'];
                                                                $title = '';
                                                                $class = 'ats_search_filter_active';
                                                                //
                                                                if (isset($job['Title'])) {
                                                                    $jobSid = $job['sid'];
                                                                    $class = $job['active'] == 1 ? 'ats_search_filter_active' : 'ats_search_filter_inactive';
                                                                    //
                                                                    // Add CSC to job title
                                                                    // $country = "United States";
                                                                    if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                                                                        $job['Title'] .= ' - ' . ucfirst($job['Location_City']);
                                                                    }
                                                                    if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                                                                        $job['Title'] .= ', ' . db_get_state_name($job['Location_State'])['state_name'];
                                                                    }
                                                                    //
                                                                    $title .= $job['Title'];
                                                                    $title .= ' [' . ($job['active'] == 1 ? 'Active' : 'Inactive') . ']';
                                                                    if ($job['active'] == 1) {
                                                                        if ($job['activation_date'] != NULL || $job['activation_date'] != '') {
                                                                            $title .= ' - (' . (formatDate($job['activation_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME)) . ')';
                                                                        }
                                                                    } else {
                                                                        if ($job['deactivation_date'] != NULL || $job['deactivation_date'] != '') {
                                                                            $title .= ' - (' . (formatDate($job['deactivation_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME)) . ')';
                                                                        }
                                                                    }
                                                                    $title .= ' (' . common_get_job_applicants_count($job['sid'], $archived) . ')';
                                                                } else {
                                                                    $c = common_get_job_applicants_count($job['desired_job_title'], $archived, true, $session['company_detail']['sid']);
                                                                    if ($c == 0) continue;
                                                                    $title = $job['desired_job_title'] . ' (Transferred Candidates)';
                                                                    $jobSid = 'd' . $job['sid'];
                                                                    $title .= $job['desired_job_title'] == '' ? ' (0) ' : ' (' . $c . ')';
                                                                }
                                                                //
                                                                if ($jobs_approval_module_status == '1' && isset($job['Title'])) {
                                                                    if ($job['approval_status'] != 'approved') continue;
                                                                }
                                                                //
                                                                echo '<option value="' . ($jobSid) . '" class="' . ($class) . '" ' . (in_array($jobSid, $job_sid_array) ? 'selected="true"' : '') . '>' . ($title) . '</option>';
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 custom-col">
                                                <div class="form-group full-width">
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="status" id="status">
                                                            <option value="all" selected>All Statuses</option>
                                                            <?php if ($have_status == false) { ?>
                                                                <option value="not_contacted_yet" <?php
                                                                                                    if (isset($status) && $status == "not_contacted_yet") {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>Not Contacted Yet</option>
                                                                <option value="left_message" <?php
                                                                                                if (isset($status) && $status == "left_message") {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>>Left Message</option>
                                                                <option value="contacted" <?php
                                                                                            if (isset($status) && $status == "contacted") {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>Contacted</option>
                                                                <option value="candidate_responded" <?php
                                                                                                    if (isset($status) && $status == "candidate_responded") {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>Candidate Responded</option>
                                                                <option value="interviewing" <?php
                                                                                                if (isset($status) && $status == "interviewing") {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>>Interviewing</option>
                                                                <option value="submitted" <?php
                                                                                            if (isset($status) && $status == "submitted") {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>Submitted</option>
                                                                <option value="qualifying" <?php
                                                                                            if (isset($status) && $status == "qualifying") {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>Qualifying</option>
                                                                <option value="ready_to_hire" <?php
                                                                                                if (isset($status) && $status == "ready_to_hire") {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>>Ready to Hire</option>

                                                                <option value="do_not_hire" <?php
                                                                                            if (isset($status) && $status == "do_not_hire") {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>Do Not Hire</option>

                                                                <option value="offered_job" <?php
                                                                                            if (isset($status) && $status == "offered_job") {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>Offered Job</option>
                                                                <option value="client_declined" <?php
                                                                                                if (isset($status) && $status == "client_declined") {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>>Client Declined</option>
                                                                <option value="not_in_consideration" <?php
                                                                                                        if (isset($status) && $status == "not_in_consideration") {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>>Not In Consideration</option>
                                                                <option value="future_opportunity" <?php
                                                                                                    if (isset($status) && $status == "future_opportunity") {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>Future Opportunity</option>
                                                            <?php } else { ?>
                                                                <?php foreach ($company_statuses as $company_status) { ?>
                                                                    <option value="<?php echo isset($company_status['name']) ? $company_status['name'] : ''; ?>" <?php
                                                                                                                                                                    if (isset($status) && (urldecode($status) == $company_status['name'])) {
                                                                                                                                                                        echo "selected";
                                                                                                                                                                    }
                                                                                                                                                                    ?>>
                                                                        <?php echo isset($company_status['name']) ? $company_status['name'] : ''; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-6 col-md-6 col-xs-12 col-sm-6' : 'col-lg-4 col-md-4 col-xs-12 col-sm-4'; ?> custom-col">
                                                <div class="form-group full-width">
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="job_fit_category" id="job_fit_category">
                                                            <option value="0">All Job Fit Categories</option>
                                                            <?php foreach ($job_fit_categories as $category) { ?>
                                                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $job_fit_category_sid) ? 'selected' : ''; ?>><?php echo $category['value']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-6 col-md-6 col-xs-12 col-sm-6' : 'col-lg-4 col-md-4 col-xs-12 col-sm-4'; ?> custom-col">
                                                <div class="form-group full-width">
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="app-type" id="app-type">
                                                            <option value="all" <?php echo $app_type == 'all' ? 'selected="selected"' : "" ?>>All Types</option>
                                                            <option value="Applicant" <?php echo $app_type == 'Applicant' ? 'selected="selected"' : "" ?>>Applicant</option>
                                                            <option value="Talent Network" <?php echo $app_type == 'Talent Network' ? 'selected="selected"' : "" ?>>Talent Network</option>
                                                            <option value="Manual Candidate" <?php echo $app_type == 'Manual Candidate' ? 'selected="selected"' : "" ?>>Manual Candidate</option>
                                                            <option value="Job Fair" <?php echo $app_type == 'Job Fair' ? 'selected="selected"' : "" ?>>Job Fair</option>
                                                            <option value="Re-Assigned Candidates" <?php echo $app_type == 'Re-Assigned Candidates' ? 'selected="selected"' : "" ?>>Re-Assigned Candidates</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($job_fair_configuration != 0) { ?>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                                                    <div class="form-group full-width">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="fair-type" id="fair-type">
                                                                <option value="all" <?php echo $fair_type == 'all' ? 'selected="selected"' : "" ?>>Job Fair Template</option>

                                                                <?php foreach ($job_fair_forms as $jff) { ?>
                                                                    <option value="<?php echo $jff['page_url']; ?>" <?php echo $jff['page_url'] == $fair_type ? 'selected="selected"' : "" ?>><?php echo $jff['title']; ?></option>
                                                                <?php                               } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php               } ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                                                <div class="form-group full-width">
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="ques-status" id="ques-status">
                                                            <option value="all" <?php echo $ques_status == 'all' ? 'selected="selected"' : "" ?>>All Questionnaire</option>
                                                            <option value="qs" <?php echo $ques_status == 'qs' ? 'selected="selected"' : "" ?>>Questionnaire Sent</option>
                                                            <option value="qc" <?php echo $ques_status == 'qc' ? 'selected="selected"' : "" ?>>Questionnaire Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                                                <div class="form-group full-width">
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="emp-app-type" id="emp-app-status">
                                                            <option value="all" <?php echo $emp_app_status == 'all' ? 'selected="selected"' : "" ?>>All Employment Application Status</option>
                                                            <option value="eas" <?php echo $emp_app_status == 'eas' ? 'selected="selected"' : "" ?>>Employment Application Sent</option>
                                                            <option value="eans" <?php echo $emp_app_status == 'eans' ? 'selected="selected"' : "" ?>>Employment Application Not Sent</option>
                                                            <option value="eac" <?php echo $emp_app_status == 'eac' ? 'selected="selected"' : "" ?>>Employment Application Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                <a class="form-btn" href="" id="filter-btn" name="filter-btn">Filter</a>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                <a class="form-btn" href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>">Clear</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-form-wrp">
                                    <span>Search Applicant(s)</span>
                                    <div class="tracking-filter">
                                        <form method="GET" name="applicant_filter" action="<?= base_url() ?>application_tracking_system">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                    <div class="hr-select-dropdown no-aarow">
                                                        <input type="text" placeholder="Search Applicants by Name, Email or Phone number" name="keyword" id="keyword" value="<?php echo isset($keyword) ? $keyword : ''; ?>" class="invoice-fields search-candidate">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                    <a id="my_submit_btn" name="my_submit_btn" class="form-btn" href="#" style="min-width: auto;">Search</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-panel text-right">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <div class="dropdown email-notification-dropdown">
                                    <?php if (check_access_permissions_for_view($security_details, 'assign_selected') || check_access_permissions_for_view($security_details, 'send_candidate_email')) { ?>
                                        <button class="btn btn-success btn-block dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Assign Selected
                                            <span class="caret"></span>
                                        </button>
                                    <?php } ?>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <?php if (check_access_permissions_for_view($security_details, 'assign_selected')) { ?>
                                            <li><a id="btn_assign_selected" href="<?php echo base_url('task_management'); ?>" class="btn btn-success btn-block">Assign Selected</a></li>
                                        <?php } ?>

                                        <?php if ($archive != 'archive') { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-warning" id="archive_selected">Archive Selected</a></li>
                                        <?php } else { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-info" id="activate_selected">Activate Selected</a></li>
                                        <?php } ?>

                                        <?php if (check_access_permissions_for_view($security_details, 'send_candidate_email')) { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-primary" id="send_candidate_email">Send Candidate Info</a></li>
                                        <?php } ?>

                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                            <?php if (check_access_permissions_for_view($security_details, 'manual_candidate')) { ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="dropdown email-notification-dropdown">
                                        <?php if (check_access_permissions_for_view($security_details, 'assign_selected') || check_access_permissions_for_view($security_details, 'send_candidate_email')) { ?>
                                            <button class="btn btn-success btn-block dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Manual Candidate
                                                <span class="caret"></span>
                                            </button>
                                        <?php } ?>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="<?= base_url() ?>manual_candidate" class="btn btn-success btn-block">+ Add Manual Candidate</a></li>
                                            <?php if (check_access_permissions_for_view($security_details, 'assign_other_job_manually')) { ?>
                                                <li><a href="javascript:void(0);" class="btn btn-primary" id="assign_to_manual">Assign To Other Job Manually</a></li>
                                            <?php } ?>
                                            <li></li>
                                        </ul>
                                    </div>
                                    <!--                                    <a href="--><? //= base_url() 
                                                                                        ?>
                                    <!--manual_candidate" class="btn btn-success btn-block">+ Add Manual Candidate</a>-->
                                </div>
                            <?php } ?>
                            <!--<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <?php /*if ($archive == 'archive') { */ ?>
                                    <a href="<?/*= base_url('application_tracking_system/active/all/all/all/all'); */ ?>" class="btn btn-success btn-block">Active Applicants</a>
                                <?php /*} else { */ ?>
                                    <a href="<?/*= base_url('application_tracking_system/archive/all/all/all/all'); */ ?>" class="btn btn-warning btn-block">Archived Applicants</a>
                                <?php /*} */ ?>
                            </div>-->
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <?php $current = $this->uri->segment(2);
                                $button_text = '';
                                switch ($current) {
                                    case 'active':
                                        $button_text = 'All Active Applicants';
                                        $button_class = 'btn-success';
                                        break;
                                    case 'archive':
                                        $button_text = 'All Archived Applicants';
                                        $button_class = 'btn-warning';
                                        break;
                                    case 'onboarding':
                                        $button_text = 'All Onboarding Applicants';
                                        $button_class = 'btn-primary';
                                        break;
                                    case 'assigned_to':
                                        $button_text = 'Applicants Assigned To Me';
                                        $button_class = 'btn-danger';
                                        break;
                                    case 'assigned_by':
                                        $button_text = 'Applicants Assigned By Me';
                                        $button_class = 'btn-info';
                                        break;
                                } ?>

                                <div class="dropdown email-notification-dropdown">
                                    <button class="btn <?php echo $button_class; ?> btn-block dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <?php echo $button_text; ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all'); ?>" class="btn btn-success btn-block">All Active Applicants</a></li>
                                        <li><a href="<?php echo base_url('application_tracking_system/archive/all/all/all/all/all/all/all/all'); ?>" class="btn btn-warning btn-block">All Archived Applicants</a></li>
                                        <li><a href="<?php echo base_url('application_tracking_system/onboarding/all/all/all/all/all/all/all/all'); ?>" class="btn btn-primary btn-block">All Onboarding Applicants</a></li>
                                        <li><a href="<?php echo base_url('application_tracking_system/assigned_to/all/all/all/all/all/all/all/all'); ?>" class="btn btn-danger btn-block">Applicants Assigned To Me</a></li>
                                        <li><a href="<?php echo base_url('application_tracking_system/assigned_by/all/all/all/all/all/all/all/all'); ?>" class="btn btn-info btn-block">Applicants Assigned By Me</a></li>
                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <div class="dropdown email-notification-dropdown">
                                    <button class="btn btn-default btn-block dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Email Notifications
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <?php if (check_access_permissions_for_view($security_details, 'send_rejection_email')) { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-danger" id="send_rej_email">Send Rejection Email</a></li>
                                        <?php }
                                        if (check_access_permissions_for_view($security_details, 'send_acknowledgement_email')) { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-info" id="send_ack_email">Send Acknowledgement Email</a></li>
                                        <?php }
                                        if (check_access_permissions_for_view($security_details, 'send_bulk_email')) { ?>
                                            <li><a href="javascript:void(0);" class="btn btn-success" id="send_bulk_email">Send Bulk Email</a></li>
                                        <?php } ?>
                                        <?php if (in_array($session['company_detail']['sid'], [15708, 8578, 51])) { ?>   
                                        <li><a href="javascript:void(0);" class="btn btn-orange" id="send_still_interested_email">Are You Still Interested?</a></li>
                                        <?php } ?>   
                                        <!--                                        <li><a href="javascript:void(0);" class="btn btn-primary" id="send_candidate_email">Send Candidate Notification</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $links; ?>
                    <?php if ($archive == 'active') { ?>
                        <?php // if ($archive == 0 && base_url() != 'http://localhost/automotoCI/') { 
                        ?>
                        <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/jsapi.js"></script> -->
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <div class="applicant-applied">
                            <div class="applicant-graphic-info">
                                <div class="col-lg-7 col-md-6 col-xs-12 col-sm-6">
                                    <div class="graphical-info">
                                        <div id="chart_div" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-xs-12 col-sm-6">
                                    <div class="graphical-info">
                                        <div id="piechart" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="applicant-count-wrp">
                                <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-4 col-md-4 col-xs-12 col-sm-4' : 'col-lg-3 col-md-3 col-xs-12 col-sm-3'; ?>">
                                    <div class="row">
                                        <div class="applicant-count" style="background-color:#0000FF; border-radius:0 0 0 5px;">
                                            <p>Applicants</p>
                                            <span>
                                                <?php
                                                if (is_admin($employer_sid)) {
                                                    if ($search_activated) {
                                                        echo $applicant_total;
                                                    } else {
                                                        echo $all_job_applicants;
                                                    }
                                                } else {
                                                    echo $applicant_total;
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-4 col-md-4 col-xs-12 col-sm-4' : 'col-lg-3 col-md-3 col-xs-12 col-sm-3'; ?>">
                                    <div class="row">
                                        <div class="applicant-count" style="background-color:#980b1e;">
                                            <p>Talent Network</p>
                                            <span><?php echo $all_talent_applicants; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-4 col-md-4 col-xs-12 col-sm-4' : 'col-lg-3 col-md-3 col-xs-12 col-sm-3'; ?>">
                                    <div class="row">
                                        <div class="applicant-count" style="background-color:#81b431;">
                                            <p>Manual Contacts</p>
                                            <span><?php echo $all_manual_applicants; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($job_fair_configuration == 1) { ?>
                                    <div class="<?php echo $job_fair_configuration == 0 ? 'col-lg-4 col-md-4 col-xs-12 col-sm-4' : 'col-lg-3 col-md-3 col-xs-12 col-sm-3'; ?>">
                                        <div class="row">
                                            <div class="applicant-count" style="background-color:#b4048a;">
                                                <p>Job Fair</p>
                                                <span><?php echo $all_job_fair_applicants; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php // } 
                        ?>
                    <?php } ?>
                    <div class="applicant-box-wrp" id="show_no_jobs">
                        <?php if (empty($employer_jobs)) { ?>
                            <span class="applicant-not-found">No Applicants found!</span>
                        <?php } else { ?>
                            <form method="POST" name="ej_form" id="ej_form">
                                <?php foreach ($employer_jobs as $employer_job) { ?>

                                    <?php
                                    $originalJobTitle = $employer_job['job_title'];
                                    // Add CSC to job title
                                    // $country = "United States";
                                    if (isset($employer_job['Location_City']) && $employer_job['Location_City'] != NULL) {
                                        $employer_job['job_title'] .= ' - ' . ucfirst($employer_job['Location_City']);
                                    }
                                    if (isset($employer_job['Location_State']) && $employer_job['Location_State'] != NULL) {
                                        $employer_job['job_title'] .= ', ' . db_get_state_name($employer_job['Location_State'])['state_name'];
                                    }
                                    ?>
                                    <?php //_e($employer_job,true); ?>
                                    <article id="manual_row<?php echo $employer_job["sid"]; ?>" class="applicant-box <?php echo check_blue_panel_status() && $employer_job['is_onboarding'] == 1 ? 'onboarding' : '';
                                                                                                                        echo strtolower(preg_replace('/[^a-z]/i', '', $employer_job['status'])) == 'donothire' ? 'donothirebox' : ''; ?> ">
                                        <div class="box-head">
                                            <div class="row date-bar">
                                                <div class="col-lg-1 col-md-1 col-xs-1 col-sm-1">
                                                    <label class="control control--checkbox">
                                                        <input name="ej_check[]" data-applicant-name="<?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>" data-job_title="<?php echo $originalJobTitle; ?>" data-job_id="<?php echo $employer_job["job_sid"]; ?>" type="checkbox" value="<?php echo $employer_job["applicant_sid"]; ?>" data-list='<?php echo $employer_job['sid'] ?>' class="ej_checkbox applicant_sids">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-7 col-sm-7">
                                                    <time class="date-applied"><?= formatDate($employer_job["date_applied"], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></time>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                    <?php if ($archive == 'archive') { ?>
                                                        <!--   <a class="float-right aplicant-documents delete-text-color" onclick="delete_single_applicant(<?php //echo $employer_job["sid"]; 
                                                                                                                                                            ?>)" href="javascript:;">
                                                            <i class="fa fa-times"></i><span class="btn-tooltip">Delete</span>
                                                        </a>-->
                                                        <a class="float-right aplicant-documents" onclick="active_single_applicant(<?php echo $employer_job["sid"]; ?>)" href="javascript:;">
                                                            <i class="fa fa-undo"></i><span class="btn-tooltip">Re-Activate</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a class="pull-right aplicant-documents" onclick="archive_single_applicant(<?php echo $employer_job["sid"]; ?>)" href="javascript:;"><i class="fa fa-archive"></i><span class="btn-tooltip">Archive</span></a>
                                                    <?php } ?>
                                                    <?php if (check_access_permissions_for_view($security_details, 'notes_popup')) { ?>
                                                        <a href="javascript:void(0);" onclick="fLaunchNotesModal(this)" class="pull-right <?= $employer_job["notes_count"] == 0 ? 'aplicant-documents' : 'aplicant-notes-span'; ?>" data-applicant-sid="<?= $employer_job["applicant_sid"]; ?>" data-applicant-email="<?= $employer_job["email"]; ?>" data-job-sid="<?= $employer_job["sid"]; ?>" data-document-title="Notes For <?= $employer_job["first_name"]; ?> <?= $employer_job["last_name"]; ?>"><i class="fa fa-edit"></i><span class="btn-tooltip">Notes</span></a>
                                                    <?php } ?>

                                                    <!--  -->
                                                    <?php
                                                    $upload_resume = $employer_job['resume'];
                                                    $file_name = explode(".", $upload_resume);
                                                    $resume_name = $file_name[0];
                                                    $resume_extension = isset($file_name[1]) ? $file_name[1] : '';

                                                    $userType               = 'applicant';
                                                    $companyId              = $employer_job['employer_sid'];
                                                    $userSid                = $employer_job['applicant_sid'];
                                                    $send_request_datetime  = 'no_date';

                                                    $job_sid            = $employer_job['job_sid'];
                                                    $job_title          = $employer_job['job_title'];
                                                    $job_listing_sid    = $employer_job['sid'];
                                                    $requested_job_sid     = '';
                                                    $requested_job_type    = '';

                                                    if (!empty($job_sid) || $job_sid > 0) {
                                                        $requested_job_sid     = $job_sid;
                                                        $requested_job_type    = 'job';
                                                    } else if ($job_sid == 0 && !empty($job_title) && $job_title != 'Job Not Applied') {
                                                        $requested_job_sid     = $job_listing_sid;
                                                        $requested_job_type    = 'desired_job';
                                                    } else {
                                                        $requested_job_sid     = '0';
                                                        $requested_job_type    = 'job_not_applied';
                                                    }

                                                    $last_send_request_date = get_resume_lsq_date($companyId, $userType, $userSid);


                                                    if (!empty($last_send_request_date) && $last_send_request_date != NULL) {
                                                        $send_request_datetime = date('M d Y, D H:i:s', strtotime($last_send_request_date));
                                                    }

                                                    ?>
                                                    <a href="javascript:void(0);" class="pull-right <?= empty($resume_name) ? 'aplicant-documents' : 'aplicant-notes-span'; ?>" onclick="show_resume_popup(this);" data-preview-url="<?php echo $employer_job["resume_direct_link"]; ?>" data-download-url="<?php echo base_url('hr_documents_management/download_upload_document') . '/' . $employer_job['resume']; ?>" data-fullname="<?php echo urldecode($upload_resume); ?>" data-file-name="<?php echo urldecode($resume_name); ?>" data-file-ext="<?php echo $resume_extension; ?>" data-document-title="<?php echo 'Resume For ' . $employer_job['job_title']; ?>" data-applicant-sid="<?php echo $employer_job['applicant_sid']; ?>" data-job-list-sid="<?php echo $employer_job['sid']; ?>" data-requested-job-sid="<?php echo $requested_job_sid; ?>" data-requested-job-type="<?php echo $requested_job_type; ?>" data-request_datetime="<?php echo $send_request_datetime; ?>">
                                                        <i class="fa fa-file-text-o"></i>
                                                        <span class="btn-tooltip">Resume</span>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right <?= empty($employer_job["cover_letter_download_link"]) ? 'aplicant-documents' : 'aplicant-notes-span'; ?>" data-preview-url="<?php echo $employer_job["cover_letter_direct_link"]; ?>" data-download-url="<?php echo $employer_job["cover_letter_download_link"]; ?>" data-file-name="<?php echo $employer_job['cover_letter']; ?>" data-document-title="Cover Letter"><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Cover Letter</span></a>
                                                    <!-- <?php //if($this->session->userdata('logged_in')['company_detail']['ems_status'] && ($session['company_detail']['has_applicant_approval_rights'] == 0 || $session['employer_detail']['has_applicant_approval_rights'] == 1)){
                                                            ?>
                                                        <a href="javascript:void(0);" onclick="fun_hire_applicant(<?php //echo $employer_job["employer_sid"]; 
                                                                                                                    ?>, <?php //echo $employer_job["applicant_sid"]; 
                                                                                                                        ?>, <?php //echo $employer_job['sid']; 
                                                                                                                            ?>);" class="pull-right aplicant-documents"><i class="fa fa-user-plus" aria-hidden="true"></i><span class="btn-tooltip">Direct Hire Candidate</span></a>
                                                    <?php //} 
                                                    ?> -->
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="review-score">
                                                        <span class="job-count">
                                                            <?php if ($employer_job['multiple_jobs'] == 'Yes') { ?>
                                                                Applied to multiple job positions
                                                            <?php }  ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="applicant-info">
                                            <figure>
                                                <?php if ($has_access_to_profile) { ?>
                                                    <a href="<?php echo base_url('/applicant_profile/' . $employer_job["applicant_sid"] . '/' . $employer_job['sid']); ?>" title="<?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>">
                                                        <?php if (empty($employer_job["pictures"])) { ?>
                                                            <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                        <?php } else { ?>
                                                            <img src="<?php echo AWS_S3_BUCKET_URL . $employer_job["pictures"]; ?>">
                                                        <?php } ?>
                                                    </a>
                                                <?php } else { ?>
                                                    <?php if (empty($employer_job["pictures"])) { ?>
                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                    <?php } else { ?>
                                                        <img src="<?php echo AWS_S3_BUCKET_URL . $employer_job["pictures"]; ?>">
                                                    <?php } ?>
                                                <?php } ?>
                                            </figure>
                                            <div class="text">
                                                <p>
                                                    <?php if ($has_access_to_profile) { ?>
                                                        <a href="<?php echo base_url('/applicant_profile/' . $employer_job["applicant_sid"] . '/' . $employer_job['sid']); ?>" title="<?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>">
                                                            <?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>
                                                    <?php } ?>
                                                </p>
                                                <div class="phone-number">
                                                    <?php if (isset($employer_job['phone_number']) && $employer_job['phone_number'] != '') {
                                                        echo '<a class="theme-color" href="javascript:void(0)"><strong><i class="fa fa-phone"></i> ' . (isset($phone_pattern_enable) && $phone_pattern_enable == 1 ? phonenumber_format($employer_job['phone_number']) : $employer_job['phone_number']);
                                                        if ($phone_sid != '') {
                                                            echo '<a
                                                                href="javascript:void(0);"
                                                                class="pull-right aplicant-documents js-sms-btn"
                                                                data-id="' . $employer_job['sid'] . '"
                                                                data-applicant-id="' . $employer_job["applicant_sid"] . '"
                                                                data-name="' . ucwords($employer_job["first_name"] . ' ' . $employer_job["last_name"]) . '"
                                                                data-phone="' . $employer_job['phone_number'] . '"
                                                            ><i class="fa fa-comments"></i><em class="btn-tooltip">Send SMS</em></a>';
                                                        }
                                                    } ?>
                                                    <?php if (!empty($employer_job['email'])) : ?>
                                                        <br />
                                                        <strong>
                                                            <a href="javascript:void(0);" class="theme-color"><i class="fa fa-envelope"></i> <?= $employer_job['email']; ?></a>
                                                        </strong>
                                                    <?php endif; ?>
                                                </div>
                                                <br />
                                                <br />
                                                <br />

                                                <?php if (check_blue_panel_status() == true && $employer_job['is_onboarding'] == 1) { ?>
                                                    <?php $send_notification = checkOnboardingNotification($employer_job["applicant_sid"]); ?>
                                                    <?php if ($send_notification) { ?>
                                                        <span class="text-success">Onboarding Request Sent</span>
                                                    <?php } else { ?>
                                                        <span class="text-danger">Onboarding Request Pending</span>
                                                    <?php } ?>

                                                <?php } else { ?>
                                                    <span class=""><?php echo $employer_job["applicant_type"]; ?></span>
                                                <?php }  ?>

                                                <!-- hassan working area-->
                                            </div>
                                            <div class="candidate-status applicat-status-edit">
                                                <div class="label-wrapper-outer">
                                                    <?php if ($have_status == false) { ?>
                                                        <?php if ($employer_job["status"] == 'Contacted') { ?>
                                                            <div class="selected contacted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Candidate Responded') { ?>
                                                            <div class="selected responded"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Qualifying') { ?>
                                                            <div class="selected qualifying"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Submitted') { ?>
                                                            <div class="selected submitted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Interviewing') { ?>
                                                            <div class="selected interviewing"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Offered Job') { ?>
                                                            <div class="selected offered"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Not In Consideration') { ?>
                                                            <div class="selected notin"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Client Declined') { ?>
                                                            <div class="selected decline"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Placed/Hired' || $employer_job["status"] == 'Ready to Hire') { ?>
                                                            <div class="selected placed">Ready to Hire</div>
                                                        <?php } elseif (strtolower(preg_replace('/[^a-z]/i', '', $employer_job["status"])) == 'donothire') { ?>
                                                            <div class="selected donothire">Do Not Hire</div>
                                                        <?php } elseif ($employer_job["status"] == 'Not Contacted Yet') { ?>
                                                            <div class="selected not_contacted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Future Opportunity') { ?>
                                                            <div class="selected future_opportunity"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Left Message') { ?>
                                                            <div class="selected left_message"><?= $employer_job["status"] ?></div>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <div <?php echo !empty($employer_job['status_type'])  ? ' style= "background-color: ' . $employer_job['bar_bgcolor'] . '"' : '' ?> class="selected <?php echo (isset($employer_job['status_css_class'])) ? $employer_job['status_css_class'] : ''; ?>">
                                                            <?php echo (isset($employer_job['status_name'])) ? $employer_job['status_name'] : ''; ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="show-status-box jsProfileHistory" title="View Applicant Status History" data-name="<?php echo ucwords($employer_job["first_name"] .  $employer_job["last_name"]); ?>" data-id="<?= $employer_job["sid"] ?>"><i class="fa fa-pencil"></i></div>

                                                    <div class="lable-wrapper">
                                                        <div id="id" style="display:none;"><?= $employer_job["sid"] ?></div>
                                                        <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                                        <?php if ($have_status == false) { ?>
                                                            <div data-status_sid="1" data-status_class="not_contacted" data-status_name="Not Contacted Yet" class="label applicant not_contacted">
                                                                <div id="status">Not Contacted Yet</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="2" data-status_class="left_message" data-status_name="Left Message" class="label applicant left_message">
                                                                <div id="status">Left Message</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="3" data-status_class="contacted" data-status_name="Contacted" class="label applicant contacted">
                                                                <div id="status">Contacted</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="4" data-status_class="responded" data-status_name="Candidate Responded" class="label applicant responded">
                                                                <div id="status">Candidate Responded</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="5" data-status_class="qualifying" data-status_name="Interviewing" class="label applicant interviewing">
                                                                <div id="status">Interviewing</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="6" data-status_class="submitted" data-status_name="Submitted" class="label applicant submitted">
                                                                <div id="status">Submitted</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="7" data-status_class="interviewing" data-status_name="Qualifying" class="label applicant qualifying">
                                                                <div id="status">Qualifying</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="11" data-status_class="placed" data-status_name="Ready to Hire" class="label applicant placed">
                                                                <div id="status">Ready to Hire</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>


                                                            <div data-status_sid="13" data-status_class="donothire" data-status_name="Do Not Hire" class="label applicant donothire">
                                                                <div id="status">Do Not Hire</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>

                                                            <div data-status_sid="8" data-status_class="offered" data-status_name="Offered Job" class="label applicant offered">
                                                                <div id="status">Offered Job</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="10" data-status_class="decline" data-status_name="Client Declined" class="label applicant decline">
                                                                <div id="status">Client Declined</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="9" data-status_class="notin" data-status_name="Not In Consideration" class="label applicant notin">
                                                                <div id="status">Not In Consideration</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div data-status_sid="12" data-status_class="future_opportunity" data-status_name="Future Opportunity" class="label applicant future_opportunity">
                                                                <div id="status">Future Opportunity</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                        <?php } else { ?>
                                                            <?php foreach ($company_statuses as $status) {  ?>
                                                                <div <?php echo !empty($status['bar_bgcolor'])  ? ' style= "background-color: ' . $status['bar_bgcolor'] . '"' : '' ?> data-status_sid="<?php echo $status['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['name']; ?>" class="label applicant <?php echo $status['css_class']; ?>">
                                                                    <div id="status"><?php echo $status['name']; ?></div>
                                                                    <i class="fa fa-check-square check"></i>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="interview-scoreing">
                                                <?php  //if(check_access_permissions_for_view($security_details, 'resend_screening_questionnaire')) { 
                                                ?>
                                                <div class="rating-score">
                                                    <div class="rating-col">
                                                        <span class="text-left pull-left float_left"><a href="javascript:;" class="questionnaire-popup <?php echo (!empty($employer_job['manual_questionnaire_history']) || !empty($employer_job['questionnaire'])) ? 'text-blue' : 'text-success'; ?>" data-attr="<?= $employer_job["applicant_sid"]; ?>" data-job-sid="<?= $employer_job["job_sid"]; ?>" data-sid="<?= $employer_job["sid"]; ?>" onclick="fLaunchQuestionnaireModal(this)"><span class="float_left">Questionnaire Score:</span></a></span>
                                                    </div>
                                                    <div class="rating-col">
                                                        <span class="pull-right">
                                                            <?php if ($employer_job['questionnaire'] == '' || $employer_job['questionnaire'] == NULL) {
                                                                $job_manual_questionnaire_history = $employer_job['manual_questionnaire_history'];
                                                                $display_result = '';

                                                                if (!empty($job_manual_questionnaire_history)) {
                                                                    foreach ($job_manual_questionnaire_history as $job_man_key => $job_man_value) {
                                                                        $job_man_questionnaire_result   = $job_man_value['questionnaire_result'];
                                                                        $job_man_score                  = $job_man_value['score'];
                                                                        $job_manual_questionnaire       = $job_man_value['questionnaire'];

                                                                        if (($job_manual_questionnaire != NULL || $job_manual_questionnaire != '') && ($job_man_questionnaire_result != NULL || $job_man_questionnaire_result != '')) {
                                                                            $display_result = '<span class="' . strtolower($job_man_questionnaire_result) . ' pull-right">(' . $job_man_questionnaire_result . ')</span>';
                                                                            echo $job_man_score;
                                                                            break;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $display_result = '';
                                                                    echo 'N/A';
                                                                }
                                                            } else {
                                                                $my_questionnaire = unserialize($employer_job['questionnaire']);

                                                                if (isset($my_questionnaire['applicant_sid'])) {
                                                                    $questionnaire_type = 'new';
                                                                    //$questionnaire_score = $employer_job['score'].'/'.$employer_job['passing_score'];
                                                                    $questionnaire_score = $employer_job['score'];
                                                                    $questionnaire_result = $my_questionnaire['questionnaire_result'];
                                                                    $display_result = '<span class="' . strtolower($questionnaire_result) . ' pull-right">(' . $questionnaire_result . ')</span>';
                                                                } else {
                                                                    $questionnaire_type = 'old';
                                                                    $questionnaire_score = $employer_job['score'];

                                                                    if ($employer_job['score'] >= $employer_job['passing_score']) {
                                                                        $display_result = '<span class="pass pull-right">(Pass)</span>';
                                                                    } else {
                                                                        $display_result = '<span class="fail pull-right">(Fail)</span>';
                                                                    }
                                                                } ?>
                                                                <?php echo $questionnaire_score; ?>
                                                            <?php } ?>
                                                        </span>
                                                    </div>
                                                    <div class="rating-col">
                                                        <?php echo $display_result; ?>
                                                    </div>
                                                </div>
                                                <?php //} 
                                                ?>
                                                <?php if (check_access_permissions_for_view($security_details, 'review_score')) { ?>
                                                    <div class="rating-score">
                                                        <div class="rating-col text-left">
                                                            <a href="javascript:void(0);" onclick="fLaunchReviewModal(this)" class="text-left <?php echo ($employer_job['reviews_count'] > 0) ? 'text-blue' : 'text-success'; ?>" data-applicant-sid="<?= $employer_job["applicant_sid"]; ?>" data-applicant-email="<?= $employer_job["email"]; ?>" data-job-sid="<?= $employer_job["sid"]; ?>" data-document-title="Reviews For <?= $employer_job["first_name"]; ?> <?= $employer_job["last_name"]; ?>">Review Score<span class="btn-tooltip">Reviews</span></a>
                                                        </div>
                                                        <div class="rating-col text-center">
                                                            <span class="pull-left"><?php echo ($employer_job['applicant_average_rating'] > 0) ? $employer_job['applicant_average_rating'] : '0'; ?> with <?php echo $employer_job['reviews_count']; ?> Review(s)</span>
                                                        </div>
                                                        <div class="rating-col text-left">
                                                            <span class="start-rating applicant-rating">
                                                                <input readonly="readonly" id="input-21b" <?php if (!empty($employer_job['applicant_average_rating'])) { ?> value="<?php echo $employer_job['applicant_average_rating']; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="rating-score">
                                                    <div class="rating-col text-left">
                                                        <span class="text-left text-success pull-left">Avg. Interview Score :</span>
                                                    </div>
                                                    <div class="rating-col text-center">
                                                        <span class="pull-right"> <?php echo $employer_job['interview_score']['overall_score']; ?> Out of 100 Points</span>
                                                    </div>
                                                    <div class="rating-col text-left">
                                                        <span class="start-rating applicant-rating">
                                                            <input readonly="readonly" id="interview_score_<?php echo $employer_job['sid']; ?>" value="<?php echo $employer_job['interview_score']['star_rating']; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="rating-score">
                                                    <div class="rating-col text-left">
                                                        <span class="text-left text-success pull-left">Video Interview :</span>
                                                    </div>
                                                    <div class="rating-col text-center">
                                                        <span class="pull-right"> <?php echo $employer_job['video_interview_score']; ?> Out of 5 Points</span>
                                                    </div>
                                                    <div class="rating-col text-left">
                                                        <span class="start-rating applicant-rating">
                                                            <input readonly="readonly" id="video_interview_score_<?php echo $employer_job['sid']; ?>" value="<?php echo $employer_job['video_interview_score']; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="applicant-job-description">
                                            <div class="text">
                                                <span>Job Title</span>
                                                <p><?php echo $employer_job['job_title']; ?></p>
                                            </div>
                                        </div>
                                    </article>
                                <?php } ?>
                            </form>
                        <?php } ?>
                    </div>

                    <?php
                    if ($archive == 'archive') {
                        if ($applicant_total > 0) { /*
                            ?>
                            <input type="hidden" name="countainer_count" id="countainer_count" value="<?php echo $applicant_total; ?>">
                            <div class="pagination-container" id="hide_del_row">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                        <div class="delete-all">
                                            <a class="delete-all-btn" href="javascript:;" id="ej_controll_delete">Delete Selected</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                    </div>
                                </div>
                            </div>
                            <?php
                        */
                        }
                    }
                    ?>
                    <div class="pagination-container" id="hide_del_row">
                        <div class="col-xs-12 col-sm-12">
                            <?php if ($job_sid == 'all') { ?>
                                <?php echo $links; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="edit_notes" class="modal-body" style="display: none;">
                <form action="<?php echo base_url('update_notes_from_popup') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-title-section">
                        <h2>Applicant Notes</h2>
                        <div class="form-btns">
                            <input type="submit" name="note_submit" value="Update">
                            <input onclick="cancel_notes()" type="button" name="cancel" value="Cancel">
                        </div>
                    </div>
                    <div class="tab-header-sec">
                        <p class="questionnaire-heading">Miscellaneous Notes</p>
                    </div>
                    <textarea class="ckeditor" name="my_edit_notes" id="my_edit_notes" cols="67" rows="6"></textarea>
                    <input type="hidden" name="sid" id="sid" value="">
                    <input type="hidden" name="employers_sid" id="applicant_sid" value="">
                    <input type="hidden" name="perform_action" value="update">
                    <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Attachment</label>
                            <input type="file" class="filestyle" id="update_notes_attachment" name="notes_attachment" />
                        </div>
                    </div>
                </form>
            </div>
            <div id="add_notes_div" class="modal-body" style="display: none;">
                <form action="<?php echo base_url('update_notes_from_popup') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-title-section">
                        <h2>Applicant Notes</h2>
                        <div class="form-btns">
                            <input type="submit" name="note_submit" value="Save">
                            <input onclick="cancel_notes()" type="button" name="cancel" value="Cancel">
                        </div>
                    </div>
                    <textarea class="ckeditor" name="add_notes" cols="67" rows="6"></textarea>
                    <input type="hidden" name="perform_action" value="add">
                    <input type="hidden" name="employers_sid" id="employer_sid_hidden">
                    <input type="hidden" name="applicant_job_sid" id="employer_job_hidden">
                    <input type="hidden" name="applicant_email" id="employer_email_hidden">
                    <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Attachment</label>
                            <input type="file" class="filestyle" id="insert_notes_attachment" name="notes_attachment" />
                        </div>
                    </div>
                </form>
            </div>
            <div id="document_modal_footer" class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="review_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="review_modal_title">Modal title</h4>
            </div>
            <div id="review_modal_body" class="modal-body">
                ...
            </div>
            <div id="add_review_div" class="modal-body ats-rating start-rating" style="display: none;">
                <form action="<?php echo base_url('insert_review_from_popup') ?>" id="js-review-form" method="POST" enctype="multipart/form-data">
                    <div class="form-title-section">
                        <h2>Applicant Review</h2>
                        <div class="form-btns">
                            <input onclick="cancel_review()" type="button" name="cancel" value="Cancel">
                            <input type="submit" name="review_submit" value="Save">
                        </div>
                    </div>
                    <input id="input-21b" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs" />
                    <textarea class="ckeditor" name="comment" cols="67" rows="6" required></textarea>
                    <input type="hidden" name="perform_action" value="add">
                    <input type="hidden" name="applicant_job_sid" class="applicant_job_sid">
                    <input type="hidden" name="applicant_email" class="applicant_email">
                    <input type="hidden" name="users_type" value="applicant">
                    <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Attachment</label>
                            <input type="file" class="filestyle" id="insert_review_attachment" name="review_attachment" />
                        </div>
                    </div>
                    <div class="row">
                        <br />
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="padding: 0px;">
                                <label for="YouTubeVideo">Select Video:</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                            <input type="radio" name="video_source" class="add_review_video_source" value="no_video" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                            <input type="radio" name="video_source" class="add_review_video_source" value="youtube" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                            <input type="radio" name="video_source" class="add_review_video_source" value="vimeo" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                            <input type="radio" name="video_source" class="add_review_video_source" value="uploaded" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="add_review_youtube_vimeo_input">
                            <label for="YouTube_Video" id="add_review_label_youtube">Youtube Video For This Job:</label>
                            <label for="Vimeo_Video" id="add_review_label_vimeo" style="display: none">Vimeo Video For This Job:</label>
                            <input type="text" name="yt_vm_video_url" class="invoice-fields" id="add_review_yt_vm_video_url" />
                            <div id="add_review_YouTube_Video_hint" class="video-link" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX OR https://www.youtube.com/embed/XXXXXXXXXXX </div>
                            <div id="add_review_Vimeo_Video_hint" class="video-link" style='font-style: italic; display: none'><b>e.g.</b> https://vimeo.com/XXXXXXX OR https://player.vimeo.com/video/XXXXXXX </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="add_review_upload_input">
                            <label for="YouTubeVideo">Upload Video For This Job:</label>
                            <div class="upload-file invoice-fields">
                                <span class="selected-file" id="add_review_name_upload_video">No video selected</span>
                                <input name="add_upload_video" id="add_review_upload_video" onchange="upload_video_checker('add_review_upload_video')" type="file">
                                <a href="javascript:;">Choose Video</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="edit_review_div" class="modal-body ats-rating start-rating" style="display: none;">
                <form action="<?php echo base_url('insert_review_from_popup') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-title-section">
                        <h2>Applicant Review</h2>
                        <div class="form-btns">
                            <input type="submit" class="text-right" name="review_submit" value="Save">
                            <input onclick="cancel_review()" type="button" name="cancel" value="Cancel">
                        </div>
                    </div>
                    <input id="input-rating" type="number" name="rating" class="edit-rating" min=0 max=5 step=0.2 data-size="xs" />
                    <textarea class="ckeditor" name="edit_comment" cols="67" rows="6" required></textarea>
                    <input type="hidden" name="perform_action" value="edit">
                    <input type="hidden" name="applicant_job_sid" class="applicant_job_sid">
                    <input type="hidden" name="applicant_email" class="applicant_email">
                    <input type="hidden" name="users_type" value="applicant">
                    <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Attachment</label>
                            <input type="file" class="filestyle" id="edit_review_attachment" name="review_attachment" />
                        </div>
                    </div>

                    <div class="row">
                        <br />
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="padding: 0px;">
                                <label for="YouTubeVideo">Select Video:</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                            <input type="radio" name="video_source" class="review_video_source" value="no_video" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                            <input type="radio" name="video_source" class="review_video_source" value="youtube" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                            <input type="radio" name="video_source" class="review_video_source" value="vimeo" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                            <input type="radio" name="video_source" class="review_video_source" value="uploaded" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="review_youtube_vimeo_input">
                            <label for="YouTube_Video" id="review_label_youtube">Youtube Video For This Job:</label>
                            <label for="Vimeo_Video" id="review_label_vimeo" style="display: none">Vimeo Video For This Job:</label>
                            <input type="text" name="yt_vm_video_url" class="invoice-fields" id="review_yt_vm_video_url" />
                            <div id="review_YouTube_Video_hint" class="video-link" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX OR https://www.youtube.com/embed/XXXXXXXXXXX </div>
                            <div id="review_Vimeo_Video_hint" class="video-link" style='font-style: italic; display: none'><b>e.g.</b> https://vimeo.com/XXXXXXX OR https://player.vimeo.com/video/XXXXXXX </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="review_upload_input">
                            <label for="YouTubeVideo">Upload Video For This Job:</label>
                            <div class="upload-file invoice-fields">
                                <span class="selected-file" id="review_name_upload_video">No video selected</span>
                                <input name="upload_video" id="review_upload_video" onchange="upload_video_checker('review_upload_video')" type="file">
                                <a href="javascript:;">Choose Video</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div id="review_modal_footer" class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="questionnaire_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="questionnaire_modal_title">Modal title</h4>
            </div>
            <?php if (check_access_permissions_for_view($security_details, 'resend_screening_questionnaire')) { ?>
                <div class="col-lg-12 hidden-print">
                    <div class="row">
                        <form id="sendResendQueForm" action="" method="post">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>Questionnaire:</label>
                                    <select class="form-control" name="questionnaire" id="questionnaire" required>
                                        <option value="">Select Questionnaire</option>
                                        <?php foreach ($questionnaires as $questionnaire) {
                                            $select = '';

                                            if ($questionnaire['que_count'] > 0)
                                                echo '<option value="' . $questionnaire['sid'] . '" ' . $select . ' >' . $questionnaire['name'] . '</option>';
                                        } ?>
                                    </select>
                                    <input type="hidden" name="current-url" value="<?= current_url(); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                <input type="submit" class="submit-btn" value="Resend Questionnaire">
                            </div>
                        </form>
                    </div>
                </div>
            <?php       } ?>
            <div id="questionnaire_modal_body" class="modal-body"></div>
            <div id="questionnaire_modal_footer" class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Bulk Email to Applicants</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100 autoheight">
                                <label>Email Template</label>
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields" name="template" id="template">
                                        <option id="" data-name="" data-subject="" data-body="" value="">Please Select</option>
                                        <?php if (!empty($portal_email_templates)) { ?>
                                            <?php foreach ($portal_email_templates as $template) { ?>
                                                <option id="template_<?php echo $template['sid']; ?>" data-name="<?php echo $template['template_name'] ?>" data-subject="<?php echo $template['subject']; ?>" data-body="<?php echo htmlentities($template['message_body']); ?>" value="<?php echo $template['sid']; ?>"><?php echo $template['template_name']; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option id="template_" data-name="" data-subject="" data-body="" value="">No Custom Template Defined</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </li>
                            <form method='post' id='register-form' name='register-form'>

                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Subject<span class="hr-required red"> * </span></label>
                                            <input type='text' class="invoice-fields" id="bulk_email_subject" name='subject' />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Message<span class="hr-required red"> * </span></label>
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="bulk_email_message" name="bulk_email_message"></textarea>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Attachments</label>
                                    <?php if (!empty($portal_email_templates)) {
                                        foreach ($portal_email_templates as $template) { ?>
                                            <div id="<?php echo $template['sid']; ?>" class="temp-attachment" style="display: none">
                                                <?php if (sizeof($template['attachments']) > 0) {
                                                    foreach ($template['attachments'] as $attachment) { ?>
                                                        <div class="invoice-fields">
                                                            <span class="selected-file"><?php echo $attachment['original_file_name'] ?></span>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="invoice-fields">
                                                        <span class="selected-file">No Attachments</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                    <?php
                                        }
                                    } ?>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Additional Attachments</label>
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file">No file selected</span>
                                        <input type="file" name="message_attachment" id="message_attachment" class="image">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="submit" value="Send Message" id="send-message-email" class="submit-btn" onclick="bulk_email_form_validate()">
                                    </div>
                                </li>
                                <div class="custom_loader">
                                    <div id="loader" class="loader" style="display: none">
                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                        <span>Sending...</span>
                                    </div>
                                </div>

                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="assign_to_manual_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign following Candidate(s) to an Additional Job</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>

                            <form method='post' id='assign_to_manual_form' name='assign_to_manual_form' action="">

                                <div id="manual_applicant_selected">

                                </div>
                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="submit" value="Assign" id="assign_manual_other_btn" class="submit-btn">
                                    </div>
                                </li>

                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="send_candidate_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Candidate Applications Notifications</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-md-3"><b>Select a Receiver</b><span class="hr-required red"> * </span></div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control control--radio">Email
                                                    <input type="radio" name="send_invoice" value="to_email" id="to_email" checked>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control control--radio">Employees
                                                    <input type="radio" name="send_invoice" value="to_employees" id="to_employees">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!--                            <li class="form-col-100 autoheight">-->
                            <!--                                <label>Email Template</label>-->
                            <!--                                <div class="hr-select-dropdown">-->
                            <!--                                    <select class="invoice-fields" name="template" id="template">-->
                            <!--                                        <option id="" data-name=""  data-subject="" data-body="" value="">Please Select</option>-->
                            <!--                                        --><?php //if(!empty($portal_email_templates)) { 
                                                                            ?>
                            <!--                                            --><?php //foreach($portal_email_templates as $template) { 
                                                                                ?>
                            <!--                                                <option id="template_--><?php //echo $template['sid']; 
                                                                                                        ?>
                            <!--" data-name="--><?php //echo $template['template_name']
                                                ?>
                            <!--"  data-subject="--><?php //echo $template['subject']; 
                                                    ?>
                            <!--" data-body="--><?php //echo htmlentities($template['message_body']); 
                                                ?>
                            <!--"  value="--><?php //echo $template['sid']; 
                                                ?>
                            <!--">--><?php //echo $template['template_name']; 
                                        ?>
                            <!--</option>-->
                            <!--                                            --><?php //} 
                                                                                ?>
                            <!--                                        --><?php //} else { 
                                                                            ?>
                            <!--                                            <option id="template_" data-name=""  data-subject="" data-body="" value="">No Custom Template Defined</option>-->
                            <!--                                        --><?php //} 
                                                                            ?>
                            <!--                                    </select>-->
                            <!--                                </div>-->
                            <!--                            </li>-->
                            <form method='post' id='candidate-register-form' name='register-form' autocomplete="off">

                                <li class="form-col-100 autoheight" style="display: none" id="to_email_div">
                                    <div class="row">
                                        <div class="col-md-3"><b>Message To (E-Mail)</b></div>
                                        <div class="col-md-9">
                                            <p>Please enter comma separated values</p>
                                            <input class="invoice-fields" name="toemail" id="toemail" type="text">
                                        </div>
                                    </div>
                                </li>
                                <!-- ***************************** -->
                                <li class="form-col-100 autoheight" style="display: none" id="to_employees_div">
                                    <div class="row">
                                        <div class="col-md-3"><b>Select Employees</b></div>
                                        <div class="col-md-9">
                                            <div class="field-row">
                                                <div class="multiselect-wrp">
                                                    <?php if (sizeof($employees) > 0) { ?>
                                                        <select style="width:350px;" multiple class="chosen-select" tabindex="8" name='employees[]' id='employees'>
                                                            <?php
                                                            foreach ($employees as $employee) {
                                                                if ($employer_sid == $employee['sid']) { ?>
                                                                    <option value="<?php echo $employee['sid']; ?>"><?php echo '(You) ' . $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                                                                <?php
                                                                    continue;
                                                                }
                                                                $access = $employee['is_executive_admin'] == 1 ? 'Executive Admin' : $employee['access_level'];
                                                                ?>
                                                                <option value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name'] . ' ' . $employee['email'] . ' [' . $access . ']'; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <p>No Employee Found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Subject<span class="hr-required red"> * </span></label>
                                            <input type='text' class="invoice-fields" id="candidate_email_subject" value="<?php echo $candidate_notification_template['subject']; ?>" name='candidate_email_subject' />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Message<span class="hr-required red"> * </span></label>
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="candidate_email_message" name="candidate_email_message"><?php echo $candidate_notification_template['text']; ?></textarea>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="submit" value="Send Message" id="send-candidate-message-email" class="submit-btn" onclick="return candidate_email_form_validate()">
                                    </div>
                                </li>
                                <div class="custom_loader">
                                    <div id="candidate-loader" class="candidate-loader" style="display: none">
                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                        <span>Sending...</span>
                                    </div>
                                </div>
                                <input type="hidden" id="from_email" value="<?php echo $candidate_notification_template['from_email'] ?>">

                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="show_applicant_resume" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="resume_modal_title"></h4>
            </div>
            <div class="modal-body" id="resume_modal_body">

            </div>
            <div class="modal-footer" id="resume_modal_footer"></div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    var radio = '';
    $('input[name="send_invoice"]').change(function(e) {
        var div_to_show = $(this).val();
        display(div_to_show);
        radio = div_to_show;
    });

    $("#assign_to_manual_modal").on("hidden", function() {
        console.log('asd');
        $("body").removeClass("ajs-no-overflow");
    });

    function display(div_to_show) {
        $('input[name="subject"]').prop('disabled', false);
        $("#message").removeAttr("disabled");
        $("#submit_button").removeAttr("disabled");
        if (div_to_show == 'to_email') {
            $('#to_email_div').show();
            $("#toemail").prop('required', true);
            $('#to_employees_div').hide();
            //$('#contact_name').show();
        } else if (div_to_show == 'to_employees') {
            $("#toemail").prop('required', false);
            $('#to_email_div').hide();
            $('#to_employees_div').show();

            // disable fields if array is empty
            var emp_size = '<?php echo sizeof($employees); ?>';
            if (emp_size <= 0) {
                $('input[name="subject"]').prop('disabled', true);
                $("#message").attr("disabled", "disabled");
                $("#submit_button").attr("disabled", "disabled");
            }
        }
    }

    $(document).ready(function() {
        var div_to_show = $('input[name="send_invoice"]').val();
        display(div_to_show);
        radio = div_to_show;
        $('#template').on('change', function() {
            var template_sid = $(this).val();
            var msg_subject = $('#template_' + template_sid).attr('data-subject');
            var msg_body = $('#template_' + template_sid).attr('data-body');
            $('#bulk_email_subject').val(msg_subject);
            CKEDITOR.instances.bulk_email_message.setData(msg_body);
            $('.temp-attachment').hide();
            $('#' + template_sid).show();
        });

        $('#message_attachment').on('change', function() {
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });
    });

    $(document).on('shown.bs.collapse', '.collapse', function() {
        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    });

    $(document).on('hidden.bs.collapse', '.collapse', function() {
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });

    <?php if ($archive == 'active') { ?>
        google.load("visualization", "1", {
            packages: ["corechart"]
        });
        google.setOnLoadCallback(draw_area_chart);

        function draw_area_chart() {
            var data = google.visualization.arrayToDataTable(<?php echo $graph; ?>);
            var options = {
                title: 'Company Performance',
                curveType: 'function',
                hAxis: {
                    title: 'Year',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    minValue: 0
                },
                legend: {
                    position: 'top',
                    maxLines: 3
                },
                series: {
                    0: {
                        color: '#0000FF'
                    },
                    1: {
                        color: '#81b431'
                    },
                    2: {
                        color: '#980b1e'
                    },
                    3: {
                        color: '#b4048a'
                    }
                }
            };
            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        google.load("visualization", "1", {
            packages: ["corechart"]
        });
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $chart; ?>);
            var options = {
                title: '',
                is3D: true,
                legend: {
                    position: 'top',
                    maxLines: 3
                },
                slices: {
                    0: {
                        color: '#0000FF'
                    },
                    1: {
                        color: '#980b1e'
                    },
                    2: {
                        color: '#81b431'
                    },
                    3: {
                        color: '#b4048a'
                    }
                }
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

    <?php } ?>

    $(document).on('click', '#add_notes', function() {
        event.preventDefault();
        $('#edit_notes').hide();
        $('#document_modal_body').html('');
        $('#add_notes_div').show();
    });

    $(document).on('click', '#add_review', function() {
        event.preventDefault();
        $('#edit_review_div').hide();
        $('#review_modal_body').html('');
        $('#add_review_div').show();
        $('.add_review_video_source[value="no_video"]').trigger('click');
    });

    function fLaunchNotesModal(source) {
        var applicant_sid = $(source).attr('data-applicant-sid');
        var applicant_email = $(source).attr('data-applicant-email');
        var document_title = $(source).attr('data-document-title');
        var job_sid = $(source).attr('data-job-sid');
        var modal_content = '<input type="submit" class="btn btn-success pull-right" id="add_notes" value="Add note"><br>';
        var footer_content = '';
        var iframe_url = '';
        $('#edit_notes').hide();
        $('#add_notes_div').hide();
        $.ajax({
            type: 'POST',
            url: '<?= base_url('application_tracking_system/ajax_responder') ?>',
            async: false,
            data: {
                applicant_sid: applicant_sid,
                perform_action: 'fetch_applicant_notes'
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.length > 0) {
                    $.each(data, function(index, object) {
                        var file = '';
                        var uploaded_file = object['attachment'];
                        var file_extension = object['attachment_extension'];
                        if (file_extension != '' && file_extension != null) {
                            if (file_extension == 'png' || file_extension == 'jpg' || file_extension == 'jpe' || file_extension == 'jpeg' || file_extension == 'gif' || file_extension == 'doc' || file_extension == 'docx' || file_extension == 'pdf') {
                                file = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + object['attachment'] + '" downloaded="downloaded">Download Attachment</a>';
                            } else if (file_extension == 'mp3' || file_extension == 'aac' || file_extension == 'wav') {
                                var source_type = file_extension == 'mp3' ? 'type="audio/mpeg"' : file_extension == 'ogg' ? 'type="audio/ogg"' : 'type="audio/wav"';
                                file = '<audio width="800" controls><source src="<?php echo AWS_S3_BUCKET_URL; ?>' + uploaded_file + ' " ' + source_type + '> Your browser does not support the audio element.</audio>';
                            }

                        }
                        var response = '<article class="notes-list"><h2><span id="html_' + object['sid'] + '">' + object['notes'] + '</span><p>' + file + '</p><p class="postdate">' + object['insert_date'] + '</p><div class="edit-notes"><a href="javascript:;" style="height: 20px; line-height: 0; color: white; font-size: 10px;" class="grayButton siteBtn notes-btn" onclick="modify_note(' + object['sid'] + ',' + applicant_sid + ')">View/Edit</a><a href="javascript:;" style="height: 20px; line-height: 0; color: white; font-size: 10px;" class="siteBtn notes-btn btncancel" onclick="delete_note(' + object['sid'] + ')">Delete</a></div></h2></article><br>';
                        modal_content = modal_content + response;
                    });
                } else {
                    modal_content = modal_content + '<h5 class="text-center">No ' + document_title + ' Found.</h5>';
                }
            },
            error: function() {

            }
        });

        footer_content = '';

        $('#employer_sid_hidden').val(applicant_sid);
        $('#employer_job_hidden').val(job_sid);
        $('#employer_email_hidden').val(applicant_email);
        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }

    function fLaunchReviewModal(source) {
        var applicant_sid = $(source).attr('data-applicant-sid');
        var applicant_email = $(source).attr('data-applicant-email');
        var document_title = $(source).attr('data-document-title');
        var job_sid = $(source).attr('data-job-sid');
        var modal_content = '<input type="submit" class="btn btn-success pull-right" id="add_review" value="Add Review"><br>';
        var footer_content = '';
        var review_flag = 0;
        $('#edit_review_div').hide();
        $('#add_review_div').hide();
        $.ajax({
            type: 'POST',
            url: '<?= base_url('application_tracking_system/ajax_responder') ?>',
            async: false,
            data: {
                applicant_sid: applicant_sid,
                perform_action: 'fetch_applicant_reviews'
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.length > 0) {
                    $.each(data, function(index, object) {
                        var file = '';
                        var file2 = '';
                        var edit_div = '';
                        var uploaded_file = object['attachment'];
                        var file_extension = object['attachment_extension'];
                        var reviewer_name = object['first_name'] + ' ' + object['last_name'];

                        if (file_extension != '' && file_extension != null) {
                            if (file_extension == 'png' || file_extension == 'jpg' || file_extension == 'jpe' || file_extension == 'jpeg' || file_extension == 'gif' || file_extension == 'doc' || file_extension == 'docx' || file_extension == 'pdf') {
                                file = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + object['attachment'] + '" downloaded="downloaded">Download Attachment</a>';
                            } else if (file_extension == 'mp3' || file_extension == 'aac' || file_extension == 'wav') {
                                var source_type = file_extension == 'mp3' ? 'type="audio/mpeg"' : file_extension == 'ogg' ? 'type="audio/ogg"' : 'type="audio/wav"';
                                file = '<audio width="800" controls><source src="<?php echo AWS_S3_BUCKET_URL; ?>' + uploaded_file + ' " ' + source_type + '> Your browser does not support the audio element.</audio>';
                            } else {
                                file = '<video width="50%" controls><source src="<?php echo AWS_S3_BUCKET_URL; ?>' + uploaded_file + '" type="video/mp4"> Your browser does not support the video element.</video>';
                            }
                        }

                        if (object['source_value'] != '' && object['source_type'] != 'no_video') {
                            if (object['source_type'] == 'uploaded') {
                                file2 = '<video width="50%" controls><source src="<?php echo AWS_S3_BUCKET_URL; ?>' + object['source_value'] + '" type="video/mp4"> Your browser does not support the video element.</video>';
                            } else {
                                file2 = '<iframe src="' + (object['source_value']) + '" frameborder="0" width="50%"></iframe>';
                            }
                        }

                        if (object['employer_sid'] == <?= $employer_sid; ?>) {
                            edit_div = '<div class="edit-notes"><a href="javascript:;" style="height: 20px; line-height: 0; color: white; font-size: 10px;" class="grayButton siteBtn notes-btn" onclick="modify_review(' + object['sid'] + ',' + applicant_sid + ',' + object['rating'] + ',\'' + object['attachment'] + '\',\'' + object['attachment_extension'] + '\',\'' + object['source_type'] + '\',\'' + object['source_value'] + '\')">View/Edit</a></div>';
                            review_flag = 1;
                        }
                        var attachementSection = '';
                        if (file != '' && file2 != '') attachementSection = '<p>' + file + '&nbsp;' + file2 + '</p>';
                        else if (file != '') attachementSection = '<p>' + file + '</p>';
                        else if (file2 != '') attachementSection = '<p>' + file2 + '</p>';


                        var response = '<article class="notes-list ats-rating start-rating"><h1>' + reviewer_name + '</h1><h2><span id="html_' + object['sid'] + '">' + object['comment'] + '</span><input readonly id="input-21b" value=' + object['rating'] + ' type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs" />' + (attachementSection) + '<p class="postdate">' + object['date_added'] + '</p>' + edit_div + '</article><br>';
                        modal_content = modal_content + response;
                    });
                } else {
                    modal_content = modal_content + '<h5 class="text-center">No ' + document_title + ' Found.</h5>';
                }
            },
            error: function() {

            }
        });

        footer_content = '';

        $('.applicant_job_sid').val(applicant_sid);
        $('.applicant_email').val(applicant_email);
        $('#review_modal_body').html(modal_content);
        $('#review_modal_footer').html(footer_content);
        $('#review_modal_title').html(document_title);
        $('.rating').rating();

        if (review_flag) {
            $('#add_review').hide();
        }

        $('#review_modal').modal("toggle");
        $('#review_modal').find('label').css({
            'width': '100%',
            'text-align': 'left'
        });
    }

    function fLaunchQuestionnaireModal(source) {
        var applicant_sid = $(source).attr('data-attr');
        var job_sid = $(source).attr('data-job-sid');
        var sid = $(source).attr('data-sid');
        var footer_content = '';
        var modal_content = '';
        var review_flag = 0;
        $.ajax({
            type: 'POST',
            url: '<?= base_url('application_tracking_system/ajax_responder') ?>',
            async: false,
            data: {
                applicant_sid: applicant_sid,
                perform_action: 'fetch_applicant_questionnaire'
            },
            success: function(data) {
                // data = JSON.parse(data);
                modal_content = data;
                $('#sendResendQueForm').attr('action', '<?= base_url('resend_screening_questionnaire') ?>' + '/' + applicant_sid + '/' + sid + '/' + job_sid + '/0');
            },
            error: function() {

            }
        });

        $('#questionnaire_modal_body').html(modal_content);
        $('#questionnaire_modal_footer').html(footer_content);
        $('#questionnaire_modal_title').html('Screening questionnaire');
        $('#questionnaire_modal').modal("toggle");
    }

    function delete_note(id) {
        url = "<?= base_url() ?>applicant_profile/delete_note";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Note?",
            function() {
                $.post(url, {
                        sid: id
                    })
                    .done(function(data) {
                        location.reload();
                    });
            },
            function() {
                alertify.error('Canceled');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function modify_note(val, applicant_sid) {
        $('#edit_notes').show();
        $('#add_notes_div').hide();
        var edit_note_text = $('#html_' + val).html();
        $('#document_modal_body').html('');
        document.getElementById("sid").value = val;
        document.getElementById("applicant_sid").value = applicant_sid;
        CKEDITOR.instances.my_edit_notes.setData(edit_note_text);
    }

    function modify_review(val, applicant_sid, rating, attachment, attachment_extension, source_type, source_value) {
        $('#edit_review_div').show();
        $('#add_review_div').hide();
        $('.bootstrap-filestyle input[type=text]').val('');
        var edit_note_text = $('#html_' + val).html();
        $('#review_modal_body').html('');
        $('.applicant_job_sid').val(applicant_sid);
        $('.edit-rating').val(rating);
        $('.edit-rating').rating();

        $('.review_video_source').prop('checked', false);
        $('#review_YouTube_Video_hint').hide();
        $('#review_Vimeo_Video_hint').hide();
        $('#review_yt_vm_video_url').hide();

        if (source_type == 'youtube' && source_value != '') {
            $('.review_video_source[value="youtube"]').prop('checked', true);
            $('#review_yt_vm_video_url').val(source_value).show();
            $('#review_YouTube_Video_hint').show();
            $('#review_youtube_vimeo_input').show();
        } else if (source_type == 'vimeo') {
            $('.review_video_source[value="vimeo"]').prop('checked', true);
            $('#review_yt_vm_video_url').val(source_value).show();
            $('#review_Vimeo_Video_hint').show();
            $('#review_youtube_vimeo_input').show();
        } else if (source_type == 'uploaded') {
            $('.review_video_source[value="uploaded"]').prop('checked', true);
            $('#review_upload_input').show();
        } else {
            $('.review_video_source[value="no_video"]').prop('checked', true);
        }

        CKEDITOR.instances.edit_comment.setData(edit_note_text);
    }

    function cancel_notes() {
        $('#document_modal').modal("toggle");
    }

    function cancel_review() {
        $('#review_modal').modal("toggle");
    }

    function show_resume_popup(source) {
        var iframe_url = '';
        var modal_content = '';
        var document_print_url = '';
        var footer_print_content = '';
        var footer_download_content = '';
        var file_extension = $(source).attr('data-file-ext');
        var document_file_name = $(source).attr('data-file-name');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var applicant_sid = $(source).attr('data-applicant-sid');
        var job_list_sid = $(source).attr('data-job-list-sid');
        var request_time = $(source).attr('data-request_datetime');
        var requested_job_sid = $(source).attr('data-requested-job-sid');
        var requested_job_type = $(source).attr('data-requested-job-type');

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'pdf':
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                default:
                    iframe_url = 'https://docs.google.com/gview?url=https://automotohrattachments.s3.amazonaws.com/' + $(source).attr('data-fullname') + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + $(source).attr('data-fullname');
            }

            resume_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            footer_download_content = '<span class="pull-right"><a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a></span>';
            footer_print_content = '<span class="pull-right"><a target="_blank" class="btn btn-success" style="margin-right: 10px;" href="' + document_print_url + '">Print</a></span>';
            var requested_job = "'" + requested_job_type + "'";
            footer_send_resume = '<span class="pull-left"><button target="_blank" class="btn btn-success" onclick="send_applicant_resume_request(' + applicant_sid + ', ' + job_list_sid + ', ' + requested_job_sid + ', ' + requested_job + ')">Send A Resume Request</button></span><div class="clearfix"></div>';

            var request_message = '';

            if (request_time != 'no_date') {
                request_message = '<p class="text-left">The last resume request was sent on <strong> ' + request_time + ' </strong></p>';
            }

            $('#resume_modal_body').html(resume_content);
            $("#resume_iframe").attr("src", iframe_url);
            $('#resume_modal_footer').html(footer_download_content);
            $('#resume_modal_footer').append(footer_print_content);
            $('#resume_modal_footer').append(footer_send_resume);
            $('#resume_modal_footer').append(request_message);
            $('#resume_modal_title').html(document_title);
            $('#show_applicant_resume').modal('show');
            loadIframe(iframe_url, '#preview_iframe', true);
        } else {

            var request_message = '';

            if (request_time != 'no_date') {
                request_message = '<p class="text-left">The last resume request was sent on <strong> ' + request_time + ' </strong></p>';
            }

            alertify.confirm('Resume', '<div class="text-center"><b>No Resume Found!</b></div><br>' + request_message,
                function() {
                    setTimeout(function() {
                        send_applicant_resume_request(applicant_sid, job_list_sid, requested_job_sid, requested_job_type);
                    }, 0);
                },
                function() {

                }).set('labels', {
                ok: 'Send A Resume Request',
                cancel: 'Ok'
            });
        }
    }

    function send_applicant_resume_request(applicant_sid, job_list_sid, requested_job_sid, requested_job_type) {
        alertify.confirm(
            'Confirm',
            'Are you sure you want to send a resume request to this applicant?',
            function() {

                var send_resume_url = '<?= base_url('onboarding/handler') ?>';

                var form_data = new FormData();
                form_data.append('action', 'send_resume_request');
                form_data.append('user_sid', applicant_sid);
                form_data.append('user_type', 'applicant');
                form_data.append('job_list_sid', job_list_sid);
                form_data.append('requested_job_sid', requested_job_sid);
                form_data.append('requested_job_type', requested_job_type);

                $.ajax({
                    url: send_resume_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(resp) {
                        alertify.alert('SUCCESS!', resp.Response, function() {
                            window.location.reload();
                        });
                    },
                    error: function() {}
                });
            },
            function() {

            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });

    };

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    // modal_content = '<object style="width:100%; height:80em;" data="' + iframe_url + '"></object>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    //console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default:
                    //console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    // iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    // modal_content = '<object style="width:100%; height:80em;" data="' + iframe_url + '"></object>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }

    function delete_single_applicant(id) {
        alertify.confirm("Please Confirm Delete", "Are you sure you want to delete applicant?",
            function() {
                url = "<?= base_url('applicant_profile/delete_single_applicant') ?>";
                $.post(url, {
                        del_id: id,
                        action: "del_single_applicant"
                    })
                    .done(function(data) {
                        $('#manual_row' + id).hide();
                        var total_rows = $('#countainer_count').val();
                        total_rows = total_rows - 1;
                        $('#countainer_count').val(total_rows);

                        if (total_rows <= 0) {
                            $('#hide_del_row').hide();
                            $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                        }
                        alertify.notify(data, 'success');
                    });
            },
            function() {
                alertify.error('Cancelled');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function archive_single_applicant(id) {
        alertify.confirm("Please Confirm Archive", "Are you sure you want to archive applicant?",
            function() {
                var myUrl = "<?= base_url('applicant_profile/archive_single_applicant') ?>";
                var myRequest;

                myRequest = $.ajax({
                    url: myUrl,
                    type: 'post',
                    data: {
                        arch_id: id,
                        action: "arch_single_applicant"
                    }
                });

                myRequest.done(function(response) {
                    $('#manual_row' + id).hide();
                    var total_rows = $('#countainer_count').val();
                    total_rows = total_rows - 1;
                    $('#countainer_count').val(total_rows);

                    if (total_rows <= 0) {
                        $('#hide_del_row').hide();
                        $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                    }

                    alertify.notify(response, 'success');
                });
            },
            function() {
                alertify.error('Cancelled');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function active_single_applicant(id) {
        alertify.confirm("Please Confirm Reactive", "Are you sure you want to reactive applicant?",
            function() {
                url = "<?= base_url('applicant_profile/active_single_applicant'); ?>";
                $.post(url, {
                        active_id: id,
                        action: "active_single_applicant"
                    })
                    .done(function(data) {
                        $('#manual_row' + id).hide();
                        var total_rows = $('#countainer_count').val();
                        total_rows = total_rows - 1;
                        $('#countainer_count').val(total_rows);

                        if (total_rows <= 0) {
                            $('#hide_del_row').hide();
                            $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                        }
                        alertify.notify(data, 'success');
                    });
            },
            function() {
                alertify.error('Cancelled');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function candidate_email_form_validate() {
        if (radio == 'to_email') {
            $("#candidate-register-form").validate({
                ignore: [],
                rules: {
                    to_email: {
                        required: true
                    },
                    candidate_email_subject: {
                        required: true
                    },
                    candidate_email_message: {
                        required: function() {
                            CKEDITOR.instances.candidate_email_message.updateElement();
                        },
                        minlength: 10
                    }
                },
                messages: {
                    to_email: {
                        required: 'Email is required'
                    },
                    candidate_email_subject: {
                        required: 'E-Mail Subject is required'
                    },
                    candidate_email_message: {
                        required: "E-Mail Message is required",
                        minlength: "Please enter few characters"
                    }
                },
                submitHandler: function() {
                    var ids = [{}];
                    var list_ids = [{}];
                    var counter = 0;
                    var job_titles = [{}];

                    $.each($(".ej_checkbox:checked"), function() {
                        job_titles[counter] = $(this).attr('data-job_title');
                        ids[counter] = $(this).val();
                        list_ids[counter++] = $(this).attr('data-list');

                        //  job_titles[$(this).val()] = $(this).attr('data-job_title');
                    });

                    var subject = ($('#candidate_email_subject').val()).trim();
                    var toemail = $('#toemail').val().trim();
                    var message = ($('#candidate_email_message').val()).trim();
                    var from_email = ($('#from_email').val()).trim();

                    var form_data = new FormData();
                    form_data.set('subject', subject);
                    form_data.set('ids', ids);
                    form_data.set('list_ids', list_ids);
                    form_data.set('to_email', toemail);
                    form_data.set('action', 'candidate_bulk_email');
                    form_data.set('message', message);
                    form_data.set('job_titles', job_titles);
                    form_data.set('from_email', from_email);

                    $('#candidate-loader').show();
                    $('#send-candidate-message-email').addClass('disabled-btn');
                    $('#send-candidate-message-email').prop('disabled', true);
                    url_to = "<?= base_url() ?>send_manual_email/send_candidate_email";
                    $.ajax({
                        url: url_to,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        data: form_data,
                        success: function(response) {
                            $("#send_candidate_email_modal .close").click();
                            $('#candidate-loader').hide();
                            $('#send-candidate-message-email').removeClass('disabled-btn');
                            $('#send-candidate-message-email').prop('disabled', false);
                            alertify.success('Candidate Information is sent to selected employee(s).');
                        },
                        error: function() {}
                    });
                }
            });
        } else if (radio == 'to_employees') {
            $("#candidate-register-form").validate({
                ignore: [],
                rules: {
                    candidate_email_subject: {
                        required: true
                    },
                    candidate_email_message: {
                        required: function() {
                            CKEDITOR.instances.candidate_email_message.updateElement();
                        },
                        minlength: 10
                    }
                },
                messages: {
                    candidate_email_subject: {
                        required: 'E-Mail Subject is required'
                    },
                    candidate_email_message: {
                        required: "E-Mail Message is required",
                        minlength: "Please enter few characters"
                    }
                },
                submitHandler: function() {
                    var items_length = $('#employees :selected').length;

                    if (items_length == 0) {
                        alertify.alert('Error! Employees Missing', "Employees cannot be Empty");
                        return false;
                    }

                    var ids = [{}];
                    var counter = 0;
                    var job_titles = [{}];

                    $.each($(".ej_checkbox:checked"), function() {
                        job_titles[counter] = $(this).attr('data-job_title');
                        ids[counter++] = $(this).val();
                        // job_titles[$(this).val()] =$(this).attr('data-job_title');
                    });

                    var subject = ($('#candidate_email_subject').val()).trim();
                    var employee = $('#employees').val();
                    var message = ($('#candidate_email_message').val()).trim();
                    var from_email = ($('#from_email').val()).trim();

                    var form_data = new FormData();
                    form_data.set('subject', subject);
                    form_data.set('ids', ids);
                    form_data.set('employee', employee);
                    form_data.set('action', 'candidate_bulk_email');
                    form_data.set('message', message);
                    form_data.set('job_titles', job_titles);
                    form_data.set('from_email', from_email);

                    $('#candidate-loader').show();
                    $('#send-candidate-message-email').addClass('disabled-btn');
                    $('#send-candidate-message-email').prop('disabled', true);
                    url_to = "<?= base_url() ?>send_manual_email/send_candidate_email";
                    $.ajax({
                        url: url_to,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        data: form_data,
                        success: function(response) {
                            $("#send_candidate_email_modal .close").click();
                            $('#candidate-loader').hide();
                            $('#send-candidate-message-email').removeClass('disabled-btn');
                            $('#send-candidate-message-email').prop('disabled', false);
                            alertify.success('Candidate Information is sent to selected employee(s).');
                        },
                        error: function() {}
                    });
                }
            });
        }

    }

    function bulk_email_form_validate() {
        $("#register-form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true
                },
                bulk_email_message: {
                    required: function() {
                        CKEDITOR.instances.bulk_email_message.updateElement();
                    },
                    minlength: 10
                }
            },
            messages: {
                subject: {
                    required: 'E-Mail Subject is required'
                },
                bulk_email_message: {
                    required: "E-Mail Message is required",
                    minlength: "Please enter few characters"
                }
            },
            submitHandler: function() {
                var ids = [{}];
                var counter = 0;

                $.each($(".ej_checkbox:checked"), function() {
                    ids[counter++] = $(this).val();
                });
                var file_data = $('#message_attachment').prop('files')[0];
                var subject = ($('#bulk_email_subject').val()).trim();
                var message = ($('#bulk_email_message').val()).trim();
                var template = $('#template').val();
                var form_data = new FormData();
                form_data.append('message_attachment', file_data);
                form_data.append('subject', subject);
                form_data.append('ids', ids);
                form_data.append('action', 'bulk_email');
                form_data.append('message', message);
                form_data.append('template_id', template);
                $('#loader').show();
                $('#send-message-email').addClass('disabled-btn');
                $('#send-message-email').prop('disabled', true);
                url_to = "<?= base_url() ?>send_manual_email/send_bulk_email";
                $.ajax({
                    url: url_to,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(response) {
                        $("#bulk_email_modal .close").click();
                        $('#loader').hide();
                        $('#send-message-email').removeClass('disabled-btn');
                        $('#send-message-email').prop('disabled', false);
                        alertify.success('Bulk email sent to selected applicant(s).');
                    },
                    error: function() {}
                });
                // $.post(url_to, {action: "bulk_email", ids: ids, subject: subject, message: message, message_attachment: file_data})
                // .done(function (response) {
                // $("#bulk_email_modal .close").click();
                // alertify.success('Bulk email sent to selected applicant(s).');
                // });
                return false;
            }
        });
    }

    $("#assign_to_manual_form").validate({
        ignore: ":hidden:not(select)",
        submitHandler: function(form) {
            var ajax_data = new Object();
            $.each($('.manual_candidate_job'), function(index, object) {
                ajax_data[$(this).attr('data-attr')] = $(this).val();
            });
            $.ajax({
                url: '<?= base_url('manual_candidate/add_to_other_job'); ?>',
                type: 'POST',
                data: {
                    selected_candidates: ajax_data
                },
                success: function(data) {
                    if (data == 'added') {
                        alertify.success("Candidate(s) Assigned to Additional Job");
                        $('#assign_to_manual_modal').modal('toggle');
                        $("body").removeClass("ajs-no-overflow");
                        location.reload();
                    }
                },
                error: function() {
                    alertify.error('Something went wrong.');
                }
            });
        }
    });

    //    $(document).on('click','#assign_manual_other_btn',function(e) {
    //        e.preventDefault();
    //        var ajax_data = new Object();
    //        $.each($('.manual_candidate_job'), function(index, object){
    //            ajax_data[$(this).attr('data-attr')] = $(this).val();
    //        });
    //        $.ajax({
    //            url:'<? //= base_url('manual_candidate/add_to_other_job'); 
                        ?>//',
    //            type: 'POST',
    //            beforeSubmit: $("#assign_to_manual_form").validate(),
    //            data:{
    //                selected_candidates: ajax_data
    //            },
    //            success: function(data){
    //                if(data == 'added'){
    //                    alertify.success("Candidates Assignerd to Additional Job")
    //                    $('#assign_to_manual_modal').modal('toggle');
    //                }
    //            },
    //            error: function(){
    //
    //            }
    //        });
    //    });

    $(document).ready(function() {
        $('.applicant_sids').each(function() {
            $(this).on('click', function() {
                var selected = $('.applicant_sids:checked');
                var sids = [];
                selected.each(function() {
                    var sid = $(this).val();

                    if (sids.indexOf(sid) < 0) {
                        sids.push(sid);
                    }
                });

                var url = '<?php echo base_url('task_management/assign_applicant'); ?>';
                url = url + '/' + encodeURIComponent(sids.join(','));
                $('#btn_assign_selected').attr('href', url);
            });
        });

        $('.close').click(function() {
            $("body").removeClass("ajs-no-overflow");
        });
        $('body').click(function() {
            $("body").removeClass("ajs-no-overflow");
        });

        $('#jobs_list, #status, #job_fit_category, #app-type, #fair-type, #emp-app-status, #ques-status').on('change', function() {
            var js_job_id = $('#jobs_list').val();
            var job_fit_category = $('#job_fit_category').val();
            var app_type = encodeURIComponent($('#app-type').val());
            var fair_type = encodeURIComponent($('#fair-type').val());
            var emp_app_status = encodeURIComponent($('#emp-app-status').val());
            var ques_status = encodeURIComponent($('#ques-status').val());

            if (job_fit_category == 0) {
                job_fit_category = 'all';
            }

            var status = $('#status').val();
            status = encodeURIComponent(status);
            js_job_id = encodeURIComponent(js_job_id);

            if (js_job_id == 'null') {
                js_job_id = 'all';
            }
            //var str = window.location.href;
            var myUrl = "<?php echo base_url('application_tracking_system') . '/' . $archive; ?>" + "/all/" + js_job_id + "/" + status + "/" + job_fit_category + "/" + app_type + "/" + fair_type + "/" + ques_status + "/" + emp_app_status;
            $('#filter-btn').attr('href', myUrl);
        });

        $('#keyword, #my_submit_btn').on('keyup, click', function() {
            var keyword = $('#keyword').val();

            if (keyword == '' || keyword == undefined || keyword == null) {
                keyword = 'all';
            }

            var myurl = '<?php echo base_url("application_tracking_system") . '/' . $archive; ?>' + '/' + keyword + '/all/all/all';
            $('#my_submit_btn').attr('href', myurl);
        });

        $('#keyword').keyup(function(event) {
            if (event.keyCode == '13') {
                var keyword = $('#keyword').val();

                if (keyword == '' || keyword == undefined || keyword == null) {
                    keyword = 'all';
                }

                var myurl = '<?php echo base_url("application_tracking_system") . '/' . $archive; ?>' + '/' + keyword + '/all/all/all';
                $('#my_submit_btn').attr('href', myurl);
                document.getElementById('my_submit_btn').click();
            }
        });

        $('.show-status-box').click(function() {
            $(this).next().show();
        });


        $('#send_rej_email').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to send rejection email to selected Applicant(s)?",
                    function() {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function() {
                            ids[counter++] = $(this).val();
                        });

                        url_to = "<?= base_url() ?>send_manual_email";
                        $.post(url_to, {
                                action: "rejection_letter",
                                ids: ids
                            })
                            .done(function(response) {
                                alertify.success(response);
                            });
                        // alertify.success('Rejection email sent to selected applicants.');
                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.alert('Send Rejection Email Error', 'Please select Applicant(s) to send rejection email.');
            }
        });

        $('#send_ack_email').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to send acknowledgement email to selected Applicant(s)?",
                    function() {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function() {
                            ids[counter++] = $(this).val();
                        });

                        url_to = "<?= base_url() ?>send_manual_email";
                        $.post(url_to, {
                                action: "application_acknowledgement_letter",
                                ids: ids
                            })
                            .done(function(response) {
                                alertify.success(response);
                            });

                        // alertify.success('Acknowledgement email sent to selected applicants.');
                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.alert('Send Acknowledgment Email Error', 'Please select Applicant(s) to send acknowledgement email.');
            }
        });

        $('#assign_to_manual').click(function() {
            <?php
            $my_select = '';
            foreach ($all_jobs as $job) {
                if (!isset($job['Title'])) continue;
                $options = '';
                if ($jobs_approval_module_status == '1') {
                    if ($job['approval_status'] == 'approved') {
                        $options = '<option value="' . $job["sid"] . '">' . $job['Title'] . ($job['active'] == 1 ? ' [Active] ' : ' [Inactive] ') . '</option>';
                    } ?>
            <?php } else {
                    $options = '<option value="' . $job["sid"] . '">' . $job['Title'] . ($job['active'] == 1 ? ' [Active] ' : ' [Inactive] ') . '</option>';
                }
                $my_select = $my_select . $options;
            } ?>
            var manual_candidate_job_select = '<option value="">Please Select</option>' + '<?php echo $my_select; ?>';

            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm: Assign Additional Job', "Are you sure you want to assign additional job to selected Applicant(s)?",
                    function() {
                        var name_select = '';
                        $.each($(".ej_checkbox:checked"), function() {
                            // ids[counter++] = $(this).val();
                            name_select += '<li class="form-col-100 autoheight"> <label>Applicant Name: ' + $(this).attr('data-applicant-name') + '</label> <div class="hr-select-dropdown"><select data-attr="' + $(this).val() + '" required data-require="1" class="invoice-fields manual_candidate_job" name="manual_candidate_job' + $(this).val() + '" id="manual_candidate_job' + $(this).val() + '">' + manual_candidate_job_select + '</select></div> </li>';

                        });
                        $('#manual_applicant_selected').html(name_select);
                        $('#assign_to_manual_modal').modal('toggle');

                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.error('Please select Applicant(s) to assign to additional job.');
            }
        });

        $('#send_bulk_email').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm Bulk E-Mail', "Are you sure you want to send bulk email to selected Applicant(s)?",
                    function() {
                        setTimeout(toggle_bulk_email_modal, 1000);
                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.error('Please select Applicant(s) to send bulk email.');
            }
        });

        $('#send_candidate_email').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm Candidate Application E-Mail', "Are you sure you want to send selected Applicant(s) information?",
                    function() {
                        setTimeout(toggle_candidate_notification_modal, 1000);

                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.error('Please select Applicant(s).');
            }
        });

        $('#archive_selected').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm Candidate Archive', "Are you sure you want to archive selected Applicant(s) information?",
                    function() {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function() {
                            ids[counter++] = $(this).attr('data-list');
                        });
                        var url_to = "<?= base_url() ?>application_tracking_system/ajax_responder";
                        $.ajax({
                            url: url_to,
                            type: 'POST',
                            data: {
                                arch_id: ids,
                                perform_action: "arch_bulk_applicants"
                            },
                            success: function(response) {
                                if (response == 'success') {
                                    window.location = window.location.href;
                                } else {
                                    alertify.error('Something went wrong!');
                                }
                            },
                            error: function() {

                            }
                        });

                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.error('Please select Applicant(s).');
            }
        });

        $('#activate_selected').click(function() {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm Candidate Activate', "Are you sure you want to activate selected Applicant(s) information?",
                    function() {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function() {
                            ids[counter++] = $(this).attr('data-list');
                        });
                        var url_to = "<?= base_url() ?>application_tracking_system/ajax_responder";
                        $.ajax({
                            url: url_to,
                            type: 'POST',
                            data: {
                                active_id: ids,
                                perform_action: "active_bulk_applicants"
                            },
                            success: function(response) {
                                if (response == 'success') {
                                    window.location = window.location.href;
                                } else {
                                    alertify.error('Something went wrong!');
                                }
                            },
                            error: function() {

                            }
                        });

                    },
                    function() {
                        alertify.error('Cancelled');
                    }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                alertify.error('Please select Applicant(s).');
            }
        });

        function toggle_bulk_email_modal() {
            $('#bulk_email_modal').modal('toggle')
        }

        function toggle_candidate_notification_modal() {
            $('#send_candidate_email_modal').modal('toggle')
        }

        $('#ej_controll_delete').click(function() {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to delete application(s)?",
                        function() {
                            $('#ej_form').append('<input type="hidden" name="delete_contacts" value="true" />');
                            $("#ej_form").submit();
                            alertify.success('Deleted');
                        },
                        function() {
                            alertify.error('Cancelled');
                        }).set('labels', {
                        ok: 'Yes',
                        cancel: 'No'
                    });
                }
            } else {
                alertify.alert('Please select application(s) to delete');
            }
        });

        $('.selected').click(function() {
            $(this).next().next().css("display", "block");
        });

        $('.candidate').click(function() {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            var status = $(this).find('#status').html();
            // var id = $(this).parent().find('#id').html();
            var id = $(this).parent().find('#id').text();

            url = "<?= base_url() ?>application_tracking_system/update_status";
            $.post(url, {
                    "id": id,
                    "status": status,
                    "action": "ajax_update_status_candidate"
                })
                .done(function(data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.candidate').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function() {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            //var status = $(this).find('#status').html();
            // var id = $(this).parent().find('#id').html();
            var id = $(this).parent().find('#id').text();

            var status_name = $(this).attr('data-status_name');
            if (status_name.replace(/[^a-z]/ig, '').toLowerCase() == "donothire") {
                $("#manual_row" + id).addClass("donothirebox ");
            } else {
                $("#manual_row" + id).removeClass("donothirebox ");
            }
            var status_sid = $(this).attr('data-status_sid');
            var my_url = "<?= base_url() ?>application_tracking_system/update_status";
            var my_request;

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: {
                    "id": id,
                    "status": status_name,
                    "status_sid": status_sid,
                    "action": "ajax_update_status"
                }
            });

            my_request.done(function(response) {
                if (response == 'success' || response == 'Done') {
                    alertify.success("Candidate status updated successfully.");
                } else {
                    alertify.error("Could not update Candidate Status.");
                }
            });
        });

        $('.applicant').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function() {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function() {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function() {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });

        //$(".chosen-select").chosen().change(function () {});

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    });
    // Format Phone Number
    // @param phone_number
    // The phone number string that
    // need to be reformatted
    // @param format
    // Match format
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        //
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else {
            var af = '',
                an = '',
                cur = 1;
            if (cleaned.substring(0, 1) != '') {
                af += "(_";
                an += '(' + cleaned.substring(0, 1);
                cur++;
            }
            if (cleaned.substring(1, 2) != '') {
                af += "_";
                an += cleaned.substring(1, 2);
                cur++;
            }
            if (cleaned.substring(2, 3) != '') {
                af += "_) ";
                an += cleaned.substring(2, 3) + ') ';
                cur = cur + 3;
            }
            if (cleaned.substring(3, 4) != '') {
                af += "_";
                an += cleaned.substring(3, 4);
                cur++;
            }
            if (cleaned.substring(4, 5) != '') {
                af += "_";
                an += cleaned.substring(4, 5);
                cur++;
            }
            if (cleaned.substring(5, 6) != '') {
                af += "_-";
                an += cleaned.substring(5, 6) + '-';
                cur = cur + 2;
            }
            if (cleaned.substring(6, 7) != '') {
                af += "_";
                an += cleaned.substring(6, 7);
                cur++;
            }
            if (cleaned.substring(7, 8) != '') {
                af += "_";
                an += cleaned.substring(7, 8);
                cur++;
            }
            if (cleaned.substring(8, 9) != '') {
                af += "_";
                an += cleaned.substring(8, 9);
                cur++;
            }
            if (cleaned.substring(9, 10) != '') {
                af += "_";
                an += cleaned.substring(9, 10);
                cur++;
            }

            if (is_return) return match === null ? false : true;

            return {
                number: default_number.replace(af, an),
                cur: cur
            };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if (elem != null) {
            if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            } else {
                if (elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }



    //
    $('#send_still_interested_email').click(function() {
        if ($(".ej_checkbox:checked").size() > 0) {
            alertify.confirm('Confirmation', "Are you sure you want to send still interested email to selected Applicant(s)?",
                function() {
                    var ids = [{}];
                    var counter = 0;
                    $.each($(".ej_checkbox:checked"), function() {
                        ids[counter++] = $(this).val();
                    });

                    var ids = [{}];
                    var list_ids = [{}];
                    var counter = 0;
                    var job_titles = [{}];
                    var job_ids = [{}];

                    $.each($(".ej_checkbox:checked"), function() {
                        job_titles[counter] = $(this).attr('data-job_title');
                        ids[counter] = $(this).val();
                        list_ids[counter++] = $(this).attr('data-list');
                        job_ids[counter] = $(this).attr('data-job_id');
                    });

                    var form_data = new FormData();
                    form_data.set('ids', ids);
                    form_data.set('list_ids', list_ids);
                    form_data.set('job_titles', job_titles);
                    form_data.set('job_ids', job_ids);

                    $('#candidate-loader').show();
                    url_to = "<?= base_url() ?>send_manual_email/send_still_interested_email";
                    $.ajax({
                        url: url_to,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        data: form_data,
                        success: function(response) {
                            $('#candidate-loader').hide();
                            alertify.success('Success: Email sent to selected applicants.');
                        },
                        error: function() {}
                    });

                },
                function() {
                    alertify.error('Cancelled');
                }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        } else {
            alertify.alert('Send Still Interested Email Error', 'Please select Applicant(s) to send Are You Still Interested  email.');
        }

    });

</script>



<script>
    function fun_hire_applicant(comp_sid, app_sid, app_job_sid) {
        alertify.confirm(
            'Are you Sure?',
            'By selecting this option the Candidate will skip the onboarding process. Are you sure you want to directly hire this Candidate?',
            function() {
                var hiring_url = "<?php echo base_url('hire_onboarding_applicant/hire_applicant_manually'); ?>";

                $.ajax({
                    type: 'POST',
                    data: {
                        applicant_sid: app_sid,
                        applicant_job_sid: app_job_sid,
                        company_sid: comp_sid
                    },
                    url: hiring_url,
                    success: function(data) {
                        if (data == 'success') {
                            window.location.href = '<?php echo base_url("application_tracking_system/active/all/all/all/all/all/all/all/all/all"); ?>';
                            //alertify.success('Applicant is successfully hired!');
                            // setTimeout(function(){
                            //     window.location.href = '<?php //echo base_url("application_tracking_system/active/all/all/all/all/all/all/all/all/all"); 
                                                            ?>';
                            // }, 1000);
                        } else if (data == 'failure_e') {
                            alertify.error('Error! The E-Mail address of the applicant is already registered at your company as employee!');
                        } else if (data == 'failure_f') {
                            alertify.error('Could not found applicant data, Please try again!');
                        } else if (data == 'failure_i') {
                            alertify.error('Could not move applicant to employee due to database error, Please try again!');
                        } else if (data == 'error') {
                            alertify.error('Could not found applicant information, Please try again!');
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Cancelled!');
            }).set('labels', {
            ok: 'YES!',
            cancel: 'NO'
        });
    }

    $(function() {
        var megaOBJ = {},
            targetForm = $('.js-sms-form'),
            targetModal = $('.js-sms-modal'),
            targetSMSArea = $('.js-sms-area'),
            targetTriggerForm = $('.js-sms-form-btn'),
            have_all_records,
            last_fetched_id = 0;
        //
        $('.js-sms-btn').click(function() {
            megaOBJ = {};
            megaOBJ.id = $(this).data('id');
            megaOBJ.applicant_id = $(this).data('applicant-id');
            megaOBJ.name = $(this).data('name');
            megaOBJ.phone_e16 = megaOBJ.phone_e16 = $(this).data('phone');

            have_all_records = true;
            last_fetched_id = 0;

            megaOBJ.phone_e16 = megaOBJ.phone_e16.toString().replace(/[^0-9]/g, '');
            //
            if (megaOBJ.phone_e16.toString().length >= 10 && megaOBJ.phone_e16.toString().substr(0, 1) == 1) {
                megaOBJ.phone_e16 = megaOBJ.phone_e16.toString().substr(1, 10);
            }

            <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                var is_valid = fpn(megaOBJ.phone_e16.toString(), null, true);

                if (is_valid) {
                    var t = fpn(megaOBJ.phone_e16.toString().replace(/\D/, '').substr(1));
                    megaOBJ.phone = typeof(t) === 'object' ? t.number : t;
                } else {
                <?php } ?>
                var tmp_phone = fpn(megaOBJ.phone_e16.toString());
                megaOBJ.phone = typeof(tmp_phone) === 'object' ? tmp_phone.number : tmp_phone;
                <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                }
            <?php } else { ?>
                var is_valid = true;
            <?php } ?>



            // Set button
            var btn_row = '<div class="row js-sms-view-btn" ' + (!is_valid ? 'style="display: none;"' : '') + '><div class="col-sm-12"><a href="javascript:void(0);" class="btn btn-success pull-right js-sms-form-btn">Send SMS</a></div></div>',
                // Loader
                loader_row = '<div class="cs-modal-loader js-modal-loader"><i class="fa fa-spinner fa-spin"></i></div>',
                // Set table structure
                table_row = '<div class="row js-sms-view-area" ' + (!is_valid ? 'style="display: none;"' : '') + '><div class="col-sm-12" style="margin: 20px 0;"><div class="js-sms-area"><p class="text-center">Please wait while we are loading conversation....</p></div></div></div>';
            //
            send_sms_box = '';
            send_sms_box += '<div class="row js-sms-view-form" style="display: none;">';
            send_sms_box += '   <div class="col-sm-12">';
            send_sms_box += loader_row;
            send_sms_box += '       <form action="javascript:void(0)" id="js-sms-form">';
            send_sms_box += '           <div class="form-group">';
            send_sms_box += '               <label>Receiver Primary Number</label>';
            send_sms_box += '               <div class="input-group">';
            send_sms_box += '                   <div class="input-group-addon">+1</div>';
            send_sms_box += '                   <input type="text" disabled="true" name="txt_phone" class="form-control" value="' + (megaOBJ.phone) + '" />';
            send_sms_box += '               </div>';
            send_sms_box += '           </div>';
            send_sms_box += '           <div class="form-group">';
            send_sms_box += '               <label>Message</label>';
            send_sms_box += '               <textarea class="form-control" name="txt_message" placeholder="Write the message here..." rows="5"></textarea>';
            send_sms_box += '           </div>';
            send_sms_box += '           <div class="form-group">';
            send_sms_box += '               <label>&nbsp</label>';
            send_sms_box += '               <input type="submit" class="btn btn-success" value="Send SMS" />';
            send_sms_box += '               <input type="button" class="btn btn-default js-sms-cancel-btn" value="Cancel" />';
            send_sms_box += '           </div>';
            send_sms_box += '           <div class="form-group">';
            send_sms_box += '               <p class="text-center">OR</p>';
            send_sms_box += '           </div>';
            send_sms_box += '           <div class="form-group">';
            send_sms_box += '               <button type="button" class="btn btn-success form-control jsSMSUpdateNumber">Update Number</button>';
            send_sms_box += '           </div>';
            send_sms_box += '       </form>';
            send_sms_box += '   </div>';
            send_sms_box += '</div>';


            var update_phone_box = '';
            update_phone_box += '<div class="row js-phone-form-view" style="display: ' + (!is_valid ? 'block' : 'none') + ';">';
            update_phone_box += '   <div class="col-sm-12">';
            update_phone_box += loader_row;
            update_phone_box += '       <form action="javascript:void(0)" id="js-phone-update-form">';
            update_phone_box += '           <div class="form-group">';
            update_phone_box += '               <label>Receiver Phone Number</label>';
            <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                update_phone_box += '               <div class="input-group">';
                update_phone_box += '                   <div class="input-group-addon">+1</div>';
                update_phone_box += '                   <input type="text" name="txt_phone" class="form-control" id="js-phone-update-input" value="' + (megaOBJ.phone) + '" />';
                update_phone_box += '               </div>';
            <?php } else { ?>
                update_phone_box += '                   <input type="text" name="txt_phone" class="form-control" id="js-phone-update-input" value="' + (megaOBJ.phone) + '" />';
            <?php } ?>
            update_phone_box += '               <span class="cs-error js-error"></span>';
            update_phone_box += '           </div>';
            update_phone_box += '           <div class="form-group">';
            update_phone_box += '               <label>&nbsp</label>';
            update_phone_box += '               <input type="submit" class="btn btn-success" value="Update Number" />';
            update_phone_box += '           </div>';
            update_phone_box += '       </form>';
            update_phone_box += '   </div>';
            update_phone_box += '</div>';

            // Set modal view
            var modal = '';
            modal += '<div class="modal fade" id="js-sms-modal">';
            modal += '    <div class="modal-dialog modal-' + (!is_valid ? 'sm' : 'lg') + '">';
            modal += '        <div class="modal-content">';
            modal += '            <div class="modal-header modal-header-bg">';
            modal += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            modal += '                <h4 class="modal-title">SMS to ' + (megaOBJ.name) + '</h4>';
            modal += '            </div>';
            modal += '            <div class="modal-body">';
            modal += btn_row;
            modal += send_sms_box;
            modal += update_phone_box;
            modal += table_row;
            modal += '            </div>';
            modal += '            <div class="modal-footer">';
            modal += '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            modal += '            </div>';
            modal += '        </div>';
            modal += '    </div>';
            modal += '</div>';

            $('#js-sms-modal').remove();
            $('body').append(modal);
            // Fetch previous records - TODO
            fetch_previous_conversation(megaOBJ);
            $('#js-sms-modal').modal();
        });

        //
        $(document).on('click', '.jsSMSUpdateNumber', function(e) {
            e.preventDefault();
            $('.js-phone-form-view').show(0);
            $('.js-sms-view-form').hide(0);
            $('.js-sms-view-area').hide();
        });

        //
        $(document).on('click', '.js-sms-form-btn', function() {
            $('#js-sms-modal').find('div.modal-dialog').switchClass('modal-lg', 'modal-sm');
            $('.js-sms-area').parent().parent().fadeOut(0);
            $('#js-sms-form').parent().parent().fadeIn(300);
            $(this).fadeOut(0);
        });

        //
        $(document).on('click', '.js-sms-cancel-btn', function() {
            $('.js-sms-area').parent().parent().fadeIn(300);
            $('#js-sms-form').parent().parent().fadeOut(0);
            $('.js-sms-form-btn').fadeIn(300);
            $('#js-sms-modal').find('.modal-dialog').switchClass('modal-sm', 'modal-lg');
        });

        //
        $(document).on('submit', '#js-sms-form', function(e) {
            e.preventDefault();
            var targetPhone = $(this).find('input[name="txt_phone"]'),
                targetMessage = $(this).find('textarea[name="txt_message"]');
            //
            targetPhone.parent().find('span').remove();
            targetMessage.parent().find('span').remove();
            // Validation
            if (megaOBJ.phone_e16 == '') {
                targetPhone.after('<span class="cs-error">Phone number is required.</span>');
            }
            <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                else if (!fpn(megaOBJ.phone_e16, '', true)) {
                    targetPhone.after('<span class="cs-error">Phone number format is invalid. (XXX) XXX-XXXX</span>');
                }
            <?php } ?>
            else if (targetMessage.val().trim() == '') {
                targetMessage.after('<span class="cs-error">Message is required and can not be empty.</span>');
            } else {
                $('.js-modal-loader').show();
                $('#js-sms-modal').find('.btn').addClass('disabled').prop('disabled', true);
                megaOBJ.message = targetMessage.val();
                megaOBJ.type = 'applicant';
                megaOBJ.action = 'send_sms';
                $.post("<?= base_url('application_tracking_system/handler'); ?>", megaOBJ, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('Error!', resp.Response);
                        return;
                    }

                    alertify.alert('Success!', resp.Response);
                    $('.js-modal-loader').hide();
                    $('#js-sms-modal').find('.btn').removeClass('disabled').prop('disabled', false);

                    targetMessage.val('');
                    //
                    $('.js-sms-cancel-btn').trigger('click');
                    have_all_records = true;
                    last_fetched_id = 0;
                    fetch_previous_conversation(megaOBJ);
                });
            }
        });

        //
        $(document).on('submit', '#js-phone-update-form', function(e) {
            e.preventDefault();
            var tmp_phone = $(this).find('input[name="txt_phone"]').val().trim();

            $(this).find('span.js-error').hide();
            //
            if (megaOBJ.id == '') {
                $(this).find('span.js-error').text('The id is missing...').show();
                return;
            }
            <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                //
                if (!fpn(tmp_phone, null, true)) {
                    $(this).find('span.js-error').text('Please provide a valid phonenumber').show();
                    return;
                }
            <?php } else { ?>
                if (tmp_phone == '') {
                    $(this).find('span.js-error').text('Please provide a valid phone number').show();
                    return;
                }
            <?php } ?>
            //
            megaOBJ.phone = tmp_phone;
            <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
                megaOBJ.phone_e16 = '+1' + (tmp_phone.toString().replace(/\D/g, ''));
            <?php } else { ?>
                megaOBJ.phone_e16 = (tmp_phone.toString().replace(/\D/g, ''));
            <?php } ?>
            megaOBJ.action = 'update_phone_number';
            $('.js-modal-loader').show();
            $('#js-sms-modal').find('.btn').addClass('disabled').prop('disabled', true);
            //
            $.post("<?= base_url('application_tracking_system/handler'); ?>", megaOBJ, function(resp) {
                if (resp.Status === false) {
                    $('.js-modal-loader').hide(0);
                    $('#js-sms-modal').find('.btn').removeClass('disabled').prop('disabled', false);
                    alertify.alert('Error!', resp.Response);
                    return;
                }

                alertify.alert('Success!', resp.Response, function() {
                    window.location.reload();
                });
                return;
                $('.js-modal-loader').hide(0);
                $('#js-sms-modal').find('.btn').removeClass('disabled').prop('disabled', false);
                //
                // lets reset the view
                $('#js-sms-modal').find('.modal-dialog').switchClass('modal-sm', 'modal-lg');
                $('.js-phone-form-view').hide(0);
                $('.js-sms-view-btn').show();
                $('.js-sms-view-form').hide(0);
                $('.js-sms-view-area').show();
                //
                $('#js-sms-form').find('input[name="txt_phone"]').val(megaOBJ.phone);
                // For empty
                if ($('#manual_row' + (megaOBJ.id) + '').find('.phone-number > a').length != 0) {
                    $('#manual_row' + (megaOBJ.id) + '').find('.phone-number > a').prop('href', 'tel:' + megaOBJ.phone_e16);
                    $('#manual_row' + (megaOBJ.id) + '').find('.phone-number > a > strong').html('<i class="fa fa-phone"></i>&nbsp;' + (resp.Phone) + '');
                } else $('#manual_row' + (megaOBJ.id) + '').find('.phone-number').append('<a href="tel:' + megaOBJ.phone_e16 + '" class="theme-color"><strong><i class="fa fa-phone"></i>&nbsp;' + (resp.Phone) + '</strong></a>');
            });
        });

        <?php if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) { ?>
            //
            $(document).on('keyup', '#js-phone-update-input', function(e) {
                var tmp_phone = fpn($(this).val().trim());
                //
                if (typeof(tmp_phone) === 'object') {
                    $(this).val(tmp_phone.number);
                    setCaretPosition(e.target, tmp_phone.cur);
                } else $(this).val(tmp_phone);
            });
        <?php } ?>

        var xhr = null;

        //
        function fetch_previous_conversation(obj) {
            if (xhr !== null) return;
            $('.js-sms-loader').show();
            obj.type = 'applicant';
            obj.action = 'fetch_sms_ats';
            obj.last_fetched_id = last_fetched_id;
            xhr = $.post("<?= base_url('application_tracking_system/handler'); ?>",
                obj,
                function(resp) {
                    xhr = null;
                    if (resp.Status === false) {
                        have_all_records = false;
                        $('.js-sms-loader').hide(0);
                        $('.js-sms-area > p').text(resp.Response);
                        return;
                    }
                    //
                    var rows = '';
                    if (last_fetched_id == 0) {
                        rows += '   <div class="cs-modal-loader js-sms-loader"><i class="fa fa-spinner fa-spin"></i></div>';
                        rows += '<div class="cs-ms-window">';
                        rows += '   <ul>';
                    }

                    $.each(resp.Data, function(i, v) {
                        rows += '<li class="' + (v.message_type == 'received' ? 'cs-right' : '') + '">';
                        rows += '    <p class="cs-li-head">' + (v.full_name) + ' <br /><span>' + (v.created_at) + '</span></p>';
                        rows += '    <p class="cs-li-body">';
                        rows += '       ' + (v.message_body) + '';
                        rows += '    </p>';
                        rows += '</li>';
                    });

                    if (last_fetched_id == 0) {
                        rows += '   </ul>';
                        rows += '</div>';
                    }
                    //
                    if (last_fetched_id == 0) {
                        $('.js-sms-area').html(rows);
                        // scroll_down($('.cs-ms-window'));
                    } else $('.js-sms-area ul').append(rows);
                    $('.js-sms-loader').hide(0);
                    last_fetched_id = resp.LastId;
                    $('.cs-ms-window').bind('scroll', load_sms);
                }
            );
        }

        //
        function scroll_down(target) {
            target.animate({
                scrollTop: target[0].scrollHeight
            }, "slow");
        }

        //
        function load_sms(e) {
            if (($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) && have_all_records !== false) {
                fetch_previous_conversation(megaOBJ);
            }
        }
    })
</script>

<style>
    .cs-modal-loader {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgba(255, 255, 255, .5);
        z-index: 2;
        display: none;
    }

    .cs-modal-loader i {
        position: relative;
        font-size: 30px;
        text-align: center;
        display: block;
        top: 50%;
        margin-top: -30px;
    }

    .cs-error {
        color: #cc0000;
    }

    .cs-ms-window {
        max-height: 500px;
        overflow-y: auto;
    }

    .cs-ms-window ul {
        list-style: none;
    }

    .cs-ms-window ul li {
        display: block;
        border-bottom: 1px solid #eee;
        margin-top: 10px;
        padding: 5px;
    }

    .cs-ms-window ul li.cs-right {
        background: rgba(129, 180, 49, .1);
        /*text-align: right;*/
    }

    .cs-ms-window ul li p.cs-li-head {
        font-weight: bold;
    }

    .cs-ms-window ul li p.cs-li-head span {
        font-size: 11px;
    }
</style>


<?php
$full_url = STORE_PROTOCOL_SSL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$this->session->set_userdata('ats_full_url', $full_url);
$this->session->set_userdata('ats_params', $_SERVER['REQUEST_URI']);
?>

<script>
    $(function() {

        $('#edit_review_div form').submit(function(e) {
            if ($('input[class="review_video_source"]:checked').val() == 'no_video') return true;
            if ($('input[class="review_video_source"]:checked').val() == '') return true;
            if ($('input[class="review_video_source"]:checked').val() == undefined) return true;
            var flag = 0;
            if ($('input[class="review_video_source"]:checked').val() == 'youtube') {
                if ($('#review_yt_vm_video_url').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/((watch(?:\.php)?\?.*v=)|(embed\/)))([a-zA-Z0-9\-_]+)/;
                    if (!$('#review_yt_vm_video_url').val().match(p)) {
                        alertify.alert('ERROR!', 'Please add a proper YouTube video URL.');
                        flag = 0;
                        e.preventDefault();
                        return false;
                    } else {
                        flag = 1;
                    }
                } else {
                    flag = 0;
                    alertify.alert('Please add valid YouTube video URL.');
                    e.preventDefault();
                    return false;
                }
            } else if ($('input[class="review_video_source"]:checked').val() == 'vimeo') {

                if ($('#review_yt_vm_video_url').val() != '') {
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $('#my_loader').show();
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#review_yt_vm_video_url').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                $('#my_loader').hide();
                                alertify.alert('ERROR!', 'Please add a valid Vimeo video URL.');
                                flag = 0;
                                e.preventDefault();
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    flag = 0;
                    e.preventDefault();
                    alertify.alert('Please add valid Vimeo video URL.');
                    return false;
                }
            } else if ($('input[class="review_video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#review_pre_upload_video_url').val();
                if (old_uploaded_video != '') {
                    flag = 1;
                } else {
                    var file = upload_video_checker('review_upload_video');
                    if (file == false) {
                        flag = 0;
                        e.preventDefault();
                        return false;
                    } else {
                        flag = 1;
                    }
                }
            }

            if (flag == 1) {
                // $('#applicant_profile_form').submit();
            } else {
                e.preventDefault();
                return false;
            }
        });

        $('#add_review_div form').submit(function(e) {
            console.log($('input[class="add_review_video_source"]:checked').val());
            if ($('input[class="add_review_video_source"]:checked').val() == 'no_video') return true;
            if ($('input[class="add_review_video_source"]:checked').val() == '') return true;
            if ($('input[class="add_review_video_source"]:checked').val() == undefined) return true;
            var flag = 0;
            if ($('input[class="add_review_video_source"]:checked').val() == 'youtube') {
                if ($('#add_review_yt_vm_video_url').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/((watch(?:\.php)?\?.*v=)|(embed\/)))([a-zA-Z0-9\-_]+)/;
                    if (!$('#add_review_yt_vm_video_url').val().match(p)) {
                        alertify.alert('ERROR!', 'Please add a proper YouTube video URL.');
                        flag = 0;
                        e.preventDefault();
                        return false;
                    } else {
                        flag = 1;
                    }
                } else {
                    flag = 0;
                    alertify.alert('Please add valid YouTube video URL.');
                    e.preventDefault();
                    return false;
                }
            } else if ($('input[class="add_review_video_source"]:checked').val() == 'vimeo') {

                if ($('#add_review_yt_vm_video_url').val() != '') {
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $('#my_loader').show();
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#add_review_yt_vm_video_url').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                $('#my_loader').hide();
                                alertify.alert('ERROR!', 'Please add a valid Vimeo video URL.');
                                flag = 0;
                                e.preventDefault();
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    flag = 0;
                    e.preventDefault();
                    alertify.alert('Please add valid Vimeo video URL.');
                    return false;
                }
            } else if ($('input[class="add_review_video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#add_review_pre_upload_video_url').val();
                if (old_uploaded_video != '') {
                    flag = 1;
                } else {
                    var file = upload_video_checker('add_review_upload_video');
                    if (file == false) {
                        flag = 0;
                        e.preventDefault();
                        return false;
                    } else {
                        flag = 1;
                    }
                }
            }

            if (flag == 1) {
                // $('#applicant_profile_form').submit();
            } else {
                e.preventDefault();
                return false;
            }
        });

        $('#review_youtube_vimeo_input').hide();
        $('#review_upload_input').hide();
        $('.review_video_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#review_label_youtube').show();
                $('#review_label_vimeo').hide();
                $('#review_YouTube_Video_hint').show();
                $('#review_Vimeo_Video_hint').hide();
                $('#review_youtube_vimeo_input').show();
                $('#review_upload_input').hide();
                $('#review_yt_vm_video_url').show();
            } else if (selected == 'vimeo') {
                $('#review_label_youtube').hide();
                $('#review_label_vimeo').show();
                $('#review_YouTube_Video_hint').hide();
                $('#review_Vimeo_Video_hint').show();
                $('#review_youtube_vimeo_input').show();
                $('#review_upload_input').hide();
                $('#review_yt_vm_video_url').show();
            } else if (selected == 'uploaded') {
                $('#review_youtube_vimeo_input').hide();
                $('#review_upload_input').show();
            } else {
                $('#review_youtube_vimeo_input').hide();
                $('#review_upload_input').hide();
            }
        });
        $('.review_video_source:checked').trigger('click');




        $('#add_review_youtube_vimeo_input').hide();
        $('#add_review_upload_input').hide();
        $('.add_review_video_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#add_review_label_youtube').show();
                $('#add_review_label_vimeo').hide();
                $('#add_review_YouTube_Video_hint').show();
                $('#add_review_Vimeo_Video_hint').hide();
                $('#add_review_youtube_vimeo_input').show();
                $('#add_review_upload_input').hide();
            } else if (selected == 'vimeo') {
                $('#add_review_label_youtube').hide();
                $('#add_review_label_vimeo').show();
                $('#add_review_YouTube_Video_hint').hide();
                $('#add_review_Vimeo_Video_hint').show();
                $('#add_review_youtube_vimeo_input').show();
                $('#add_review_upload_input').hide();
            } else if (selected == 'uploaded') {
                $('#add_review_youtube_vimeo_input').hide();
                $('#add_review_upload_input').show();
            } else {
                $('#add_review_youtube_vimeo_input').hide();
                $('#add_review_upload_input').hide();
            }
        });
        $('.add_review_video_source:checked').trigger('click');

    })



    //
    $('.jsProfileHistory').click(getOnboardingStatusHistory);

    function getOnboardingStatusHistory(e) {

        sId = $(this).data('id');
        applicantName = $(this).data('name');
        //
        Model({
            Id: 'jsEmployeeProfileHistoryModel',
            Loader: 'jsEmployeeProfileHistoryLoader',
            Body: '<div class="container"><div id="jsEmployeeProfileHistoryBody"></div></div>',
            Title: 'Applicant Status History of ' + applicantName
        }, getData);

    }

    //
    function getData() {
        //
        $.get(
            "<?= base_url('get_applicant_onboarding_history/'); ?>/" + sId,
            function(resp) {
                $('#jsEmployeeProfileHistoryBody').html(resp.view);
                ml(false, 'jsEmployeeProfileHistoryLoader');
            });

    }

    //
    $("#fair-type").change(function() {
        $('#app-type').val('Job Fair');
    });
</script>

<?php $this->load->view('iframeLoader'); ?>