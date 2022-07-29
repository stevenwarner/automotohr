<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $report_name = "yearly_sales_comparison_from_" . $from_year . "_to_" . $to_year; ?>
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
                                                            <label for="from_year">From Year</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(4);?>
                                                                <select id="from_year" name="from_year" class="invoice-fields">
                                                                    <option value="<?php echo date('Y'); ?>">Please Select Year</option>
                                                                    <?php for($y = 2016; $y <= date('Y'); $y++) { ?>
                                                                        <?php $default_selected = $y == $selected ? true : false; ?>
                                                                        <option <?php echo set_select('from_year', $y, $default_selected); ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div class="field-row">
                                                            <label for="to_year">To Year</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(5);?>
                                                                <select id="to_year" name="to_year" class="invoice-fields">
                                                                    <option value="<?php echo date('Y'); ?>">Please Select Year</option>
                                                                    <?php for($y = 2016; $y <= date('Y'); $y++) { ?>
                                                                        <?php $default_selected = $y == $selected ? true : false; ?>
                                                                        <option <?php echo set_select('to_year', $y, $default_selected); ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div class="field-row">
                                                            <label for="month">&nbsp;</label>
                                                            <a href="" class="btn btn-equalizer btn-success btn-block" id="search_btn">Search</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 text-right">
                                            <a target="_blank" class="btn btn-success" href="<?php echo base_url('manage_admin/financial_reports/print_yearly_sales_comparison')."/". $from_year . "/" . $to_year; ?>">Print</a>
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="download_report">Download</a>
                                        </div>
                                    </div>

                                    <div class="hr-box" id="download_report">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Yearly Sales Comparison From <?php echo $from_year; ?> To <?php echo $to_year; ?> </span>
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
                                                        Total Sales: <span>$<?php echo number_format($total_super_admin_sales + $total_employer_portal_sales, 2); ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-4 text-center" rowspan="2">Year</th>
                                                            <th class="col-xs-8 text-center" colspan="2">Sales</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4 text-center">Super Admin</th>
                                                            <th class="col-xs-4 text-center">Employer Portal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php for($year = $from_year; $year <= $to_year; $year++) { ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <span><?php echo $year; ?></span>
                                                                    <span>
                                                                        <?php if( $super_admin_sales[$year] + $employer_portal_sales[$year] > 0) { ?>
                                                                            ( <a href="<?php echo base_url('manage_admin/financial_reports/yearly_sales/' . $year); ?>" class="">Details</a> )
                                                                        <?php } else { ?>
                                                                            ( <a href="javascript:void(0);" class="text-muted">Details</a> )
                                                                        <?php } ?>
                                                                    </span>
                                                                </td>
                                                                <td class="text-right">$<?php echo number_format($super_admin_sales[$year], 2); ?></td>
                                                                <td class="text-right">$<?php echo number_format($employer_portal_sales[$year], 2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-center"><strong>Total</strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format($total_super_admin_sales, 2);?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format($total_employer_portal_sales, 2);?></strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                    <span class="btn btn-success btn-lg btn-block">
                                                        Total Sales: <span>$<?php echo number_format($total_super_admin_sales + $total_employer_portal_sales, 2); ?></span>
                                                    </span>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<script>

    $(document).ready(function () {
        $('select').on('change', function(){
            var fromYear = $('#from_year').val();
            var toYear = $('#to_year').val();

            if(toYear < fromYear){
                toYear = fromYear;
            }

            var myUrl = '<?php echo base_url("manage_admin/financial_reports/yearly_sales_comparison")?>'+ '/' + fromYear + '/' + toYear;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }).trigger('change');


    });


    var ctx = document.getElementById("bar-chart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $chart_years; ?>,
            datasets: [{
                label: 'Super Admin Sales',
                data: <?php echo $chart_super_admin_sales; ?>,
                backgroundColor: 'rgba(81, 132, 1, 0.5)',
                borderColor:'#518401'
            }, {
                label: 'Employer Portal Sales',
                data: <?php echo $chart_employer_portal_sales; ?>,
                backgroundColor: 'rgba(0, 153, 255, 0.5)',
                borderColor:'#09f'
            }]
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
                        labelString: 'Years'
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