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
                <!-- ****************** -->
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
                <?php //if (!empty($companies[0]['inactive_employers'])) { 
                ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
                        <div class="bt-panel">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
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
                <?php //} 
                ?>
                <div style="min-height: 400px" id="main_container_for_ajax_response" class="main_container_for_ajax_response">
                    <div class="text-center">
                        <span class="no-data">Please select Date</span>
                    </div>
                </div>
                <?php //if (!empty($companies[0]['inactive_employers'])) { 
                ?>
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
                <?php //} 
                ?>
                <!-- ****************** -->
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
<script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>

<script type="text/javascript">
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
        var company_sid = '<?php echo $company_sid; ?>';
        var report_date = $('#report_date').val();

        if (report_date != '' && report_date != null && report_date != undefined) {
            var request_data = {
                "perform_action": "get_daily_inactivity",
                "report_date": report_date,
                "company_sid": company_sid
            };
            var my_request;
            var my_url = '<?php echo base_url('reports/daily_inactivity_report/ajax_responder'); ?>';
            $('#main_container_for_ajax_response').html('<div class="cssload-loader"></div>');
            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: request_data
            });
            my_request.done(function(response) {
                $('.bt-panel').show();
                $('#main_container_for_ajax_response').html(response);
            });
        } else {
            alertify.error('Please select date');
        }
    }

    function print_page(elem) {
        $('.bt-panel').hide();
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
        $('.bt-panel').show();
    }
</script>