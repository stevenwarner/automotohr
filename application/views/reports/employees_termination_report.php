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
                                                                            <label class="">Start Date</label>
                                                                            <?php $start_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-d-Y'); ?>
                                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <div class="field-row">
                                                                            <label class="">End Date</label>
                                                                            <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y'); ?>
                                                                            <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                        <div class="field-row autoheight text-right">

                                                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('reports/generate/applicants'); ?>">Reset Filters</a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bottom here-->

                                        <?php if (isset($terminatedEmployees) && sizeof($terminatedEmployees) > 0) { ?>
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
                                                    <h1 class="hr-registered">Termination Report</h1>
                                                </span>
                                                <span class="pull-right">
                                                    <h1 class="hr-registered">Total Records Found : <?php echo $terminatedEmployeesCount; ?></h1>
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
                                                                        <th>Employee Name</th>
                                                                        <th>Employee ID</th>
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
                                                                        <td class="text-center" colspan="7"><div class="no-data">No terminated employee found.</div></td>
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
        //
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';



        var url = '<?php echo base_url('reports/employeeTerminationReport'); ?>' + '/' +  start_date_applied + '/' + end_date_applied;

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