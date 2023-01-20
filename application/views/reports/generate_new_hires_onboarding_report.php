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
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="startdate">Applicant Name</label>
                                                                <?php $keyword = $keyword!='all' ? $keyword : '';?>
                                                                <input type="text" id="keyword" class="invoice-fields" name="keyword" value="<?php echo set_value('keyword',$keyword); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="startdate">Start Date</label>
                                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate',$startdate); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="enddate">End Date</label>
                                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate',$enddate); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="report-btns">
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                        <button class="form-btn" id="btn_apply_filters" onclick="fApplyDateFilters();">Filter</button>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<!--                                                                        <button class="form-btn" onclick="fClearDateFilters();">Clear</button>-->
                                                                        <a class="form-btn" id="clear">Clear</a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
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

                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <hr>
                                                </div>
                                                <div class="page-header-area">
                                                    <span class="page-heading pull-right">
                                                        <b><?= 'Total number of applicants:    ' . sizeof($applicants)?></b>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                            <div class="table-responsive table-outer" id="print_div">
                                                <div class="border-none mylistings-wrp">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-6 col-sm-6 col-md-6 col-lg-6">Job Title</th>
                                                                <th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Applicant Name</th>
                                                                <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                                    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Hired On</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($applicants)) { ?>
                                                                <?php foreach ($applicants as $applicant) { ?>
                                                                    <?php
                                                                        $state = $city = '';
                                                                        if(isset($applicant['Location_City']) && $applicant['Location_City'] != null && $applicant['Location_City'] != '') $city = ' - '.ucfirst($applicant['Location_City']);
                                                                        if(isset($applicant['Location_State']) && $applicant['Location_State'] != null && $applicant['Location_State'] != '') $state = ', '.db_get_state_name($applicant['Location_State'])['state_name'];
                                                                    ?>
                                                                    <tr>
                                                                        <td style="color:<?php echo ($applicant['Title'] != '' ? 'green' : 'red'); ?>">
                                                                        <?php
                                                                         echo ($applicant['Title'] != '' ? $applicant['Title'].$city.$state : 'Job Removed From System');
                                                                        ?></td>
                                                                        <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                        <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
                                                                            <td><?=reset_datetime(array('datetime' => $applicant['hired_date'], '_this' => $this)); ?></td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr><td colspan="3">No Applicants Found</td></tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
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
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script>
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    jQuery(function () {
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

        var url = '<?php echo base_url();?>' + 'reports/generate_new_hires_onboarding_report/';
        $('#clear').attr('href',url);

    });

    function fApplyDateFilters(){
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();

        var url = '<?php echo base_url();?>' + 'reports/generate_new_hires_onboarding_report/';


        if(startDate != '' && endDate == ''){
            url += encodeURI(startDate) + '/end-of-days/';
        }

        if(endDate != '' && startDate == ''){
            url += 'beginning-of-time/' + encodeURI(endDate) + '/';
        }

        if((startDate != '') && (endDate != '')){
            url += encodeURI(startDate) + '/' + encodeURI(endDate) + '/';
        }

        if(keyword != ''){
            url += encodeURI(keyword);
        }else{
            url += encodeURI('all');
        }

        $('#form-filters').attr('action', url);

        $('#form-filters').submit();

    }

//    function fClearDateFilters(){
//        $('#startdate').val('');
//        $('#enddate').val('');
//
//        var url = '<?php //echo base_url();?>//' + 'reports/generate_new_hires_onboarding_report/';
//        window.location = url;
//    }

    function print_page(elem)
    {
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
