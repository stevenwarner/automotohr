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
                    <form method="GET" action="<?php echo base_url('reports/applicant_offers_report/' . $company_sid); ?>" name="search" id="search">
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
                                <a class="btn btn-success" href="<?php echo base_url('reports/applicant_offers_report/' . $company_sid); ?>">Reset Filters</a>
                            </div>
                        </div>
                    </form>
                    <!-- search form -->
                </div>
                <!-- search form drop down -->
                <?php if (isset($candidates) && !empty($candidates)) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
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
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr>
                </div>
                <div class="page-header-area">
                    <span class="page-heading pull-right">
                        <b><?= 'Total number of candidates:    ' . sizeof($candidates)?></b>
                    </span>
                </div>
                <?php } ?>
                <!-- table -->
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left"><span class="text-success">Applicant Status Report</span></h1>
                    </div>
                    <div id="print_div" class="table-responsive hr-innerpadding">
                        <table class="table table-bordered table-stripped">
                            <thead>
                                <tr>
                                    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Offer Date</th>
                                    <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Job Title</th>
                                    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Applicant Name</th>                                                                
                                    <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Email</th>
                                    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Employee Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($candidates) && !empty($candidates)) { ?>
                                    <?php foreach ($candidates as $candidate) { ?>
                                        <tr>
                                            <td>
<!--                                                --><?php //echo my_date_format($candidate['registration_date']); ?>
                                                <?php echo reset_datetime(array(
                                                    'datetime' => $candidate['registration_date'],
                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                    // 'format' => 'h:iA', //
                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                    'from_timezone' => $executive_user['timezone'], //
                                                    '_this' => $this
                                                )) ?>
                                            </td>
                                            <td style="color:<?php echo ($candidate['job_title'] != 'Job Deleted' ? 'green' : 'red'); ?>"><?php echo ($candidate['job_title'] != 'Job Deleted' ? $candidate['job_title'] : 'Job Removed From System'); ?></td>
                                            <td><?php echo ucwords($candidate['first_name'] . ' ' . $candidate['last_name']); ?></td>
                                            <td><?php echo $candidate['email']; ?></td>
                                            <td><?php echo ucwords($candidate['access_level']); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="5">
                                            <div class="no-data"> No applicants found.</div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- table -->
                <?php if (isset($candidates) && !empty($candidates)) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
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
                    </div>
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
            onSelect: function (value) {
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
            onSelect: function (value) {
                //console.log(value);
                $('#startdate').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());

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
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>