<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Unpaid Commission Details</h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/pending_commissions'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Unpaid Commission</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered pull-left">Commission Details</span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-xs-3">Date</th>
                                                        <td><?php echo convert_date_to_frontend_format(strtolower($commission['created'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Invoice Number</th>
                                                        <td><?php echo $commission['invoice_number']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Company Name</th>
                                                        <td><?php echo $commission['company_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Marketing Agency Name</th>
                                                        <td><?php echo $commission['full_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Against Invoice Type</th>
                                                        <td><?php echo $commission['invoice_type']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Against Invoice Number</th>
                                                        <?php if ($commission['invoice_origin'] == 'super_admin') { ?>
                                                            <td class="col-xs-8"><?php echo STORE_CODE . '-' . str_pad($commission['invoice_sid'], 6, 0, STR_PAD_LEFT); ?></td>
                                                        <?php } else if ($commission['invoice_origin'] == 'employer_portal') { ?>
                                                            <td class="col-xs-8"><?php echo $commission['invoice_sid']; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Subtotal</th>
                                                        <td class="col-xs-8">$ <?php echo number_format($commission['value'], 2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Discount Percentage</th>
                                                        <td class="col-xs-8"><?php echo number_format($commission['discount_percentage'], 2); ?> %</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Discount Amount</th>
                                                        <td class="col-xs-8">$ <?php echo number_format($commission['discount_amount'], 2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Total</th>
                                                        <?php if ($commission['discount_amount'] > 0) { ?>
                                                            <td class="col-xs-8">$ <?php echo number_format($commission['total_after_discount'], 2); ?></td>
                                                        <?php } else { ?>
                                                            <td class="col-xs-8">$ <?php echo number_format($commission['value'], 2); ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-4">Total Commission Payable</th>
                                                        <?php if ($commission['discount_amount'] > 0) { ?>
                                                            <td class="col-xs-8">$ <?php echo number_format($commission['total_commission_after_discount'], 2); ?></td>
                                                        <?php } else { ?>
                                                            <td class="col-xs-8">$ <?php echo number_format($commission['commission_value'], 2); ?></td>
                                                        <?php } ?>
                                                    </tr>
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
<script>
    function func_send_pending_invoices_summary() {
        $('#form_send_pending_invoices_summary').validate();

        if($('#form_send_pending_invoices_summary').valid()){
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want this detail to be sent to Client?',
                function () {
                    $('#form_send_pending_invoices_summary').submit();
                },
                function () {
                    alertify.error('Cancelled');
                });
        }
    }

    function func_delete_invoice(invoice_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Invoice?',
            function () {
                $('#form_delete_invoice_' + invoice_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>