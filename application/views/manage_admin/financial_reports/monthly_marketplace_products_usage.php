<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $report_name = "monthly_marketplace_products_usage-history"; ?>
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
                                        <h1 class="page-title"><i class="fa fa-dollar"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/financial_reports'); ?>"><i class="fa fa-long-arrow-left"></i> Financial Reports</a>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area" id="collapseExample">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="field-row">
                                                            <label for="vendor">Product Vendor</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(4);?>
                                                                <select id="vendor" class="invoice-fields">
                                                                    <option value="all">All Vendors</option>
                                                                    <?php foreach($vendors as $key => $value) { ?>
                                                                        <?php $default_selected = $key == $selected ? true : false; ?>
                                                                        <option <?php echo set_select('vendor', $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="field-row">
                                                            <label for="year">Year</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(5);?>
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
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="field-row">
                                                            <label for="month">Month</label>
                                                            <div  class="hr-select-dropdown">
                                                                <?php $selected = $this->uri->segment(6); ?>
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
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
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
                                            <a target="_blank" class="btn btn-success" href="<?php echo base_url('manage_admin/financial_reports/print_monthly_marketplace_products_usage')."/". $vendor . "/" . $year . "/" . $month; ?>">Print</a>
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="download_report">Download</a>
                                        </div>
                                    </div>

                                    <div class="hr-box" id="download_report">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Monthly Marketplace Products Usage History </span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="hidden-xs">
                                                <hr />
                                                <div class="row">
                                                    <!--<div class="col-xs-6">
                                                        <canvas style="width:100%;" id="bar-chart"></canvas>
                                                    </div>-->
                                                    <div class="col-xs-3"></div>
                                                    <div class="col-xs-6 text-center">
                                                        <canvas id="pie-chart"></canvas>
                                                    </div>
                                                    <div class="col-xs-3"></div>
                                                </div>
                                                <hr />
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <p class="text-muted"><small>All Calculations are based on <strong>Current Product Sale Price</strong> and <strong>Current Product Cost</strong> saved in Marketplace Products section against each product.</small></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-2 text-left">Company</th>
                                                                    <th class="col-xs-3 text-left">Product</th>
                                                                    <th class="col-xs-1 text-left">Usage Date</th>
                                                                    <th class="col-xs-2 text-center">Price</th>
                                                                    <th class="col-xs-2 text-center">Cost</th>
                                                                    <th class="col-xs-2 text-center">Profit</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(!empty($product_usage)) { ?>
                                                                    <?php foreach($product_usage as $product) { ?>
                                                                        <tr>
                                                                            <td class="text-left">
                                                                                <?php echo $product['company_name']; ?>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <?php echo $product['product_name']; ?>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <?php echo convert_date_to_frontend_format($product['usage_date']); ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                $<?php echo number_format($product['product_price'], 2); ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                $<?php echo number_format($product['product_cost_price'], 2); ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                $<?php echo number_format($product['profit'], 2); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">
                                                                            <span class="no-data">No Sales</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class="text-left" colspan="3">
                                                                        <strong>Total</strong>
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <strong>$<?php echo number_format($total_sale, 2);?></strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <strong>$<?php echo number_format($total_cost, 2);?></strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <strong>$<?php echo number_format($total_profit, 2);?></strong>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
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
            var myVendor = $('#vendor').val();
            var myYear = $('#year').val();
            var myMonth = $('#month').val();

            var myUrl = '<?php echo base_url("manage_admin/financial_reports/monthly_marketplace_products_usage")?>' + '/' + myVendor + '/' + myYear + '/' + myMonth;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }).trigger('change');
    });

    //Pie Graph
    var pie_ctx = document.getElementById("pie-chart").getContext('2d');

    var myChart = new Chart(pie_ctx, {
        type: 'pie',
        data: {
            labels: ['Products Total Sale', 'Products Total Cost', 'Total Profit'],
            datasets: [
                {
                    label: ['Products Total Sale', 'Products Total Cost', 'Total Profit'],
                    data: [<?php echo $total_sale; ?>, <?php echo $total_cost; ?>, <?php echo $total_profit; ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(0, 190, 0, 0.6)']
                }
            ]
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


    //Bar graph
    /*var ctx = document.getElementById("bar-chart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Products Sales Price vs Cost Price'],
            datasets: [
                {
                    label: 'Products Total Sale',
                    data: [<?php echo $total_sale; ?>],
                    backgroundColor: 'rgba(81, 132, 1, 0.5)',
                    borderColor:'#518401'
                },
                {
                    label: 'Products Total Cost',
                    data: [<?php echo $total_cost; ?>],
                    backgroundColor: 'rgba(0, 153, 255, 0.5)',
                    borderColor:'#09f'
                }
            ]
        },
        options: {
            scaleStartValue: 0,
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'US Dollars ($)'
                    },
                    ticks: {
                        beginAtZero: true,
                        min : 0
                    }

                }]
            }
        }

    });*/
</script>