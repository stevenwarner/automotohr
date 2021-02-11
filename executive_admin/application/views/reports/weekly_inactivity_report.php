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
                            <label class="valign-middle" for="week_span">Week</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row field-row-autoheight">
                            <input type="hidden" id="start_date" name="start_date" value="" />
                            <input type="hidden" id="end_date" name="end_date" value="" />
                            <input id="week_span" class="week-picker invoice-fields" name="week_span" placeholder="Please Select Date" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <div class="field-row field-row-autoheight">
                            <button class="btn btn-success btn-equalizer btn-block" id="btn_apply_filters"
                                    onclick="get_inactivity_report();">Get Report
                            </button>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
                        <div class="bt-panel">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                            <form method="post" id="export" name="export">
                                <input type="hidden" id="export" name="export" value="export_data" />
                                <input type="hidden" id="excel_week_span" name="excel_week_span" value="" />
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
                                <input type="hidden" id="excel_week_span" name="excel_week_span" value="" />
                                <button class="btn btn-success" onclick="fExportCSV();" type="submit">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    Export
                                </button>
                            </form>
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
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function fExportCSV(){
        var week = $('#week_span').val(); 
        if(week != '' && parseInt(week) != 0 && week != null && week != undefined){
            $('#excel_week_span').val(week);
            $('#export').submit();
        } else {
            alertify.error('Please select week');
        }
    }
    
    $(document).ready(function () {        
        $('.bt-panel').hide();
        var startDate;
        var endDate;
        var selectCurrentWeek = function () {
            window.setTimeout(function () {
                $('.ui-weekpicker').find('.ui-datepicker-current-day a').addClass('ui-state-active').removeClass('ui-state-default');
                $('.ui-weekpicker tr').on('mousemove', function () {
                    $(this).find('td a').each(function () {
                        $(this).addClass('ui-state-hover');
                    });
                });
                $('.ui-weekpicker tr').on('mouseleave', function () {
                    $(this).find('td a').each(function () {
                        $(this).removeClass('ui-state-hover');
                    });
                });
            }, 1);
        };
        var setDates = function (input) {
            var $input = $(input);
            var date = $input.datepicker('getDate');
            if (date !== null) {
                var firstDay = $input.datepicker("option", "firstDay");
                var dayAdjustment = date.getDay() - firstDay;
                if (dayAdjustment < 0) {
                    dayAdjustment += 7;
                }
                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment);
                endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment + 6);
                var inst = $input.data('datepicker');
                var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                var start_date = $.datepicker.formatDate(dateFormat, startDate, inst.settings);
                $('#start_date').val(start_date);
                var end_date = $.datepicker.formatDate(dateFormat, endDate, inst.settings);
                $('#end_date').val(end_date);
                $input.val(start_date + ' - ' + end_date);
            }
        };
        $('.week-picker').datepicker({
            firstDay: 1,
            format:'yyyy/mm/dd',
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('ui-weekpicker');
                selectCurrentWeek();
            },
            onClose: function () {
                $('#ui-datepicker-div').removeClass('ui-weekpicker');
            },
            showOtherMonths: true,
            selectOtherMonths: true,
            onSelect: function (dateText, inst) {
                setDates(this);
                selectCurrentWeek();
                $(this).change();
            },
            beforeShowDay: function (date) {
                var cssClass = '';
                if (date >= startDate && date <= endDate)
                    cssClass = 'ui-datepicker-current-day';
                return [true, cssClass];
            },
            onChangeMonthYear: function (year, month, inst) {
                selectCurrentWeek();
            }
        });
        setDates('.week-picker');
    });
    
    function get_inactivity_report() {
        var company_sid = '<?php echo $company_sid; ?>';
        var week_span = $('#week_span').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        
        if (week_span != '' && week_span != null && week_span != undefined) {
            var request_data = {
                "perform_action": "get_weekly_inactivity",
                "start_date": start_date,
                "end_date": end_date,
                "week_span": week_span,
                "company_sid" : company_sid
            };
            var my_request;
            var my_url = '<?php echo base_url('reports/weekly_inactivity_report/ajax_responder'); ?>';
            $('#main_container_for_ajax_response').html('<div class="cssload-loader"></div>');
            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: request_data
            });
            my_request.done(function (response) {
                //console.log(response);
                $('.bt-panel').show();
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