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

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Monthly Marketplace Product Statistics </span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center" rowspan="2">Date</th>
                                                                    <th class="col-xs-4 text-center" rowspan="2">Company</th>
                                                                    <th class="col-xs-4 text-center" rowspan="2">Product</th>
                                                                    <th class="col-xs-4 text-center" colspan="3">Quantity</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Purchased</th>
                                                                    <th class="col-xs-1 text-center">Used</th>
                                                                    <th class="col-xs-1 text-center">Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(!empty($all_invoices)) { ?>
                                                                    <?php foreach($all_invoices as $invoice) { ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['date'])?></td>
                                                                            <td class="text-left"><?php echo $invoice['CompanyName']; ?></td>
                                                                            <td class="text-left"><?php echo $invoice['invoice_items'][0]['name']; ?></td>
                                                                            <td class="text-center"><?php echo $invoice['invoice_items'][0]['item_qty']; ?></td>
                                                                            <td class="text-center"><?php echo $invoice['invoice_items'][0]['item_qty'] - $invoice['invoice_items'][0]['item_remaining_qty']; ?></td>
                                                                            <td class="text-center"><?php echo $invoice['invoice_items'][0]['item_remaining_qty']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">
                                                                            <span class="no-data">No Invoices</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
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
<script>
    $(document).ready(function () {
        $('select').on('change', function(){
            var myVendor = $('#vendor').val();
            var myYear = $('#year').val();
            var myMonth = $('#month').val();

            var myUrl = '<?php echo base_url("manage_admin/financial_reports/monthly_marketplace_product_statistics")?>' + '/' + myVendor + '/' + myYear + '/' + myMonth;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }).trigger('change');
    });




</script>