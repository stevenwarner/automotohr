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
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>

                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="field-row">
                                                <label>Keyword</label>
                                                <?php $keyword = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : '';?>
                                                <input type="text" class="invoice-fields" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                    Search by Applicant Name or Email
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="field-row">
                                                <?php $job_sid = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                <label>Job</label>
                                                <div class="hr-select-dropdown ">
                                                    <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                                        <?php if (!empty($jobOptions)) { ?>
<!--                                                            <option value="all" --><?php //if (in_array('all', $job_sid_array)) { ?><!-- selected="selected" --><?php //} ?><!-->All Jobs</option>-->
                                                            <?php foreach ($jobOptions as $key => $value) { ?>
                                                                <option value="<?= $key ?>" <?php if (in_array($key, $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                                    <?php echo $value; ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">No jobs found</option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                        Please leave blank to search all jobs
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                            <div class="col-xs-12">
                                                <div class="row">
<!--                                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">-->
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
<!--                                                            <div class="field-row">-->
                                                                <label >Applicant Status</label>
                                                                <?php $applicant_status = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : '';?>
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
<!--                                                            </div>-->
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <div class="form-col-100">
                                                                <label for="startdate">Start Date</label>
                                                                <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y');?>
                                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="<?php echo date('m-d-Y'); ?>" value="<?php echo set_value('startdate', $start_date); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <div class="form-col-100">
                                                                <label for="enddate">End Date</label>
                                                                <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y');?>
                                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="<?php echo date('m-d-Y'); ?>" value="<?php echo set_value('enddate', $end_date); ?>">
                                                            </div>
                                                        </div>
<!--                                                    </form>-->
                                                </div>
                                            </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                            <div class="form-col-100">
                                                <label>Applicant Type</label>
                                                <?php $applicant_type =  $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : '' ?>
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
                                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                            <label>&nbsp;</label>
                                            <a href="#" id="btn_apply_filters" class="btn btn-success btn-block">Apply Filters</a>

                                        </div>
                                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                            <label>&nbsp;</label>
                                            <a href="<?php echo base_url('reports/generate_applicant_status_report'); ?>" id="btn_clear_filters" class="btn btn-success btn-block">Clear Filters</a>

                                        </div>
                                    </div>
<!--                                    <div class="row">-->
<!--                                        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">-->
<!--                                            <div class="report-btns">-->
<!--                                                <div class="row">-->
<!--                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">-->
<!--                                                        <button class="form-btn" onclick="fApplyDateFilters();">Filter</button>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">-->
<!--                                                        <button class="form-btn" onclick="fClearDateFilters();">Clear</button>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!---->
<!--                                    </div>-->
                                    <hr>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php if(isset($applicants) && sizeof($applicants) > 0) { ?>
                                            <div class="box-view reports-filtering">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <form method="post" id="export" name="export">
                                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                            </form>
                                                            <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count?></p>
                                                    </span>
                                                    <span class="pull-right">
                                                        <?php echo $page_links?>
                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <hr>
                                                </div>
                                                <div class="page-header-area">
                                                    <span class="page-heading pull-right">
                                                        <b><?= 'Total number of applicants:    ' . $applicants_count?></b>
                                                    </span>
                                                </div>

                                            <?php } ?>
                                            <div class="table-responsive table-outer" id="print_div">
                                                <div class="border-none mylistings-wrp">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Application Date</th>
                                                                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Applicant Name</th>
                                                                <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Job Title</th>
                                                                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Email</th>
                                                                <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($applicants)) { ?>
                                                                <?php foreach ($applicants as $applicant) { ?>
                                                                    <?php
                                                                    $state = $city = '';
                                                                    if(isset($applicant['Location_City']) && $applicant['Location_City'] != null && $applicant['Location_City'] != '') $city = ' - '.ucfirst($applicant['Location_City']);
                                                                    if(isset($applicant['Location_State']) && $applicant['Location_State'] != null && $applicant['Location_State'] != '') $state = ', '.db_get_state_name($applicant['Location_State'])['state_name'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?=reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this));?></td>
                                                                        <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                        <td style="color:<?php echo ($applicant['Title'] != 'Job Deleted' ? 'green' : 'red'); ?>"><?php echo ($applicant['Title'] != '' ? $applicant['Title'].$city.$state : 'Job Removed From System'); ?></td>
                                                                        <td><?php echo $applicant['email']; ?></td>
                                                                        <td class="text-center">
                                                                            <div class="contacts_label auto-height  <?php echo $applicant['css_class']?>">
                                                                                <?php echo ucwords($applicant['status']); ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr><td colspan="5">No Applicants Found</td></tr>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script>
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function generate_search_url(){
        var keyword = $('#keyword').val();
        var applicant_status = $('#applicant_status').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var start_date_applied = $('#startdate').val();
        var end_date_applied = $('#enddate').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';

        var url = '<?php echo base_url('reports/generate_applicant_status_report'); ?>' + '/' + keyword + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied + '/' + job_sid + '/' + applicant_type;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {

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

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        $('#applicant_status').on('change',function (value) {
                generate_search_url();
            }
        );
        $('#applicant_type').on('change',function (value) {
                generate_search_url();
            }
        );
        $('#job_sid').on('change',function (value) {
                generate_search_url();
            }
        );

        $('#startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) {
                //console.log(value);
                $('#enddate').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#enddate').val());

        $('#enddate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) {
                //console.log(value);
                $('#startdate').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());

        $('#keyword').on('keyup', function(){
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        // Search Area Toggle Function
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });


    });

    function print_page(elem) {
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
    }
</script>
