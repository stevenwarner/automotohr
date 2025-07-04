<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$referrerChartArray = array();
$referrerChartArray[] = array('Referral', 'Count');
?>
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
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?php echo isset($flag) && $flag == true ? 'style="display:block;"' : ''; ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/applicant_origination_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="text-left">Company :</label>
                                                        <div class="hr-select-dropdown">
                                                            <?php $company = $this->uri->segment(4); ?>
                                                            <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                <option value="all">Please Select</option>
                                                                <?php if (!empty($companies)) { ?>
                                                                    <?php foreach ($companies as $active_company) { ?>
                                                                        <option <?php if ($company == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                            <?php echo ucwords($active_company['CompanyName']); ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="text-left">Source :</label>
                                                        <?php $source = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="source" id="source">
                                                                <option value="all">Please Select</option>
                                                                <option value="automotosocial" <?php if ($source == 'automotosocial') { ?> selected="selected" <?php } ?>>Automoto Social</option>
                                                                <option value="jobs2careers" <?php if ($source == 'jobs2careers') { ?> selected="selected" <?php } ?>>Jobs2Careers</option>
                                                                <option value="ziprecruiter" <?php if ($source == 'ziprecruiter') { ?> selected="selected" <?php } ?>>ZipRecruiter</option>
                                                                <option value="glassdoor" <?php if ($source == 'glassdoor') { ?> selected="selected" <?php } ?>>Glassdoor</option>
                                                                <option value="indeed" <?php if ($source == 'indeed') { ?> selected="selected" <?php } ?>>Indeed</option>
                                                                <option value="juju" <?php if ($source == 'juju') { ?> selected="selected" <?php } ?>>Juju</option>
                                                                <option value="careerwebsite" <?php if ($source == 'careerwebsite') { ?> selected="selected" <?php } ?>>Career Website</option>
                                                                <option value="careerbuilder" <?php if ($source == 'careerbuilder') { ?> selected="selected" <?php } ?>>Career Builder</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Date From </label>
                                                        <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Date To </label>
                                                        <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Applicant Name</label>
                                                        <?php $keyword = $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : ''; ?>
                                                        <input class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label>&nbsp;</label>
                                                        <a id="btn_apply_filters" class="btn btn-success btn-block" href="#">Apply Filters</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label>&nbsp;</label>
                                                        <a class="btn btn-success btn-block" href="<?php echo base_url('manage_admin/reports/applicant_origination_report'); ?>">Reset Report</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- *** table *** -->

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
                                                    <table class="table table-bordered table-striped table-hover table-condensed" id="example">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-2">Date Applied</th>
                                                                <th class="col-xs-2">Name</th>
                                                                <?php if ($company == 'all') { ?>
                                                                    <th>Company</th>
                                                                <?php } ?>
                                                                <th class="col-xs-3">Job Title</th>
                                                                <th class="col-xs-2">IP Address</th>
                                                                <th class="col-xs-2">Applicant Source</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($applicants)) { ?>
                                                                <?php foreach ($applicants as $applicant) { ?>
                                                                    <tr>
                                                                        <td><?php echo date_with_time($applicant['date_applied']); ?></td>
                                                                        <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                        <?php if ($company == 'all') { ?>
                                                                            <td><?php echo ucwords($applicant['CompanyName']); ?></td>
                                                                        <?php } ?>
                                                                        <td>
                                                                            <?php
                                                                            $city = '';
                                                                            $state = '';
                                                                            if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                                $city = ' - ' . ucfirst($applicant['Location_City']);
                                                                            }
                                                                            if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                                $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                                            }
                                                                            echo $applicant['Title'] . $city . $state;
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo $applicant['ip_address']; ?></td>
                                                                        <td>
                                                                            <div class="table-responsive applicant_source_link_in_table">
                                                                                <?php
                                                                                $a = domainParser($applicant['applicant_source'], $applicant['main_referral'], true);
                                                                                if (is_array($a)) {
                                                                                    //                                                                                    $a['sid'] = $applicant['sid'];
                                                                                    // Set chart array
                                                                                    if (!isset($referrerChartArray[$a['ReferrerSource']])) {
                                                                                        $referrerChartArray[$a['ReferrerSource']] = array($a['ReferrerSource'], 1);
                                                                                    } else {
                                                                                        $referrerChartArray[$a['ReferrerSource']][1] = $referrerChartArray[$a['ReferrerSource']][1] + 1;
                                                                                    }
                                                                                    echo $a['Text'] . '<a class="btn btn-link" href="javascript:;" data-html="true" data-toggle="popover" data-placement="left" data-content="Source: ' . $a['ReferrerSource'] . '<br /> Source URL: ' . $a['Original']['Source'] . (!empty($a['Original']['Referrer']) ? (' <br />Referrer: ' . $a['Original']['Referrer']) : '') . '">View More</a>';
                                                                                } else {
                                                                                    if ($a == 'N/A') {
                                                                                        $referrerChartArray['Direct'] = array('Direct', ++$count);
                                                                                        echo '<b>Direct</b>';
                                                                                    } else {
                                                                                        echo $a;
                                                                                    }
                                                                                } ?>
                                                                                <!--                                                                                --><?php //echo $applicant['applicant_source']; 
                                                                                                                                                                        ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="6">
                                                                        No applicants found <?php echo $source; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
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
                                    <!-- *** table *** -->
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
<script language="JavaScript" type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {

        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var keyword = $("#keyword").val();
        var source = $('#source').val();
        var company = $('#company_sid').val();
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        source = source != '' && source != null && source != undefined && source != 0 ? encodeURIComponent(source) : 'all';
        company = company != '' && company != null && company != undefined && company != 0 ? encodeURIComponent(company) : 'all';

        var url = '<?php echo base_url('manage_admin/reports/applicant_origination_report'); ?>' + '/' + company + '/' + source + '/' + start_date_applied + '/' + end_date_applied + '/' + keyword;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {
        $('.btn-link').hover(function() {
            $(this).popover('show');
        }, function() {
            $(this).popover('hide');
        });
        $('#btn_apply_filters').click(function(e) {
            var select = $('#source').val();
            var company_sid = $('#company_sid').val();
            if (company_sid == '') {
                alertify.error('Please select the company');
                return false;
            }
            if (select == '') {
                alertify.error('Please select the source');
                return false;
            }
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });

        $('#source').on('change', function(value) {
            generate_search_url();
        });
        $('#company_sid').on('change', function(value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

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

    //    $('#apply_filters_submit').click(function () {
    //        $("#search").validate({
    //            ignore: [],
    //            rules: {
    //                company_sid: {
    //                    required: true,
    //                },
    //                source: {
    //                    required: true,
    //                }
    //            },
    //            messages: {
    //                company_sid: {
    //                    required: 'Company name is required'
    //                },
    //                source: {
    //                    required: 'Source is required'
    //                }
    //            }
    //        });
    //    });
</script>