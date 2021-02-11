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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-dollar"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/financial_reports'); ?>"><i class="fa fa-long-arrow-left"></i> Financial Reports</a>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/financial_reports/monthly_sales/' . $year . '/' . $month); ?>"><i class="fa fa-long-arrow-left"></i> Months Sales</a>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered pull-left">Super Admin Sales Summary</span>
                                            <span class="hr-registered pull-right"><?php echo date('l, jS F Y', mktime(0,0,0, $month, $day, $year)); ?></span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center col-xs-3">Invoice Number</th>
                                                            <th class="text-center col-xs-6">Company Name</th>
                                                            <th class="text-center col-xs-3">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $total_sale = 0; ?>
                                                        <?php if(!empty($receipts_super_admin)) { ?>
                                                            <?php foreach($receipts_super_admin as $receipt) { ?>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <span>
                                                                            <a href="<?php echo base_url('manage_admin/invoice/view_admin_invoice/' . $receipt['invoice_sid']); ?>" target="_blank">
                                                                                <?php echo STORE_CODE . '-' . str_pad($receipt['invoice_sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                            </a>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <?php echo ucwords($receipt['CompanyName']); ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $<?php echo number_format($receipt['amount'], 2); ?>
                                                                    </td>
                                                                </tr>
                                                                <?php $total_sale = $total_sale + $receipt['amount']; ?>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="3">
                                                                    <span class="no-data">No Sale</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-right">
                                                                <strong>Total: <?php echo number_format($total_sale, 2); ?></strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered pull-left">Employer Portal Sales Summary</span>
                                            <span class="hr-registered pull-right"><?php echo date('l, jS F Y', mktime(0,0,0, $month, $day, $year)); ?></span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center col-xs-3">Invoice Number</th>
                                                        <th class="text-center col-xs-6">Company Name</th>
                                                        <th class="text-center col-xs-3">Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $total_sale = 0; ?>
                                                    <?php if(!empty($receipts_employer_portal)) { ?>
                                                        <?php foreach($receipts_employer_portal as $receipt) { ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <span>
                                                                        <a href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $receipt['invoice_sid']); ?>" target="_blank">
                                                                            <?php echo 'MP-' . str_pad($receipt['invoice_sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                        </a>

                                                                    </span>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php echo ucwords($receipt['CompanyName']); ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    $<?php echo number_format($receipt['amount'], 2); ?>
                                                                </td>
                                                            </tr>
                                                            <?php $total_sale = $total_sale + $receipt['amount']; ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="3">
                                                                <span class="no-data">No Sale</span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <strong>Total: <?php echo number_format($total_sale, 2); ?></strong>
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
