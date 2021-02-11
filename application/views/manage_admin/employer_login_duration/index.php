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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-o"></i>Employer Login Duration</h1>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-4 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <label for="country">Company</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="company_sid" name="company_sid" >
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($companies as $company) { ?>
                                                                <option <?php echo set_select('company_sid', $company['sid']); ?> value="<?php echo $company['sid'] ?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-4 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <label for="employer_sid">Employer</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="employer_sid" name="employer_sid" >
                                                            <option value="">Please Select Company</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <label class="transparent-label">Click</label>
                                                    <button onclick="fGetActivityLog();" class="btn btn-success btn-equalizer btn-block js-log-btn">Get Activity Log</button>
                                                    <input type="hidden" id="selected_employer_sid" name="selected_employer_sid" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <!-- -->
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row"><?php // echo $links;         ?>
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
                                                        <h1 class="hr-registered">Activity Log : <span class="text-success"><?php echo date('m/01/Y'); ?> - <?php echo date('m/d/Y'); ?></span> </h1>
                                                    </div>
                                                    <div id="activity_container" class="table-responsive hr-innerpadding text-center activity_container" style="min-height: 200px;">
                                                        <div class="no-data">Please select an employer</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row"><?php // echo $links;         ?>
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#activity_container');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                
                                                    <button type="submit" name="export_csv_file" class="btn btn-success" value="export_csv_file" onclick="fExportToCSV();">
                                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                                                    </button>
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
</div>

<script src="<?=base_url('assets/calendar/moment.min.js');?>"></script>


<script>
    //
    var activities = [];
    function fExportToCSV(){
        var company_sid = $('#hidden_company_sid').val();
        var employer_sid = $('#hidden_employer_sid').val();

        console.log(company_sid);
        console.log(employer_sid);

        if((company_sid != '' && parseInt(company_sid) != 0 && company_sid != null && company_sid != undefined) &&
            (employer_sid != '' && parseInt(employer_sid) != 0 && employer_sid != null && employer_sid != undefined)){
            $('#export_to_csv').submit();
        } else {
            alertify.error('Please select a Company and Employer!');
        }

    }

    $(document).ready(function () {
        $('.bt-panel').hide();

        $('#company_sid').on('change', function () {
            var company_sid = $(this).val();

            //set values for csv file
            $('#hidden_company_sid').val(company_sid);
            $('#hidden_employer_sid').val(0);

            var data_to_send = {"perform_action": "get_company_users", "company_sid": company_sid};

            var my_request;

            my_request = $.ajax({
                url: "<?php echo base_url('manage_admin/employer_login_duration/ajax_responder'); ?>",
                type: "POST",
                data: data_to_send
            });

            my_request.done(function (response) {
                $('#employer_sid').html(response);
            });
        });


        $('#employer_sid').on('change', function () {
            $('#selected_employer_sid').val($(this).val());
            $('#hidden_employer_sid').val($(this).val());
        });


    });


    var xhr = null;


    function fGetActivityLog() {
        var employer_sid = $('#selected_employer_sid').val();

        if (employer_sid != '' && employer_sid != 0 && employer_sid != undefined && employer_sid != null) {

            var data_to_send = {"perform_action": "get_login_duration_log", "employer_sid": employer_sid};

            $('#activity_container').html(' <div class="cssload-loader"></div> ');

            if(xhr !== null) return;

            $('.js-log-btn').addClass('disabled').prop('disabled', true);

            xhr = $.ajax({
                url: "<?php echo base_url('manage_admin/employer_login_duration/ajax_responder'); ?>",
                type: "POST",
                data: data_to_send
            });

            xhr.done(function (resp) {
                xhr = null;
                //
                $('.js-log-btn').removeClass('disabled').prop('disabled', false);
                //
                if(resp.Status === false){
                    $('.cssload-loader').hide(0);
                    $('#activity_container').html('No records found.');
                    return;
                }
                var rows = '';
                $('.bt-panel').show();
                $('#activity_container').html(template());
                activities= resp.Data;
                $.each(resp.Data, function( i0, v0 ){
                    $.each(v0.hours, function( i1, v1 ) {
                        $('tr.js-'+(v0.day)+'').find('td:nth-child('+(parseInt(v1)+2)+')').replaceWith('<td class="text-center"><div class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Active">A</div></td>');
                    });
                });
                $('[data-toggle="tooltip"]').tooltip();
            });
        } else {
            alertify.error('Please Select an Employer!');
        }
    }
    
    
    function print_page(elem)
    {
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


    function template(){
        var rows = '';
        rows +='<table class="table table-bordered table-hover table-striped">';
        rows +='    <thead>';
        rows +='        <tr>';
        rows +='            <th colspan="2" rowspan="2"></th>';
        rows +='            <th colspan="24" class="text-center text-success">Hours</th>';
        rows +='        </tr>';
        rows +='        <tr>';
        rows +='            <th class="text-center">00</th>';
        rows +='            <th class="text-center">01</th>';
        rows +='            <th class="text-center">02</th>';
        rows +='            <th class="text-center">03</th>';
        rows +='            <th class="text-center">04</th>';
        rows +='            <th class="text-center">05</th>';
        rows +='            <th class="text-center">06</th>';
        rows +='            <th class="text-center">07</th>';
        rows +='            <th class="text-center">08</th>';
        rows +='            <th class="text-center">09</th>';
        rows +='            <th class="text-center">10</th>';
        rows +='            <th class="text-center">11</th>';
        rows +='            <th class="text-center">12</th>';
        rows +='            <th class="text-center">13</th>';
        rows +='            <th class="text-center">14</th>';
        rows +='            <th class="text-center">15</th>';
        rows +='            <th class="text-center">16</th>';
        rows +='            <th class="text-center">17</th>';
        rows +='            <th class="text-center">18</th>';
        rows +='            <th class="text-center">19</th>';
        rows +='            <th class="text-center">20</th>';
        rows +='            <th class="text-center">21</th>';
        rows +='            <th class="text-center">22</th>';
        rows +='            <th class="text-center">23</th>';
        rows +='        </tr>';
        rows +='    </thead>';
        rows +='    <tbody>';
        rows +='       <tr>';
        rows +='            <th rowspan="'+(moment().add('1', 'day').date())+'" class="text-center" style="vertical-align:middle;"><span class="duration-days-strip text-success">Days</span></th>';
        rows +='        </tr>';

        var i = 1,
        a = 1;
        j = parseInt(moment().add('1', 'day').date());
        for(i;i<j;i++){
            a = a <= 9 ? '0'+a : a;
            i = i <= 9 ? '0'+i : i;
            rows +='        <tr class="js-'+(i)+'">';
            rows +='            <th class="text-center">'+(a)+'</th>';
            rows +='            <td></td>'.repeat(24);
            rows +='        </tr>';
            a++;
        }

        rows +='    </tbody>';
        rows +='</table>';

        return rows;
    }
</script>

<script>
    $('select#company_sid').select2();
    $('select#employer_sid').select2();
</script>