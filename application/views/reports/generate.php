<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel-group-wrp">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                            <h4 class="panel-title">
                                                                Advanced Search Filters <span class="glyphicon glyphicon-plus"></span>
                                                            </h4>
                                                        </a>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse <?php if (isset($filter_state) && $filter_state == true) {
                                                                                                                echo 'in';
                                                                                                            } ?>">
                                                        <form method="get" enctype="multipart/form-data">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <div class="field-row">
                                                                            <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                                            <label>Keyword</label>
                                                                            <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                                            <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                                                Search by First Name, Last Name, Email or Phone number
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- jobs div -->
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <div class="field-row">
                                                                            <label>Source</label>
                                                                            <?php $source = $this->uri->segment(10) != 'all' ? urldecode($this->uri->segment(10)) : ''; ?>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="source" id="source">
                                                                                    <option value="all">Please Select</option>
                                                                                    <option value="automotosocial" <?php if ($source == 'automotosocial') { ?> selected="selected" <?php } ?>>Automoto Social</option>
                                                                                    <option value="jobs2careers" <?php if ($source == 'jobs2careers') { ?> selected="selected" <?php } ?>>Jobs2Careers</option>
                                                                                    <option value="ziprecruiter" <?php if ($source == 'ziprecruiter') { ?> selected="selected" <?php } ?>>ZipRecruiter</option>
                                                                                    <option value="glassdoor" <?php if ($source == 'glassdoor') { ?> selected="selected" <?php } ?>>Glassdoor</option>
                                                                                    <option value="indeed" <?php if ($source == 'indeed') { ?> selected="selected" <?php } ?>>Indeed</option>
                                                                                    <option value="juju" <?php if ($source == 'juju') { ?> selected="selected" <?php } ?>>Juju</option>
                                                                                    <option value="career_website" <?php if ($source == 'career_website') { ?> selected="selected" <?php } ?>>Career Website</option>
                                                                                    <option value="others" <?php if ($source == 'others') { ?> selected="selected" <?php } ?>>Others</option>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="field-row">
                                                                            <label>Applicant Type</label>
                                                                            <?php $applicant_type = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="applicant_type" id="applicant_type">
                                                                                    <?php if (!empty($applicant_types)) { ?>
                                                                                        <option value="all">All</option>
                                                                                        <?php foreach ($applicant_types as $type) { ?>
                                                                                            <option <?php echo set_select('applicant_type', $type, $applicant_type == $type); ?> value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <option value="all">No Applicant Types found</option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                                                        <div class="field-row">
                                                                            <label>Applicant Status</label>
                                                                            <?php $applicant_status = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="applicant_status" id="applicant_status">
                                                                                    <?php if (!empty($applicant_statuses)) { ?>
                                                                                        <option value="all">All</option>
                                                                                        <?php foreach ($applicant_statuses as $status) { ?>
                                                                                            <option <?php echo set_select('applicant_status', $status['name'], $applicant_status == $status['name']); ?> value="<?php echo $status['name']; ?>"><?php echo $status['name']; ?></option>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                                        <option <?php echo $applicant_status == 'Not Contacted Yet' ? 'selected="selected"' : ''; ?> value="Not Contacted Yet">Not Contacted Yet</option>
                                                                                        <option <?php echo $applicant_status == 'Left Message' ? 'selected="selected"' : ''; ?> value="Left Message">Left Message</option>
                                                                                        <option <?php echo $applicant_status == 'Contacted' ? 'selected="selected"' : ''; ?> value="Contacted">Contacted</option>
                                                                                        <option <?php echo $applicant_status == 'Candidate Responded' ? 'selected="selected"' : ''; ?> value="Candidate Responded">Candidate Responded</option>
                                                                                        <option <?php echo $applicant_status == 'Interviewing' ? 'selected="selected"' : ''; ?> value="Interviewing">Interviewing</option>
                                                                                        <option <?php echo $applicant_status == 'Submitted' ? 'selected="selected"' : ''; ?> value="Submitted">Submitted</option>
                                                                                        <option <?php echo $applicant_status == 'Qualifying' ? 'selected="selected"' : ''; ?> value="Qualifying">Qualifying</option>
                                                                                        <option <?php echo $applicant_status == 'Ready to Hire' ? 'selected="selected"' : ''; ?> value="Ready to Hire">Ready to Hire</option>
                                                                                        <option <?php echo $applicant_status == 'Offered Job' ? 'selected="selected"' : ''; ?> value="Offered Job">Offered Job</option>
                                                                                        <option <?php echo $applicant_status == 'Client Declined' ? 'selected="selected"' : ''; ?> value="Client Declined">Client Declined</option>
                                                                                        <option <?php echo $applicant_status == 'Not In Consideration' ? 'selected="selected"' : ''; ?> value="Not In Consideration">Not In Consideration</option>
                                                                                        <option <?php echo $applicant_status == 'Future Opportunity' ? 'selected="selected"' : ''; ?> value="Future Opportunity">Future Opportunity</option>
                                                                                    <?php } ?>
                                                                                </select>

                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="field-row">
                                                                            <label>Managers</label>
                                                                            <?php $manager_id = $this->uri->segment(11) != 'all' ? urldecode($this->uri->segment(11)) : ''; ?>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="managers" id="managers">
                                                                                    <?php if (!empty($managers)) { ?>
                                                                                        <option value="all">All</option>
                                                                                        <?php foreach ($managers as $managerRow) { ?>
                                                                                            <option value="<?php echo $managerRow['employeeId']; ?>"><?php echo getUserNameBySID($managerRow['employeeId']) ?></option>
                                                                                        <?php } ?>
                                                                                    <?php } ?>

                                                                                </select>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                                                        <div class="field-row">
                                                                            <label class="">Start Date</label>
                                                                            <?php $start_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-d-Y'); ?>
                                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                                                        <div class="field-row">
                                                                            <label class="">End Date</label>
                                                                            <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y'); ?>
                                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                        <div class="field-row autoheight">
                                                                            <?php $job_sid = $this->uri->segment(5) != 'all' ? $this->uri->segment(5) : ''; ?>
                                                                            <label>Job</label>
                                                                            <!--                                                                            <div class="Category_chosen">-->
                                                                            <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid" placeholder="Please leave blank to search all jobs">
                                                                                <?php if (!empty($jobOptions)) { ?>
                                                                                    <!--                                                                                        <option value="all" <?php //if (in_array('all', $job_sid_array)) { 
                                                                                                                                                                                                    ?> selected="selected" <?php //} 
                                                                                                                                                                                                                            ?>>All Jobs</option>-->
                                                                                    <?php foreach ($jobOptions as $key => $value) { ?>
                                                                                        <option value="<?= $key ?>" <?php if (in_array($key, $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                                                            <?php echo $value; ?>
                                                                                        </option>
                                                                                        <!--                                                                                            <option --><?php //echo set_select('job_sid', $key, $job_sid == $key); 
                                                                                                                                                                                                    ?>
                                                                                        <!-- value="--><?php //echo $key; 
                                                                                                        ?>
                                                                                        <!--">--><?php //echo $value; 
                                                                                                    ?>
                                                                                        <!--</option>-->
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <option value="">No jobs found</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <!--                                                                                <div class="video-link" style='font-style: italic;'><b>Hint:</b>-->
                                                                            <!--                                                                                    Please leave blank to search all jobs-->
                                                                            <!--                                                                                </div>-->
                                                                            <!--                                                                            </div>-->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                        <div class="field-row autoheight text-right">

                                                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('reports/generate/applicants'); ?>">Reset Filters</a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!--                                                            <div class="panel-footer">-->
                                                            <!--                                                                <div class="form-group">-->
                                                            <!--                                                                    <input type="submit" class="submit-btn pull-right" value="Apply Filters" name="submit"/>-->
                                                            <!--                                                                    <a class="submit-btn pull-right"-->
                                                            <!--                                                                       href="--><?php //echo base_url('reports/generate/' . $type); 
                                                                                                                                                ?>
                                                            <!--">Reset-->
                                                            <!--                                                                        Filters</a>-->
                                                            <!--                                                                </div>-->
                                                            <!--                                                                <div class="clearfix"></div>-->
                                                            <!--                                                            </div>-->
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bottom here-->

                                        <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                            <div class="box-view reports-filtering">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <form method="post" id="export" name="export">
                                                                <label class="control control--checkbox pull-left">
                                                                    Pull Applicant Source In Export
                                                                    <input type="checkbox" value="1" name="embed-source" class="pull-right" checked>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                            </form>
                                                            <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                                Print
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>
                                        <!-- table -->
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="hr-registered">Applicants Report</h1>
                                                </span>
                                                <span class="pull-right">
                                                    <h1 class="hr-registered">Total Records Found : <?php echo $applicants_count; ?></h1>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                        </span>
                                                        <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive" id="print_div">
                                                            <table class="table table-bordered horizontal-scroll" id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-lg-2">Job Title</th>
                                                                        <th>First Name</th>
                                                                        <th>Last Name</th>
                                                                        <th>Email</th>
                                                                        <th class="text-center">Primary Number</th>
                                                                        <th class="text-center">Date Applied</th>
                                                                        <th>Applicant Type</th>
                                                                        <th>Applicant Source</th>
                                                                        <th>Applicant Status</th>
                                                                        <th>Status Changed Date</th>
                                                                        <th>Status Changed By</th>
                                                                        <th class="text-center">Questionnaire Score</th>
                                                                        <th class="text-center">Reviews Score</th>
                                                                        <th class="col-lg-2 text-center">Reviews Info</th>
                                                                        <th class="col-lg-2">Interview Scores</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                                                        <?php foreach ($applicants as $applicant) { ?>
                                                                            <tr>
                                                                                <td style="color: <?php echo (($applicant['Title'] == 'Job Deleted' || $applicant['Title'] == 'Job Not Applied') ? 'red' : '#81b431'); ?>;">
                                                                                    <?php
                                                                                    $city = '';
                                                                                    $state = '';
                                                                                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                                        $city = ' - ' . ucfirst($applicant['Location_City']);
                                                                                    }
                                                                                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                                        $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                                                    }
                                                                                    echo $applicant['Title'] . $city . $state; ?>
                                                                                </td>
                                                                                <td><?php echo ucwords($applicant['first_name']); ?></td>
                                                                                <td><?php echo ucwords($applicant['last_name']); ?></td>
                                                                                <td><?php echo $applicant['email']; ?></td>
                                                                                <td class="text-center"><?php echo $applicant['phone_number']; ?></td>
                                                                                <td class="text-center"><?= reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this)); ?></td>
                                                                                <td><?php echo ucwords($applicant['applicant_type']); ?></td>
                                                                                <td><?php echo ucwords($applicant['applicant_source']); ?></td>
                                                                                <td class="text-center">
                                                                                    <div class="contacts_label auto-height  <?php echo $applicant['css_class'] ?>">
                                                                                        <?php echo ucwords($applicant['status']); ?>
                                                                                    </div>

                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $applicant['status_change_date'] != null ? date_with_time($applicant['status_change_date']) : 'N/A'; ?>
                                                                                </td>
                                                                                <td><?php echo  $applicant['status_change_by'] != null ? getUserNameBySID($applicant['status_change_by']) : 'N/A' ?></td>

                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($applicant['questionnaire'] == '' || $applicant['questionnaire'] == NULL) {
                                                                                        echo '<span style="color:red;"> N/A </span>';
                                                                                    } else {
                                                                                        echo $applicant['score'];
                                                                                        if ($applicant['score'] >= $applicant['passing_score']) {
                                                                                            echo '<span style="color: #81b431;"> (Pass) </span>';
                                                                                        } else {
                                                                                            echo '<span style="color:red;"> (Fail) </span>';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php echo $applicant['review_score']; ?>
                                                                                    with
                                                                                    <?php echo $applicant['review_count']; ?>
                                                                                    Review(s)
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    if (!empty($applicant['review_comment'])) {
                                                                                        foreach ($applicant['review_comment'] as $commentRow) {
                                                                                            echo  "Employer: " . getUserNameBySID($commentRow['employer_sid']) . "<br><br> Rating : " . $commentRow['rating'] . "<br><br>Note: " . $commentRow['comment'] . "<br> Date: ".date_with_time($commentRow['date_added'])." <hr>";
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    if (sizeof($applicant['scores']) > 0) {
                                                                                        $i = 0;
                                                                                        foreach ($applicant['scores'] as $score) { ?>
                                                                                            <p>Employer : <?php echo ucwords($score['first_name'] . ' ' . $score['last_name']); ?> </p>
                                                                                            <p>Candidate Score : <?php echo $score['candidate_score']; ?> out of 100 </p>
                                                                                            <p>Job Relevancy Score : <?php echo $score['job_relevancy_score']; ?> out of 100 </p>
                                                                                            <?php $i++;
                                                                                            if ($i < sizeof($applicant['scores'])) { ?> <br> <?php } ?>
                                                                                        <?php
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <p>No interview scores</p>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td class="text-center" colspan="15">
                                                                                <div class="no-data">No applicants found.</div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-right">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- table -->


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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        var keyword = $('#keyword').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var applicant_status = $('#applicant_status').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var source = $('#source').val();
        var managers = $('#managers').val();



        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        source = source != '' && source != null && source != undefined && source != 0 ? encodeURIComponent(source) : 'all';
        managers = managers != '' && managers != null && managers != undefined && managers != 0 ? encodeURIComponent(managers) : 'all';



        var url = '<?php echo base_url('reports/generate/applicants'); ?>' + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied + '/' + source + '/' + managers;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {


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

        //
        $('#managers').val('<?php echo $this->uri->segment(11);?>');

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('#job_sid').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_type').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_status').on('change', function(value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        // Search Area Toggle Function
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

    });

    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }
</script>