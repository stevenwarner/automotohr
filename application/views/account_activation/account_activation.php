<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery.datetimepicker.css" />
        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/public-form-style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">
        <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
        <script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/functions.js"></script>
    </head>

    <body>
        <!-- Wrapper Start -->
        <!-- Main Start -->
        <div class="main-content">
            <div class="container">
                <!-- Header Start -->
                <header class="header header-position">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                                <h2 style="color: #fff; text-align: center;"><?php echo $title; ?></h2>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Header End -->
                <div class="row">					
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                        <div class="pricing-box-wrp">
                            <div class="pricing-header bg-green">
                                <h2>Standard</h2>
                                <p>HR and Hiring Platform</p>
                            </div>
                            <div class="box-body">
                                <div class="price-box-main">
                                    <?php foreach ($standard_products as $product) { ?>
                                        <input type="hidden" value="<?php echo $product['special_discount']; ?>" id="special_discount_<?php echo $product['sid']; ?>" >
                                        <div class="price-inner" >
                                            <div class="price-amount">
                                                <div class="old-price" id="old_product_price_<?php echo $product['sid']; ?>"><span>$</span> <?php echo $product['price']; ?></div>
                                                <div class="new-price text-line" id="new_product_div_<?php echo $product['sid']; ?>">
                                                    <?php
                                                    if ($product['expiry_days'] == 365) {
                                                        $total_price = 0;
                                                        foreach ($standard_products as $products) {
                                                            if ($products['expiry_days'] == 30) {
                                                                $total_price = $products['price'] * 12;
                                                            }
                                                        }
                                                        ?>
                                                        <span>$</span><span id="new_product_price_<?php echo $product['sid']; ?>" ><?php echo $total_price; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <span class="subscription-time"><?php if ($product['expiry_days'] == 30) { ?>
                                                    /per month per rooftop
                                                <?php } elseif ($product['expiry_days'] == 365) { ?>
                                                    /per year per rooftop
                                                <?php } ?>
                                            </span>
                                            <div class = "container-buybtn">
                                                <ul>
                                                    <li>
                                                        <input type="hidden" id="product_price_<?php echo $product['sid']; ?>" value="<?php echo $product['price']; ?>">
                                                        <input type = "radio" class="product_radio_button"  name="product" onclick="calculateTotal(this.id)"  id="<?php echo $product['sid']; ?>"  value="<?php echo $product['sid']; ?>" required="">
                                                        <label for = "<?php echo $product['sid']; ?>">get now</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="key-features-area">
                                    <h2>Features</h2>
                                    <ul>
                                        <li><i class="fa fa-check-circle-o"></i>Fully Customize Themes</li>
                                        <li><i class="fa fa-check-circle-o"></i>Mobile job ad creation</li>
                                        <li><i class="fa fa-check-circle-o"></i>Job distribution</li>
                                        <li><i class="fa fa-check-circle-o"></i>Applicant tracking</li>
                                        <li><i class="fa fa-check-circle-o"></i>Interview scheduling</li>
                                        <li><i class="fa fa-check-circle-o"></i>Recruiter mobile app</li>
                                        <li><i class="fa fa-check-circle-o"></i>Access to vendor Marketplace</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                        <div class="pricing-box-wrp">
                            <div class="pricing-header bg-black">
                                <h2>Enterprise</h2>
                                <p>HR and Hiring Platform</p>
                            </div>
                            <div class="box-body">
                                <div class="price-box-main">
                                    <?php foreach ($enterprise_products as $product) { ?>
                                        <input type="hidden" value="<?php echo $product['special_discount']; ?>" id="special_discount_<?php echo $product['sid']; ?>" >
                                        <div class="price-inner">
                                            <div class="price-amount">
                                                <div class="old-price" id="old_product_price_<?php echo $product['sid']; ?>"><span>$</span> <?php echo $product['price']; ?></div>
                                                <div class="new-price text-line" id="new_product_div_<?php echo $product['sid']; ?>">
                                                    <?php
                                                    if ($product['expiry_days'] == 365) {
                                                        $total_price = 0;
                                                        foreach ($enterprise_products as $products) {
                                                            if ($products['expiry_days'] == 30) {
                                                                $total_price = $products['price'] * 12;
                                                            }
                                                        }
                                                        ?>
                                                        <span>$</span><span id="new_product_price_<?php echo $product['sid']; ?>" ><?php echo $total_price; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <span class="subscription-time"><?php if ($product['expiry_days'] == 30) { ?>
                                                    /per month per rooftop
                                                <?php } elseif ($product['expiry_days'] == 365) { ?>
                                                    /per year per rooftop
                                                <?php } ?>
                                            </span>
                                            <div class = "container-buybtn">
                                                <ul>
                                                    <li>
                                                        <input type="hidden" id="product_price_<?php echo $product['sid']; ?>" value="<?php echo $product['price']; ?>">
                                                        <input type = "radio" class="product_radio_button"  name="product" onclick="calculateTotal(this.id)"  id="<?php echo $product['sid']; ?>"  value="<?php echo $product['sid']; ?>" required="">
                                                        <label for = "<?php echo $product['sid']; ?>">get now</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="key-features-area">
                                    <h2>Features</h2>
                                    <ul>
                                        <li><i class="fa fa-check-circle-o"></i>Fully Customize Themes</li>
                                        <li><i class="fa fa-check-circle-o"></i>Mobile job ad creation</li>
                                        <li><i class="fa fa-check-circle-o"></i>Job distribution</li>
                                        <li><i class="fa fa-check-circle-o"></i>Applicant tracking</li>
                                        <li><i class="fa fa-check-circle-o"></i>Interview scheduling</li>
                                        <li><i class="fa fa-check-circle-o"></i>Recruiter mobile app</li>
                                        <li><i class="fa fa-check-circle-o"></i>Access to vendor Marketplace</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="main" style="margin-top: 50px;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="table-responsive table-outer">
                                            <div class="product-detail-area">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <td class="text-align">Price</td>
                                                            <td class="text-align">Sub Total</td>
                                                            <td class="text-align">Discount</td>
                                                            <?php if ($companyDetail['discount_amount'] > 0) { ?>
                                                                <td class="text-align">Special Discount</td>
                                                            <?php } ?>
                                                            <td class="text-align">Total</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-align">
                                                                <b id="checkout_title">
                                                                    Total
                                                                </b>
                                                            </td>
                                                            <td class="text-align"><span>$</span><p id="checkout_subtotal"><?php echo '0'; ?></p></td>
                                                            <td class="text-align"><span>$</span><p id="checkout_discount"><?php echo '0'; ?></p></td>
                                                            <?php if ($companyDetail['discount_amount'] > 0) { ?>
                                                                <td class="text-align"><span>$</span><p id="checkout_special_discount"><?php echo '0'; ?></p></td>
                                                            <?php } ?>
                                                            <td class="text-align"><span>$</span><p id="checkout_total"><?php echo '0'; ?></p></td>
                                                        </tr>
                                                    </tbody>

                                                </table>         
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="universal-form-style-v2 payment-area container"  id="login_div" style="margin-bottom: 50px;" >
                                <ul>
                                    <div class="row">
                                        <?php echo form_open('', array('id' => 'usercredentials')); ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 savedccd">
                                            <header class="payment-heading">
                                                <h2>Your Login Credentials</h2>
                                                <p>For security reasons please validate your account.</p>
                                            </header>
                                            <div class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <li>
                                                            <label>Username<span class="staric">*</span></label>
                                                            <input id="username" type="text" name="username" value="" class="invoice-fields"> 
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <li>
                                                            <label>Password<span class="staric">*</span></label>
                                                            <input id="password" type="password" name="password" value="" class="invoice-fields"> 
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <li>
                                                            <label>&nbsp;</label>
                                                            <input type="submit" onclick="validate_user_form()" value="Verify User" class="submit-btn">
                                                        </li>
                                                    </div>
                                                    <div id="login_error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </ul>
                            </div>
                            <div class="universal-form-style-v2 payment-area container" id="payment_div" style="display: none">
                                <ul>
                                    <div class="row">
                                        <div class="form-col-100">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <li>
                                                    <label>Discount Coupon</label>
                                                    <input type="text" value="" name="discount_coupon" id="discount_coupon_main_cart" class="invoice-fields"> 
                                                    <div id="coupon_response_main_cart"></div>
                                                </li>

                                                <li>
                                                    <label>Payment with</label>
                                                    <div class="hr-select-dropdown">
                                                        <select name="p_with_main" id="p_with_main" onchange="check_ccd(this.value)" class="invoice-fields">
                                                            <option value="new">Add new credit card</option>
                                                            <?php
                                                            $get_data = $this->session->userdata('logged_in');
                                                            $cards = db_get_card_details($companyDetail['sid']);
                                                            if (!empty($cards)) {
                                                                foreach ($cards as $card) {
                                                                    echo '<option value="' . $card['sid'] . '">' . $card['number'] . ' - ' . $card['type'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <li>
                                                    <label>&nbsp;</label>
                                                    <input type="button" id="apply_coupon" value="Apply Coupon" class="submit-btn">
                                                </li>
                                                <div class="payment-method"><img src="<?= base_url() ?>assets/images/payment-img.jpg"></div>
                                            </div>
                                        </div>
                                        <div class="form-col-100 savedccd">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <span>(<span class="staric hint-str">*</span>) Denotes required fields</span>
                                            </div>
                                        </div>
                                        <?php echo form_open('', array('id' => 'ccmain')); ?>
                                        <div id="novalidatemain"></div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                            <header class="payment-heading">
                                                <h2>Credit Card Details</h2>
                                            </header>
                                            <div class="form-col-100">
                                                <li>
                                                    <label>Number<span class="staric">*</span></label>
                                                    <input id="cc_card_no" type="text" name="cc_card_no" value="" class="invoice-fields"> 
                                                </li>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Expiration month<span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select id="cc_expire_month" name="cc_expire_month" class="invoice-fields">
                                                                    <option value=""></option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Year<span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <?php $current_year = date('Y'); ?>
                                                                <select id="cc_expire_year" name="cc_expire_year" class="invoice-fields">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    for ($i = $current_year; $i <= $current_year + 10; $i++) {
                                                                        ?>
                                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                            <header class="payment-heading">
                                                <h2>&nbsp;</h2>
                                            </header>
                                            <div class="form-col-100">
                                                <li>
                                                    <label>Type<span class="staric">*</span></label>
                                                    <div class="hr-select-dropdown">
                                                        <select id="cc_type" name="cc_type" class="invoice-fields">
                                                            <option value=""></option>
                                                            <option value="visa">Visa</option>
                                                            <option value="mastercard">Mastercard</option>
                                                            <option value="discover">Discover</option>
                                                            <option value="amex">Amex</option>
                                                        </select>
                                                    </div>
                                                </li>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label class="small-case">ccv</label>
                                                            <input id="cc_ccv" type="text" name="cc_ccv" value="" class="invoice-fields"> 
                                                        </li>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="maincartcouponarea"></div>
                                        <div id="employer_id_div"></div>
                                        <div id="cart_product_id"></div>
                                        <div class="form-col-100 autoheight">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="checkbox-field savedccd">
                                                    <input type="checkbox" name="cc_future_payment" id="future-payment">
                                                    <label for="future-payment">Save this card for future payment</label>
                                                </div>
                                                <div class="btn-panel">
                                                    <input type="submit" id="cc_send" value="Confirm payment" onclick="return pp_confirm_main()" style="display:none" class="submit-btn">
                                                    <div id="cc_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i> Processing...</div>
                                                </div>
                                                <p id="checkout_error_message"></p>
                                                <p id="checkout_error_message_coupon"></p>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main End -->
    </div>
    <!-- Wrapper End -->
</body>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
                                                        function validate_user_form() {
                                                            $("#usercredentials").validate({
                                                                ignore: ":hidden:not(select)",
                                                                rules: {
                                                                    username: {
                                                                        required: true,
                                                                    },
                                                                    password: {
                                                                        required: true,
                                                                        minlength: 6,
                                                                    }
                                                                },
                                                                messages: {
                                                                    username: {
                                                                        required: 'Username is required!',
                                                                    },
                                                                    password: {
                                                                        required: 'Password is required!',
                                                                        minlength: 'Invalid password'
                                                                    }
                                                                },
                                                                submitHandler: function (form) {
                                                                    $.ajax({
                                                                        url: "<?= base_url() ?>user_validation",
                                                                        type: "POST",
                                                                        //contentType: "application/json; charset=utf-8",
                                                                        //data: JSON.stringify($(form).serialize()),
                                                                        data: $(form).serialize(),
                                                                        dataType: "json",
                                                                        success: function (response) {
                                                                            if (response == 0) {
                                                                                $('#username').addClass('warning');
                                                                                $('#password').addClass('warning');
                                                                                $('#login_error').html('<p class="warning"><i class="fa fa-warning"></i> To Proceed, Please enter valid Username and Password.</p>');

                                                                            }
                                                                            else if (response == -1) {
                                                                                $('#username').addClass('warning');
                                                                                $('#password').addClass('warning');
                                                                                $('#login_error').html('<p class="warning"><i class="fa fa-warning"></i> Your company is not Expired. To login please <a href="<?php echo base_url("login"); ?>" targer="_blank">click here</a>. </p>');

                                                                            }
                                                                            else {
                                                                                $('#login_error').html('');
                                                                                $('#payment_div').fadeIn();
                                                                                $('#login_div').hide();
                                                                                $('#employer_id_div').html('<input type="hidden" id="employer_sid" name="employer_sid" value="' + response + '">');
                                                                            }
                                                                        },
                                                                        error: function (request, status, error) {

                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        }
                                                        // main header cart functions to apply coupon
                                                        $('#apply_coupon').click(function () {
                                                            var coupon_code = $("input[id^='discount_coupon_main_cart']").val().trim();
                                                            result = $('.product_radio_button').is(':checked');
                                                            product_id = $('input[name=product]:checked').val();
                                                            if (coupon_code == "") {
                                                                $('#discount_coupon_main_cart').addClass('warning');
                                                                $('#coupon_response_main_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please provide coupon code</p>');
                                                            }
                                                            else if (result == false) {
                                                                $('#discount_coupon_main_cart').addClass('warning');
                                                                $('#coupon_response_main_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please select a product first</p>');

                                                            } else {
                                                                $('#discount_coupon_main_cart').removeClass('warning');
                                                                myurl = "<?= base_url() ?>home/apply_coupon_code";
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: myurl,
                                                                    data: {coupon_code: coupon_code, action: "apply_coupon_code", type: "account_package_coupan"},
                                                                    dataType: "json", // Set the data type so jQuery can parse it for you
                                                                    success: function (data) {
                                                                        if (data[0] == 'error') {
                                                                            $('#discount_coupon_main_cart').addClass('error');
                                                                            $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                                                                            productPrice = $("#product_price_" + product_id).val();
                                                                            $("#checkout_subtotal").html(productPrice);
                                                                            $('#checkout_discount').html(0);

                                                                            productSpecialDiscountPrice = 0;
                                                                            productSpecialDiscountPrice = $("#special_discount_" + product_id).val();
                                                                            $("#checkout_special_discount").html(productSpecialDiscountPrice);
                                                                            $("#checkout_total").html(productPrice - productSpecialDiscountPrice);

//                                                                            //new place for all prices
//                                                                            $('#new_product_div_' + product_id).hide();
//                                                                            $('#old_product_price_' + product_id).removeClass('text-line');
//                                                                            $('#new_product_price_' + product_id).html("");
                                                                        } else {
                                                                            $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-spinner fa-spin"></i> loading...</p>');
                                                                            var js_coupon_code = data[1];
                                                                            var js_coupon_discount = data[2];
                                                                            var js_coupon_type = data[3];

                                                                            var checkout_subtotal = $('#checkout_subtotal').html();
                                                                            if (js_coupon_type != 'fixed') {
                                                                                js_coupon_discount = ((checkout_subtotal * js_coupon_discount) / 100).toFixed(2);
                                                                            }
                                                                            $('#checkout_discount').html(parseFloat(js_coupon_discount).toFixed(2));
                                                                            productSpecialDiscountPrice = 0;
                                                                            productSpecialDiscountPrice = $("#special_discount_" + product_id).val();
                                                                            var toal_after_discount = (checkout_subtotal - js_coupon_discount - productSpecialDiscountPrice).toFixed(2);
                                                                            $('#discount_coupon_main_cart').removeClass('error');
                                                                            $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-exclamation-circle"></i> Coupon code is succesfully applied!</p>');
                                                                            $('#maincartcouponarea').html('<input type="hidden" name="coupon_type" value="' + js_coupon_type + '"><input type="hidden" name="coupon_discount" value="' + js_coupon_discount + '"><input type="hidden" name="coupon_code" value="' + js_coupon_code + '">');
                                                                            $('#cart_product_id').html('<input type="hidden" name="product_id" value="' + product_id + '">');
                                                                            $('#checkout_total').html(parseFloat(toal_after_discount).toFixed(2));
                                                                            $("#checkout_special_discount").html(parseFloat(productSpecialDiscountPrice).toFixed(2));

//                                                                            //new place for all prices
//                                                                            $(".old-price").not('#new_product_price_' + product_id).removeClass('text-line');
//                                                                            $(".new-price").not('#new_product_price_' + product_id).hide();
//
//                                                                            $('#new_product_div_' + product_id).show();
//                                                                            $('#old_product_price_' + product_id).addClass('text-line');
//                                                                            $('#new_product_price_' + product_id).html(toal_after_discount);

                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        });
                                                        // main header checkout function for cc
                                                        function pp_confirm_main() {

                                                            product_id = $('input[name=product]:checked').val();
                                                            login_flag = 'false';
                                                            $('#cart_product_id').html('<input type="hidden" name="product_id" value="' + product_id + '">');
                                                            $('#checkout_error_message').html('');
                                                            $("#ccmain").validate({
                                                                ignore: ":hidden:not(select)",
                                                                rules: {
                                                                    cc_card_no: {
                                                                        required: true,
                                                                        minlength: 12,
                                                                        maxlength: 19,
                                                                        digits: true
                                                                    },
                                                                    cc_expire_month: {
                                                                        required: true
                                                                    },
                                                                    cc_expire_year: {
                                                                        required: true
                                                                    },
                                                                    cc_type: {
                                                                        required: true
                                                                    },
                                                                    cc_ccv: {
                                                                        digits: true,
                                                                        minlength: 3,
                                                                        maxlength: 4,
                                                                    }
                                                                },
                                                                messages: {
                                                                    cc_card_no: {
                                                                        required: 'Credit Card No is required!',
                                                                        minlength: 'Invalid Card no',
                                                                        maxlength: 'Invalid Card no',
                                                                        digits: 'Invalid Card no'
                                                                    },
                                                                    cc_expire_month: {
                                                                        required: 'Required field!'
                                                                    },
                                                                    cc_expire_year: {
                                                                        required: 'Required field!'
                                                                    },
                                                                    cc_type: {
                                                                        required: 'Required field!'
                                                                    },
                                                                    cc_ccv: {
                                                                        digits: 'Invalid CCV Code',
                                                                        minlength: 'Invalid CCV Code',
                                                                        maxlength: 'Invalid CCV Code'
                                                                    }
                                                                },
                                                                submitHandler: function (form) {
                                                                    $('#cc_send').prop('disabled', true);
                                                                    $('#cc_send').addClass("disabled-btn");
                                                                    $('#cc_spinner').removeClass("hide");
                                                                    $.ajax({
                                                                        url: "<?= base_url() ?>misc/account_package_payment",
                                                                        type: "POST",
                                                                        //contentType: "application/json; charset=utf-8",
                                                                        //data: JSON.stringify($(form).serialize()),
                                                                        data: $(form).serialize(),
                                                                        dataType: "json",
                                                                        success: function (response) {
                                                                            $('#cc_send').prop('disabled', false);
                                                                            $('#cc_send').removeClass("disabled-btn");
                                                                            $('#cc_spinner').addClass("hide");

                                                                            if (response[0] == 'error') {
                                                                                error_product = response[1];
                                                                                error_coupon = response[2];
                                                                                error_card = response[3];
                                                                                if (error_card != 'no_error') {
                                                                                    $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + error_card + '</div></div>');
                                                                                } else {
                                                                                    $('#checkout_error_message').html('');
                                                                                }

                                                                                if (error_product == 'no_error') {
                                                                                    $('#checkout_error_message').html('');
                                                                                    $('.noproducterrors').html('');
                                                                                } else {
                                                                                    error_product = error_product.split(",");
                                                                                    for (index = 0; index < error_product.length; ++index) {
                                                                                        var product_id = error_product[index].trim();
                                                                                        $('#noproduct_' + product_id).html('It is no longer available');
                                                                                    }
                                                                                    $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Error: There was some error, Please check Market Place products!</div></div>');
                                                                                }
                                                                                if (error_coupon == 'coupon_error') {
                                                                                    $('#coupon_amount').html('$0');
                                                                                    var checkout_subtotal = $('#cart_subtotal_value').html();
                                                                                    $('#checkout_total').html('$' + checkout_subtotal);
                                                                                    $('#checkout_subtotal_value').html(checkout_subtotal);
                                                                                    $('#maincartcouponarea').html('');
                                                                                    $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                                                                                    $('#checkout_error_message_coupon').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Coupon Error! You can still process payment without coupon code!</div></div>');
                                                                                } else {
                                                                                    $('#coupon_response_main_cart').html('');
                                                                                    $('#checkout_error_message_coupon').html('');
                                                                                }

                                                                            } else {
                                                                                login_flag = 'true';
                                                                                $('#checkout_error_message_coupon').html('');
                                                                                $('#checkout_error_message').html('');
                                                                                $('.noproducterrors').html('');
                                                                                $('#coupon_response_main_cart').html('');


                                                                                userId = $("#employer_sid").val();
                                                                                url_to = "<?= base_url() ?>manage_admin/employers/employer_login";
                                                                                $.post(url_to, {action: "login", sid: userId, task: "account_expiry"})
                                                                                        .done(function (data) {
                                                                                            window.location.assign("<?= base_url('dashboard') ?>");
                                                                                        });
                                                                            }
                                                                        },
                                                                        error: function (request, status, error) {
                                                                            $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Error: Your card could not be processed, Please check card details and try again!</div></div>');
                                                                            $('#checkout_error_message_coupon').html('');
                                                                            $('#cc_send').prop('disabled', false);
                                                                            $('#cc_send').removeClass("disabled-btn");
                                                                            $('#cc_spinner').addClass("hide");
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                        }

                                                        // check cc type
                                                        function check_ccd(val) {
                                                            if (val == 'new') {
                                                                $('#cc_card_no').prop('readonly', false);
                                                                $('#cc_card_no').val('');
                                                                $('#cc_type').prop('disabled', false);
                                                                $('#cc_expire_month').prop('disabled', false);
                                                                $('#cc_expire_year').prop('disabled', false);
                                                                $('#cc_ccv').prop('readonly', false);
                                                                $('#novalidatemain').html('');
                                                                $('.savedccd').show();
                                                            } else {
                                                                $('#cc_card_no').prop('readonly', true);
                                                                $('#cc_card_no').val('000000000000');
                                                                $('#cc_type').prop('disabled', true);
                                                                $('#cc_expire_month').prop('disabled', true);
                                                                $('#cc_expire_year').prop('disabled', true);
                                                                $('#cc_ccv').prop('readonly', true);
                                                                $('#cc_ccv').val('');
                                                                $('#novalidatemain').html('<input type="hidden" name="cc_card_no" value="000000000000"><input type="hidden" name="cc_expire_month" value="00"><input type="hidden" name="cc_expire_year" value="0000"><input type="hidden" name="cc_type" value="0000"><input type="hidden" name="cc_id" value="' + val + '">');
                                                                $('.savedccd').hide();
                                                                //  $( "#cc_card_no" ).rules( "remove" );
                                                            }
                                                        }
                                                        function calculateTotal(product_id) {

                                                            productPrice = $("#product_price_" + product_id).val();
                                                            productSpecialDiscountPrice = 0;
                                                            productSpecialDiscountPrice = $("#special_discount_" + product_id).val();
                                                            $("#checkout_subtotal").html(parseFloat(productPrice).toFixed(2));
                                                            $("#checkout_special_discount").html(parseFloat(productSpecialDiscountPrice).toFixed(2));
                                                            $("#checkout_total").html(parseFloat(productPrice - productSpecialDiscountPrice).toFixed(2));
                                                            $("#checkout_discount").html(0);

                                                            $('#discount_coupon_main_cart').removeClass('error');
                                                            $('#coupon_response_main_cart').html('');
                                                            $('#discount_coupon_main_cart').val('');
                                                        }

                                                        $('.product_radio_button').click(function () {
                                                            $("#cc_send").show();
                                                        });
</script>
</html>