<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                    <div class="heading-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-users"></i>
                                            <?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row field-row-autoheight">
                                                <label class="valign-middle" for="report_date">Date</label>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row field-row-autoheight">
                                                <input type="text" name="report_date" value="<?php echo set_value('report_date', date('m/d/Y')); ?>" class="invoice-fields" id="report_date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row field-row-autoheight">
                                                <button class="btn btn-success btn-equalizer btn-block" onclick="get_inactivity_report();">Get Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <!-- -->
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row">
                                            <div class="bt-panel">
                                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                <form method="post" id="export" name="export">
                                                    <input type="hidden" id="export" name="export" value="export_data" />
                                                    <input type="hidden" id="excel_date" name="excel_date" value="" />
                                                    <button class="btn btn-success" onclick="fExportCSV();" type="submit">
                                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                        Export
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="min-height: 400px" id="main_container_for_ajax_response" class="main_container_for_ajax_response">
                                        <div class="text-center">
                                            <span class="no-data">Please select Date</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row">
                                            <div class="bt-panel">
                                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                <form method="post" id="export" name="export">
                                                    <input type="hidden" id="export" name="export" value="export_data" />
                                                    <input type="hidden" id="excel_date" name="excel_date" value="" />
                                                    <button class="btn btn-success" onclick="fExportCSV();" type="submit">
                                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                        Export
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fExportCSV() {
        var date = $('#report_date').val();

        if (date != '' && parseInt(date) != 0 && date != null && date != undefined) {
            $('#excel_date').val(date);
            $('#export').submit();
        } else {
            alertify.error('Please select date');
        }
    }


    $(document).ready(function() {
        $('.bt-panel').hide();

        $('#report_date').datepicker({
            format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });


    function get_inactivity_report() {
        var report_date = $('#report_date').val();

        if (report_date != '' && report_date != null && report_date != undefined) {
            var request_data = {
                // "perform_action": "get_daily_activity_overview",
                "perform_action": "get_all_active_companies",
                "report_date": report_date
            };

            var my_request;
            var my_url = '<?php echo base_url('manage_admin/reports/daily_activity_overview_report/ajax_responder'); ?>';

            $('#main_container_for_ajax_response').html('<div class="cssload-loader"></div>');

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: request_data
            });

            my_request.done(function(response) {
                //console.log(response);
                $('.bt-panel').show();
                $('#main_container_for_ajax_response').html(response);
            });


        } else {
            alertify.error('Please select company and date');
        }

    }


    function print_page(elem) {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
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