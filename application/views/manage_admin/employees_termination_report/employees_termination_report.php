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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?php
                                    if ($flag == true) {
                                        echo 'opened';
                                    }
                                    ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?php
                                    if ($flag == true) {
                                        echo "style='display:block'";
                                    }
                                    ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/employees_termination_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 field-row">
                                                    <div id="company_div">
                                                        <label>Companies :</label>
                                                        <div class="hr-select-dropdown">
                                                            <?php if (sizeof($active_companies) > 0) { ?>
                                                                <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                    <option value="">Any Company</option>
                                                                    <?php foreach ($active_companies as $active_company) { ?>
                                                                        <option <?php if ($this->uri->segment(4) != 'all' && urldecode($this->uri->segment(4)) == $active_company['sid']) { ?>
                                                                            selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
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
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-5 field-row">
                                                    <label>Date From:</label>
                                                    <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y');?>
                                                    <input class="invoice-fields"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="start_date_applied"
                                                           id="start_date_applied"
                                                           value="<?php echo set_value('start_date_applied', $start_date); ?>"/>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-5 field-row">
                                                    <label>Date To:</label>
                                                    <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y');?>
                                                    <input class="invoice-fields"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="end_date_applied"
                                                           id="end_date_applied"
                                                           value="<?php echo set_value('end_date_applied', $end_date); ?>"/>
                                                </div>
                                                <div class="col-lg-12 text-right field-row field-row-autoheight">
                                                    <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filters</a>
                                                    <a href="<?php echo base_url('manage_admin/reports/employees_termination_report'); ?>" class="btn btn-success">Reset Filters</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php if (isset($terminatedEmployees) && sizeof($terminatedEmployees) > 0) { ?>
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
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row"><?php echo $links; ?></div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Employees Report</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo sizeof($terminatedEmployees);?></h1>
                                            </span>
                                        </div>
                                        <div class="table-responsive hr-innerpadding" id="print_div">
                                            <table class="table table-stripped table-hover table-bordered" id="example">
                                                <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Employee ID</th>
                                                        <th>Company Name</th>
                                                        <th>Job Title</th>
                                                        <th>Department</th>
                                                        <th>Hire Date</th>
                                                        <th>Last Day Worked</th>
                                                        <th>Termination Reason</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($terminatedEmployees) && sizeof($terminatedEmployees) > 0) { ?>
                                                        <?php foreach ($terminatedEmployees as $terminatedEmployee) {?>
                                                            <tr>
                                                                <td>
                                                                    <?php 
                                                                        echo remakeEmployeeName([
                                                                            'first_name' => $terminatedEmployee['first_name'],
                                                                            'last_name' => $terminatedEmployee['last_name'],
                                                                            'access_level' => $terminatedEmployee['access_level'],
                                                                            'timezone' => isset($terminatedEmployee['timezone']) ? $terminatedEmployee['timezone'] : '',
                                                                            'access_level_plus' => $terminatedEmployee['access_level_plus'],
                                                                            'is_executive_admin' => $terminatedEmployee['is_executive_admin'],
                                                                            'pay_plan_flag' => $terminatedEmployee['pay_plan_flag'],
                                                                            'job_title' => $terminatedEmployee['job_title'],
                                                                        ]); 
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo "AHR-".$terminatedEmployee['sid']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo getCompanyNameBySid($terminatedEmployee['parent_sid']); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $terminatedEmployee['job_title']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo getDepartmentNameBySID($terminatedEmployee['department_sid']); ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        echo formatDateToDB(get_employee_latest_joined_date(
                                                                            $terminatedEmployee['registration_date'],
                                                                            $terminatedEmployee['joined_at'],
                                                                            $terminatedEmployee['rehire_date'],
                                                                            false
                                                                        ), DB_DATE, DATE);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        echo formatDateToDB($terminatedEmployee['termination_date'], DB_DATE, DATE); 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php 
                                                                        
                                                                        $reason = $terminatedEmployee['termination_reason'];
            
                                                                        if ($reason == 1) {
                                                                            echo 'Resignation';
                                                                        } else if ($reason == 2) {
                                                                            echo 'Fired';
                                                                        } else if ($reason == 3) {
                                                                            echo 'Tenure Completed';
                                                                        } else if ($reason == 4) {
                                                                            echo 'Personal';
                                                                        } else if ($reason == 5) {
                                                                            echo 'Personal';
                                                                        } else if ($reason == 6) {
                                                                            echo 'Problem with Supervisor';
                                                                        } else if ($reason == 7) {
                                                                            echo 'Relocation';
                                                                        } else if ($reason == 8) {
                                                                            echo 'Work Schedule';
                                                                        } else if ($reason == 9) {
                                                                            echo 'Retirement';
                                                                        } else if ($reason == 10) {
                                                                            echo 'Return to School';
                                                                        } else if ($reason == 11) {
                                                                            echo 'Pay';
                                                                        } else if ($reason == 12) {
                                                                            echo 'Without Notice/Reason';
                                                                        } else if ($reason == 13) {
                                                                            echo 'Involuntary';
                                                                        } else if ($reason == 14) {
                                                                            echo 'Violating Company Policy';
                                                                        } else if ($reason == 15) {
                                                                            echo 'Attendance Issues';
                                                                        } else if ($reason == 16) {
                                                                            echo 'Performance';
                                                                        } else if ($reason == 17) {
                                                                            echo 'Workforce Reduction';
                                                                        } elseif ($reason == 18) {
                                                                            echo 'Store Closure';
                                                                        } else {
                                                                            echo 'N/A';
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="8"><div class="no-data">No terminated employee found.</div></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row"><?php echo $links; ?></div>
                                    </div>
                                    <?php if (isset($terminatedEmployees) && sizeof($terminatedEmployees) > 0) { ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    function generate_search_url(){

        var company_sid = $("#company_sid").val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();

        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';


        var url = '<?php echo base_url('manage_admin/reports/employees_termination_report'); ?>' + '/' + company_sid + '/' + start_date_applied + '/' + end_date_applied ;

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
            onSelect: function (value) {
                //
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
                //
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });

    $('#apply_filters_submit').click(function () {
        $("#search").validate({
            ignore: [],
            rules: {
                company_sid: {required: function (element) {
                        return $('input[name=company_or_brand]:checked').val() == 'company';
                    }
                },
            },
            messages: {
                company_sid: {
                    required: 'Company name is required'
                }
            }
        });
    });
    
    
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