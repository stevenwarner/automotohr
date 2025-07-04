<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back To Settings</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive table-outer">
                                <div class="scrollable-area">
                                    <table class="table table-bordered table-stripped table-hover fixTable-header">
                                        <thead>
                                            <tr>
                                                <th class="text-left">Invoice Details</th>
                                                <th class="col-xs-2 text-center">Amount</th>
                                                <th class="col-xs-2 text-center">Discount</th>
                                                <th class="col-xs-2 text-center">Total</th>
                                                <th class="col-xs-1 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($invoices as $key => $invoice) { ?>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="dotted-border">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Company</strong></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?php echo ucwords($invoice['company_name']); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="dotted-border">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Created Date</strong></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?=reset_datetime(array('datetime' => $invoice['created'], '_this' => $this));?></div>
                                                                </div>
                                                            </div>

                                                            <div class="dotted-border">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Invoice #</strong></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?php echo $invoice['invoice_number']; ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="dotted-border">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Payment Status</strong></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 <?php echo ucwords($invoice['payment_status']); ?>"><?php echo ucwords($invoice['payment_status']); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="dotted-border">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Payment Date</strong></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?= $invoice['payment_date'] != '0000-00-00' ? reset_datetime(array('datetime' => $invoice['payment_date'], '_this' => $this)) : ''; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p>Items Summary</p>
                                                    <ul class="list-unstyled invoice-description-list">
                                                        <?php foreach($invoice['item_names'] as $item) { ?>
                                                            <li class="invoice-description-list-item"><?php echo $item['item_name']; ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </td>
                                                <td class="text-right">$ <?php echo number_format($invoice['value'] ,2,'.', ',')?></td>
                                                <td class="text-right">$ <?php echo number_format($invoice['discount_amount'] ,2,'.', ',')?></td>
                                                <td class="text-right">
                                                    <!--
                                                    <?php /*if($invoice['is_discounted'] == 1) { */?>
                                                        $ <?php /*echo number_format($invoice['total_after_discount'] ,2,'.', ',')*/?>
                                                    <?php /*} else { */?>
                                                        $ <?php /*echo number_format($invoice['value'] ,2,'.', ',')*/?>
                                                    <?php /*} */?>
                                                    -->

                                                    $ <?php echo number_format($invoice['total_after_discount'] ,2,'.', ',')?>
                                                </td>


                                                <td class="col-xs-1 text-center">                    
                                                    <a href="<?php echo base_url('settings/view_packages_addons_invoice/' . $invoice['sid']); ?>" class="submit-btn invoice-links">View</a>

                                                    <?php if($invoice['payment_status'] == 'unpaid') { ?>
                                                        <a href="<?php echo base_url('misc/process_payment_admin_invoice/' . $invoice['sid'])?>" class="submit-btn invoice-links">Pay</a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" class="submit-btn disabled-btn invoice-links">Pay</a>
                                                    <?php } ?>  
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

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/js/tableHeadFixer.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable({
            paging: false,
            info: false,
            stateSave: true
        });

        $(".fixTable-header").tableHeadFixer(); 
    });
</script>