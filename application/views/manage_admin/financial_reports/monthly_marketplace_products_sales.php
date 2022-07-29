<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $report_name = "monthly_marketplace_products_sales"; ?>
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
                                            <div class="hr-search-criteria">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area">
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
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="print_report">Print</a>
                                            <a class="btn btn-success" href="JavaScript:;" onclick="jsReportAction(this)" data-action="download_report">Download</a>
                                        </div>
                                    </div>

                                    <div class="hr-box" id="download_report">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Monthly Marketplace Products Sales History </span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="hidden-xs">
                                                <hr />
                                                <div class="row">
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
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center" rowspan="2">Invoice</th>
                                                                    <th class="col-xs-1 text-center" rowspan="2">Date</th>
                                                                    <th class="col-xs-2 text-left" rowspan="2">Company</th>
                                                                    <th class="col-xs-2 text-left" rowspan="2">Product</th>
                                                                    <th class="col-xs-1 text-center" rowspan="2">Qty</th>
                                                                    <th class="col-xs-1 text-center" colspan="2">Price</th>
                                                                    <th class="col-xs-1 text-center" colspan="2">Cost</th>
                                                                    <th class="col-xs-1 text-center" rowspan="2">Profit</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Unit</th>
                                                                    <th class="col-xs-1 text-center">Total</th>
                                                                    <th class="col-xs-1 text-center">Unit</th>
                                                                    <th class="col-xs-1 text-center">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $total_sale = 0; ?>
                                                                <?php $total_cost = 0; ?>
                                                                <?php $total_profit = 0; ?>
                                                                <?php if(!empty($products_sold)) { ?>
                                                                    <?php foreach($products_sold as $product){ ?>
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                <a href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $product['sid']); ?>" target="_blank">
                                                                                    <?php echo 'MP-' . str_pad($product['sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($product['date']); ?></td>
                                                                            <td class="text-left"><?php echo $product['CompanyName']; ?></td>
                                                                            <td class="text-left"><?php echo $product['invoice_items'][0]['name']; ?></td>
                                                                            <td class="text-center"><?php echo $product['invoice_items'][0]['item_qty']; ?></td>

                                                                            <td class="text-right">$<?php echo number_format($product['invoice_items'][0]['price'], 2); ?></td>
                                                                            <td class="text-right">$<?php echo number_format($product['invoice_items'][0]['total_price'], 2); ?></td>

                                                                            <td class="text-right">$<?php echo number_format($product['invoice_items'][0]['cost_price'], 2); ?></td>
                                                                            <td class="text-right">$<?php echo number_format($product['invoice_items'][0]['total_cost'], 2); ?></td>

                                                                            <td class="text-right">$<?php echo number_format($product['invoice_items'][0]['total_profit'], 2); ?></td>


                                                                        </tr>
                                                                        <?php $total_sale = $total_sale + $product['invoice_items'][0]['total_price']; ?>
                                                                        <?php $total_cost = $total_cost + $product['invoice_items'][0]['total_cost']; ?>
                                                                        <?php $total_profit = $total_profit + $product['invoice_items'][0]['total_profit']; ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="10">
                                                                            <span class="no-data">No invoices</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class="text-left" colspan="6"><strong>Total</strong></td>
                                                                    <td class="text-right"><strong>$<?php echo number_format($total_sale, 2); ?></strong></td>
                                                                    <td></td>
                                                                    <td class="text-right"><strong>$<?php echo number_format($total_cost, 2); ?></strong></td>

                                                                    <td class="text-right"><strong>$<?php echo number_format($total_profit, 2); ?></strong></td>
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

            var myUrl = '<?php echo base_url("manage_admin/financial_reports/monthly_marketplace_products_sales")?>' + '/' + myVendor + '/' + myYear + '/' + myMonth;

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

</script>