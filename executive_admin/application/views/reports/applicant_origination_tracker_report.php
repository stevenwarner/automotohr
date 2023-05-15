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
                <div class="hr-search-criteria <?php echo $flag == true ? 'opened' : ''; ?>">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" <?php echo $flag == true ? 'style="display: block;"' : '' ?>>
                    <!-- search form -->
                    <form method="GET" action="<?php echo base_url('reports/applicant_origination_tracker_report/' . $company_sid); ?>" name="search" id="search">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="field-row">
                                    <label class="text-left">Source</label>
                                    <?php $source = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                    <select name="applicant_status" id="source">
                                        <option <?php echo $source == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                        <option <?php echo $source == 'automotosocial' ? 'selected="selected"' : ''; ?> value="automotosocial">Automoto Social</option>
                                        <option <?php echo $source == 'jobs2careers' ? 'selected="selected"' : ''; ?> value="jobs2careers">Jobs2Careers</option>
                                        <option <?php echo $source == 'jobs2careers' ? 'selected="selected"' : ''; ?> value="ziprecruiter">ZipRecruiter</option>
                                        <option <?php echo $source == 'career_website' ? 'selected="selected"' : ''; ?> value="career_website">Career Website</option>
                                        <option <?php echo $source == 'glassdoor' ? 'selected="selected"' : ''; ?> value="glassdoor">Glassdoor</option>
                                        <option <?php echo $source == 'indeed' ? 'selected="selected"' : ''; ?> value="indeed">Indeed</option>
                                        <option <?php echo $source == 'juju' ? 'selected="selected"' : ''; ?> value="juju">Juju</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="field-row">
                                    <label>Date From </label>
                                    <?php $start_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y'); ?>
                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="field-row">
                                    <label>Date To </label>
                                    <?php $end_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-xs-8 col-sm-12">
                                <div class="field-row">
                                    <label class="text-left">Applicant Name</label>
                                    <?php $keyword = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                    <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-12">
                                <div class="field-row">
                                    <label class="text-left">&nbsp;</label>
                                    <a id="btn_apply_filters" class="btn btn-success btn-block" href="#">Apply Filters</a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-12">
                                <div class="field-row">
                                    <label class="text-left">&nbsp;</label>
                                    <a class="btn btn-success btn-block" href="<?php echo base_url('reports/applicant_origination_tracker_report/' . $company_sid); ?>">Reset Filters</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- search form -->
                </div>
                <!-- search form drop down -->
                <?php if (isset($companies_applicants_by_source) && !empty($companies_applicants_by_source)) { ?>
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
                <!-- table -->
                <div class="row" id="print_div">
                    <div class="col-xs-12">
                        <div class="add-new-company">

                            <div class="hr-box" id="print_div">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Applicant Origination Report</h1>
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
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover table-condensed" id="example">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-2">Date Applied</th>
                                                            <th class="col-xs-2">Name</th>
                                                            <th class="col-xs-3">Job Title</th>
                                                            <th class="col-xs-2">IP Address</th>
                                                            <th class="col-xs-3">Applicant Source</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($applicants)) { ?>
                                                            <?php foreach ($applicants as $applicant) { ?>
                                                                <tr>
                                                                    <td style="white-space: nowrap; vertical-align: top;">
                                                                        <!--                                                                    --><?php //echo DateTime::createFromFormat('Y-m-d H:i:s', $applicant['date_applied'])->format('M d Y, D h:i:s'); 
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
                                                                    <td style="white-space: nowrap; vertical-align: top;"><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                    <td style="white-space: nowrap; vertical-align: top;"><?php echo ucwords($applicant['Title']); ?></td>
                                                                    <td style="white-space: nowrap; vertical-align: top;"><?php echo $applicant['ip_address']; ?></td>
                                                                    <td style="vertical-align: top;">
                                                                        <div class="table-responsive applicant_source_link_in_table">
                                                                            <?php echo $applicant['applicant_source']; ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="6">
                                                                    <span class="no-data">No applicants Found</span>
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
                                            <span class="pull-right">
                                                <?php echo $page_links ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <?php if (isset($companies_applicants_by_source) && !empty($companies_applicants_by_source)) { ?>
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
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {

        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var source = $('#source').val();
        var keyword = $('#keyword').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        source = source != '' && source != null && source != undefined && source != 0 ? encodeURIComponent(source) : 'all';

        var url = '<?php echo base_url('reports/applicant_origination_tracker_report/' . $company_sid); ?>' + '/' + source + '/' + start_date_applied + '/' + end_date_applied + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }
    $(document).ready(function() {

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        //        $('#btn_apply_filters').click(function(){
        //            var select = $('#source').val();
        //            if(select==''){
        ////                alertify.error('Please select the source');
        //                alert('Please Select the Source');
        //                return false;
        //            }
        //        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('#source').selectize({
            onChange: function(value) {
                generate_search_url();
            }
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
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>