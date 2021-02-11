<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/interviews_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2"><b>Please Select : </b><span class="hr-required red"> * </span></div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio">Company
                                                                    <input type="radio" name="company_or_brand" value="company" id="company" <?php
                                                                    if ($company_or_brand == 'company') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio">Oem,Independent,Vendor
                                                                    <input type="radio" name="company_or_brand" value="brand" id="brand" <?php
                                                                    if ($company_or_brand == 'brands') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 field-row field-row-autoheight">
                                                    <div id="company_div" class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="valign-middle">Company : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <?php $company = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : '';?>
                                                                    <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($companies as $active_company) { ?>
                                                                            <option <?php if ($company == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
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
                                                    <div id="brand_div" class="row">
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">
                                                            <label class="valign-middle">Oem,Independent,Vendor : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-6">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($brands) > 0) { ?>
                                                                    <?php $oem = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : '';?>
                                                                    <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($brands as $brand) { ?>
                                                                            <option <?php if ($oem == $brand['sid']) { ?>
                                                                                    selected="selected" <?php } ?> value="<?php echo $brand['sid']; ?>">
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
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 field-row field-row-autoheight">
                                                    <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
<!--                                                    <input type="submit" class="btn btn-equalizer btn-block btn-success" value="Apply Filters" name="submit" id="apply_filters_submit">-->
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 field-row field-row-autoheight">
                                                    <a class="btn btn-equalizer btn-block btn-success" href="<?php echo base_url('manage_admin/reports/interviews_report'); ?>">Reset Filters</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- view -->
                                    <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
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
                                        <div class="col-xs-12" id="print_div">
                                            <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
                                                <?php foreach ($users_events as $user_event) { ?>
                                                    <div class="row job-per-month-row">
                                                        <div class="col-lg-2 col-md-4 col-xs-12 col-sm-12">
                                                            <div class="month-name">
                                                                <?php echo ucwords($user_event['first_name'] . ' ' . $user_event['last_name']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-10 col-md-8 col-xs-12 col-sm-12">
                                                            <div class="hr-box">
                                                                <div class="table-responsive hr-innerpadding">
                                                                    <table class="table table-bordered table-stripped table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-xs-5">Interview Scheduled For</th>
                                                                                <th class="col-xs-2">Interview Date</th>
                                                                                <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                                    <th class="col-xs-3">Company Name</th>
                                                                                <?php } ?>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (!empty($user_event['events'])) { ?>
                                                                                <?php foreach ($user_event['events'] as $event) { ?>
                                                                                    <tr>
                                                                                        <td><?php echo ucwords($event['applicant_first_name'] . ' ' . $event['applicant_last_name']); ?></td>
                                                                                        <td><?php echo date_with_time($event['date']); ?></td>
                                                                                        <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                                            <td><?php echo ucwords($event['CompanyName']); ?></td>
                                                                                        <?php } ?>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr><td colspan="<?php
                                                                                    if ($company_or_brand == 'brands' || $company_or_brand == 'all') {
                                                                                        echo '3';
                                                                                    } else {
                                                                                        echo '2';
                                                                                    }
                                                                                    ?>">No Interviews Found</td></tr>
                                                                                <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="hr-box">
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Interview Scheduled For</th>
                                                                    <th class="col-xs-2">Interview Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr><td class="text-center" colspan="2">
                                                                        <?php if (isset($flag) && $flag == false) { ?>
                                                                            <div class="no-data">Please select company.</div>
                                                                        <?php } else { ?>
                                                                            <div class="no-data">No users found.</div>
                                                                        <?php } ?>
                                                                    </td></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
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
        var brand = $('#brand_sid').val();
        var company = $('#company_sid').val();
        brand = brand != '' && brand != null && brand != undefined && brand != 0 ? encodeURIComponent(brand) : 'all';
        company = company != '' && company != null && company != undefined && company != 0 ? encodeURIComponent(company) : 'all';

        var url = '<?php echo base_url('manage_admin/reports/interviews_report'); ?>' + '/' + company + '/' + brand ;

        $('#btn_apply_filters').attr('href', url);
    }

    $('#btn_apply_filters').click(function (e) {
        var selectedRadio = $('input[name="company_or_brand"]:checked').val();
        if(selectedRadio == 'company' && $('#company_sid').val()==''){
            alertify.error('Please select Company');
            return false;
        }
        if(selectedRadio == 'brand' && $('#brand_sid').val()==''){
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

    $('#brand_sid').on('change',function (value) {
        generate_search_url();
    });
    $('#company_sid').on('change',function (value) {
        generate_search_url();
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

    $(document).ready(function () {
        var div_to_show = $('input[name="company_or_brand"]:checked').val();
        display(div_to_show);

        $('input[name="company_or_brand"]').change(function (e) {
            var div_to_show = $(this).val();
            display(div_to_show);
            if(div_to_show == 'brand'){
                $('#company_sid').val('');
            }
            else{
                $('#brand_sid').val('');
            }
        });
    });
    // ***** on radio button change ***** //
    
    
    function print_page(elem)
    {
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