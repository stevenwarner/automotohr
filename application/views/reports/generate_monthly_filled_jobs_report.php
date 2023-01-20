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

                            <div class="panel-group-wrp">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    <span class="glyphicon glyphicon-<?php if(isset($filter_state) && $filter_state == true){ echo 'minus'; } else{ echo 'plus'; } ?>"></span>
                                                    Click to modify search criteria
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse <?php if(isset($filter_state) && $filter_state == true){ echo 'in'; } ?>">
                                            <div class="panel-body">
                                                <div class="hr-search-main" <?php
                                                if (isset($flag) && $flag == true) {
                                                    echo "style='display:block'";
                                                }
                                                ?>>
                                                    <!-- search form -->
                                                    <form method="GET" action="" name="search" id="search">
                                                        <div class="row">
                                                            <!-- year drop down -->
                                                            <div class="universal-form-style-v2">
                                                                <div class="field-row field-row-autoheight">
                                                                    <div class="col-lg-6 col-md-6 col-xs-8 col-sm-6">
                                                                        <label>Year : <span class="staric">*</span></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="year" id="year">
                                                                                <?php for ($i = 2015; $i < 2025; $i++) { ?>
                                                                                    <option value="<?php echo $i; ?>" <?php
                                                                                    if (isset($search['year']) && $search['year'] == $i) {
                                                                                        echo 'selected';
                                                                                    } else if (!(isset($search['year'])) && date("Y") == $i) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>><?php echo $i; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-8 col-sm-6">
                                                                        <label>Status : <span class="staric">*</span></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="status" id="status">
                                                                                <option value="0" <?php if(!$status) echo 'selected';?>>All</option>
                                                                                <option value="1" <?php if($status) echo 'selected';?>>Hired</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <!-- year drop down -->
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 text-right field-row field-row-autoheight">
                                                                <input type="submit" class="btn btn-success" value="Apply Filters" name="submit" id="apply_filters_submit">
                                                                <a class="btn btn-success" href="<?php echo base_url('reports/generate_monthly_filled_jobs_report'); ?>">Reset Filters</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- search form -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div id="col_chart" class=""></div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
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
                                        </div>
                                        <div class="col-xs-12" id="print_div">
                                            <?php foreach ($jobs as $month => $jobList) { ?>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <div class="month-name">
                                                            <?php echo $month; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <div class="table-responsive table-outer">
                                                            <div class="border-none mylistings-wrp">
                                                                <table class="table table-bordered table-stripped table-hover">
                                                                    <thead>
                                                                        <tr><th class="col-xs-8">Job Title</th><th class="col-xs-2">Filled Date</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (!empty($jobList)) { ?>
                                                                            <?php foreach ($jobList as $job) { ?>
                                                                                <?php
                                                                                    $state = $city = '';
                                                                                    if(isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - '.ucfirst($job['Location_City']);
                                                                                    if(isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="color:<?php echo ($job['Title'] != '' ? 'green' : 'red'); ?>"><?php echo ($job['Title'] != '' ? $job['Title'].$city.$state : 'Job Removed From System'); ?></td>
                                                                                    <td><?=reset_datetime(array('datetime' => $job['hired_date'], '_this' => $this));?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="2">No Jobs Found</td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <!--<div id="pie_chart" class=""></div>-->
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

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#apply_filters_submit').click();
        }
    });
    $(document).ready(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Jobs');

        //data.addColumn({ role: 'style' });
        /*
        data.addRows([
            ['January', 3],
            ['February', 1],
            ['March', 1],
            ['April', 1],
            ['June', 2],
            ['July', 2],
            ['August', 2],
            ['September', 2],
            ['October', 2],
            ['November', 2],
            ['December', 2]
        ]);
        */

        data.addRows(<?php echo $chart_data; ?>);
        // Set chart options
        var pie_options = {
            'legend':'bottom',
            'title':'Jobs Filled Per Month',
            'is3D':true,
            'width':'100%',
            'height': 400
        };

        // Instantiate and draw our chart, passing in some options.
        var pie_chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        pie_chart.draw(data, pie_options);

        // Set chart options
        var col_options = {
            'legend':'bottom',
            'title':'Jobs Filled Per Month',
            'is3D':true,
            'width':'100%',
            'height':300
        };
        var col_chart = new google.visualization.ColumnChart(document.getElementById('col_chart'));
        col_chart.draw(data, col_options);
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
