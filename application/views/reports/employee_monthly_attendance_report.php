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
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
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
                                                        <div id="collapseOne" class="panel-collapse collapse">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="universal-form-style-v2">
                                                                        <ul class="row">
                                                                            <li class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                                                                <label>Employee</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <?php $temp = $this->uri->segment(3);?>
                                                                                    <select class="invoice-fields" name="employee_sid" id="employee_sid">
                                                                                        <?php if(!empty($employees)) { ?>
                                                                                            <?php foreach($employees as $employee) { ?>
                                                                                                <?php $default_selected = $temp == $employee['sid'] ? true : false;?>
                                                                                                <option <?php echo set_select('employee_sid', $employee['sid'], $default_selected); ?> value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></option>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </li>
                                                                            <li class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                                                <label>Year</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <?php $temp = $this->uri->segment(4);?>
                                                                                    <select class="invoice-fields" name="year" id="year">
                                                                                        <?php for($count = 2017; $count <= intval(date('Y')); $count++) { ?>
                                                                                            <?php $default_selected = $temp == $count ? true : false;?>
                                                                                            <option <?php echo set_select('year', $count, $default_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </li>
                                                                            <li class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                                                <label>Month</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <?php $temp = $this->uri->segment(5);?>
                                                                                    <select class="invoice-fields" name="month" id="month">
                                                                                        <?php for($count = 1; $count <= 12; $count++) { ?>
                                                                                            <?php $default_selected = $temp == $count ? true : false;?>
                                                                                            <option <?php echo set_select('year', $count, $default_selected); ?> value="<?php echo $count; ?>"><?php echo $months[$count]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </li>
                                                                            <li class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                                                <label>&nbsp;</label>
                                                                                <a id="my_search_btn" href="" class="submit-btn">Search</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="hr-box" id="print_div">
                                                <div class="hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right">
                                                            <div class="img-thumbnail pull-right">
                                                                <?php if($employee_details['profile_picture'] != '') { ?>
                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $employee_details['profile_picture']; ?>" class="img-responsive" />
                                                                <?php } else { ?>
                                                                    <img src="<?php echo base_url('assets/images');?>/default_pic.jpg" class="img-responsive" />
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 pull-left">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover table-striped">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th class="col-xs-4">Employee Name</th>
                                                                            <td class="col-xs-8"><?php echo $employee_details['first_name'] . ' ' . $employee_details['last_name']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-4">Email</th>
                                                                            <td class="col-xs-8"><?php echo $employee_details['email']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-4">Access Level</th>
                                                                            <td class="col-xs-8"><?php echo $employee_details['access_level']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-4">Job Title</th>
                                                                            <td class="col-xs-8"><?php echo $employee_details['job_title']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-4">Employee Since</th>
                                                                            <td class="col-xs-8"><?php echo date_with_time($employee_details['registration_date']); ?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="table-responsive table-outer" id="print_div">
                                                                <div class="table-wrp mylistings-wrp border-none">
                                                                    <table class="table table-bordered table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="col-xs-3 text-center">Clock In</th>
                                                                            <th class="col-xs-3 text-center">Clock Out</th>
                                                                            <th class="col-xs-3 text-center">Total Break<br/>(HH:MM)</th>
                                                                            <th class="col-xs-3 text-center">Total Time<br/>(HH:MM)</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if (!empty($attendances)) { ?>
                                                                            <?php foreach($attendances as $attendance) { ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo date('m/d/Y H:i:s', strtotime($attendance['start_attendance_date'])); ?></td>
                                                                                    <td class="text-center"><?php echo date('m/d/Y H:i:s', strtotime($attendance['attendance_date'])); ?></td>
                                                                                    <td class="text-center"><?php echo str_pad($attendance['total_break_hours'],2,0,STR_PAD_LEFT) . ':' . str_pad($attendance['total_break_minutes'],2,0,STR_PAD_LEFT); ?></td>
                                                                                    <td class="text-center"><?php echo str_pad($attendance['hours_after_breaks'],2,0,STR_PAD_LEFT) . ':' . str_pad($attendance['minutes_after_breaks'],2,0,STR_PAD_LEFT); ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr class="text-center">
                                                                                <td colspan="4">
                                                                                    <span class="no-data">No Attendance Recorded</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td class="text-center"><strong>Total</strong></td>
                                                                            <td class="text-center" colspan="2" ></td>
                                                                            <td class="text-center"><?php echo str_pad($grand_total_hours,2,0,STR_PAD_LEFT) . ':' . str_pad($grand_total_minutes,2,0,STR_PAD_LEFT); ?></td>
                                                                        </tr>
                                                                        </tfoot>
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
            </div>
        </div>
    </div>
</div>

<script>
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#my_search_btn').click();
        }
    });
    $(document).ready(function () {
        $('#my_search_btn').click(function () {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
            $("#search").validate({
                ignore: [],
                rules: {
                    company_sid: {required: function (element) {
                        return $('input[name=company_or_brand]:checked').val() == 'company';
                    }
                    },
                    brand_sid: {required: function (element) {
                        return $('input[name=company_or_brand]:checked').val() == 'brand';
                    }
                    },
                    company_or_brand: {
                        required: true,
                    }
                },
                messages: {
                    company_sid: {
                        required: 'Company name is required'
                    },
                    brand_sid: {
                        required: 'Brand name is required'
                    },
                    company_or_brand: {
                        required: 'Please select one of the options'
                    }
                }
            });
        });
        $('select').on('change', function(){
            update_search_url();
        }).trigger('change');
    });

    function update_search_url(){
        var employee_sid = $('#employee_sid').val();
        var year = $('#year').val();
        var month = $('#month').val();
        var my_url = '<?php echo base_url('reports/employee_monthly_attendance_report')?>/' + employee_sid +'/' + year + '/' + month;
        $('#my_search_btn').attr('href', my_url);
    }
    
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
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); 
        mywindow.focus(); 
    }
</script>