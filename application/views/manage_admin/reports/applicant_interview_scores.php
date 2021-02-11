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

                                    <?php $flag = ( $this->uri->segment(4) > 0 || $this->uri->segment(5) > 0 ? true : false ); ?>

                                    <div class="hr-search-criteria <?php echo (isset($flag) && $flag == true ? 'opened' : ''); ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?php echo (isset($flag) && $flag == true ? 'style="display:block"' : ''); ?> >
                                        <form action="<?php echo base_url('manage_admin/reports/applicant_status_report'); ?>" name="search" id="search">
                                            <input type="hidden" id="search_url" name="search_url" value="<?php echo current_url(); ?>" />
                                            <div class="row">
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <b>Please Select : </b><span class="hr-required red"> * </span>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">

                                                                <label id="company_label" class="control control--radio">Company
                                                                    <input onclick="display('company');" type="radio" name="company_or_brand" value="company" id="company" <?php echo ($this->uri->segment(5) != 'all' && $this->uri->segment(5) > 0 ? 'checked="checked"' : ''); ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label id="brand_label" class="control control--radio">Oem,Independent,Vendor
                                                                    <input onclick="display('brand');" type="radio" name="company_or_brand" value="brand" id="brand"  <?php echo ($this->uri->segment(4) != 'all' && $this->uri->segment(4) > 0 ? 'checked="checked"' : ''); ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row field-row-autoheight">
                                                    <div id="company_div" class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="valign-middle">Company : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                        <option value="0">Please Select</option>
                                                                        <?php foreach ($companies as $active_company) { ?>
                                                                            <?php $default_selected = ( $this->uri->segment(5) == $active_company['sid'] ? true : false );?>
                                                                            <option <?php echo set_select('company_sid', $active_company['sid'], $default_selected); ?>  value="<?php echo $active_company['sid']; ?>"><?php echo ucwords($active_company['CompanyName']); ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No company found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="brand_div" class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="valign-middle">Oem,Independent,Vendor : <span class="hr-required">*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($brands) > 0) { ?>
                                                                    <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                        <option value="0">Please Select</option>
                                                                        <?php foreach ($brands as $brand) { ?>
                                                                            <?php $default_selected = ( $this->uri->segment(4) == $brand['sid'] ? true : false );?>
                                                                            <option <?php echo set_select('brand_sid', $brand['sid'], $default_selected); ?> value="<?php echo $brand['sid']; ?>"><?php echo $brand['oem_brand_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No Oem,Independent,Vendor found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="field-row field-row-autoheight">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label class="valign-middle">Between : </label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                <?php /*$temp = ($this->uri->segment(6) == 'all' ? '' : $this->uri->segment(6));  */?>
                                                                <input type="text" name="start_date" value="<?php /*echo set_value('start_date', $temp); */?>" class="invoice-fields datepicker" id="start_date" readonly>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                                                                <label class="valign-middle">To</label>
                                                            </div>
                                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                <?php /*$temp = ($this->uri->segment(7) == 'all' ? '' : $this->uri->segment(7));  */?>
                                                                <input type="text" name="end_date" value="<?php /*echo set_value('end_date', $temp); */?>" class="invoice-fields datepicker" id="end_date" readonly>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>-->
                                                <div class="field-row field-row-autoheight col-lg-12 text-right">
                                                    <a class="btn btn-success" id="apply_filters_submit" href="<?php echo current_url(); ?>">Apply Filters</a>
                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/reports/applicant_origination_tracker/all/all/all/all'); ?>">Reset Filters</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- view -->
                                    <?php if (isset($companies_applicant_scores) && !empty($companies_applicant_scores)) { ?>
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

                                    <div class="row" id="print_div">
                                        <div class="col-xs-12">
                                            <div class="add-new-company">
                                                <?php if(!empty($companies_applicant_scores)) { ?>
                                                    <?php foreach($companies_applicant_scores as $company_applicant_scores) { ?>

                                                        <?php $company_info = $company_applicant_scores['company_info']; ?>
                                                        <?php $applicant_scores = $company_applicant_scores['applicant_scores']; ?>
                                                        <div class="hr-box">
                                                            <div class="hr-box-header bg-header-green">
                                                                <span class="hr-registered pull-left">
                                                                    <?php echo ucwords($company_info['CompanyName']); ?>
                                                                </span>
                                                                <span class="label label-default pull-right" style="font-size: 14px; background-color:#518401; padding: 0.5em 0.8em;">
                                                                    Total <?php echo count($applicant_scores); ?> Applicant Interview(s)
                                                                </span>
                                                            </div>
                                                            <div class="hr-box-body hr-innerpadding">
                                                                <div class="hr-box-body">
                                                                    <div class="table-responsive hr-innerpadding">
                                                                        <table class="table table-bordered horizontal-scroll">
                                                                            <thead>

                                                                            <tr>
                                                                                <th class="text-center" rowspan="2">Interview Date</th>
                                                                                <th class="text-center" rowspan="2">Applicant</th>
                                                                                <th class="text-center" rowspan="2">Conducted By</th>

                                                                                <th class="text-center" rowspan="2">For Position</th>

                                                                                <th class="text-center col-xs-2" colspan="2">Evaluation Score</th>
                                                                                <th class="text-center col-xs-2" colspan="2">Overall Score</th>

                                                                                <th class="text-center" rowspan="2">Star Rating</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-center col-xs-1">Applicant</th>
                                                                                <th class="text-center col-xs-1">Job Relevancy</th>

                                                                                <th class="text-center col-xs-1">Applicant</th>
                                                                                <th class="text-center col-xs-1">Job Relevancy</th>

                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if(!empty($applicant_scores)) { ?>
                                                                                <?php foreach($applicant_scores as $key => $applicant_score) { ?>
                                                                                    <tr>
                                                                                        <td class="text-center">
                                                                                            <?php echo convert_date_to_frontend_format($applicant_score['created_date']); ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php echo ucwords($applicant_score['first_name'] . ' ' . $applicant_score['last_name']); ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div><strong><?php echo ucwords($applicant_score['conducted_by_first_name'] . ' ' . $applicant_score['conducted_by_last_name']); ?></strong></div>
                                                                                            <div><small><?php echo ucwords($applicant_score['conducted_by_job_title']); ?></small></div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php echo ucwords($applicant_score['job_title']); ?>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <div><strong><?php echo $applicant_score['candidate_score']; ?></strong> Point(s)</div>
                                                                                            <div><small>( Out of 100 )</small></div>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <div><strong><?php echo $applicant_score['job_relevancy_score']; ?></strong> Point(s)</div>
                                                                                            <div><small>( Out of 100 )</small></div>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <div><strong><?php echo $applicant_score['candidate_overall_score'] * 10; ?></strong> Point(s)</div>
                                                                                            <div><small>( Out of 100 )</small></div>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <div><strong><?php echo $applicant_score['job_relevancy_overall_score'] * 10; ?></strong> Point(s)</div>
                                                                                            <div><small>( Out of 100 )</small></div>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <div class="star_div star-rating">
                                                                                                <input id="star_rating" value="<?php echo $applicant_score['star_rating']; ?>" type="number" class="rating" min=0 max=5 step=0.2 data-size="xs" readonly="readonly">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td class="text-center" colspan="9">
                                                                                        <span class="no-data">No Applicants</span>
                                                                                    </td>
                                                                                </tr>
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
                                                        <div class="hr-box-body text-center">
                                                            <span class="no-data">No Applicants</span>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($companies_applicant_scores) && !empty($companies_applicant_scores)) { ?>
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

<script>



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

    function func_build_url(override_company_sid = false, override_brand_sid = false){
        var company_sid = $('#company_sid').val();
        var brand_sid = $('#brand_sid').val();
        //var start_date = $('#start_date').val();
        //var end_date = $('#end_date').val();

        console.log(brand_sid);

        var url_string = '<?php echo base_url("manage_admin/reports/applicant_interview_scores"); ?>';

        if(company_sid == '' || company_sid == 0 || company_sid == undefined || company_sid == null){
            company_sid = 'all';
        }

        if(brand_sid == '' || brand_sid == 0 || brand_sid == undefined || brand_sid == null){
            brand_sid = 'all';
        }

        /*
        if(start_date == '' || start_date == 0 || start_date == undefined || start_date == null){
            start_date = 'all';
        }

        if(end_date == '' || end_date == 0 || end_date == undefined || end_date == null){
            end_date = 'all';
        }
        */


        if(override_company_sid == true){
            company_sid = 'all';
        }

        if(override_brand_sid == true){
            brand_sid = 'all';
        }


        //url_string = url_string + '/' + (brand_sid) + '/' + (company_sid) + '/' + (start_date) + '/' + (end_date);
        url_string = url_string + '/' + (brand_sid) + '/' + (company_sid);


        $('#search_url').val(url_string);
        $('#apply_filters_submit').attr('href', url_string);
        return url_string;
    }

    $(document).ready(function () {
        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value) {
                console.log(func_build_url());
            }
        });

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value) {
                console.log(func_build_url());
            }
        });

        $('#company_sid').on('change', function () {
            func_build_url(false, true);
        });

        $('#brand_sid').on('change', function () {
            func_build_url(true, false);
        });

        <?php if($this->uri->segment(5) != 'all' && $this->uri->segment(5) > 0) { ?>

        display('company');

        <?php } else if ($this->uri->segment(4) != 'all' && $this->uri->segment(4) > 0) { ?>

        display('brand');

        <?php } ?>

        <?php if( $this->uri->segment(5) == 'all' && $this->uri->segment(4) == 'all') { ?>

        $('#company').prop('checked', true);

        display('company');

        <?php } ?>
    });
    // ***** on radio button change ***** //
    
    
    function print_page(elem)
    {
        $('table').removeClass('horizontal-scroll');
        $('.star_div').removeClass('star-rating');
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
        $('table').addClass('horizontal-scroll');
        $('.star_div').addClass('star-rating');
    }
</script>