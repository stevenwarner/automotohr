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
                                    <div class="heading-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-users"></i>
                                            <?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                    <div class="hr-search-criteria <?php
                                                                    if (isset($flag) && $flag == true) {
                                                                        echo 'opened';
                                                                    }
                                                                    ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?php
                                                                                        if (isset($flag) && $flag == true) {
                                                                                            echo "style='display:block'";
                                                                                        }
                                                                                        ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/advanced_jobs_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <!-- radio buttons -->
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><b>Please Select : </b><span class="hr-required red"> * </span></div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio">Company
                                                                    <input type="radio" name="company_or_brand" value="company" id="company" <?php if ($radios != 'all') echo 'checked' ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio">All
                                                                    <input type="radio" name="company_or_brand" value="brand" id="brand" <?php if ($radios == 'all') echo 'checked' ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- radio buttons -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row field-row-autoheight">
                                                    <div id="company_div" class="row">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label class="valign-middle">Company : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <select class="invoice-fields" name="company_sid" id="company_sid" <?php if ($radios == 'all') echo 'disabled' ?>>
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($companies as $active_company) { ?>
                                                                            <option <?php if ($this->uri->segment(4) != 'all' && urldecode($this->uri->segment(4)) == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                                <?php echo $active_company['CompanyName']; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No company found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- oem brand drop down -->

                                                </div>
                                                <!-- oem brand drop down -->
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label class="valign-middle">Date From: </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y'); ?>
                                                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                                                                <label class="valign-middle">Date To:</label>
                                                            </div>
                                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="field-row field-row-autoheight col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label class="valign-middle">Priority : </label>
                                                        </div>
                                                        <?php $priority = $this->uri->segment(7); ?>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="priority" id="priority">
                                                                    <option value="active" <?php echo $priority == 'active' ? 'selected="selected"' : ''; ?>> Creation Date</option>
                                                                    <option value="deactive" <?php echo $priority == 'deactive' ? 'selected="selected"' : ''; ?>> De-Activation Date</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 text-right">
                                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                            <a class="btn btn-success" href="<?php echo base_url('manage_admin/reports/advanced_jobs_report'); ?>">Reset Filters</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- view -->
                                    <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
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
                                            <div class="hr-box">
                                                <div class="table-responsive hr-innerpadding" id="print_div">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-1">Date Time</th>
                                                                <th class="col-xs-1">Deactivated On</th>
                                                                <th class="col-xs-5">Job Title / Company</th>
                                                                <th class="col-xs-1">Status</th>
                                                                <th class="col-xs-1">Views</th>
                                                                <th class="col-xs-1">Applicants</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
                                                                <?php foreach ($all_jobs as $job) { ?>
                                                                    <tr>
                                                                        <td><?php echo date_with_time($job['activation_date']); ?></td>
                                                                        <td><?php
                                                                            if ($job['active']) {
                                                                                echo 'NULL';
                                                                            } else {
                                                                                if ($job['deactivation_date'] == NULL) {
                                                                                    echo 'Deactivation Not Specified';
                                                                                } else {
                                                                                    echo date_with_time($job['deactivation_date']);
                                                                                }
                                                                            }
                                                                            ?></td>
                                                                        <td>
                                                                            <?php
                                                                            $city = '';
                                                                            $state = '';
                                                                            if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                                                                                $city = ' - ' . ucfirst($job['Location_City']);
                                                                            }
                                                                            if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                                                                                $state = ',' . db_get_state_name($job['Location_State'])['state_name'];
                                                                            }
                                                                            echo ucwords($job['Title'] . $city . $state . ' <br> <b>' . $job['company_title'] . '</b>');
                                                                            ?>
                                                                        </td>
                                                                        <?php if ($job['active'] == 0) { ?>
                                                                            <td style="color:red">Inactive</td>
                                                                        <?php } else if ($job['active'] == 1) { ?>
                                                                            <td style="color:green;">Active</td>
                                                                        <?php } else if ($job['active'] == 2) { ?>
                                                                            <td style="color:red;">Archived</td>
                                                                        <?php } ?>
                                                                        <td style="color: <?php echo ($job['views'] == 0 ? 'red' : 'green'); ?>;"><?php echo $job['views']; ?></td>
                                                                        <td style="color: <?php echo ($job['applicant_count'] == 0 ? 'red' : 'green'); ?>;"><?php echo $job['applicant_count']; ?></td>

                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="<?php
                                                                                                        if (isset($search['company_or_brand']) && $search['company_or_brand'] == 'brand') {
                                                                                                            echo '6';
                                                                                                        } else {
                                                                                                            echo '5';
                                                                                                        }
                                                                                                        ?>">
                                                                        <?php if (!isset($all_jobs)) { ?>
                                                                            <div class="no-data">Please select company.</div>
                                                                        <?php } else if (isset($all_jobs) && sizeof($all_jobs) <= 0) { ?>
                                                                            <div class="no-data">No jobs found.</div>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
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
                                    <!-- view -->
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

<script>
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {

        var company_sid = $("#company_sid").val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var priority = $('#priority').val();

        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        priority = priority != '' && priority != null && priority != undefined && priority != 0 ? encodeURIComponent(priority) : 'all';


        var url = '<?php echo base_url('manage_admin/reports/advanced_jobs_report'); ?>' + '/' + company_sid + '/' + start_date_applied + '/' + end_date_applied + '/' + priority;

        $('#btn_apply_filters').attr('href', url);
    }


    $('#btn_apply_filters').click(function(e) {
        if ($('#company_sid').val() == '' && $('input[name="company_or_brand"]:checked').val() == 'company') {
            alertify.error('Please Select Company');
            return false;
        }

        e.preventDefault();
        generate_search_url();
        window.location = $(this).attr('href').toString();
    });

    // ***** on radio button change ***** //
    function display(div_to_show) {
        if (div_to_show == 'company') {
            $('#company_div').show();
            $('#brand_div').hide();
        } else {
            $('#company_div').hide();
            $('#brand_div').show();
        }
    }

    $(document).ready(function() {

        $('input[name="company_or_brand"]').change(function(e) {
            var div_to_show = $(this).val();
            var company_or_brand = $(this).val();
            if (company_or_brand == 'company') {
                $('#company_sid').val(jQuery('options:first', $('#company_sid')).val());
                $('#company_sid').attr('required', 'required');
                $('#company_sid').removeProp('disabled');
            } else {
                $('#company_sid').val(jQuery('options:first', $('#company_sid')).val());
                $('#company_sid').attr('disabled', 'disabled');
                $('#company_sid').removeProp('required');
                $('#brand_sid').val('all');
            }
        });

        $("#company_sid").change(function() {
            generate_search_url();
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

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
    // ***** on radio button change ***** //

    function print_page(elem) {
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
    }
</script>