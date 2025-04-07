<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/reports/' . $company_sid); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Reports
                    </a>
                </div>
                <!-- search form drop down -->
                <div class="hr-search-criteria <?php echo isset($flag) && $flag == true ? 'opened' : ''; ?>">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" <?php echo isset($flag) && $flag == true ? 'style="display:block"' : ''; ?>>
                    <!-- search form -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="field-row">
                                    <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                    <label>Keyword</label>
                                    <input placeholder="John Doe" class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                </div>
                            </div>
                            <!-- jobs div -->
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="field-row">
                                    <?php $job_sid = $this->uri->segment(5) != 'all' ? $this->uri->segment(5) : ''; ?>
                                    <label class="text-left">Job</label>
                                    <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                        <?php if (!empty($jobs)) { ?>
                                            <option value="all" <?php if (in_array('all', $job_sid_array)) { ?> selected="selected" <?php } ?>>All Jobs</option>
                                            <?php foreach ($jobs as $job) {
                                                $active = ' (In Active) ';
                                                if($job['active']){
                                                    $active = ' (Active) ';
                                                }?>
                                                <option value="<?= $job['sid'] ?>" <?php if (in_array($job['sid'], $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $job['Title'] . $active; ?>
                                                </option>
<!--                                                <option --><?php //echo set_select('job_sid', $job['sid'], $job_sid == $job['sid']); ?><!-- value="--><?php //echo $job['sid']; ?><!--">--><?php //echo $job['Title'] . $active; ?><!--</option>-->
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="">No jobs found</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <!-- jobs div -->
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="field-row">
                                    <label class="text-left">Applicant Type</label>
                                    <?php $applicant_type = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                    <select name="applicant_type" id="applicant_type">
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
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="field-row">
                                    <label class="text-left">Applicant Status</label>
                                    <?php $applicant_status = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : '';?>
                                    <select name="applicant_status" id="applicant_status">
                                        <?php if (!empty($applicant_statuses)) { ?>
                                            <option value="all">All</option>
                                            <?php foreach ($applicant_statuses as $status) { ?>
                                                <option <?php echo set_select('applicant_status', $status['name'], $applicant_status == $status['name']); ?> value="<?php echo $status['name']; ?>"><?php echo $status['name']; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                            <option <?php echo $applicant_status == 'Not Contacted Yet' ? 'selected="selected"' : ''; ?> value="Not Contacted Yet">Not Contacted Yet</option>
                                            <option <?php echo $applicant_status == 'Left Message' ? 'selected="selected"' : ''; ?> value="Left Message" >Left Message</option>
                                            <option <?php echo $applicant_status == 'Contacted' ? 'selected="selected"' : ''; ?> value="Contacted" >Contacted</option>
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
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="field-row">
                                    <label class="">Date From</label>
                                    <?php $start_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-d-Y');?>
                                    <input class="invoice-fields "
                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                           type="text"
                                           name="start_date_applied"
                                           id="start_date_applied"
                                           value="<?php echo set_value('start_date_applied', $start_date); ?>"/>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="field-row">
                                    <label class="">Date To</label>
                                    <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y');?>
                                    <input class="invoice-fields"
                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                           type="text"
                                           name="end_date_applied"
                                           id="end_date_applied"
                                           value="<?php echo set_value('end_date_applied', $end_date); ?>"/>
                                </div>
                            </div>
                            <div class="field-row field-row-autoheight col-lg-12 text-right">
                                <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filters</a>
                                <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('reports/applicants_report/' . $company_sid . '/all/all/all/all/all/all'); ?>">Reset Filters</a>
                            </div>
                        </div>

                    <!-- search form -->
                </div>
                <!-- search form drop down -->
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
                                <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                            </form>
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
                            <h1 class="hr-registered">Total Records Found : <?php echo $applicants_count;?></h1>
                        </span>
                    </div>
                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-left">
                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count?></p>
                                </span>
                                <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                    <?php echo $page_links?>
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
                                            <th class="text-center">Phone Number</th>
                                            <th class="text-center">Date Applied</th>
                                            <th>Applicant Type</th>
                                            <th>Applicant Status</th>
                                            <th class="text-center">Questionnaire Score</th>
                                            <th class="text-center">Reviews Score</th>
                                            <th class="col-lg-2">Interview Scores</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                            <?php foreach ($applicants as $applicant) { ?>
                                                <tr>
                                                    <td style="color: <?php echo(($applicant['Title'] == 'Job Deleted' || $applicant['Title'] == 'Job Not Applied') ? 'red' : '#81b431'); ?>;"><?php echo $applicant['Title']; ?></td>
                                                    <td><?php echo ucwords($applicant['first_name']); ?></td>
                                                    <td><?php echo ucwords($applicant['last_name']); ?></td>
                                                    <td><?php echo $applicant['email']; ?></td>
                                                    <td class="text-center"><?php echo $applicant['phone_number']; ?></td>
                                                    <td class="text-center">
<!--                                                        --><?php //echo date_with_time($applicant['date_applied']); ?>
                                                        <?php echo reset_datetime(array(
                                                            'datetime' => $applicant['date_applied'],
                                                            // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                            // 'format' => 'h:iA', //
                                                            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                            'from_timezone' => $executive_user['timezone'], //
                                                            '_this' => $this
                                                        )) ?>
                                                    </td>
                                                    <td><?php echo ucwords($applicant['applicant_type']); ?></td>
                                                    <td><?php echo ucwords($applicant['status']); ?></td>
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
                                                        if(sizeof($applicant['scores']) > 0){
                                                            $i = 0;
                                                            foreach($applicant['scores'] as $score){?>
                                                                <p>Employer :  <?php echo ucwords($score['first_name'] . ' ' . $score['last_name']); ?> </p>
                                                                <p>Candidate Score : <?php echo $score['candidate_score']; ?> out of 100 </p>
                                                                <p>Job Relevancy Score :  <?php echo $score['job_relevancy_score']; ?> out of 100 </p>
                                                                <?php $i++; if($i < sizeof($applicant['scores'])) { ?> <br> <?php } ?>
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
                                                <td class="text-center" colspan="10">
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
                                    <?php echo $page_links?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
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
                                <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                            </form>
                        </div>                                                               
                    </div>
                </div>
                <?php } ?>
            </div>               					
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
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

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';


        var url = '<?php echo base_url('reports/applicants_report/' . $company_sid); ?>' + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {
        $('#btn_apply_filters').on('click', function (e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

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


        $('#job_sid').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });
        $('#applicant_type').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });
        $('#applicant_status').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });

        $('#keyword').on('keyup', function () {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function () {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) {
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
            onSelect: function (value) {
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
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }
</script>