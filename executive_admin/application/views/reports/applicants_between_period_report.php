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
                <div class="hr-search-criteria opened">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" style="display: block;">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="field-row">
                                <?php $keyword = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                <label>Keyword</label>
                                <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="field-row">
                                <label class="">Date From </label>
                                <?php $start_date = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : date('m-d-Y'); ?>
                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="field-row">
                                <label class="">Date To</label>
                                <?php $end_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y'); ?>
                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-2">
                            <a id="btn_apply_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/applicants_between_period_report/' . $company_sid); ?>">Apply Filters</a>
                        </div>
                        <div class="col-xs-2">
                            <a id="btn_clear_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/applicants_between_period_report/' . $company_sid); ?>">Clear Filters</a>
                        </div>
                    </div>
                </div>
                <!-- search form drop down -->
                <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
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
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <span class="pull-left">
                            <h1 class="hr-registered"><?php echo $title; ?></h1>
                        </span>
                        <span class="pull-right">
                            <h1 class="hr-registered">Total Records Found : <?php echo $applicants_count; ?></h1>
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
                                <div id="print_div" class="table-responsive">
                                    <table class="table table-stripped table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Applicant</th>
                                                <th class="col-xs-5 col-sm-5 col-md-5 col-lg-5">Job Title</th>
                                                <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">Hired On</th>
                                                <?php } else { ?>
                                                    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">Application Date</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                                <?php foreach ($applicants as $applicant) { ?>
                                                    <tr>
                                                        <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                        <td style="color:<?php echo (($applicant['Title'] != 'Job Deleted' && $applicant['Title'] != 'Job Not Applied') ? 'green' : 'red'); ?>"><?php echo ($applicant['Title'] != '' ? $applicant['Title'] : 'Job Removed From System'); ?></td>
                                                        <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                            <td>
                                                                <!--                                                            --><?php //echo date_with_time($applicant['hired_date']); 
                                                                                                                                    ?>
                                                                <?php echo reset_datetime(array(
                                                                    'datetime' => $applicant['hired_date'],
                                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                    // 'format' => 'h:iA', //
                                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                    'from_timezone' => $executive_user['timezone'], //
                                                                    '_this' => $this
                                                                )) ?>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td class="text-center">
                                                                <!--                                                            --><?php //echo date_with_time($applicant['date_applied']); 
                                                                                                                                    ?>
                                                                <?php echo reset_datetime(array(
                                                                    'datetime' => $applicant['date_applied'],
                                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                    // 'format' => 'h:iA', //
                                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                    'from_timezone' => $executive_user['timezone'], //
                                                                    '_this' => $this
                                                                )) ?>
                                                            </td>
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
                        <hr />
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
                    </div>
                </div>
                <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
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
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {

        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var keyword = $('#keyword').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';

        var url = '<?php echo base_url('reports/applicants_between_period_report/' . $company_sid); ?>' + '/' + start_date_applied + '/' + end_date_applied + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }


    $(document).ready(function() {

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });

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
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
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