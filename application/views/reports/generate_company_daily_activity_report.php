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
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div id="col_chart" class=""></div></div>
                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-12">
                                            <label for="report_date">Date</label>
                                            <div class="field-row field-row-autoheight">
                                                <input type="text" name="report_date"
                                                       value="<?php echo set_value('report_date', date('m/d/Y')); ?>"
                                                       class="invoice-fields" id="report_date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                            <label class="transparent-label">&nbsp;</label>
                                            <div class="field-row">
                                                <button class="btn btn-success" id="get-report" onclick="get_activity_report();">Get Report</button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div style="min-height: 400px" id="main_container_for_ajax_response" class="main_container_for_ajax_response">
                                        <div class="text-center">
                                            <span class="no-data">Please select Date</span>
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
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    function fExportCSV() {
        var report_date = $('#hidden_report_date').val();
        if (
            (parseInt(report_date) != 0 && report_date != '' && report_date != null && report_date != undefined)
        ) {
            $('#form_export_csv').submit();
        } else {
            alertify.error('Please Select a Company and Report Date');
        }
    }

    $(document).ready(function () {
        $('#report_date').datepicker({
            format: 'mm/dd/yyyy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function () {
                var report_date = $('#report_date').val();

                $('#hidden_report_date').val(report_date);
            }
        });

        $('#get-report').click();

    });


    function get_activity_report() {
        var report_date = $('#report_date').val();

        if (
            (report_date != '' && report_date != null && report_date != undefined)
        ) {
            var request_data = { "perform_action" : "get_employers_daily_activity","report_date" : report_date}



            var my_request;
            var my_url = '<?php echo base_url('reports/activity_ajax_responder'); ?>';

            $('#main_container_for_ajax_response').html('<div class="cssload-loader"></div>');

            my_request = $.ajax({
                url : my_url,
                type: 'POST',
                data: request_data
            });

            my_request.done(function (response) {
                //console.log(response);

                $('#main_container_for_ajax_response').html(response);
            });


        } else {
            alertify.error('Please select date');
        }

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
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); 
        mywindow.focus(); 
    }
</script>

