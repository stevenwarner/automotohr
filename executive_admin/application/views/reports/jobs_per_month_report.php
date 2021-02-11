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
                <div class="hr-search-criteria <?php
                if (isset($flag) && $flag == true) {
                    echo 'opened';
                }
                ?>">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" <?php
                if (isset($flag) && $flag == true) {
                    echo "style='display:block'";
                }
                ?>>
                    <!-- search form -->
                    <form method="GET" action="<?php echo base_url('reports/jobs_per_month_report/' . $company_sid); ?>" name="search" id="search">
                        <div class="row">
                            <!-- year drop down -->
                            <div class="field-row field-row-autoheight">
                                <div class="col-lg-1 col-md-1 col-xs-4 col-sm-1">
                                    <label class="valign-middle">Year : <span class="hr-required">*</span></label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-xs-8 col-sm-5">
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

                                <div class="col-lg-1 col-md-1 col-xs-4 col-sm-1">
                                    <label class="valign-middle">Status : <span class="hr-required">*</span></label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-xs-8 col-sm-5">
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="status" id="status">
                                            <option value="0" <?php if(!$status) echo 'selected';?>>All</option>
                                            <option value="1" <?php if($status) echo 'selected';?>>Hired</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- year drop down -->
                            </div>
                            <div class="col-lg-12 text-right field-row field-row-autoheight">
                                <input type="submit" class="btn btn-success" value="Apply Filters" name="submit" id="apply_filters_submit">
                                <a class="btn btn-success" href="<?php echo base_url('reports/jobs_per_month_report/' . $company_sid); ?>">Reset Filters</a>
                            </div>
                        </div>
                    </form>
                    <!-- search form -->
                </div>
                <!-- search form drop down -->     
                <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export To Excel</button>
                    </form>
                </div>
                <?php } ?>
                <div id="print_div">
                    <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                        <?php foreach ($jobs as $month => $jobList) { ?>
                            <div class="row job-per-month-row">
                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                    <div class="month-name">
                                        <?php echo $month; ?>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="table-responsive hr-innerpadding">
                                            <table class="table table-bordered table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-5">Job Title</th>
                                                        <th class="col-xs-2 text-center">Filled Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($jobList)) { ?>
                                                        <?php foreach ($jobList as $job) { ?>
                                                            <tr>
                                                                <td style="color:<?php echo (($job['Title'] != 'Job Deleted' && $job['Title'] != '') ? 'green' : 'red'); ?>"><?php echo ($job['Title'] != '' ? $job['Title'] : 'Job Removed From System'); ?></td>
                                                                <td class="text-center">
<!--                                                                    --><?php //echo date_with_time($job['hired_date']); ?>
                                                                    <?php echo reset_datetime(array(
                                                                        'datetime' => $job['hired_date'],
                                                                        // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                        // 'format' => 'h:iA', //
                                                                        'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                        'from_timezone' => $executive_user['timezone'], //
                                                                        '_this' => $this
                                                                    )) ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="2" >No Jobs Found</td>
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
                        <div>
                            <div>
                                <table class="table table-bordered table-stripped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="">Job Title</th>
                                            <th class="col-xs-2">Filled Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="2">
                                                <div class="no-data">
                                                    No jobs found
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                                        
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export To Excel</button>
                    </form>
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
            $('#apply_filters_submit').click();
        }
    });
    $(document).ready(function () {
        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();
    });

    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>