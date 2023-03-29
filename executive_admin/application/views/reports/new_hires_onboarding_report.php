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
                    <form method="GET" action="<?php echo base_url('reports/new_hires_onboarding_report/' . $company_sid); ?>" name="search" id="search">
                        <div class="row">
                            <div class="field-row field-row-autoheight">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="field-row">
                                        <label>Applicant Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                    <div class="field-row">
                                        <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo isset($search['keyword']) ? $search['keyword'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <label class="valign-middle">Date From </label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                            <input type="text" name="start" value="<?php echo isset($search['start']) ? $search['start'] : date('m-d-Y'); ?>" class="invoice-fields datepicker" id="startdate" readonly>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                                            <label class="valign-middle">Date To</label>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                            <input type="text" name="end" value="<?php echo isset($search['end']) ? $search['end'] : date('m-d-Y'); ?>" class="invoice-fields datepicker" id="enddate" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-row field-row-autoheight col-lg-12 text-right">
                                <input type="submit" class="btn btn-success" value="Apply Filters" name="submit" id="apply_filters_submit">
                                <a class="btn btn-success" href="<?php echo base_url('reports/new_hires_onboarding_report/' . $company_sid); ?>">Reset Filters</a>
                            </div>
                        </div>
                    </form>
                    <!-- search form -->
                </div>
                <!-- search form drop down -->
                <?php if (isset($applicants) && !empty($applicants)) { ?>
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
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <hr>
                    </div>
                    <div class="page-header-area">
                        <span class="page-heading pull-right">
                            <b><?= 'Total number of applicants:    ' . sizeof($applicants) ?></b>
                        </span>
                    </div>
                <?php } ?>
                <!-- table -->
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($applicants) && !empty($applicants)) { ?>
                                            <?php foreach ($applicants as $applicant) { ?>
                                                <tr>
                                                    <td style="color:<?php echo ($applicant['Title'] != 'Job Deleted' ? 'green' : 'red'); ?>"><?php echo ($applicant['Title'] != '' ? $applicant['Title'] : 'Job Removed From System'); ?></td>
                                                    <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                    <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                        <td><?php echo date_with_time($applicant['hired_date']); ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="3">
                                                    <div class="no-data">No applicants found.</div>
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
                <?php if (isset($applicants) && !empty($applicants)) { ?>
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
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#apply_filters_submit').click();
        }
    });
    $(document).ready(function() {
        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        $('#startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#enddate').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#enddate').val());

        $('#enddate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                //console.log(value);
                $('#startdate').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());
    });

    function print_page(elem) {
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