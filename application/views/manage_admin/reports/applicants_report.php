<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?php echo $flag == true ? 'opened' : ''; ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?php echo $flag == true ? 'style="display: block;"' : '' ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/applicants_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <div class="field-row">
                                                        <label class="text-left">Company / Oem Vendor</label>
                                                        <?php $applicant_status = $this->uri->segment(12); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="company_oem_select" id="company_oem_select" class="invoice-fields">
                                                                <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                <option <?php echo $applicant_status == 'company' ? 'selected="selected"' : ''; ?> value="company">Company</option>
                                                                <option <?php echo $applicant_status == 'oem' ? 'selected="selected"' : ''; ?> value="oem">Oem Independent Vendor</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                                    <div class="field-row" id="oem_div">
                                                        <label class="text-left">Oem Independent Vendor</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                <option value="all">All</option>
                                                                <?php if (!empty($brands)) { ?>
                                                                    <?php foreach ($brands as $brand) { ?>
                                                                        <option <?php if ($this->uri->segment(11) != 'all' && urldecode($this->uri->segment(11)) == $brand['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $brand['sid']; ?>">
                                                                            <?php echo $brand['oem_brand_name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="field-row" id="company_div">
                                                        <label class="text-left">Company : <span class="hr-required">*</span></label>
                                                        <?php $applicant_status = $this->uri->segment(12); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="company_sid" id="company_sid">
                                                                <option value="all">Please Select</option>
                                                                <?php if (!empty($companies)) { ?>
                                                                    <?php foreach ($companies as $active_company) { ?>
                                                                        <option <?php if ($this->uri->segment(4) != 'all' && urldecode($this->uri->segment(4)) == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                            <?php echo $active_company['CompanyName']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row" id="jobs_div">
                                                        <label class="text-left">Job Title :</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                                                <option value="">Please Select Company</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <div class="field-row">
                                                        <label>Applicant Type</label>
                                                        <?php $applicant_type = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="applicant_type" id="applicant_type" class="invoice-fields">
                                                                <?php if (!empty($applicant_types)) { ?>
                                                                    <option value="all">All</option>
                                                                    <?php foreach ($applicant_types as $type) { ?>
                                                                        <option <?php echo set_select('applicant_type', $type, $applicant_type == $type); ?> value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <option <?php echo $applicant_type == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                    <option <?php echo $applicant_type == 'Applicant' ? 'selected="selected"' : ''; ?> value="Applicant">Applicant</option>
                                                                    <option <?php echo $applicant_type == 'Talent Network' ? 'selected="selected"' : ''; ?> value="Talent Network">Talent Network</option>
                                                                    <option <?php echo $applicant_type == 'Manual Candidate' ? 'selected="selected"' : ''; ?> value="Manual Candidate">Manual Candidate</option>
                                                                    <option <?php echo $applicant_type == 'Re-Assigned Candidates' ? 'selected="selected"' : ''; ?> value="Re-Assigned Candidates">Re-Assigned Candidates</option>
                                                                    <option <?php echo $applicant_type == 'Job Fair' ? 'selected="selected"' : ''; ?> value="Job Fair">Job Fair</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                                    <div class="field-row">
                                                        <?php $applicant_name = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                        <label>Name</label>
                                                        <input placeholder="John Doe" class="invoice-fields" type="text" id="applicant_name" name="applicant_name" value="<?php echo set_value('applicant_name', $applicant_name); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row">
                                                        <label class="text-left">Applicant Status</label>
                                                        <?php $applicant_status = $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : ''; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="applicant_status" id="applicant_status" class="invoice-fields">

                                                                <option <?php echo $applicant_status == '' ? 'selected="selected"' : ''; ?> value="">Select Company</option>
                                                                <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Not Contacted Yet' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Not Contacted Yet">Not Contacted Yet</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Left Message' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Left Message" >Left Message</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Contacted' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Contacted" >Contacted</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Candidate Responded' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Candidate Responded">Candidate Responded</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Interviewing' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Interviewing">Interviewing</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Submitted' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Submitted">Submitted</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Qualifying' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Qualifying">Qualifying</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Ready to Hire' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Ready to Hire">Ready to Hire</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Offered Job' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Offered Job">Offered Job</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Client Declined' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Client Declined">Client Declined</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Not In Consideration' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Not In Consideration">Not In Consideration</option>-->
                                                                <!--                                                                <option --><?php //echo $applicant_status == 'Future Opportunity' ? 'selected="selected"' : ''; 
                                                                                                                                                ?>
                                                                <!-- value="Future Opportunity">Future Opportunity</option>-->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row">
                                                        <label class="">Date From</label>
                                                        <?php $start_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row">
                                                        <label class="">Date To</label>
                                                        <?php $end_date = $this->uri->segment(10) != 'all' && $this->uri->segment(10) != '' ? urldecode($this->uri->segment(10)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                    <div class="field-row">
                                                        <label class="">&nbsp;</label>
                                                        <a id="btn_apply_filters" class="btn btn-success btn-block" href="#">Apply Filters</a>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                    <div class="field-row">
                                                        <label class="">&nbsp;</label>
                                                        <a id="btn_reset_filters" class="btn btn-success btn-block" href="<?php echo base_url('manage_admin/reports/applicants_report'); ?>">Reset Filters</a>
                                                    </div>
                                                </div>
                                                <!--                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">-->
                                                <!--                                                    <div class="field-row">-->
                                                <!--                                                        <label class="">&nbsp;</label>-->
                                                <!--                                                        <a id="btn_export_records" class="btn btn-success btn-block" href="--><?php //echo base_url('manage_admin/reports/applicants_report'); 
                                                                                                                                                                                    ?>
                                                <!--">Export</a>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                            </div>
                                        </form>
                                    </div>

                                    <!-- *** table *** -->
                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                        Print
                                                    </a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit">
                                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                            Export To Excel
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
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
                                                    <span class="pull-right">
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
                                                                    <th>Job Title</th>
                                                                    <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                        <th>Company Name</th>
                                                                    <?php } ?>
                                                                    <th>First Name</th>
                                                                    <th>Last Name</th>
                                                                    <th>Email</th>
                                                                    <th>Primary Number</th>
                                                                    <th>Date Applied</th>
                                                                    <th>Applicant Type</th>
                                                                    <th>Applicant Status</th>
                                                                    <th>Questionnaire Score</th>
                                                                    <th>Reviews Score</th>
                                                                    <th class="col-lg-2">Interview Scores</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                                                    <?php foreach ($applicants as $applicant) { ?>
                                                                        <tr>
                                                                            <td style="color: <?php echo ($applicant['Title'] == 'Job Deleted' ? 'red' : 'green'); ?>;"><?php
                                                                                                                                                                        $city = '';
                                                                                                                                                                        $state = '';
                                                                                                                                                                        if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                                                                                                                            $city = ' - ' . ucfirst($applicant['Location_City']);
                                                                                                                                                                        }
                                                                                                                                                                        if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                                                                                                                            $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                                                                                                                                        }
                                                                                                                                                                        echo $applicant['Title'] . $city . $state; ?></td>
                                                                            <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                                <td><?php echo ucwords($applicant['CompanyName']); ?></td>
                                                                            <?php } ?>
                                                                            <td><?php echo ucwords($applicant['first_name']); ?></td>
                                                                            <td><?php echo ucwords($applicant['last_name']); ?></td>
                                                                            <td><?php echo $applicant['email']; ?></td>
                                                                            <td><?php echo $applicant['phone_number']; ?></td>
                                                                            <td><?php echo date_with_time($applicant['date_applied']); ?></td>
                                                                            <td><?php echo $applicant['applicant_type']; ?></td>
                                                                            <td><?php echo $applicant['status']; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if ($applicant['questionnaire'] == '' || $applicant['questionnaire'] == NULL) {
                                                                                    echo '<span style="color:red;"> N/A </span>';
                                                                                } else {
                                                                                    echo $applicant['score'];
                                                                                    if ($applicant['score'] >= $applicant['passing_score']) {
                                                                                        echo '<span style="color:green;"> (Pass) </span>';
                                                                                    } else {
                                                                                        echo '<span style="color:red;"> (Fail) </span>';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $applicant['review_score']; ?>
                                                                                with
                                                                                <?php echo $applicant['review_count']; ?>
                                                                                Review(s)
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
                                                                        <td class="text-center" colspan="<?php
                                                                                                            if ($company_or_brand == 'brands' || $company_or_brand == 'all') {
                                                                                                                echo '11';
                                                                                                            } else {
                                                                                                                echo '10';
                                                                                                            }
                                                                                                            ?>">
                                                                            <?php if (!isset($applicants)) { ?>
                                                                                <div class="no-data">Please select company...</div>
                                                                            <?php } else if (isset($applicants) && sizeof($applicants) <= 0) { ?>
                                                                                <div class="no-data">No applicants found.</div>
                                                                            <?php } ?>
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
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                    </span>
                                                    <span class="pull-right">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- *** table *** -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    $(document).ready(function() {
        var company_selectize = $('#company_sid').selectize({
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

        var jobs_selectize = $('#job_sid').selectize({
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

        load_jobs();
        var job_select = jobs_selectize[0].selectize;

        $('body').on('change', '#company_sid', function() {
            var selected = $(this).val();
            var my_data = {
                'company_sid': selected,
                'perform_action': 'load_jobs'
            };
            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/reports/applicants_report/ajax_responder'); ?>",
                data: my_data
            });

            myRequest.done(function(response) {

                data = $.parseJSON(response);
                job_select.clearOptions();
                job_select.load(function(callback) {
                    var arr = [{}];
                    var j = 0;
                    arr[j++] = {
                        value: 'all',
                        text: 'All Jobs'
                    };
                    $.each(data, function(i, item) {
                        var job_sid = item.sid;
                        var job_title = item.Title;
                        var status = item.active;
                        if (status == 1) {
                            job_title = job_title + ' (Active)';
                        } else {
                            job_title = job_title + ' (In Active)';
                        }
                        arr[j++] = {
                            value: job_sid,
                            text: job_title
                        }

                    });
                    callback(arr);
                    var selected_job = '<?php echo $job_sid_array; ?>';
                    selected_job = selected_job.split(',');
                    $.each(selected_job, function(i, item) {
                        job_select.addItems(item);
                    });
                    job_select.refreshItems();
                });
            });
        });

        $('#company_sid').trigger('change');

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        var company_oem_select = $("#company_oem_select").val();
        if (company_oem_select == 'all' || company_oem_select == 'oem') {
            $('#applicant_status').val('');
            $('#applicant_status').attr('disabled', 'disabled');
        } else {
            load_statuses();
        }

        $('#company_oem_select').on('change', function() {
            var selected = $(this).val();
            if (selected == 'oem') {
                $('#oem_div').show();
                $('#company_div').hide();
                $('#jobs_div').hide();
                $('#applicant_status').val('');
                $('#applicant_status').attr('disabled', 'disabled');
            } else if (selected == 'company') {
                $('#oem_div').hide();
                $('#company_div').show();
                $('#jobs_div').show();
            } else if (selected == 'all') {
                $('#oem_div').hide();
                $('#company_div').hide();
                $('#jobs_div').hide();
                $('#applicant_status').val('');
                $('#applicant_status').attr('disabled', 'disabled');
            }
        }).trigger('change');

        generate_search_url();

        $('select').on('change', function() {
            generate_search_url();
        });

        $('input').on('keyup', function() {
            generate_search_url();
        });


        $("#company_sid").change(function() {
            load_statuses();
        });


        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) { //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) { //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });


    function generate_search_url() {
        var company_oem_select = $("#company_oem_select").val();
        var company_sid = $("#company_sid").val();
        var brand_sid = $("#brand_sid").val();
        var applicant_name = $('#applicant_name').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var applicant_status = $('#applicant_status').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();

        company_oem_select = company_oem_select != '' && company_oem_select != null && company_oem_select != undefined && company_oem_select != 0 ? encodeURIComponent(company_oem_select) : 'all';
        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        brand_sid = brand_sid != '' && brand_sid != null && brand_sid != undefined && brand_sid != 0 ? encodeURIComponent(brand_sid) : 'all';
        applicant_name = applicant_name != '' && applicant_name != null && applicant_name != undefined && applicant_name != 0 ? encodeURIComponent(applicant_name) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';

        if (company_oem_select == 'all') {
            company_sid = 'all';
            brand_sid = 'all';
            job_sid = 'all';
            applicant_status = 'all';
        } else if (company_oem_select == 'company') {
            brand_sid = 'all';
        } else if (company_oem_select == 'oem') {
            company_sid = 'all';
            applicant_status = 'all';
            job_sid = 'all';
        }


        var url = '<?php echo base_url('manage_admin/reports/applicants_report'); ?>' + '/' + company_sid + '/' + applicant_name + '/' + job_sid + '/' + applicant_type + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied + '/' + brand_sid + '/' + company_oem_select + '/';

        $('#btn_apply_filters').attr('href', url);
    }

    function load_jobs() {
        var company_sid = $("#company_sid").val();

        var selected_job = '<?php echo $job_sid_array; ?>';

        if (company_sid == 0 || company_sid == '') {
            $('#job_sid').find('option').remove().end();
            $('#job_sid').append('<option value="">Please Select Company</option>');
        } else {
            data = {
                'company_sid': company_sid,
                'perform_action': 'load_jobs'
            };

            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/reports/applicants_report/ajax_responder'); ?>",
                data: data
            });

            myRequest.done(function(response) {
                selected_job = selected_job.split(',');
                $('#job_sid').find('option').remove().end();
                if ($.inArray('all', selected_job))
                    $('#job_sid').append('<option value="all" selected>All Jobs</option>');
                else
                    $('#job_sid').append('<option value="all">All Jobs</option>');
                data = $.parseJSON(response);
                //                var selected_job = '<?php //echo $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; 
                                                        ?>//';


                $.each(data, function(i, item) {
                    var job_sid = item.sid;
                    var job_title = item.Title;
                    var status = item.active;
                    if (status == 1) {
                        status = ' (Active)';
                    } else {
                        status = ' (In Active)';
                    }
                    //                    if (selected_job != '' && $.inArray(job_sid,selected_job)) {
                    //                        $('#job_sid').append('<option value="' + job_sid + '" selected>' + job_title + status + '</option>');
                    //                    } else {
                    //                        $('#job_sid').append('<option value="' + job_sid + '">' + job_title + status + '</option>');
                    //                    }
                });
            });
        }
    }

    function load_statuses() {
        var company_sid = $("#company_sid").val();

        $('#applicant_status').removeAttr('disabled', 'disabled');

        if (company_sid == 0 || company_sid == '') {
            $('#applicant_status').find('option').remove().end();
            $('#applicant_status').append('<option value="">Please Select Company</option>');
        } else {
            var data = {
                'company_sid': company_sid,
                'perform_action': 'load_status'
            };

            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/reports/applicants_report/ajax_responder'); ?>",
                data: data
            });

            myRequest.done(function(response) {
                $('#applicant_status').find('option').remove().end();
                $('#applicant_status').append('<option value="">All</option>');

                data = $.parseJSON(response);
                var selected_status = '<?php echo $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : ''; ?>';

                $.each(data, function(i, item) {
                    var status_title = item.name;

                    if (selected_status != '' && selected_status == status_title) {
                        $('#applicant_status').append('<option value="' + status_title + '" selected>' + status_title + '</option>');
                    } else {
                        $('#applicant_status').append('<option value="' + status_title + '">' + status_title + '</option>');
                    }
                });
            });
        }
    }

    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
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
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }
</script>