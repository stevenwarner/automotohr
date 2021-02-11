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
                <div class="add-new-company">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row field-row-autoheight">
                                <label class="valign-middle" for="employer_sid">Select Employer</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="field-row">
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields" id="employer_sid" name="employer_sid" >
                                        <option value="">No Employer Found</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <div class="field-row">
                                <button onclick="fGetActivityLog();" class="btn btn-success btn-equalizer btn-block">Get Activity Log</button>
                                <input type="hidden" id="selected_employer_sid" name="selected_employer_sid" value="" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row">
                            <div class="bt-panel">
                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#activity_container');">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <form method="post" id="export_to_csv" name="export_to_csv">
                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_csv_file" />
                                    <input type="hidden" id="hidden_company_sid" name="company_sid" value="0" />
                                    <input type="hidden" id="hidden_employer_sid" name="employer_sid" value="0" />
                                </form>

                                <button type="submit" name="export_csv_file" class="btn btn-success" value="export_csv_file" onclick="fExportToCSV();">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <h1 class="hr-registered">Activity Log : <span class="text-success">
                                            <?php echo reset_datetime(array(
                                                'datetime' => date('m/01/Y'),
                                                 'from_format' => 'm/d/Y', // Y-m-d H:i:s
                                                 'format' => 'm/d/Y', //
                                                'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                'from_timezone' => $executive_user['timezone'], //
                                                '_this' => $this
                                            )) ?>

                                            <?php //echo date('m/01/Y'); ?> -
                                            <?php echo reset_datetime(array(
                                                'datetime' => date('m/t/Y'),
                                                'from_format' => 'm/t/Y', // Y-m-d H:i:s
                                                'format' => 'm/t/Y', //
                                                'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                'from_timezone' => $executive_user['timezone'], //
                                                '_this' => $this
                                            )) ?>
                                            <?php //echo date('m/t/Y'); ?></span> </h1>
                                </div>
                                <div id="activity_container" class="table-responsive hr-innerpadding text-center activity_container" style="min-height: 200px;">
                                    <div class="no-data">Please select an employer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row">
                            <div class="bt-panel">
                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#activity_container');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>

                                <button type="submit" name="export_csv_file" class="btn btn-success" value="export_csv_file" onclick="fExportToCSV();">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ****************** -->
            </div>               					
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
<script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>

<script type="text/javascript">
    function fExportToCSV(){
        var company_sid = '<?php echo $company_sid; ?>'; 
        var employer_sid = $('#hidden_employer_sid').val();

        if((company_sid != '' && parseInt(company_sid) != 0 && company_sid != null && company_sid != undefined) &&
            (employer_sid != '' && parseInt(employer_sid) != 0 && employer_sid != null && employer_sid != undefined)){
            $('#export_to_csv').submit();
        } else {
            alertify.error('Please select a Company and Employer!');
        }
    }

    $(document).ready(function () { 
        $('.bt-panel').hide();

        // $('#company_sid').on('change', function () {
            var company_sid = '<?php echo $company_sid; ?>';

            //set values for csv file
            $('#hidden_company_sid').val(company_sid);
            $('#hidden_employer_sid').val(0);

            var data_to_send = {"perform_action": "get_company_users", "company_sid": company_sid};
            var my_request;

            my_request = $.ajax({
                url: "<?php echo base_url('reports/employer_login_duration/ajax_responder'); ?>",
                type: "POST",
                data: data_to_send
            });

            my_request.done(function (response) { 
                $('#employer_sid').html(response);
            });
        // });
        $('#employer_sid').on('change', function () {
            $('#selected_employer_sid').val($(this).val());
            $('#hidden_employer_sid').val($(this).val());
        });
    });

    function fGetActivityLog() { 
        var employer_sid = $('#selected_employer_sid').val();

        if (employer_sid != '' && employer_sid != 0 && employer_sid != undefined && employer_sid != null) {
            var data_to_send = {"perform_action": "get_login_duration_log", "employer_sid": employer_sid};
            var my_request;
            $('#activity_container').html(' <div class="cssload-loader"></div> ');

            my_request = $.ajax({
                url: "<?php echo base_url('reports/employer_login_duration/ajax_responder'); ?>",
                type: "POST",
                data: data_to_send
            });
            
            $('#activity_container').html('<p class="no-data">Please wait ...</p>');
            
            my_request.done(function (response) { 
                $('.bt-panel').show();
                $('#activity_container').html(response);
                $('[data-toggle="tooltip"]').tooltip();
            });
        } else {
            alertify.error('Please Select an Employer!');
        }
    }
    
    function print_page(elem)
    {
        $('.bt-panel').hide();
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
        $('.bt-panel').show();
    }
</script>