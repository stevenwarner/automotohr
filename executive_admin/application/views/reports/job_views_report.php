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
                <!-- counts -->
                <div class="job-views-applicants">
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="applicant-count" style="background-color:#162c3a;">
                                <p>Total Jobs</p>
                                <span><?php echo isset($all_jobs) ? count($all_jobs) : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="applicant-count" style="background-color:#980b1e;">
                                <p>Total Job Views</p>
                                <span><?php echo isset($total_views) ? $total_views : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="applicant-count" style="background-color:#4f8d09;">
                                <p>Total Job Applicants</p>
                                <span><?php echo isset($total_applicants) ? $total_applicants : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- counts -->
                <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
                    <div class="bt-panel">
                        <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            Print
                        </a>
                        <form method="post" id="export" name="export">
                            <input type="hidden" name="submit" value="Export" />
                            <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                        </form>
                    </div>
                <?php } ?>
                <!-- table -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="hr-box">
                            <div class="table-responsive hr-innerpadding" id="print_div">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-1">Date</th>
                                            <th class="col-xs-5">Job Title</th>
                                            <th class="col-xs-1">Status</th>
                                            <th class="col-xs-1">Views</th>
                                            <th class="col-xs-1">Applicants</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
                                            <?php foreach ($all_jobs as $job) {
                                                $state = $city = '';
                                                if (isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - ' . ucfirst($job['Location_City']);
                                                if (isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', ' . db_get_state_name($job['Location_State'])['state_name'];
                                            ?>
                                                <tr>
                                                    <td>
                                                        <!--                                                        --><?php //echo convert_date_to_frontend_format($job['activation_date']); 
                                                                                                                        ?>
                                                        <?php echo reset_datetime(array(
                                                            'datetime' => $job['activation_date'],
                                                            // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                            // 'format' => 'h:iA', //
                                                            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                            'from_timezone' => $executive_user['timezone'], //
                                                            '_this' => $this
                                                        )) ?>
                                                    </td>
                                                    <td><?php echo ucwords($job['Title'] . $city . $state); ?></td>
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
                                                <td class="text-center" colspan="5">
                                                    <div class="no-data">No jobs found.</div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <?php if (isset($all_jobs) && !empty($all_jobs)) { ?>
                    <div class="bt-panel">
                        <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            Print
                        </a>
                        <form method="post" id="export" name="export">
                            <input type="hidden" name="submit" value="Export" />
                            <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function print_page(elem) {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo getCompanyNameBySid($company_sid); ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>