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
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/new_hires_onboarding_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <!-- radio buttons -->
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><b>Please Select : </b><span class="hr-required red"> * </span></div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Company
                                                                    <input type="radio" name="company_or_brand" value="company" id="company" <?php
                                                                                                                                                if ($company_or_brand == 'company') {
                                                                                                                                                    echo 'checked';
                                                                                                                                                }
                                                                                                                                                ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Oem,Independent,Vendor
                                                                    <input type="radio" name="company_or_brand" value="brand" id="brand" <?php
                                                                                                                                            if ($company_or_brand == 'brands') {
                                                                                                                                                echo 'checked';
                                                                                                                                            }
                                                                                                                                            ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">All
                                                                    <input type="radio" name="company_or_brand" value="all" id="all" <?php
                                                                                                                                        if ($company_or_brand == 'all') {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- radio buttons -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row field-row-autoheight">
                                                    <div id="company_div" class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="valign-middle">Company : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <select class="invoice-fields" name="company_sid" id="company_sid">
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
                                                    <div id="brand_div" class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="valign-middle">Oem,Independent,Vendor : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($brands) > 0) { ?>
                                                                    <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($brands as $brand) { ?>
                                                                            <option <?php if ($this->uri->segment(7) != 'all' && urldecode($this->uri->segment(7)) == $brand['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $brand['sid']; ?>">
                                                                                <?php echo $brand['oem_brand_name']; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No Oem,Independent,Vendor found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- oem brand drop down -->
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label class="valign-middle">Applicant Name :</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <div class="hr-select">
                                                            <?php $keyword = $this->uri->segment(9) != 'all' ? urldecode($this->uri->segment(9)) : ''; ?>
                                                            <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label class="">Date From</label>
                                                            <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y'); ?>
                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label class="">Date To</label>
                                                            <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field-row field-row-autoheight col-lg-12 text-right">
                                                    <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                    <!--                                                    <input type="submit" class="btn btn-success" value="Apply Filters" name="submit" id="apply_filters_submit">-->
                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/reports/new_hires_onboarding_report'); ?>">Reset Filters</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- view -->
                                    <?php if (isset($applicants) && !empty($applicants)) { ?>
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
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-6 col-sm-6 col-md-6 col-lg-6">Job Title</th>
                                                                <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Applicant Name</th>
                                                                <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Hired On</th>
                                                                <?php } ?>
                                                                <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                    <th class="col-xs-2">Company Name</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (isset($applicants) && !empty($applicants)) { ?>
                                                                <?php foreach ($applicants as $applicant) { ?>
                                                                    <tr>
                                                                        <td style="color:<?php echo ($applicant['Title'] != 'Job Deleted' ? 'green' : 'red'); ?>"><?php echo ($applicant['Title'] != '' ? $applicant['Title'] : 'Job Removed From System'); ?></td>
                                                                        <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                        <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                                            <td><?php echo date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date']))); ?></td>
                                                                        <?php } ?>
                                                                        <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                            <td><?php echo ucwords($applicant['CompanyName']); ?></td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="<?php
                                                                                                        if ($company_or_brand == 'brands' || $company_or_brand == 'all') {
                                                                                                            echo '4';
                                                                                                        } else {
                                                                                                            echo '3';
                                                                                                        }
                                                                                                        ?>">
                                                                        <?php if (!isset($applicants)) { ?>
                                                                            <div class="no-data">Please select company.</div>
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
                                    </div>

                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <span class="pull-right">
                                                <?php echo $page_links ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if (isset($applicants) && !empty($applicants)) { ?>
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
        var brand_sid = $("#brand_sid").val();
        var keyword = $("#keyword").val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var all = 0;

        if ($('input[name="company_or_brand"]:checked').val() == 'all') {
            all = 1;
        }

        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        brand_sid = brand_sid != '' && brand_sid != null && brand_sid != undefined && brand_sid != 0 ? encodeURIComponent(brand_sid) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';


        var url = '<?php echo base_url('manage_admin/reports/new_hires_onboarding_report'); ?>' + '/' + company_sid + '/' + start_date_applied + '/' + end_date_applied + '/' + brand_sid + '/' + all + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }


    $('#btn_apply_filters').click(function() {

        var selectedRadio = $('input[name="company_or_brand"]:checked').val();
        if (selectedRadio == 'company' && $('#company_sid').val() == '') {
            alertify.error('Please select Company');
            return false;
        }
        if (selectedRadio == 'brand' && $('#brand_sid').val() == '') {
            alertify.error('Please select Oem,Independent,Vendor');
            return false;
        }
        e.preventDefault();
        generate_search_url();
        window.location = $(this).attr('href').toString();
        //        $("#search").validate({
        //            ignore: [],
        //            rules: {
        //                company_sid: {required: function (element) {
        //                        return $('input[name=company_or_brand]:checked').val() == 'company';
        //                    }
        //                },
        //                brand_sid: {required: function (element) {
        //                        return $('input[name=company_or_brand]:checked').val() == 'brand';
        //                    }
        //                },
        //                company_or_brand: {
        //                    required: true,
        //                }
        //            },
        //            messages: {
        //                company_sid: {
        //                    required: 'Company name is required'
        //                },
        //                brand_sid: {
        //                    required: 'Brand name is required'
        //                },
        //                company_or_brand: {
        //                    required: 'Please select one of the options'
        //                }
        //            }
        //        });
    });



    // ***** on radio button change ***** //
    function display(div_to_show) {
        if (div_to_show == 'company') {
            $('#company_div').show();
            $('#brand_div').hide();
        } else if (div_to_show == 'brand') {
            $('#company_div').hide();
            $('#brand_div').show();
        } else {
            $('#company_div').show();
            $('#brand_div').hide();
        }
    }

    $(document).ready(function() {
        var div_to_show = $('input[name="company_or_brand"]:checked').val();
        if (div_to_show == 'brand') {
            $('#company_sid').val('');
            $('#company_sid').removeProp('disabled', 'disabled');
            $('#brand_sid').removeProp('disabled', 'disabled');
        } else if (div_to_show == 'company') {
            $('#brand_sid').val('');
            $('#company_sid').removeProp('disabled', 'disabled');
            $('#brand_sid').removeProp('disabled', 'disabled');
        } else {
            $('#company_sid').attr('disabled', 'disabled');
            $('#brand_sid').attr('disabled', 'disabled');
            $('#company_sid').val('');
            $('#brand_sid').val('');
        }
        display(div_to_show);

        $('input[name="company_or_brand"]').change(function(e) {
            var div_to_show = $(this).val();
            if (div_to_show == 'brand') {
                $('#company_sid').val('');
                $('#company_sid').removeProp('disabled', 'disabled');
                $('#brand_sid').removeProp('disabled', 'disabled');
            } else if (div_to_show == 'company') {
                $('#brand_sid').val('');
                $('#company_sid').removeProp('disabled', 'disabled');
                $('#brand_sid').removeProp('disabled', 'disabled');
            } else {
                $('#company_sid').attr('disabled', 'disabled');
                $('#brand_sid').attr('disabled', 'disabled');
                $('#company_sid').val('');
                $('#brand_sid').val('');
                generate_search_url();
            }
            display(div_to_show);
        });

        $('.datepicker').datepicker({
            ateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        $("#company_sid").change(function() {
            generate_search_url();
        });

        $('#brand_sid').change(function() {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

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