<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $report_name = "monthly_profit_report"; ?>
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
                                                            <a href="" class="btn btn-equalizer btn-success btn-block" id="search_btn">Search</a>
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

                                    <div id="download_report">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="hr-registered">Admin Invoices Profit for Month of <?php echo $months[$month]; ?>, <?php echo $year; ?></span>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="hidden-xs">
                                                    <hr />
                                                    <div class="row">

                                                        <div class="col-xs-5">
                                                            <canvas id="sa-bar-chart"></canvas>
                                                        </div>
                                                        <div class="col-xs-2"></div>
                                                        <div class="col-xs-5">
                                                            <canvas id="sa-pie-chart"></canvas>
                                                        </div>

                                                    </div>
                                                    <hr />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                        <span class="btn btn-success btn-lg btn-block">

                                                            Total Profit : <span>$<?php echo number_format(round($sa_grand_total_profit_after_fee), 2)?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <hr />

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2" class="text-center">Date</th>
                                                                <th rowspan="2" class="text-center">Company</th>
                                                                <th colspan="4" class="text-center table-header-blue">Invoice Detail</th>

                                                                <th colspan="4" class="text-center table-header-green">Profit Details</th>


                                                            </tr>
                                                            <tr>
                                                                <th class="text-center table-header-blue">No.</th>
                                                                <th class="text-center table-header-blue">Subtotal</th>
                                                                <th class="text-center table-header-blue">Discount</th>
                                                                <th class="text-center table-header-blue">Total</th>

                                                                <th class="text-center table-header-green">Invoice Cost</th>
                                                                <th class="text-center table-header-green">Profit</th>

                                                                <th class="text-center table-header-green">Paypal Fee</th>
                                                                <th class="text-center table-header-green">Profit after Fee</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php if(!empty($super_admin_invoices)) { ?>
                                                                <?php foreach($super_admin_invoices as $invoice) { ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['created']);?></td>
                                                                        <td class="text-left"><?php echo ($invoice['company_name'])?></td>
                                                                        <td class="text-center"><a target="_blank"  href="<?php echo base_url('manage_admin/invoice/view_admin_invoice/' . $invoice['sid']); ?>"><?php echo ($invoice['invoice_number'])?></a></td>
                                                                        <td class="text-right">$<?php echo number_format($invoice['value'], 2)?></td>

                                                                        <td class="text-right">
                                                                        <?php if($invoice['discount_amount'] > 0) { ?>
                                                                            $<?php echo number_format($invoice['discount_amount'], 2);?>
                                                                        <?php }  else { ?>
                                                                            $<?php echo number_format(0, 2)?>
                                                                        <?php } ?>
                                                                            <br /><small>(<?php echo number_format($invoice['total_discount_percentage'], 2);?>%)</small>
                                                                        </td>
                                                                        <td class="text-right">$<?php echo number_format($invoice['total_after_discount'], 2);?></td>
                                                                        <td class="text-right"><?php echo ($invoice['total_cost'] > 0 ? '$' .  number_format($invoice['total_cost'], 2) : 'N/A'); ?></td>
                                                                        <td class="text-right">$<?php echo number_format($invoice['total_actual_profit'], 2);?></td>
                                                                        <td class="text-right">$<?php echo number_format($invoice['paypal_fee'], 2);?></td>

                                                                        <td class="text-right">$<?php echo $invoice['total_profit_after_paypal_fee'] > 0 ? number_format(round($invoice['total_profit_after_paypal_fee']), 2) : number_format(0, 2);?></td>
                                                                    </tr>


                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="11" class="text-center">
                                                                        <span class="no-data">No Sale</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="5"><strong>Total</strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($sa_grand_total_sale), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($sa_grand_total_cost), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($sa_grand_total_profit), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($sa_grand_total_paypal_fee), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($sa_grand_total_profit_after_fee), 2)?></strong></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                </div>


                                                <hr />

                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                        <span class="btn btn-success btn-lg btn-block">
                                                            Total Profit : <span>$<?php echo number_format(round($sa_grand_total_profit_after_fee), 2)?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <hr />


                                            </div>
                                        </div>


                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="hr-registered">Marketplace Invoices Profit for Month of <?php echo $months[$month]; ?>, <?php echo $year; ?></span>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="hidden-xs">
                                                    <hr />
                                                    <div class="col-xs-5">
                                                        <canvas id="ep-bar-chart"></canvas>
                                                    </div>
                                                    <div class="col-xs-2"></div>
                                                    <div class="col-xs-5">
                                                        <canvas id="ep-pie-chart"></canvas>
                                                    </div>
                                                    <hr />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                        <span class="btn btn-success btn-lg btn-block">
                                                            Total Profit : <span>$<?php echo number_format(round($ep_grand_total_profit_after_fee), 2)?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <hr />

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2" class="text-center">Date</th>
                                                                <th rowspan="2" class="text-center">Company</th>
                                                                <th colspan="4" class="text-center table-header-blue">Invoice Detail</th>

                                                                <th colspan="4" class="text-center table-header-green">Profit Details</th>


                                                            </tr>
                                                            <tr>
                                                                <th class="text-center table-header-blue">No.</th>
                                                                <th class="text-center table-header-blue">Subtotal</th>
                                                                <th class="text-center table-header-blue">Discount</th>
                                                                <th class="text-center table-header-blue">Total</th>

                                                                <th class="text-center table-header-green">Invoice Cost</th>
                                                                <th class="text-center table-header-green">Profit</th>

                                                                <th class="text-center table-header-green">Paypal Fee</th>
                                                                <th class="text-center table-header-green">Profit after Fee</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php if(!empty($employer_portal_invoices)) { ?>
                                                            <?php foreach($employer_portal_invoices as $invoice) { ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['date'])?></td>
                                                                    <td class="text-left"><?php echo ($invoice['company_name'])?></td>
                                                                    <td class="text-center"> <a target="_blank" href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $invoice['sid']); ?>"><?php echo 'MP-' . str_pad($invoice['sid'],6,0,STR_PAD_LEFT);?></a></td>
                                                                    <td class="text-right">$<?php echo number_format($invoice['total_price'], 2)?></td>

                                                                    <td class="text-right">
                                                                    <?php if($invoice['total_discount'] > 0) { ?>
                                                                        $<?php echo number_format($invoice['total_discount'], 2)?>

                                                                    <?php }  else { ?>
                                                                        $<?php echo number_format(0, 2)?>

                                                                    <?php } ?>
                                                                        <br /><small>(<?php echo number_format($invoice['total_discount_percentage'], 2)?>%)</small>
                                                                    </td>

                                                                    <td class="text-right">$<?php echo number_format($invoice['total'], 2)?></td>

                                                                    <td class="text-right"><?php echo ($invoice['total_cost'] > 0 ? '$' . number_format($invoice['total_cost'], 2) : 'N/A'); ?></td>
                                                                    <td class="text-right">$<?php echo number_format($invoice['total_actual_profit'] , 2)?></td>
                                                                    <td class="text-right">$<?php echo number_format($invoice['paypal_fee'], 2)?></td>
                                                                    <td class="text-right">$<?php echo number_format($invoice['total_profit_after_paypal_fee'], 2) ;?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="11" class="text-center">
                                                                    <span class="no-data">No Sale</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="5"><strong>Total</strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($ep_grand_total_sale), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($ep_grand_total_cost), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($ep_grand_total_profit), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($ep_grand_total_paypal_fee), 2)?></strong></td>
                                                            <td class="text-right"><strong>$<?php echo number_format(round($ep_grand_total_profit_after_fee), 2)?></strong></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                </div>
                                                <hr />

                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                        <span class="btn btn-success btn-lg btn-block">
                                                            Total Profit : <span>$<?php echo number_format(round($ep_grand_total_profit_after_fee), 2)?></span>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<script>
    $(document).ready(function () {
        $('select').on('change', function(){
            var myYear = $('#year').val();
            var myMonth = $('#month').val();
            var myUrl = '<?php echo base_url("manage_admin/financial_reports/monthly_profit_report")?>'+ '/' + myYear + '/' + myMonth;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }).trigger('change');
    });


    //Pie Graph
    var sa_pie_ctx = document.getElementById("sa-pie-chart").getContext('2d');

    var pie_chart = new Chart(sa_pie_ctx, {
        type: 'pie',
        data: {
            labels: ['Total Sale', 'Total Cost', 'Total Paypal Fee', 'Total Profit'],
            datasets: [
                {
                    label: 'Sales Vs Cost',
                    data: [<?php echo round($sa_grand_total_sale); ?>, <?php echo round($sa_grand_total_cost); ?>, <?php echo round($sa_grand_total_paypal_fee); ?>, <?php echo round($sa_grand_total_profit_after_fee); ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.6)']
                }
            ]
        }

    });

    var sa_bar_ctx = document.getElementById("sa-bar-chart").getContext('2d');

    sa_bar_ctx.canvas.width = sa_pie_ctx.canvas.width;
    sa_bar_ctx.canvas.height = sa_pie_ctx.canvas.height;

    var bar_chart = new Chart(sa_bar_ctx, {
        type: 'bar',
        labels: ['Sales Vs Cost Vs Profit'],
        data: {
            labels: ['Total Sale', 'Total Cost', 'Total Paypal Fee', 'Total Profit'],
            datasets: [
                {
                    label: 'Sales Vs Cost',
                    data: [<?php echo round($sa_grand_total_sale); ?>, <?php echo round($sa_grand_total_cost); ?>, <?php echo round($sa_grand_total_paypal_fee); ?>, <?php echo round($sa_grand_total_profit_after_fee); ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.6)']
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
                        labelString: 'Years'
                    }
                }]
            }
        }

    });




    //Pie Graph
    var ep_pie_ctx = document.getElementById("ep-pie-chart").getContext('2d');

    var eppie_chart = new Chart(ep_pie_ctx, {
        type: 'pie',
        data: {
            labels: ['Total Sale', 'Total Cost', 'Total Paypal Fee', 'Total Profit'],
            datasets: [
                {
                    label: 'Sales Vs Cost',
                    data: [<?php echo round($ep_grand_total_sale); ?>, <?php echo round($ep_grand_total_cost); ?>, <?php echo round($ep_grand_total_paypal_fee); ?>, <?php echo round($ep_grand_total_profit_after_fee); ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.6)']
                }
            ]
        }

    });

    var ep_bar_ctx = document.getElementById("ep-bar-chart").getContext('2d');

    ep_bar_ctx.canvas.width = ep_pie_ctx.canvas.width;
    ep_bar_ctx.canvas.height = ep_pie_ctx.canvas.height;

    var ep_bar_chart = new Chart(ep_bar_ctx, {
        type: 'bar',
        labels: ['Sales Vs Cost Vs Profit'],
        data: {
            labels: ['Total Sale', 'Total Cost', 'Total Paypal Fee', 'Total Profit'],
            datasets: [
                {
                    label: 'Sales Vs Cost',
                    data: [<?php echo round($ep_grand_total_sale); ?>, <?php echo round($ep_grand_total_cost); ?>, <?php echo round($ep_grand_total_paypal_fee); ?>, <?php echo round($ep_grand_total_profit_after_fee); ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(128, 0, 128, 0.8)', 'rgba(0, 190, 0, 0.6)']
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