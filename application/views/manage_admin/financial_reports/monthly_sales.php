<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $report_name = "months_sales_summary_for_" . $months[$month] . "_" . $year; ?>

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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-dollar"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/financial_reports'); ?>"><i class="fa fa-long-arrow-left"></i> Financial Reports</a>
                                    </div>



                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div class="field-row">
                                                            <label for="year">Year</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(4);?>
                                                                <select id="year" class="invoice-fields">
                                                                    <option value="<?php echo date('Y'); ?>">Please Select Year</option>
                                                                    <?php for($y = 2016; $y <= date('Y'); $y++) { ?>
                                                                        <?php $default_selected = $y == $selected ? true : false; ?>
                                                                        <option <?php echo set_select('year', $y, $default_selected); ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div class="field-row">
                                                            <label for="month">Month</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(5); ?>
                                                                <select id="month" class="invoice-fields">
                                                                    <option value="<?php echo date('m'); ?>">Please Select Month</option>
                                                                    <?php foreach($months as $key => $m) { ?>
                                                                        <?php $default_selected = $key == $selected ? true : false; ?>
                                                                        <?php if($key != 0) { ?>
                                                                            <option <?php echo set_select('month', $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $m?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div class="field-row">
                                                            <label for="month">&nbsp;</label>
                                                            <a href="" class="btn btn-equalizer btn-success btn-block" id="search_btn">Search
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 text-right">
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="print_report">Print</a>
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="download_report">Download</a>
                                        </div>
                                    </div>        

                                    <div class="hr-box" id="download_report">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Months Sales Summary for <?php echo $months[$month]; ?>, <?php echo $year; ?></span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="hidden-xs">
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <canvas id="bar-chart"></canvas>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                    <span class="btn btn-success btn-lg btn-block">
                                                        Total Sales: <span>$<?php echo number_format($total_sale_super_admin + $total_sale_employer_portal, 2); ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-2 text-center">Day Of Month</th>
                                                            <th class="col-xs-4 text-center">Super Admin</th>
                                                            <th class="col-xs-4 text-center">Employer Portal</th>
                                                            <th class="col-xs-4 text-center">Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php for($day = $month_start; $day <= $month_end; $day++) { ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <span><?php echo str_pad($day, 2, 0, STR_PAD_LEFT); ?></span>&nbsp;
                                                                    <span>
                                                                        <?php if($months_sale_super_admin[$day] + $months_sale_employer_portal[$day] > 0) { ?>
                                                                            ( <a href="<?php echo base_url('manage_admin/financial_reports/daily_sales/' . $year . '/' . $month . '/' . $day); ?>" class="">Details</a> )
                                                                        <?php }  else { ?>
                                                                            ( <a href="javascript:void(0);" class="text-muted">Details</a> )
                                                                        <?php } ?>
                                                                    </span>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span >$<?php echo number_format($months_sale_super_admin[$day], 2); ?></span>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span >$<?php echo number_format($months_sale_employer_portal[$day], 2); ?></span>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span >$<?php echo number_format($months_sale_super_admin[$day] + $months_sale_employer_portal[$day],2); ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center"><strong>Sub Total</strong></td>
                                                            <td class="text-right">
                                                                <strong>$<?php echo number_format($total_sale_super_admin, 2); ?></strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>$<?php echo number_format($total_sale_employer_portal, 2);?></strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>$<?php echo number_format($total_sale_employer_portal + $total_sale_employer_portal,2); ?></strong>
                                                            </td>
                                                        </tr>

                                                    </tfoot>
                                                </table>

                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                    <span class="btn btn-success btn-lg btn-block">
                                                        Total Sales: <span>$<?php echo number_format($total_sale_super_admin + $total_sale_employer_portal, 2); ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        $('select').on('change', function(){
            var myYear = $('#year').val();
            var myMonth = $('#month').val();
            var myUrl = '<?php echo base_url("manage_admin/financial_reports/monthly_sales")?>'+ '/' + myYear + '/' + myMonth;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }).trigger('change');
    });


    var ctx = document.getElementById("bar-chart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $days?>,
            datasets: [
                {
                    label: 'Super Admin Sales',
                    data: <?php echo $days_sales_super_admin?>,
                    backgroundColor: 'rgba(81, 132, 1, 0.5)',
                    borderColor:'#518401'
                },
                {
                    label: 'Employer Portal Sales',
                    data: <?php echo $days_sales_employer_portal?>,
                    backgroundColor: 'rgba(0, 153, 255, 0.5)',
                    borderColor:'#09f'
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'US Dollars ($)'
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Day of the Month'
                    }
                }]
            }
        }

    });

    function jsReportAction (source) {
        var action = $(source).data('action');

        if(action == 'download_report') { 
            var draw = kendo.drawing;
            draw.drawDOM($("#download_report"), {
                avoidLinks: false,
                paperSize: "auto",
                multiPage: true,
                margin: { bottom: "2cm" },
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                var pdf;
                pdf = data;

                $('#myiframe').attr("src",data);
                kendo.saveAs({
                    dataURI: pdf,
                    fileName: '<?php echo $report_name.".pdf"; ?>',
                });
                window.close();
            });
        } else { 
            window.print();
            //
            window.onafterprint = function(){
                window.close();
            }
        }
    }
</script>