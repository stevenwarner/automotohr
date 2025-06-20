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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                            <div class="hr-search-main search-collapse-area" <?php echo isset($flag) && $flag == true ? 'style="display:block;"' : ''; ?>>
                                                <form method="GET"
                                                    action="<?php echo base_url('manage_admin/reports/applicant_origination_report'); ?>"
                                                    name="search" id="search">
                                                    <div class="row">

                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label>Applicant Name</label>
                                                                <?php $keyword = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                                <input class="invoice-fields" type="text" id="keyword"
                                                                    name="keyword"
                                                                    value="<?php echo set_value('keyword', $keyword); ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label class="text-left">Status :</label>
                                                                <?php $status = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="status" id="status">
                                                                        <option value="all">Please Select</option>
                                                                        <option value="queued" <?php if ($status == 'queued') { ?>
                                                                            selected="selected" <?php } ?>>Queued</option>
                                                                        <option value="in_progress" <?php if ($status == 'in_progress') { ?> selected="selected"
                                                                            <?php } ?>>In Progress</option>
                                                                        <option value="completed" <?php if ($status == 'completed') { ?> selected="selected"
                                                                            <?php } ?>>Completed</option>
                                                                        <option value="failed" <?php if ($status == 'failed') { ?>
                                                                            selected="selected" <?php } ?>>Failed</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label>Date From </label>
                                                                <?php $start_date = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                                <input class="invoice-fields"
                                                                    type="text" readonly
                                                                    name="start_date_applied" id="start_date_applied"
                                                                    value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label>Date To </label>
                                                                <?php $end_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                                <input class="invoice-fields"
                                                                    type="text" readonly
                                                                    name="end_date_applied" id="end_date_applied"
                                                                    value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
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
                                                                    href="<?php echo base_url('reports/applicantsAiScoreReport'); ?>">Reset
                                                                    Report</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- *** table *** -->

                                            <div class="hr-box" id="print_div">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="pull-left">
                                                        <h1 class="hr-registered">Applicants AI Score Report</h1>
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
                                                            <table
                                                                class="table table-bordered table-striped table-hover table-condensed"
                                                                id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th>Applicant<br />Name</th>
                                                                        <th>Job Title</th>
                                                                        <th>Status</th>
                                                                        <th> interview <br> Pre Score</th>
                                                                        <th>Failed<br />Reason</th>
                                                                        <th>Date<br />Applied</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($applicants)) { ?>
                                                                        <?php foreach ($applicants as $applicant) {
                                                                        ?>
                                                                            <tr>
                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo $applicant['sid']; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                                                </td>

                                                                                <td style="vertical-align: middle;">
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
                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo ucfirst($applicant['status']); ?>
                                                                                </td>


                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo ucfirst($applicant['match_score']); ?>
                                                                                </td>



                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo !empty($applicant['failed_message']) ? $applicant['failed_message'] : 'N/A'; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle;">
                                                                                    <?php echo date_with_time($applicant['created_at']); ?>
                                                                                </td>

                                                                                <td style="vertical-align: middle;">
                                                                                    <div class="">
                                                                                        <a href="<?= base_url("/applicant_profile/" . $applicant["applicant_sid"] . "/" . $applicant["portal_job_applications_sid"] . "/apd"); ?>"
                                                                                            target="_blank" class="btn btn-success">
                                                                                            View Score
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td class="text-center" colspan="7">
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
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        //
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
        var url = '<?php echo base_url('reports/applicantsAiScoreReport'); ?>' + '/' + keyword + '/' + start_date_applied + '/' + end_date_applied + '/' + status;
        //
        $('#btn_apply_filters').attr('href', url);


    }

    $(document).ready(function() {

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        //
        $('#managers').val('<?php echo $this->uri->segment(11); ?>');

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('#job_sid').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_type').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_status').on('change', function(value) {
            generate_search_url();
        });

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

    });

    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

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
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }
</script>