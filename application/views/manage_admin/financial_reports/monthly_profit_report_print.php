<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/font-awesome-animation.min.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/responsive.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/jquery-ui-datepicker-custom.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/star-rating.css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>

        <script src="<?= base_url() ?>assets/manage_admin/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/jquery-ui.js"></script>
        <script src="<?= base_url() ?>assets/manage_admin/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/functions.js"></script>
        <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
        <script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>
        <link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />

        <!--select2-->
                    <link href="<?= base_url() ?>assets/manage_admin/css/select2.css" rel="stylesheet" />
            <script src="<?= base_url() ?>assets/manage_admin/js/select2.min.js"></script>
                <!-- Include MultiSelect -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/chosen.css">
        <script src="<?= base_url() ?>assets/manage_admin/js/chosen.jquery.js"></script>

        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/selectize.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/selectize.bootstrap3.css">
        <script src="<?= base_url() ?>assets/manage_admin/js/selectize.min.js"></script>

        <!-- Include Jquery Validate -->
        <script src="<?= base_url() ?>assets/manage_admin/js/jquery.validate.js"></script>
        <script src="<?= base_url() ?>assets/manage_admin/js/additional-methods.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/tableHeadFixer.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/star-rating.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/Chart.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>


        <title>Financial Reports - Yearly Sales</title>
        
        <style type="text/css" media="print">
            @page{
                size: landscape;
            }
        </style>
    </head>
    <body>       
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="main">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="inner-content">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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
                                            <div id="print_section" style="display:none">
                                                <iframe src="" id="report_iframe" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
    </body>
</html>                    
<script>

    $(document).ready(function () {
        setTimeout(function(){
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
                    $('#download_report').hide();
                    $('#print_section').show();
                    $('#report_iframe').attr("src",data);
                });
        },6000)
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
</script>