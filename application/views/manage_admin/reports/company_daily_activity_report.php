<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <div class="col-lg-5 col-md-4 col-xs-12 col-sm-12">
                                            <div class="field-row field-row-autoheight">
                                                <label for="company_sid">Company</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="company_sid"
                                                            name="company_sid">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($companies as $company) { ?>
                                                            <option <?php echo set_select('company_sid', $company['sid']); ?>
                                                                value="<?php echo $company['sid'] ?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-4 col-xs-12 col-sm-12">
                                            <label for="report_date">Date</label>
                                            <div class="field-row field-row-autoheight">
                                                <input type="text" name="report_date"
                                                       value="<?php echo set_value('report_date', date('m/d/Y')); ?>"
                                                       class="invoice-fields" id="report_date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-xs-12 col-sm-12">
                                            <label class="transparent-label">Click</label>
                                            <div class="field-row field-row-autoheight">
                                                <button class="btn btn-success btn-equalizer btn-block"
                                                        onclick="get_activity_report();">Get Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- -->

                                    <div style="min-height: 400px" id="main_container_for_ajax_response" class="main_container_for_ajax_response">
                                        <div class="text-center">
                                            <span class="no-data">Please select Date</span>
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
    function fExportCSV(){
        var company_sid = $('#hidden_company_sid').val();
        var report_date = $('#hidden_report_date').val();

        if (
            (parseInt(company_sid) != 0 && company_sid != '' && company_sid != null && company_sid != undefined) &&
            (parseInt(report_date) != 0 && report_date != '' && report_date != null && report_date != undefined)
        ){
            $('#form_export_csv').submit();
        } else {
            alertify.error('Please Select a Company and Report Date');
        }
    }




    $(document).ready(function () {
        $('#report_date').datepicker({
            format: 'mm/dd/yyyy',
            onSelect: function () {
                var company_sid = $('#company_sid').val();
                var report_date = $('#report_date').val();

                $('#hidden_company_sid').val(company_sid);
                $('#hidden_report_date').val(report_date);
            }
        });

        $('#company_sid').on('change', function () {
            var company_sid = $('#company_sid').val();
            var report_date = $('#report_date').val();

            $('#hidden_company_sid').val(company_sid);
            $('#hidden_report_date').val(report_date);
        });


    });


    function get_activity_report() {
        var company_sid = $('#company_sid').val();
        var report_date = $('#report_date').val();

        if (
            (company_sid != '' && company_sid != null && company_sid != undefined) &&
            (report_date != '' && report_date != null && report_date != undefined)
        ) {
            var request_data = { "perform_action" : "get_employers_daily_activity", "company_sid" : company_sid, "report_date" : report_date}



            var my_request;
            var my_url = '<?php echo base_url('manage_admin/reports/company_daily_activity_report/ajax_responder'); ?>';

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
            alertify.error('Please select company and date');
        }

    }
    
    
    function print_page(elem)
    {
        $('.bt-panel').hide();
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
        
        $('.bt-panel').show();
    }
</script>