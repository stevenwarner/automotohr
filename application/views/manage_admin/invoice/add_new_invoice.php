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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <!-- Edit Invoice Start -->
                                    <div class="hr-edit-invoice">
                                        <form method="post" action="" >
                                            <ul class="invoice-form">
                                                <li>
                                                    <input type="radio" name="send_invoice" value="to_employer" id="to_employer" checked>&nbsp;To a Employer
                                                    &nbsp;&nbsp;
                                                    <input type="radio" name="send_invoice" value="to_email" id="to_email">&nbsp;Email Invoice <br/><br/>      
                                                </li>
                                                <li>
                                                    <label>Invoice Status:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="status">
                                                                <option value="">Select Invoice Status</option>
                                                                <option value="Paid">Paid</option>
                                                                <option value="Unpaid">Unpaid</option>
                                                                <option value="Pending">Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li id="to_employer_div">
                                                    <label>Customer: 
                                                        <span class="hr-required">*</span>
                                                    </label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="emp_name" name="user_sid">
                                                                <option value="">Select Employer Name</option>
                                                                <?php foreach ($employers as $employer) { ?>
                                                                    <option value="<?= $employer['sid'] ?>"><?= $employer['username'] ?> (<?= $employer['email'] ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?= form_error('user_sid') ?>
                                                    </div>
                                                    <!--<input type="hidden" name="company_sid" value="<?= $employer['parent_sid'] ?>">-->
                                                </li>
                                                <li id="to_name_div" style="display: none">
                                                    <label>Receiver Name:
                                                        <span class="hr-required">*</span></label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-date">
                                                            <input type="text" value="<?= set_value('to_name') ?>" name="to_name" id="to_name"  class="invoice-fields" >
                                                        </div>
                                                    </div>
                                                </li>
                                                <li id="to_email_div" style="display: none">
                                                    <label>Receiver Email:
                                                        <span class="hr-required">*</span></label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-date">
                                                            <input type="text" value="<?= set_value('email') ?>" name="email" id="email" class="invoice-fields" >
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Invoice Date:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-date">
                                                            <input type="text" readonly="" value="<?= set_value('date') ?>" name="date" class="invoice-fields" id="startdate">
                                                            <button type="button" class="ui-datepicker-trigger"><i class="fa fa-calendar"></i></button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Payment Method:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="payment_method">
                                                                <option value="">Select Payment method</option>
                                                                <option value="Invoice_billing">Invoice billing</option>
                                                                <option value="Paypal">Paypal</option>
                                                                <option value="Free_checkout">Free Checkout</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="tax-fied">
                                                    <label>Include Tax</label>
                                                    <div class="invoice-field-wrap">
                                                        <input type="checkbox" name="include_tax" value="1">
                                                    </div>
                                                </li>

                                            </ul>
                                            <div class="hr-complex" id="product_container">
                                                <ul>  
                                                    <li style="width:100%">
                                                        <label>Invoice Description:</label>
                                                        <textarea style="padding:10px; height:150px; " class="hr-form-fileds" name="description"></textarea>
                                                    </li>
                                                </ul>
                                                <ul id="item-0">

                                                    <li>
                                                        <label>Item
                                                            <span class="hr-required">*</span>
                                                        </label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" required="required" name="product_sid[]" onchange="generatePrice(this.value, 0)">
                                                                <option value="">Please select product</option>
                                                                <?php foreach ($products as $product) { ?>
                                                                    <option value="<?= $product->sid ?>"><?= $product->name ?></option>
                                                                <?php } ?>
                                                                <option value="custom_0">Custom product</option>
                                                            </select>
                                                        </div>
                                                        <input class="invoice-fields" name="custom_text[]" type="text" id="custom_text_0" value="" style="width: 60%; margin-top:15px; display: none " >
                                                    </li>
                                                    <li>
                                                        <label>Qty</label>
                                                        <input class="invoice-fields"  type="text" name="item_qty[]" id="item-qty-0" value="0" style="width: 70px; " onchange="customAmount(this.id);" readonly="">
                                                        <input type="hidden" name="item_remaining_qty[]" id="remaining-qty-0" value="0">
                                                        <input type="hidden" name="flag[]" id="item-flag-0" value="editable">
                                                        <input type="hidden" name="no_of_days[]" id="no-of-days-0" value="0">
                                                    </li>
                                                    <li>
                                                        <label>Price</label>
                                                        <input class="invoice-fields " type="text" name="item_price[]" id="item-price-0" style="width: 90px; " onchange="customAmount(this.id);" value="0.00" readonly="">
                                                    </li>
                                                    <li>
                                                        <label>Amount</label>
                                                        <input class="invoice-fields values_to_add" type="text" id="item-amount-0" style="width: 90px; " value="0.00" readonly="">
                                                        <!--<a class="hr-item-delete" href="javascript:;"><i class="fa fa-times"></i>delete</a>-->
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="invoice-bottom">
                                                <div class="hr-add-new-item"><a href="javascript:;" onclick="addRow();"><i class="fa fa-plus"></i>Add New Item</a></div>

                                                <ul>
                                                    <li>
                                                        <input type="text" value="0.00" id="sub_total" name="sub_total" class="invoice-fields" readonly="">
                                                        <label>sub total</label>
                                                    </li>
                                                    <li>
                                                        <input type="text" value="0.00" id="total_discount" name="total_discount" class="invoice-fields" readonly="">
                                                        <label>Coupon Discount</label>
                                                    </li>
                                                    <li>
                                                        <input type="text" value="0.00" id="total" name="total"  class="invoice-fields" readonly="">
                                                        <label>total</label>
                                                    </li>
                                                    <li class="btns-row">
                                                        <!--<a href="" class="site-btn">Send Invoice to Customer</a> 
                                                        <a href="" class="site-btn">Download PDF Version</a>
                                                        <a href="" class="site-btn">Print Invoice</a> -->
                                                    </li>
                                                    <li class="btns-row">
                                                        <!--<input type="submit" class="site-btn" value="Apply">-->
                                                        <input type="submit" class="site-btn" value="Save">
                                                    </li>
                                                </ul>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Edit Invoice End -->
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
    $("#emp_name").prop('required', true);
    $('input[name="send_invoice"]').change(function (e) {
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
    var idItem = 0;
    function addRow() {
        idItem++;
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
                "<input type='hidden' name='item_remaining_qty[]' id='remaining-qty-" + idItem + "' value=''>" +
                "<input type='hidden' name='flag[]' id='item-flag-" + idItem + "' value='editable'>" +
                "<input type='hidden' name='no_of_days[]' id='no-of-days-" + idItem + "' value=''>" +
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
                "</ul>");
        $("#" + idItem).prop('required', true);

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
        $("#remaining-qty-" + id).val(qty);
        price = $("#item-price-" + id).val();

        //amount
        amount = qty * price;
        amount = parseFloat(amount).toFixed(2);

        $("#item-amount-" + id).val(amount);
        calculateTotalPrice();

    }

    function generatePrice(product_id, item_id) {
        var res = product_id.split("_");
        if (res[0] == "custom") {
            $("#custom_text_" + res[1]).css({'display': 'block', 'background-color': '#fff'});

            $("#item-amount-" + res[1]).val("0.00");

            $("#item-qty-" + res[1]).val("");
            $("#item-qty-" + res[1]).css({'display': 'block', 'background-color': '#fff'});
            $("#item-qty-" + res[1]).prop({'type': 'number', 'min': '1', 'readonly': false});

            $("#item-price-" + res[1]).val("");
            $("#item-price-" + res[1]).css({'display': 'block', 'background-color': '#fff'});
            $("#item-price-" + res[1]).prop({'type': 'number', 'min': '1', 'step': 'any', 'readonly': false});
        }
        else {
            $("#custom_text_" + item_id).css('display', 'none');

            $("#item-qty-" + item_id).prop({'readonly': true, 'type': 'text'});
            $("#item-price-" + item_id).prop({'readonly': true, 'type': 'text'});
            $("#item-qty-" + item_id).css('background-color', '#eee');
            $("#item-price-" + item_id).css('background-color', '#eee');
<?php foreach ($products as $product) { ?>

                if (product_id == <?= $product->sid ?>) {

                    $("#item-qty-" + item_id).val("<?= $product->number_of_postings ?>");
                    $("#remaining-qty-" + item_id).val("<?= $product->number_of_postings ?>");
                    $("#no-of-days-" + item_id).val("<?= $product->expiry_days ?>");
                    $("#item-price-" + item_id).val("<?= $product->price ?>");
                    $("#item-amount-" + item_id).val("<?= $product->price ?>");
                }
                else if (product_id == "")
                {
                    $("#item-qty-" + item_id).val("0");
                    $("#remaining-qty-" + item_id).val("0");
                    $("#no-of-days-" + item_id).val("0");
                    $("#item-price-" + item_id).val("0.00");
                    $("#item-amount-" + item_id).val("0.00");
                }
<?php } ?>
            calculateTotalPrice();
        }
    }

    function calculateTotalPrice() {
        var total_amount = parseFloat(0);
        $(".values_to_add").each(function () {
            total_amount = total_amount + parseFloat($(this).val());
        });
        $("#sub_total").val(total_amount.toFixed(2));
        $("#total").val(total_amount.toFixed(2));
    }
</script>
