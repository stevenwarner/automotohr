<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/invoice') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $invoiceData['company_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <!-- Edit Invoice Start -->
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="hr-edit-invoice" id="mydiv">
                                                <form autocomplete="off" method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                    <label class="control control--radio">
                                                                        To a Employer
                                                                        <input type="radio" name="send_invoice" <?php if ($invoiceData['user_sid'] != NULL) { ?> checked <?php } ?> value="to_employer" id="to_employer">&nbsp;
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                    <label class="control control--radio">
                                                                        Email Invoice
                                                                        <input type="radio" name="send_invoice" <?php if ($invoiceData['to_email'] != NULL) { ?> checked <?php } ?> value="to_email" id="to_email">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <?php $readonly = !empty($invoiceData['payment_method']) ? 'readonly="readonly"' : ''; ?>
                                                            <div class="row">
                                                                <div id="to_employer_div" <?php echo $invoiceData['user_sid'] == NULL ? 'style="display: none"' : ''; ?> class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                    <div>
                                                                        <label>Customer: <span class="hr-required">*</span></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" id="emp_name" name="user_sid">
                                                                                <option <?php if ($invoiceData['user_sid'] == NULL) { ?>selected="selected" <?php } ?> value="">Select Employer Name</option>
                                                                                <?php foreach ($employers as $employer) { ?>
                                                                                    <option <?php if ($invoiceData['user_sid'] == $employer['sid']) { ?>selected="selected" <?php } ?> value="<?= $employer['sid'] ?>"><?= ucwords($employer['username']) ?> (<?= strtolower($employer['email']) ?>)</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>

                                                                        <?= form_error('user_sid') ?>
                                                                    </div>
                                                                </div>
                                                                <div id="to_name_div" <?php echo $invoiceData['to_email'] == NULL ? 'style="display: none"' : ''; ?> class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                    <div>
                                                                        <label>Receiver Name: <span class="hr-required">*</span></label>
                                                                        <input type="text" value="<?= set_value('to_name', $invoiceData['to_name']) ?>" name="to_name" id="to_name" class="invoice-fields">
                                                                    </div>
                                                                </div>
                                                                <div id="to_email_div" <?php echo $invoiceData['to_email'] == NULL ? 'style="display: none"' : ''; ?> class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                    <div>
                                                                        <label>Receiver Email: <span class="hr-required">*</span></label>
                                                                        <input type="email" value="<?= set_value('email', $invoiceData['to_email']) ?>" name="email" id="email" class="invoice-fields">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div class="">
                                                                        <label>Credit Card Number</label>
                                                                        <div><?php echo !is_null($invoiceData['credit_card_number']) && !empty($invoiceData['credit_card_number']) ? $invoiceData['credit_card_number'] : 'N/A'; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div class="">
                                                                        <label>Credit Card Type</label>
                                                                        <div><?php echo !is_null($invoiceData['credit_card_type']) && !empty($invoiceData['credit_card_type']) ? ucwords($invoiceData['credit_card_type']) : 'N/A'; ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div>
                                                                        <label>Invoice Date:</label>
                                                                        <div class="hr-invoice-date">
                                                                            <input type="text" readonly="" value="<?= set_value('date', $invoiceData['date']) ?>" name="date" class="invoice-fields" id="startdate">
                                                                            <button type="button" class="ui-datepicker-trigger"><i class="fa fa-calendar"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if (in_array('full_access', $security_details) || in_array('mark_paid_unpaid', $security_details)) { ?>
                                                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                        <div class="">
                                                                            <label>Invoice Status:</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="status">
                                                                                    <option value="">Select Invoice Status</option>
                                                                                    <option <?php if ($invoiceData['status'] == "Paid") { ?>selected="selected" <?php } ?>>Paid</option>
                                                                                    <option <?php if ($invoiceData['status'] == "Unpaid") { ?>selected="selected" <?php } ?>>Unpaid</option>
                                                                                    <option <?php if ($invoiceData['status'] == "Pending") { ?>selected="selected" <?php } ?>>Pending</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <input type="hidden" name="status" value="<?php echo $invoiceData['status']; ?>">
                                                                <?php } ?>
                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <div>
                                                                        <label>Payment Method:</label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="payment_method">
                                                                                <option <?php if ($invoiceData['payment_method'] == "") { ?>selected="selected" <?php } ?> value="">Select Payment method</option>
                                                                                <option <?php if ($invoiceData['payment_method'] == "Invoice_billing") { ?>selected="selected" <?php } ?>>Invoice billing</option>
                                                                                <option <?php if ($invoiceData['payment_method'] == "Paypal") { ?>selected="selected" <?php } ?>>Paypal</option>
                                                                                <option <?php if ($invoiceData['payment_method'] == "Free_checkout") { ?>selected="selected" <?php } ?>>Free Checkout</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                    <label class="">&nbsp;</label>
                                                                    <label class="control control--checkbox">
                                                                        Include Tax
                                                                        <input type="checkbox" <?php if ($invoiceData['include_tax'] == 1) { ?>checked <?php } ?> name="include_tax" value="1">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />

                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="hr-complex" id="product_container">
                                                                <?php $counter = 0; ?>
                                                                <?php $serialized_products = $invoiceData['serialized_products']; ?>
                                                                <?php for ($i = 0; $i < count($serialized_products['custom_text']); $i++) { ?>
                                                                    <?php if ($serialized_products['custom_text'][$i] != '') {
                                                                        continue;
                                                                    } ?>
                                                                    <ul id="item-<?= $counter ?>">
                                                                        <li>
                                                                            <label>Item
                                                                                <span class="hr-required">*</span>
                                                                            </label>
                                                                            <div class="hr-select-dropdown">
                                                                                <?php if ($serialized_products['flag'][$i] == 'no_edit') { ?>
                                                                                    <select class="invoice-fields" required="required" name="product_sid[]">
                                                                                        <option value="<?php echo $serialized_products['products'][$i]; ?>"><?php echo db_get_product_name($serialized_products['products'][$i]); ?></option>
                                                                                    </select>
                                                                                <?php } else { ?>
                                                                                    <select class="invoice-fields" required="required" name="product_sid[]" onchange="generatePrice(this.value, <?= $counter ?>)">
                                                                                        <option value="">Please select product</option>
                                                                                        <?php foreach ($products as $product) { ?>
                                                                                            <option <?php if ($serialized_products['products'][$i] == $product->sid) { ?> selected="selected" <?php } ?> value="<?= $product->sid ?>"><?= $product->name ?></option>
                                                                                        <?php } ?>
                                                                                        <option <?php if ($serialized_products['products'][$i][0] == 'c') { ?> selected="selected" <?php } ?> value="custom_<?= $counter ?>">Custom product</option>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <input class="invoice-fields" name="custom_text[]" type="text" id="custom_text_<?= $counter ?>" value="" style="width: 60%; margin-top:15px; display: none ">
                                                                        </li>
                                                                        <li>
                                                                            <label>Qty</label>
                                                                            <input class="invoice-fields hassan" type="text" name="item_qty[]" id="item-qty-<?= $counter ?>" value="<?php echo $serialized_products['item_qty'][$i]; ?>" style="width: 70px; " onchange="customAmount(this.id);" readonly="">
                                                                        </li>
                                                                        <li>
                                                                            <label>Price</label>
                                                                            <input class="invoice-fields " type="text" name="item_price[]" id="item-price-<?= $counter ?>" style="width: 90px;" value="<?php echo $serialized_products['item_price'][$i]; ?>" onchange="customAmount(this.id);" readonly="" step="any">
                                                                        </li>
                                                                        <li>
                                                                            <label>Amount</label>
                                                                            <input class="invoice-fields values_to_add" type="text" id="item-amount-<?= $counter ?>" style="width: 90px;" value="<?php echo $serialized_products['item_price'][$i]; ?>" readonly="">
                                                                            <?php if ($counter != 0) { ?>
                                                                                <?php if ($serialized_products['flag'][$i] == 'editable') { ?>
                                                                                    <a class='hr-item-delete' id='<?= $counter ?>' href='javascript:;' onclick='deleteItem(this.id)'><i class='fa fa-times'></i></a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </li>
                                                                        <input type="hidden" name="item_remaining_qty[]" id="remaining-qty-<?= $counter ?>" value="<?php echo $serialized_products['item_remaining_qty'][$i]; ?>">
                                                                        <input type="hidden" name="flag[]" id="flag-<?= $counter ?>" value="<?php echo $serialized_products['flag'][$i]; ?>">
                                                                        <input type="hidden" name="no_of_days[]" id="no-of-days-<?= $counter ?>" value="<?php echo $serialized_products['no_of_days'][$i]; ?>">
                                                                    </ul>
                                                                    <?php $counter = $counter + 1; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="hr-add-new-item"><a href="javascript:;" onclick="addRow();"><i class="fa fa-plus"></i>Add New Item</a></div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="">
                                                                <label>Invoice Description:</label>
                                                                <textarea style="padding:10px; height:150px; " class="hr-form-fileds" name="invoice_description"><?php echo (isset($invoiceData['invoice_description']) ? $invoiceData['invoice_description'] : ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                            <div class="invoice-bottom">
                                                                <ul>
                                                                    <li class="sub_total_html">
                                                                        <input type="text" value="0.00" id="sub_total" name="sub_total" class="invoice-fields" readonly="">
                                                                        <label>sub total</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="text" value="<?php echo $invoiceData['total_discount']; ?>" id="total_discount" name="total_discount" class="invoice-fields" readonly="">
                                                                        <label>Coupon Discount</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="text" value="<?php if (isset($serialized_products['special_discount'])) {
                                                                                                        echo $serialized_products['special_discount'];
                                                                                                    } else {
                                                                                                        echo '0.00';
                                                                                                    } ?>" id="special_discount" class="invoice-fields" readonly="">
                                                                        <label>Special Discount</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="text" value="0.00" id="total" name="total" class="invoice-fields" readonly="">
                                                                        <label>total</label>
                                                                    </li>
                                                                    <li class="btns-row">
                                                                        <!--<a href="" class="site-btn">Send Invoice to Customer</a>
                                                                        <a href="" class="site-btn">Download PDF Version</a>
                                                                        <a href="" class="site-btn">Print Invoice</a> -->
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                                            <input type="submit" class="btn btn-success btn-block" name="action" value="Send">
                                                        </div>
                                                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                                            <input type="submit" class="btn btn-success btn-block" name="action" value="Save">
                                                        </div>
                                                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                                            <input type="button" class="btn btn-success btn-block" onclick="PrintElem('#mydiv')" value="Print">
                                                        </div>
                                                    </div>
                                                    <hr />

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Invoice End -->

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered">Refund Notes</span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="hidden-xs">
                                                <hr>
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
                                                            <?php if (sizeof($notes) > 0) {
                                                                foreach ($notes as $note) {
                                                            ?>
                                                                    <tr>
                                                                        <td class=""><strong><?php echo $note['invoice_type']; ?></strong></td>
                                                                        <td class=""><strong><?php echo $note['notes']; ?></strong></td>
                                                                        <td class=""><strong><?php echo $note['credit_amount']; ?></strong></td>
                                                                        <td class=""><strong><?php echo date_with_time($note['refund_date']); ?></strong></td>
                                                                    </tr>
                                                            <?php }
                                                            } else {
                                                                echo '<tr><td>No Notes Found</td></tr>';
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="hr-box-header hr-box-footer"></div>
                                                <div class="hr-user-form">
                                                    <form autocomplete="off" method="post" action="<?php echo base_url('manage_admin/invoice/edit_invoice') . '/' . $invoice_id; ?>">
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
                                                                            <input id="cr_amnt" required="required" name="cr_amnt" placeholder="Only digits are allowed" class="invoice-fields" type="number" min="1">

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
                                                </div>
                                                <hr>
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
    });
    ////////////Populate Previous Data////////////////
    <?php for ($i = 0; $i < $counter; $i++) { ?>
        console.log(<?php echo $i; ?>);
        if ("<?= $invoiceData["product_sid"][$i][0] ?>" != 'c' && "<?= $serialized_products['flag'][$i] ?>" != 'no_edit') {
            generatePrice("<?= $invoiceData["product_sid"][$i] ?>", "<?= $i ?>");
        } else {
            if ("<?= $invoiceData["product_sid"][$i][0] ?>" == 'c') {
                var custom_data_array = JSON.parse('<?= $invoiceData['serialized_items_info'] ?>');
                console.log(custom_data_array);
                product_name = custom_data_array.custom_text[<?= $i ?>];
                product_qty = custom_data_array.item_qty[<?= $i ?>];
                product_price = custom_data_array.item_price[<?= $i ?>];
                $("#custom_text_" + <?= $i ?>).css({
                    'display': 'block',
                    'background-color': '#fff'
                });
                $("#custom_text_" + <?= $i ?>).attr('value', product_name);
                $("#item-amount-" + <?= $i ?>).attr('value', product_qty * product_price);
                $("#item-qty-" + <?= $i ?>).attr('value', product_qty);
                $("#item-qty-" + <?= $i ?>).css({
                    'display': 'block',
                    'background-color': '#fff'
                });
                $("#item-qty-" + <?= $i ?>).prop({
                    'type': 'number',
                    'min': '0',
                    'readonly': false
                });
                $("#item-price-" + <?= $i ?>).attr('value', product_price);
                $("#item-price-" + <?= $i ?>).css({
                    'display': 'block',
                    'background-color': '#fff'
                });
                $("#item-price-" + <?= $i ?>).prop({
                    'type': 'number',
                    'min': '0',
                    'readonly': false
                });
            }
        }
    <?php } ?>
    calculateTotalPrice();
    /////////////////////////////////////////////////
    <?php if ($invoiceData['to_email'] == NULL) { ?>
        $("#emp_name").prop('required', true);
        $("#email").prop('required', false);
        $("#to_name").prop('required', false);
    <?php } else { ?>
        $("#emp_name").prop('required', false);
        $("#email").prop('required', true);
        $("#to_name").prop('required', true);
    <?php } ?>

    $('input[name="send_invoice"]').change(function(e) {
        var div_to_show = $(this).val();
        if (div_to_show == 'to_employer') {
            $('#to_employer_div').show();
            $("#emp_name").prop('required', true);
            $("#email").prop('required', false);
            $("#to_name").prop('required', false);
            $('#to_email_div').hide();
            $('#to_name_div').hide();
        } else {
            $('#to_employer_div').hide();
            $('#to_email_div').show();
            $('#to_name_div').show();
            $("#emp_name").prop('required', false);
            $("#email").prop('required', true);
            $("#to_name").prop('required', true);
        }
    });
    var idItem = parseInt(<?= $counter ?>);

    function addRow() {
        $('#product_container').append("<ul id='item-" + idItem + "'>" +
            "<li>" +
            "<label>Item</label>" +
            "<div class='hr-select-dropdown'>" +
            "<select class='invoice-fields' id='" + idItem + "' name='product_sid[]' onchange='generatePrice(this.value,this.id)'>" +
            "<option value=''>Please select product</option>" +
            "<?php foreach ($products as $product) { ?>" +
            "<option value='<?= $product->sid ?>'><?= $product->name ?></option>" +
            "<?php } ?>" +
            "<option value='custom_" + idItem + "'>Custom product</option>" +
            "</select>" +
            "</div>" +
            "<input class='invoice-fields'  name='custom_text[]'  type='text' id='custom_text_" + idItem + "' value='' style='width: 60%; margin-top:15px; display: none ' >" +
            "</li>" +
            "<li>" +
            "<label>Qty</label>" +
            "<input class='invoice-fields' name='item_qty[]'  id='item-qty-" + idItem + "' value='0'  type='text' style='width: 70px;'  onchange='customAmount(this.id);'  readonly=''>" +
            "</li>" +
            "<li>" +
            "<label>Price</label>" +
            "<input class='invoice-fields' name='item_price[]' id='item-price-" + idItem + "' type='text' style='width: 90px; ' value='0.00'  onchange='customAmount(this.id);'  readonly=''>" +
            "</li>" +
            "<li>" +
            "<label>Amount</label>" +
            "<input class='invoice-fields  values_to_add' id='item-amount-" + idItem + "' type='text' style='width: 90px; ' value='0.00' readonly=''>" +
            "<a class='hr-item-delete' id='" + idItem + "' href='javascript:;' onclick='deleteItem(this.id)'><i class='fa fa-times'></i></a>" +
            "</li>" +
            "<input type='hidden' name='item_remaining_qty[]' id='remaining-qty-" + idItem + "' value='0'>" +
            "<input type='hidden' name='flag[]' id='item-flag-" + idItem + "' value='editable'>" +
            "<input type='hidden' name='no_of_days[]' id='no-of-days-" + idItem + "' value=''>" +
            "</ul>");
        $("#" + idItem).prop('required', true);
        idItem++;
    }

    function deleteItem(id) {
        $("#item-" + id).remove();
        calculateTotalPrice();
    }

    function customAmount(element_id) {
        //getting element is by split
        var data = element_id.split("-");
        id = data[2];
        //now multiply qty * price to get the amount
        qty = $("#item-qty-" + id).val();
        $("#remaining-qty-" + id).attr('value', qty);
        price = $("#item-price-" + id).val();
        //amount
        amount = qty * price;
        amount = parseFloat(amount).toFixed(2);
        $("#item-amount-" + id).attr('value', amount);
        calculateTotalPrice();
    }

    function generatePrice(product_id, item_id) {
        console.log(product_id + ' ' + item_id);
        var res = product_id.split("_");
        if (res[0] == "custom") {
            $("#custom_text_" + res[1]).css({
                'display': 'block',
                'background-color': '#fff'
            });
            $("#item-amount-" + res[1]).attr('value', "0.00");
            $("#item-qty-" + res[1]).attr('value', "");
            $("#item-qty-" + res[1]).css({
                'display': 'block',
                'background-color': '#fff'
            });
            $("#item-qty-" + res[1]).prop({
                'type': 'number',
                'min': '0',
                'readonly': false
            });
            $("#item-price-" + res[1]).attr('value', "");
            $("#item-price-" + res[1]).css({
                'display': 'block',
                'background-color': '#fff'
            });
            $("#item-price-" + res[1]).prop({
                'type': 'number',
                'step': 'any',
                'min': '0',
                'readonly': false
            });
        } else {
            $("#custom_text_" + item_id).css('display', 'none');
            $("#item-qty-" + item_id).prop({
                'readonly': true,
                'type': 'text'
            });
            $("#item-price-" + item_id).prop({
                'readonly': true,
                'type': 'text'
            });
            $("#item-qty-" + item_id).css('background-color', '#eee');
            $("#item-price-" + item_id).css('background-color', '#eee');
            <?php foreach ($products as $product) { ?>
                if (product_id == <?= $product->sid ?>) {
                    $("#item-qty-" + item_id).attr('value', "<?= $product->number_of_postings ?>");
                    $("#remaining-qty-" + item_id).attr('value', "<?= $product->number_of_postings ?>");
                    $("#no-of-days-" + item_id).val("<?= $product->expiry_days ?>");
                    $("#item-price-" + item_id).attr('value', "<?= $product->price ?>");
                    $("#item-amount-" + item_id).attr('value', "<?= $product->price ?>");
                } else if (product_id == "") {
                    $("#item-qty-" + item_id).attr('value', "0");
                    $("#item-price-" + item_id).attr('value', "0.00");
                    $("#remaining-qty-" + item_id).attr('value', "0");
                    $("#no-of-days-" + item_id).val("0");
                    $("#item-amount-" + item_id).attr('value', "0.00");
                }
            <?php } ?>
            calculateTotalPrice();
        }
    }

    function calculateTotalPrice() {
        total_amount = parseFloat(0);
        $(".values_to_add").each(function() {
            total_amount += parseFloat($(this).val());
        });
        //                                                               
        $("#sub_total").attr('value', total_amount.toFixed(2));
        var total_discount = $("#total_discount").val();
        var special_discount = $("#special_discount").val();
        console.log(total_discount);
        var final_total = total_amount - total_discount - special_discount;
        if (final_total > 0) {
            $("#total").attr('value', final_total.toFixed(2));
        } else {
            $("#total").attr('value', '0.00');
        }

    }


    function PrintElem(elem) {
        $(".hr-item-delete").hide();
        $(".hr-add-new-item").hide();
        $(".bottom-buttons").hide();
        Popup($(elem).html());
        $(".hr-item-delete").show();
        $(".hr-add-new-item").show();
        $(".bottom-buttons").show();
    }

    function Popup(data) {
        var mywindow = window.open('', 'Print Invoice', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Invoice # <?php echo $invoiceData['sid'] ?></title>');
        /*optional stylesheet*/
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10


        return true;
    }
</script>