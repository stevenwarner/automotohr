<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
                                                <div class="row">
                                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="startdate">Start Date</label>
                                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="enddate">End Date</label>
                                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate'); ?>">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                                                <div class="report-btns">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <button class="form-btn" onclick="fApplyDateFilters();">Filter</button>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <button class="form-btn" onclick="fClearDateFilters();">Clear</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php if (isset($companies_applicants_by_source) && !empty($companies_applicants_by_source)) { ?>
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
                                            <?php } ?>
                                            <!-- table -->
                                            <div class="row" id="print_div">
                                                <div class="col-xs-12">
                                                    <div class="add-new-company">
                                                        <?php if (!empty($companies_applicants_by_source)) { ?>
                                                            <?php $company_applicants_by_source = $companies_applicants_by_source; ?>

                                                            <?php $company_info = $company_applicants_by_source['company_info']; ?>
                                                            <?php $applicants_by_source = $company_applicants_by_source['applicants_by_source']; ?>
                                                            <div class="hr-box">
                                                                <div class="hr-box-header bg-header-green">
                                                                    <span class="hr-registered pull-left"><?php echo ucwords($company_info['CompanyName']); ?></span>
                                                                </div>
                                                                <div class="hr-box-body hr-innerpadding">
                                                                    <?php if (!empty($applicants_by_source)) { ?>
                                                                        <?php foreach ($applicants_by_source as $key => $source_applicants) { ?>
                                                                            <div class="hr-box-body">
                                                                                <div class="heading-title page-title">
                                                                                    <h2 class="page-title" style="width: 100%;">
                                                                                        <span class="hr-registered"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
                                                                                        <span class="label label-default pull-right" style="font-size: 14px; background-color:#518401; padding: 0.5em 0.8em;">
                                                                                            Total <?php echo count($source_applicants); ?> Applicant(s)
                                                                                        </span>
                                                                                    </h2>
                                                                                </div>


                                                                                <div class="table-responsive hr-innerpadding">
                                                                                    <table class="table table-bordered table-stripped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="col-xs-2">Application Date</th>
                                                                                                <th class="col-xs-3">Applicant Name</th>
                                                                                                <th class="col-xs-4">Job Title</th>
                                                                                                <th class="col-xs-3">Email</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php if (!empty($source_applicants)) { ?>
                                                                                                <?php foreach ($source_applicants as $applicant) { ?>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <?php echo convert_date_to_frontend_format($applicant['date_applied']); ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                                                                        </td>
                                                                                                        <td <?php echo (($applicant['job_title'] == 'Job Not Applied' || $applicant['job_title'] == 'Job Deleted') ? 'style="color:red;"' : 'style="color:green;"'); ?>>
                                                                                                            <?php echo ucwords($applicant['job_title']); ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php echo ucwords($applicant['email']); ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php } ?>
                                                                                            <?php } else { ?>
                                                                                                <tr>
                                                                                                    <td class="text-center" colspan="4">
                                                                                                        <span class="no-data">No Applicants</span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <div class="hr-box">
                                                                            <div class="hr-box-body text-center">
                                                                                <span class="no-data">No Applicants</span>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="hr-box">
                                                                <div class="hr-box-body text-center">
                                                                    <span class="no-data">No Applicants</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- table -->
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

<script>
    jQuery(function() {
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });

    });

    function fApplyDateFilters() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();

        var url = '<?php echo base_url(); ?>' + 'reports/applicant_origination_tracker_report/';

        if (startDate != '' && endDate == '') {
            url += encodeURI(startDate) + '/end-of-days/';
        }

        if (endDate != '' && startDate == '') {
            url += 'beginning-of-time/' + encodeURI(endDate) + '/';
        }

        if ((startDate != '') && (endDate != '')) {
            url += encodeURI(startDate) + '/' + encodeURI(endDate) + '/';
        }


        $('#form-filters').attr('action', url);
        $('#form-filters').submit();

    }

    function fClearDateFilters() {
        $('#startdate').val('');
        $('#enddate').val('');

        var url = '<?php echo base_url(); ?>' + 'reports/applicant_origination_tracker_report/';
        window.location = url;
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