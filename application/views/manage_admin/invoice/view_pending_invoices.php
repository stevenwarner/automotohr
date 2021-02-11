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
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Unpaid Invoice Details</h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/pending_invoices'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Unpaid Invoices</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if(!empty($company_info)) { ?>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-xs-3">Company</th>
                                                        <td><?php echo ucwords(strtolower($company_info['CompanyName'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php $state_info = db_get_state_name($company_info['Location_State'])?>
                                                        <th class="col-xs-3">Address</th>
                                                        <td>
                                                            <?php echo ucwords(strtolower($company_info['Location_Address'])); ?>
                                                            <?php echo (!empty($company_info['Location_City']) ? ', ' . ucwords(strtolower($company_info['Location_City'])) : '' ) ; ?>
                                                            <?php echo (!empty($company_info['Location_ZipCode']) ? ', ' . ucwords(strtolower($company_info['Location_ZipCode'])) : '' ) ; ?>
                                                            <?php echo (!empty($company_info['Location_State']) ? ', ' . $state_info['state_name'] . ', ' . $state_info['country_name'] : '' ) ; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Email</th>
                                                        <td><?php echo strtolower($company_info['email']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-3">Phone</th>
                                                        <td><?php echo $company_info['PhoneNumber']; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                        <div class="hr-box">
                                            <div class="hr-innerpadding text-center">
                                                <span class="no-data">Company Deleted From System</span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="hr-box">
                                        <div class="table-responsive hr-innerpadding">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center col-xs-2">Invoice Number</th>
                                                <th class="text-center col-xs-2">Date</th>
                                                <th class="text-center col-xs-2">Pyament Status</th>
                                                <th class="text-center col-xs-4">Amount</th>
                                                <?php if(check_access_permissions_for_view($security_details, 'exc_inc_unpaid_invoices') || check_access_permissions_for_view($security_details, 'delete_unpaid_invoices')) { ?>
                                                    <th class="text-center col-xs-2" colspan="2">Actions</th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($invoices as $invoice) {?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo $invoice['invoice_number']; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo convert_date_to_frontend_format($invoice['created']); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-danger">
                                                            <?php echo ucwords(strtolower($invoice['payment_status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-right">$
                                                        <?php /*if($invoice['is_discounted'] == 1) { */?><!--
                                                            <?php /*echo number_format($invoice['total_after_discount'], 2); */?>
                                                        <?php /*} else { */?>
                                                            <?php /*echo number_format($invoice['value'], 2); */?>
                                                        --><?php /*} */?>
                                                        <?php echo number_format($invoice['total_after_discount'], 2); ?>
                                                    </td>
                                                    <?php if(check_access_permissions_for_view($security_details, 'exc_inc_unpaid_invoices')) { ?>
                                                        <td>
                                                            <form enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_exclusion_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                                <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />

                                                                <?php if($invoice['exclusion_status'] == 0) { ?>
                                                                    <input type="hidden" id="exclusion_status" name="exclusion_status" value="1" />
                                                                    <button type="submit" class="btn btn-danger btn-sm">Exclude</button>
                                                                <?php } else { ?>
                                                                    <input type="hidden" id="exclusion_status" name="exclusion_status" value="0" />
                                                                    <button type="submit" class="btn btn-success btn-sm">Include</button>
                                                                <?php } ?>
                                                            </form>
                                                        </td>
                                                    <?php } if(check_access_permissions_for_view($security_details, 'delete_unpaid_invoices')) { ?>
                                                        <td>
                                                            <form id="form_delete_invoice_<?php echo $invoice['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_invoice" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                                <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                            </form>
                                                            <button onclick="func_delete_invoice(<?php echo $invoice['sid']; ?>);" type="button" class="btn btn-danger btn-sm">Delete</button>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th class="text-right">$ <?php echo number_format($grand_total, 2); ?></th>
                                                <th colspan="2"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'send_unpaid_invoices')) { ?>
                                        <div class="row">
                                            <div class="col-xs-10">
                                                <form id="form_send_pending_invoices_summary" enctype="multipart/form-data" method="post" action="<?php current_url(); ?>">
                                                    <div class="row">
                                                        <div class="col-xs-10">
                                                            <div class="field-row">
                                                                <label for="email_address">Send Invoice To</label>
                                                                <input data-rule-required="true" data-rule-email="true" type="email" class="hr-form-fileds" id="email_address" name="email_address" value="<?php echo $company_info['email']; ?>" placeholder="xyz@abc.com" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <div class="field-row">
                                                                <label for="">&nbsp;</label>
                                                                <button onclick="func_send_pending_invoices_summary();" type="button" class="btn btn-success btn-block btn-equalizer">Send</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_pending_invoices" />
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid?>" />
                                                </form>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="field-row">
                                                    <label for="">&nbsp;</label>
                                                    <a href="<?php echo base_url('manage_admin/invoice/print_pending_invoices/' . $company_sid); ?>" class="btn btn-block btn-success btn-equalizer" target="_blank">Print</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
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