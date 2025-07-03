<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .cookie-category {
        border-top: 1px solid #e0e0e0;
        padding-top: 10px;
        margin-top: 10px;
    }

    .cookie-category label {
        font-weight: 600;
        /* display: flex;*/
        align-items: center;
        vertical-align: top;
        font-size: 12px;
    }
</style>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?php echo isset($flag) && $flag == true ? 'style="display:block;"' : ''; ?>>
                                        <form method="GET"
                                            action="<?php echo base_url('manage_admin/reports/applicant_origination_report'); ?>"
                                            name="search" id="search">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="text-left">Preferences :</label>
                                                        <div class="hr-select-dropdown">
                                                            <?php $preference = $this->uri->segment(4); ?>
                                                            <select class="invoice-fields" name="company_sid"
                                                                id="company_sid">
                                                                <option value="all">Please Select</option>
                                                                <option value="doNotSell" <?php echo $preference == 'doNotSell' ? 'selected' : ''; ?>>Do not sell</option>
                                                                <option value="performance" <?php echo $preference == 'performance' ? 'selected' : ''; ?>>Performance</option>
                                                                <option value="analytics" <?php echo $preference == 'analytics' ? 'selected' : ''; ?>>Analytics</option>
                                                                <option value="marketing" <?php echo $preference == 'marketing' ? 'selected' : ''; ?>>Marketing</option>
                                                                <option value="social" <?php echo $preference == 'social' ? 'selected' : ''; ?>>Social</option>
                                                                <option value="unclassified" <?php echo $preference == 'unclassified' ? 'selected' : ''; ?>>Unclassified</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>website</label>
                                                        <?php $keyword = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                        <input class="invoice-fields" type="text" id="keyword"
                                                            name="keyword"
                                                            value="<?php echo set_value('keyword', $keyword); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Date From </label>
                                                        <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                        <input class="invoice-fields" type="text" readonly
                                                            name="start_date_applied" id="start_date_applied"
                                                            value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Date To </label>
                                                        <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                        <input class="invoice-fields" type="text" readonly
                                                            name="end_date_applied" id="end_date_applied"
                                                            value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>IP</label>
                                                        <?php $indeed_ats_id = $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : ''; ?>
                                                        <input class="invoice-fields" type="text" id="indeed_ats_id"
                                                            name="indeed_ats_id"
                                                            value="<?php echo set_value('indeed_ats_id', $indeed_ats_id); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label>&nbsp;</label>
                                                        <a id="btn_apply_filters" class="btn btn-success btn-block"
                                                            href="#">Apply Filters</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label>&nbsp;</label>
                                                        <a class="btn btn-success btn-block"
                                                            href="<?php echo base_url('manage_admin/reports/cookies_report'); ?>">Reset
                                                            Report</a>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="field-row">
                                                        <label>&nbsp;</label>
                                                        <a id="btn_apply_filters_export" class="btn btn-success btn-block"
                                                            href="#">Export CSV</a>
                                                    </div>
                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                    <!-- *** table *** -->

                                    <div class="hr-box" id="print_div">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Applicants AI Report</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found :
                                                    <?php echo $applicants_count; ?>
                                                </h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to
                                                            <?php echo $to_records; ?> out of
                                                            <?php echo $applicants_count ?>
                                                        </p>
                                                    </span>
                                                    <span class="pull-right"
                                                        style="margin-top: 20px; margin-bottom: 20px;">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-2">IP</th>
                                                                    <th class="col-lg-3">Page</th>
                                                                    <th class="col-lg-3">Preferences</th>
                                                                    <th class="col-lg-2">Agent</th>
                                                                    <th class="col-lg-2">Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($logData)) { ?>
                                                                    <?php foreach ($logData as $row) { ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $row['client_ip']; ?>
                                                                            </td>
                                                                          
                                                                            <td>
                                                                                <?php echo $row['page_url']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                if ($row['preferences'] != '') {

                                                                                    $preferencesArray = json_decode($row['preferences'], true);
                                                                                ?>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['doNotSell'] == 'true' ? " checked " : ""; ?> disabled>
                                                                                        <label>Do not sell or share my personal information </label>
                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" checked disabled>
                                                                                        <label>Essential Cookies</label>
                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['performance'] == 'true' ? " checked " : ""; ?> disabled>
                                                                                        <label>
                                                                                            Performance and Functionality Cookies
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['analytics'] == 'true' ? " checked " : ""; ?> disabled>
                                                                                        <label>Analytics and Customization Cookies</label>

                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['marketing'] == 'true' ? "checked" : ""; ?> disabled>
                                                                                        <label>Advertising Cookies</label>

                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['social'] == 'true' ? " checked " : ""; ?> disabled>
                                                                                        <label>Social networking Cookies</label>
                                                                                    </div>

                                                                                    <div class="cookie-category">
                                                                                        <input type="checkbox" class="cookiecheckbox" <?php echo $preferencesArray['unclassified'] == 'true' ? " checked " : ""; ?> disabled>
                                                                                        <label>Unclassified Cookies</label>
                                                                                    </div>
                                                                                <?php } ?>

                                                                            </td>

                                                                            <td>
                                                                                <?php echo $row['client_agent']; ?>

                                                                            </td>
                                                                            <td>
                                                                                <?= formatDateToDB($row['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                            </td>


                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="6">
                                                                            No data found <?php echo $source; ?>
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
<script language="JavaScript" type="text/javascript"
    src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
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
        var company = $('#company_sid').val();
        var status = $('#status').val();
        var indeed_id = $('#indeed_ats_id').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        company = company != '' && company != null && company != undefined && company != 0 ? encodeURIComponent(company) : 'all';
        status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
        indeed_id = indeed_id != '' && indeed_id != null && indeed_id != undefined && indeed_id != 0 ? encodeURIComponent(indeed_id) : 'all';
        //
        var url = '<?php echo base_url('manage_admin/reports/cookies_report'); ?>' + '/' + company + '/' + keyword + '/' + start_date_applied + '/' + end_date_applied + '/' + indeed_id;
        //
        $('#btn_apply_filters').attr('href', url);
    }



    function generate_search_url_export() {

        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var keyword = $("#keyword").val();
        var company = $('#company_sid').val();
        var status = $('#status').val();
        var indeed_id = $('#indeed_ats_id').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        company = company != '' && company != null && company != undefined && company != 0 ? encodeURIComponent(company) : 'all';
        status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
        indeed_id = indeed_id != '' && indeed_id != null && indeed_id != undefined && indeed_id != 0 ? encodeURIComponent(indeed_id) : 'all';
        //
        var url = '<?php echo base_url('manage_admin/reports/cookies_report_export'); ?>' + '/' + company + '/' + keyword + '/' + start_date_applied + '/' + end_date_applied + '/' + indeed_id;
        //
        $('#btn_apply_filters_export').attr('href', url);
    }


    $(document).ready(function() {
        $('.btn-link').hover(function() {
            $(this).popover('show');
        }, function() {
            $(this).popover('hide');
        });
        $('#btn_apply_filters').click(function(e) {
            var company_sid = $('#company_sid').val();
            //
            if (company_sid == '') {
                alertify.error('Please select the company');
                return false;
            }
            //
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });


        $('#btn_apply_filters_export').click(function(e) {
            e.preventDefault();
            generate_search_url_export();
            window.location = $(this).attr('href').toString();
        });


        $('#status').on('change', function(value) {
            generate_search_url();
        });

        $('#company_sid').on('change', function(value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('#indeed_ats_id').on('keyup', function() {
            generate_search_url();
        });

        $('#indeed_ats_id').trigger('keyup');

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
</script>