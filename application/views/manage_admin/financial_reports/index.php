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
                                    </div>
                                </div>
                                <div class="grid-columns">
                                    <?php if (check_access_permissions_for_view($security_details, 'yearly_sales_comparison')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/yearly_sales_comparison/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Yearly Sales Comparison</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'yearly_sales')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/yearly_sales/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Yearly Sales</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_sales')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_sales/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Sales</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_products_usage')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_marketplace_products_usage/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Marketplace Products Usage History</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_products_sales')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_marketplace_products_sales/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Marketplace Products Sales History</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_product_statistics')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_marketplace_product_statistics/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Marketplace Products Statistics</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_profit_report')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_profit_report/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Profit Report</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'monthly_profit_report')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="equal-height-grid">
                                                <a class="text-success" href="<?php echo base_url('manage_admin/financial_reports/monthly_unpaid_invoices/'); ?>">
                                                    <div class="text-center" style="padding: 30px 20px;">
                                                        <p><i class="fa fa-money" style="font-size: 70px;"></i></p>
                                                        <p style="font-size: 20px;"><strong>Monthly Unpaid Invoices</strong></p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
