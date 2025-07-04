<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="invoice-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-text-o"></i>Invoice Detail</h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $invoice['company_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="admin-invoice-area">
                                        <div class="add-new-promotions">
                                            <div class="row">
                                                <form id="update_invoice_status" class="update_invoice_status" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                    <input type="hidden" id="perform_action" name="perform_action" value="update_invoice_status" />

                                                    <div style="margin:5px 0;" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="invoice_status" name="invoice_status">
                                                                <option <?php echo ($invoice['invoice_status'] == 'active' ? 'selected="selected"' : ''); ?> value="active">Active</option>
                                                                <option <?php echo ($invoice['invoice_status'] == 'cancelled' ? 'selected="selected"' : ''); ?> value="cancelled">Cancelled</option>
                                                                <option <?php echo ($invoice['invoice_status'] == 'archived' ? 'selected="selected"' : ''); ?> value="archived">Archived</option>
                                                                <option <?php echo ($invoice['invoice_status'] == 'due' ? 'selected="selected"' : ''); ?> value="due">Due</option>
                                                                <option <?php echo ($invoice['invoice_status'] == 'overdue' ? 'selected="selected"' : ''); ?> value="overdue">Overdue</option>
                                                                <option <?php echo ($invoice['invoice_status'] == 'baddebt' ? 'selected="selected"' : ''); ?> value="baddebt">Bad Debt</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="margin:5px 0;" class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <button type="button" onclick="fUpdateInvoiceStatus();" class="site-btn lineheight full-width">Update Status</button>
                                                    </div>
                                                </form>

                                                <form class="update_invoice_status" enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                    <div style="margin:5px 0;" class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                                        <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                        <input type="hidden" id="perform_action" name="perform_action" value="send_invoice_to_client_by_email" />
                                                        <input type="text" id="email_address" name="email_address" value="<?php echo $invoice['company_email']; ?>" class="invoice-fields" />
                                                        <?php echo form_error('email_address'); ?>
                                                    </div>
                                                    <div style="margin:5px 0;" class="col-xs-12 col-sm-6 col-md-6 col-lg-2">
                                                        <button type="submit" class="site-btn lineheight full-width">Email Invoice</button>
                                                    </div>
                                                </form>

                                                <div style="margin:5px 0;" class="col-xs-12 col-sm-12 col-md-6 col-lg-1">
                                                    <a class="site-btn lineheight full-width" href="<?php echo base_url('manage_admin/invoice/print_admin_invoice/' . $invoice['sid']); ?>" target="_blank">Print</a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="add-new-company">
                                                            <div class="heading-title page-title">
                                                                <h1 class="page-title">Invoice # <?php echo $invoice['invoice_number']; ?></h1>
                                                                <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/invoice/edit_admin_invoice/' . $invoice['sid']); ?>">Edit Invoice</a>
                                                            </div>
                                                        </div>
                                                        <div class="invoice-receiver">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title"><strong>Billed To:</strong></h3>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                            <label>Invoice Number</label>
                                                                            <div>
                                                                                <?php echo ucwords($invoice['invoice_number']); ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                            <label>Company Name</label>
                                                                            <div> <?php echo ucwords($invoice['company_name']); ?> </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                            <label>Created On</label>
                                                                            <?php if ($invoice['payment_status'] == 'paid') { ?>
                                                                                <div>
                                                                                    <?php echo date('m-d-Y', strtotime(str_replace('-', '/', $invoice['created']))); ?>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <div>
                                                                                    <form id="update_invoice_status" class="update_invoice_status" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                                                        <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="change_invoice_date" />
                                                                                        <input type="hidden" name="previous_date" value="<?php echo $invoice['created'] ?>" />
                                                                                        <div>
                                                                                            <?php $end_date = strtotime(str_replace('-', '/', $invoice['created'])); ?>
                                                                                            <input class="invoice-fields" type="text" name="invoice_date" id="invoice_date" value="<?php echo set_value('invoice_date', date('m-d-Y', $end_date)); ?>" />
                                                                                        </div>

                                                                                        <div class="field-row">
                                                                                            <input type="submit" id="change" class="btn btn-success pull-right" value="Update Date">
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <hr />
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                            <label>Payment Status</label>
                                                                            <div class="<?php echo $invoice['payment_status']; ?>">
                                                                                <?php echo ucwords($invoice['payment_status']); ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php if ($invoice['payment_status'] == 'paid') { ?>
                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                <label>Payment Date</label>
                                                                                <div>
                                                                                    <?php echo date('m-d-Y', strtotime(str_replace('-', '/', $invoice['payment_date']))); ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <?php if ($invoice['payment_status'] == 'paid') { ?>
                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                <label>Payment Method</label>
                                                                                <div>
                                                                                    <?php echo ucwords(str_replace('-', ' ', $invoice['payment_method'])); ?>
                                                                                    <?php if ($invoice['check_number'] != NULL) {
                                                                                        echo ' #' . $invoice['check_number'];
                                                                                    } ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <hr />
                                                                    <?php if ($invoice['payment_method'] == 'credit-card') { ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                <label>Credit Card Number</label>
                                                                                <div>
                                                                                    <?php echo !is_null($invoice['credit_card_number']) && !empty($invoice['credit_card_number']) ? $invoice['credit_card_number'] : 'N/A'; ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                <label>Credit Card Type</label>
                                                                                <div>
                                                                                    <?php echo !is_null($invoice['credit_card_type']) && !empty($invoice['credit_card_type']) ? ucwords($invoice['credit_card_type']) : 'N/A'; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr />
                                                                    <?php } ?>
                                                                    <div class="row">
                                                                        <?php if ($invoice['payment_status'] == 'paid') { ?>
                                                                            <div class="col-lg-12 col-md-12 col-xs-6 col-sm-6">
                                                                                <label>Payment Description</label>
                                                                                <div>
                                                                                    <?php echo $invoice['payment_description']; ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <?php if ($invoice['payment_status'] == 'paid') { ?>
                                                                            <!--<div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                <label>Payment Processed By</label>
                                                                                <div>
                                                                            <?php echo $invoice['payment_processed_by']; ?>
                                                                                </div>
                                                                            </div>-->
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="hr-box-header">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <h3 class="panel-title"><strong>Invoice summary</strong></h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive table-outer">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2"><strong>Item Name</strong></th>
                                                                <th class="col-lg-5 col-md-5 col-xs-5 col-sm-5"><strong>Item Description</strong></th>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2 text-right"><strong>Item Price</strong></th>
                                                                <th class="col-lg-1 col-md-1 col-xs-1 col-sm-1 text-center"><strong>Rooftops</strong></th>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2 text-right"><strong>Totals</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($invoice['items'] as $item) { ?>
                                                                <tr>
                                                                    <td class=""><?php echo $item['item_name']; ?></td>
                                                                    <td class=""><?php echo $item['item_description']; ?></td>
                                                                    <td class="text-right">$ <?php echo number_format($item['unit_price'], 2, '.', ','); ?></td>
                                                                    <td class="text-center"><?php echo $item['number_of_rooftops']; ?></td>
                                                                    <td class="text-right">$ <?php echo number_format($item['quantity_total'], 2, '.', ','); ?></td>
                                                                </tr>

                                                                <?php $facebook_api_flag = false;
                                                                if (count($invoice['items']) == 1) {
                                                                    if ($item['includes_facebook_api'] == 1 && $item['item_name'] != 'Facebook Recruiting Application') {
                                                                        $facebook_api_flag = true;
                                                                    }
                                                                } ?>

                                                            <?php } ?>

                                                            <?php //if ($facebook_api_flag == true) { 
                                                            ?>
                                                            <!--  <tr>
                                                                    <td class="">Facebook API</td>
                                                                    <td class="">Fully Featured Facebook Recruiting Application</td>
                                                                    <td class="text-right">$ <?php //echo number_format(399, 2, '.', ','); 
                                                                                                ?></td>
                                                                    <td class="text-center"></td>
                                                                    <td class="text-right">$ <?php //echo number_format(0, 2, '.', ','); 
                                                                                                ?></td>
                                                                </tr> -->
                                                            <?php //} 
                                                            ?>
                                                            <tr>
                                                                <td class="no-border"></td>
                                                                <td class="no-border"></td>
                                                                <td class="thick-line text-right" colspan="2"><strong>Subtotal</strong></td>
                                                                <td class="thick-line text-right">$ <?php echo number_format($invoice['value'], 2, '.', ',') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="no-border"></td>
                                                                <td class="no-border"></td>
                                                                <td class="text-right" colspan="2"><strong>Discount Amount</strong></td>
                                                                <td class="text-right">
                                                                    <?php if ($invoice['is_discounted'] == 1) { ?>
                                                                        $ <?php echo number_format($invoice['discount_amount'], 2, '.', ',') ?>
                                                                    <?php } else { ?>
                                                                        $ <?php echo number_format(0, 2, '.', ',') ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="no-border"></td>
                                                                <td class="no-border"></td>
                                                                <td class="text-right" colspan="2"><strong>Total</strong></td>
                                                                <td class="text-right">
                                                                    <?php if ($invoice['is_discounted'] == 1) { ?>
                                                                        $ <?php echo number_format($invoice['total_after_discount'], 2, '.', ',') ?>
                                                                    <?php } else { ?>
                                                                        $ <?php echo number_format($invoice['value'], 2, '.', ',') ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""><strong>US Dollars</strong></td>
                                                                <td class="" colspan="4">
                                                                    <?php echo ucwords(convert_number_to_words($invoice['total_after_discount'])); ?> Only.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="hr-box-header hr-box-footer"></div>
                                                <div class="panel panel-default description-panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <form enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="update_invoice_description" />

                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <label for="invoice_description">Invoice Notes</label>
                                                                        </div>
                                                                        <div class="col-xs-8">
                                                                            <textarea id="invoice_description" name="invoice_description" class="invoice-fields field-row-autoheight" rows="3"><?php echo $invoice['invoice_description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <label></label>
                                                                        </div>
                                                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                                            <button style="margin-top:15px; float:right;" type="submit" class="site-btn lineheight">Update Note</button>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default description-panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <form enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="update_company_notes" />

                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <label for="invoice_description">Invoice Notes For Company</label>
                                                                        </div>
                                                                        <div class="col-xs-8">
                                                                            <textarea id="company_notes" name="company_notes" class="invoice-fields field-row-autoheight" rows="3"><?php echo $invoice['company_notes']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <label></label>
                                                                        </div>
                                                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                                            <button style="margin-top:15px; float:right;" type="submit" class="site-btn lineheight">Update Note</button>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="hr-box-header">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <h3 class="panel-title"><strong>Refund Notes Listing</strong></h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive table-outer">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2"><strong>Invoice Type</strong></th>
                                                                <th class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice Notes</strong></th>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2"><strong>Credit Amount</strong></th>
                                                                <th class="col-lg-2 col-md-2 col-xs-2 col-sm-2"><strong>Credit/Refund Date</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (sizeof($notes) > 0) {
                                                                foreach ($notes as $note) {
                                                            ?>
                                                                    <tr>
                                                                        <td class=""><strong><?php echo $note['invoice_type']; ?></strong></td>
                                                                        <td class=""><strong><?php echo $note['notes']; ?></strong></td>
                                                                        <td class=""><strong><?php echo $note['credit_amount']; ?></strong></td>
                                                                        <td class=""><strong><?php echo date('m-d-Y', strtotime($note['refund_date'])); ?></strong></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo '<tr><td class="col-xs-12">No Notes Found</td></tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="hr-box-header hr-box-footer"></div>
                                                <?php if ($invoice['payment_status'] != 'unpaid') { ?>
                                                    <div class="hr-box">
                                                        <div class="hr-box-header bg-header-green">
                                                            <span class="hr-registered">Refund Notes</span>
                                                        </div>
                                                        <div class="hr-box-body hr-innerpadding">
                                                            <div class="hidden-xs">
                                                                <hr>
                                                                <form enctype="multipart/form-data" autocomplete="off" method="post" action="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="Add_refund_note" />
                                                                    <div class="row">
                                                                        <div style="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="row">
                                                                                <div style="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                    <div>
                                                                                        <label for="refund_notes">New Refund Note</label>
                                                                                        <div class="hr-fields-wrap">
                                                                                            <textarea id="refund_notes" name="refund_notes" class="invoice-fields field-row-autoheight" rows="3" required="required"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div>
                                                                                        <label for="rfd_date">Refund Date</label>
                                                                                        <input class="invoice-fields" type="text" name="rfd_date" id="rfd_date" value="<?php echo set_value('rfd_date'); ?>" required="required" />
                                                                                    </div>
                                                                                </div>
                                                                                <div style="" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div>
                                                                                        <label for="cr_amnt">Refund Amount</label>
                                                                                        <input id="cr_amnt" required="required" name="cr_amnt" placeholder="Only digits are allowed" class="invoice-fields" type="number" min="1" step=".01">

                                                                                    </div>
                                                                                </div>
                                                                                <div style="" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div>
                                                                                        <label></label>
                                                                                        <button style="margin-top:15px; float:right;" type="submit" class="site-btn lineheight">Add Refund</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <hr>
                                                            </div>
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
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var dateToday = new Date();
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
        $('#rfd_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
            //            minDate: dateToday,
        });
        $('#invoice_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

    function fEmailInvoiceToClient(invoice_sid, email_address) {
        alertify.prompt(
            'Are you sure?',
            'Are you sure you want to forward this invoice to Client? <br /> Client Email Address',
            email_address,
            function(event, value) {
                var myUrl = '<?php echo base_url('manage_admin/invoice/ajax_responder') ?>';

                var myRequest;

                myRequest = $.ajax({
                    url: myUrl,
                    type: 'post',
                    data: {
                        perform_action: 'send_invoice_to_client_by_email',
                        invoice_sid: invoice_sid,
                        email_address: value
                    }
                });

                myRequest.done(function(response) {
                    console.log(response);

                    if (response == 'success') {
                        alertify.success('Successfully Forwarded Invoice!');
                    }

                });
            },
            function() {

            });
    }

    function fUpdateInvoiceStatus() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to update Invoice Status?',
            function() {
                $('#update_invoice_status').submit();
            },
            function() {

            });
    }
</script>