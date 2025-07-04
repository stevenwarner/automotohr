<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="multistep-progress-form">
                        <form class="msform" action="" method="POST" enctype="multipart/form-data">
                            <!-- progressbar -->
                            <ul class="progressbar">
                                <li>create</li>
                                <li>Details</li>
                                <li id="advertise_nav" class="active">Advertise</li>
                                <li id="share_nav">Share</li>
                            </ul>
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <!-- fieldsets -->
                            <fieldset id="advertise_div">
                                <div class="top-search-area">
                                    <div class="row">
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                            <div class="tagline-area item-title">
                                                <h4><em>Advertise Job</em>:&nbsp;<span
                                                        style="color:#00a700;"><?php echo $jobDetail['Title']; ?></span><br/><em>Target
                                                        the right audience</em></h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                            <div class="cart-header">
                                                <span class="cart-info">
                                                    <label class="cart-label cart-heading">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </label>
                                                    <span class="cart-value cart-heading">
                                                        <span id="outter_cart_counter">0</span>
                                                    </span>
                                                </span>
                                                <a href="javascript:;" class="inner-cart" id="inner-cart">View Cart</a>

                                                <div class="view-cart inner-cart-view" id="inner-cart-view">
                                                    <div class="cart-header-inner">
                                                        <p>Your cart [<span id="inner_cart_counter">0</span> item(s)]
                                                        </p>
                                                    </div>
                                                    <div class="cart-body inner-cart-body">
                                                        <article id="empty_cart" style="display: none">
                                                            <div class="text">
                                                                <p>No Market place product found!</p>
                                                            </div>
                                                        </article>
                                                    </div>
                                                    <div class="cart-footer">
                                                        <div class="sub-total-count cart_hide_show">
                                                            <label>Subtotal:</label>

                                                            <p>$<span class="cart_total">0</span></p>
                                                        </div>
                                                        <div data-toggle="modal" data-target="#myModal"
                                                             class="check-out-btn">
                                                            <a style="display: none" class="cart_hide_show"
                                                               id="checkout_cart_click_minicart" href="javascript:;">Checkout</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="produt-block">
                                    <header class="form-col-100">
                                        <h2 class="section-title">Purchased Products</h2>
                                    </header>
                                    <div class="pre-purchased-products advertising-boxes">
                                        <?php if (!empty($purchasedProducts)) {
                                            foreach ($purchasedProducts as $product) {

                                                $active_flag = false;

                                                foreach($activeProductsOnJob as $activeProduct) {
                                                    if ($activeProduct['product_sid'] == $product['product_sid']) {
                                                        $active_flag = true;
                                                        break;
                                                    }
                                                }

                                                ?>

                                                <article class="purchased-product">
                                                    <input type="hidden" id="job_sid" value="<?php echo $job_sid; ?>">
                                                    <input type="hidden" id="employer_sid" value="<?php echo $employer_sid; ?>">
                                                    <input <?php echo $active_flag == true || in_array($product['product_sid'], $pending_approval_products) ? 'disabled="disabled" data-toggle="tooltip" title="Job already posted on this Job Board."' : ''; ?> class="product-checkbox" value="<?php echo $product['product_sid'] . ',' . $product['no_of_days']; ?>" type="checkbox" />
                                                    <p class="remaining-qty num-of-days">No of Days: <?php echo $product['no_of_days']; ?></p>
                                                    <p class="remaining-qty">Remaining Qty: <?php echo $product['remaining_qty']; ?></p>
                                                    <h2 class="post-title"><?php echo $product['name']; ?></h2>
                                                    <figure>
                                                        <img src="<?php echo $product['product_image'] != NULL ? AWS_S3_BUCKET_URL . $product['product_image'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg'; ?>"  alt="Category images">
                                                    </figure>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php if ($active_flag == true || in_array($product['product_sid'], $purchasedProductArray)) { ?>
                                                                <div class="already-incart disabled-products"><i
                                                                            class="fa fa-check-circle"></i>Job is already
                                                                    published here.
                                                                </div>
                                                            <?php } else if (in_array($product['product_sid'], $pending_approval_products) && !in_array($product['product_sid'], $purchasedProductArray)) { ?>
                                                                <div class="alert alert-warning">
                                                                    <i class="fa fa-clock-o"></i> Pending Approval
                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </article>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="tagline-area">
                                                <h4><span style="color:#00a700;">No purchased product avaliable.</span>
                                                </h4>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="produt-block">
                                    <header class="form-col-100">
                                        <h2 class="section-title">Products</h2>
                                    </header>
                                    <div class="advertising-boxes">
                                <?php // echo '<pre>'; print_r($notPurchasedProducts); echo '</pre>'; exit;
                                        if (!empty($notPurchasedProducts)) {
                                            foreach ($notPurchasedProducts as $product) { ?>
                                                <article>
                                                    <figure>
                                                        <img src="<?php echo $product['product_image'] != NULL ? AWS_S3_BUCKET_URL . $product['product_image'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg'; ?>" alt="Category images" />
                                                    </figure>
                                                    <h2 class="post-title"><?php echo $product['name']; ?></h2>
                                                    <?php if ($product['daily'] == 1) { ?>
                                                        <div class="count-box">$<?php echo $product['price']; ?> / Per Day </div>
                                                        <div class="count-box">
                                                            <label>No of Days:</label>
                                                            <input class="buy-btn small-qty" id="days_<?php echo $product['sid']; ?>" type="number" name="no_of_days" min="1" max="100" value="1" />
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="count-box">$<?php echo $product['price']; ?></div>
                                                        <input id="days_<?php echo $product['sid']; ?>" type="hidden" name="no_of_days" min="0" max="0" value="0">
                                                    <?php } ?>
                                                    <div class="button-panel incart-btn-fixed">
                                                        <?php
                                                        if (in_array($product['sid'], $purchasedProductArray)) { ?>
                                                            <div class="already-incart"><i class="fa fa-check-circle"></i>Job is already published here. </div>
                                                        <?php } else if(in_array($product['sid'], $pending_approval_products)) { ?>
                                                            <div class="alert alert-warning">
                                                                <i class="fa fa-clock-o"></i> Pending Approval
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="cart-btns">
                                                                <input style="position: relative; margin: auto; left: auto;" type="button" class="site-btn" id="<?php echo $product['sid']; ?>" onclick="add_to_cart(this.id)" value="Add to cart">
                                                                <a class="site-btn incart-btn inCart_<?php echo $product['sid']; ?>" style="display: none" id="inCart_<?php echo $product['sid']; ?>" onclick="remove_from_cart(this.id)" href="javascript:;">In cart</a>
                                                            </div>
                                                            <div class="cart-btns">
                                                                <a style="position: relative; margin: auto; left: auto;" type="button" class="site-btn" href="<?php echo base_url('marketplace_details') . '/' . $product['sid']; ?>" target="_blank">View Details</a>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </article>
                                                <?php
                                            }
                                        } else { ?>
                                            <div class="tagline-area">
                                                <h4><span style="color:#00a700;">No product available to purchase.</span></h4>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <a class="submit-btn" href="<?php echo base_url('edit_listing'); ?>/<?php echo $job_sid; ?>">Back to Job</a>
                                <input type="button" name="next" id="advertise_next" class="submit-btn" value="Advertise"/>
                                <a class="submit-btn" href="<?php echo base_url('add_listing_share'); ?>/<?php echo $job_sid; ?>">Share This Job</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog checkout_cart_model">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Advertise Job Checkout</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive table-outer">
                    <form method="POST" action="">
                        <div class="product-detail-area">
                            <input type="hidden" name="action" value="">
                            <input type="hidden" id="total" name="total" value="">
                            <table>
                                <thead>
                                <tr>
                                    <td colspan="2" width="60%">Item</td>
                                    <td class="text-align">Qty / Day(s)</td>
                                    <td class="text-align">Price</td>
                                    <td class="text-align">Sub Total</td>
                                </tr>
                                </thead>
                                <!--Dynamically populate market place products at checkout-popup-body-->
                                <tbody class="checkout-popup-body"></tbody>
                                <tfoot>
                                <tr>
                                    <td width="70%" colspan="4" style="text-align: right;"><b id="checkout_title_minicart">Total</b></td>
                                    <td class="text-align">
                                        <div class="cart_hide_show">
                                            <p>$<span class="cart_total">0</span></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="show_coupon_amount_minicart"></tr>
                                <tr id="show_coupon_total_minicart"></tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="universal-form-style-v2 payment-area">
                    <div>
                        <ul>
                            <div class="form-col-100">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <li>
                                        <label>Discount Coupon</label>
                                        <input type="text" value="" name="discount_coupon_mini"
                                               id="discount_coupon_mini_cart" class="invoice-fields">

                                        <div id="coupon_response_mini_cart"></div>
                                    </li>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <li>
                                        <label>&nbsp;</label>
                                        <input type="button" id="apply_coupon_mini" value="Apply Coupon" class="submit-btn">
                                    </li>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div id="free_no_payment_mini">
                        <div class="form-col-100">
                            <form id="form_free_checkout">
                                <input type="hidden" name="job_sid" value="<?php echo $job_sid; ?>">
                                <div id="free_checkout_mini_cart"></div>
                                <div id="maincartcouponarea_mini_free"></div>
                                <div class="col-xs-12">
                                    <input id="btn-free-checkout" class="submit-btn" style="width: 50%; margin:0 auto;" type="button" name="free_order_btn" value="Process Free Order" onclick="fProcessFreeCheckoutMini();"/>
                                    <div id="free_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i> Processing...</div>
                                    <input type="hidden" id="is_free_checkout_mini" name="is_free_checkout_mini" value="0" />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="cr_card_payment_mini">
                        <ul>
                            <div class="row">
                                <div class="form-col-100">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <li>
                                            <label>Payment with</label>
                                            <div class="hr-select-dropdown">
                                                <select name="p_with_main" id="p_with_mini" onchange="check_ccd(this.value)" class="invoice-fields">
                                                    <option value="new">Add new credit card</option>
                                                    <?php
                                                    $get_data = $this->session->userdata('logged_in');
                                                    $cards = db_get_card_details($get_data['company_detail']['sid']);
                                                    
                                                    if (!empty($cards)) {
                                                        foreach ($cards as $card) {
                                                            echo '<option value="' . $card['sid'] . '">' . $card['number'] . ' - ' . $card['type'] . ' ';
                                                            echo ($card['is_default'] == 1) ? '(Default)' : '';
                                                            echo '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="payment-method"><img src="<?= base_url() ?>assets/images/payment-img.jpg"></div>
                                    </div>
                                </div>
                                <div class="form-col-100 savedccd">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <span>(<span class="staric hint-str">*</span>) Denotes required fields</span>
                                    </div>
                                </div>
                                <?php echo form_open('', array('id' => 'ccmain')); ?>
                                <input type="hidden" name="process_credit_card" id="process_credit_card" value="1">
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
                                                    <label>Expiration Month<span class="staric">*</span></label>

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
                                                            <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
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
                                                    <input id="cc_ccv" type="text" name="cc_ccv" value=""
                                                           class="invoice-fields">
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-col-100 autoheight">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="checkbox-field savedccd">
                                            <input type="checkbox" name="cc_future_payment" id="future-payment">
                                            <label for="future-payment">Save this card for future payment</label>
                                        </div>
                                        <div id="maincartcouponarea_mini"></div>
                                        <div class="checkout-popup-cart"></div>
                                        <div class="btn-panel">
                                            <input type="hidden" name="job_sid" value="<?php echo $job_sid; ?>">
                                            <input type="submit" id="cc_send" value="Confirm payment" onclick="return pp_confirm_mini()" class="submit-btn">
                                            <div id="cc_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i> Processing... </div>
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
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-6">
                        <div class="media-content">
                            <h3 class="details-title">Secure payment</h3>
                            <p class="details-desc">This is a secure 256-bit SSL encrypted payment</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <span class="payment-secured">powered by <strong>Paypal</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //inner cart functions
    cart_counter = 0;
    cart_total = 0;
    var counter = 0;
    function add_to_cart(product_id) {
        //hide add button to in cart button
        //console.log('add to cart working area');
        $(".cart_hide_show").css('display', 'block');
        $("#empty_cart").css('display', 'none');
        $("#" + product_id).css('display', 'none');
        $("#inCart_" + product_id).css('display', 'block');
        var total_days = document.getElementById("days_" + product_id).value;

        <?php foreach ($notPurchasedProducts as $product) { ?>
        if (product_id == <?php echo $product['sid']; ?>) {
            no_of_days = document.getElementById("days_" + product_id).value;
            //appending products to the inner cart body
            var productToAppend = "<article id='product_<?php echo $product['sid']; ?>_div'>"
                                + "<figure>"
                                + "<img src='<?php echo AWS_S3_BUCKET_URL . $product['product_image']; ?>'>"
                                + "</figure>"
                                + "<div class='text'>"
                                + "<p><?php echo $product['name']; ?></p>"
                                + "<p>$<?php echo $product['price']; ?>"
                                + (total_days > 0 ? ' * ' + total_days + ' day(s)' : '')
                                + "</p>"
                                + "</div>"
                                + "<input type='hidden' id='no_of_days_<?php echo $product['sid']; ?>' value='" + total_days + "'>"
                                + "<input type='button' class='remove-item-btn' id='inCart_<?php echo $product['sid']; ?>' onclick='remove_from_cart(this.id);' value='X'>"
                                + "</article>";

            $(".inner-cart-body").append(productToAppend);

            if (total_days > 0) {
                product_qty = total_days;
                product_total = product_qty *
                <?php echo $product['price']; ?>
            } else {
                product_qty = 1;
                product_total = <?php echo $product['price']; ?>;
            }
            //appending products to the Checkout popup

            var checkoutProductToAppend = "<tr id='popup_product_<?php echo $product['sid']; ?>_div'>"
                                        + "<input type='hidden' name='product_id[]' value='<?php echo $product['sid']; ?>'>"
                                        + "<td width='30%'>"
                                        + "<figure>"
                                        + "<img src='<?php echo AWS_S3_BUCKET_URL . $product['product_image']; ?>'>"
                                        + "</figure>"
                                        + "</td>"
                                        + "<td><h3 class='details-title--polite'><?php echo $product['name']; ?></h3></td>"
                                        + "<td class='text-align'>" + product_qty + "</td>"
                                        + "<td class='text-align'>$<?php echo $product['price']; ?></td>"
                                        + "<td class='text-align'>$" + product_total + "</td>"
                                        + "</tr>";

            $(".checkout-popup-body").append(checkoutProductToAppend);

            var productInfoToAppend = "<p id='popup_product_<?php echo $product['sid']; ?>_cart'>"
                + "<input type='hidden' name='product[" + counter + "][id]' value='<?php echo $product['sid']; ?>'>"
                + "<input type='hidden' name='product[" + counter + "][price]' value='<?php echo $product['price']; ?>'>"
                + "<input type='hidden' name='product[" + counter + "][no_of_days]' value='" + total_days + "'>"
                + "</p>";

            $(".checkout-popup-cart").append(productInfoToAppend);

            var productInfoToAppendFree = "<p id='popup_free_product_<?php echo $product['sid']; ?>_cart'>"
                + "<input type='hidden' name='product[" + counter + "][id]' value='<?php echo $product['sid']; ?>'>"
                + "<input type='hidden' name='product[" + counter + "][price]' value='<?php echo $product['price']; ?>'>"
                + "<input type='hidden' name='product[" + counter + "][no_of_days]' value='" + total_days + "'>"
                + "</p>";

            //Add Hidden Fields to minicart free form
            $('#free_checkout_mini_cart').append(productInfoToAppendFree);

            counter = counter + 1;
            cart_total += parseInt(product_total);
        }
        <?php } ?>
        //calculating total ammount
        $(".cart_total").html(cart_total);
        //increment incart value
        cart_counter++;
        $("#inner_cart_counter,#outter_cart_counter").html(cart_counter);
        //setting value to the popup form
        //data for order table
        $('#total').val(cart_total);
        //data for order_product table
        $('#product_ids').val();
    }

    function remove_from_cart(long_product_id) {
        //console.log('remove from cart: ' + long_product_id);
        myarr = long_product_id.split("_");
        product_id = myarr[1];
        $("#" + product_id).css('display', 'block');
        no_of_days_removed = $('#no_of_days_' + product_id).val();
        $("." + long_product_id).css('display', 'none');
        id = "product_" + product_id + "_div";
        //idcart = "product_" + product_id + "_cart";
        popid = "popup_product_" + product_id + "_div";
        popidcart = "popup_product_" + product_id + "_cart";
        popidcartFree = "popup_free_product_" + product_id + "_cart";

        $("#" + id).remove();
        $("#" + popid).remove();
        //$("#" + idcart).remove();
        $("#" + popidcart).remove();
        $("#" + popidcartFree).remove();
        //calculating total ammount
        $(".cart_total").html(cart_total);
        <?php foreach ($notPurchasedProducts as $product) { ?>
        if (product_id == <?php echo $product['sid']; ?>) {
            cart_total = parseInt($(".cart_total").html());
            amount = 0;
            if (no_of_days_removed > 0) {
                product_qty = no_of_days_removed;
                amount = no_of_days_removed * <?php echo $product['price']; ?>;
            } else {
                product_qty = 1;
                amount = <?php echo $product['price']; ?>;
            }
            cart_total = cart_total - amount;
            $(".cart_total").html(cart_total);
        }
        <?php } ?>
        //increment incart value
        cart_counter = parseInt($("#inner_cart_counter").html());
        cart_counter--;
        $("#inner_cart_counter,#outter_cart_counter").html(cart_counter);
        
        if (cart_counter == 0) {
            $(".cart_hide_show").css('display', 'none');
            $("#empty_cart").css('display', 'block');
        }
    }

    $('#advertise_next').click(function () {
        var checkedValues = $('input:checkbox:checked').map(function () {
            return this.value;
        }).get();
        
        jobId = $("#job_sid").val();
        dataArray = {
            product_sid: checkedValues,
            job_sid: jobId,
            employer_sid: $("#employer_sid").val(),
        }
        
        url = "<?= base_url() ?>job_listings/save_jobs_to_feed";
        $.post(url, dataArray)
            .done(function (data) {
//                alert(data);
                if (data == "success") {
                    alertify.success('Success: Job published on selected Job Board(s).');
//                        window.location.href = "<?= base_url('add_listing_share') ?>" + '/' + jobId;
                    location.reload();
                } else {
                    alertify.error('Error: No job board selected, Try again');
                    location.reload();
                }
            });
    });

    // mini checkout function for cc
    function pp_confirm_mini() {
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
                    maxlength: 3,
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
                    url: "<?= base_url() ?>misc/cc_apply_mini",
                    type: "POST",
                    data: $(form).serialize(),
                    success: function (response) {
                        response = JSON.parse(response);
//                        console.log(response);
                        $('#cc_send').prop('disabled', false);
                        $('#cc_send').removeClass("disabled-btn");
                        $('#cc_spinner').addClass("hide");

                        if (response[0] == 'error') {
                            //console.log('This is error');
                            error_coupon = response[2];
                            error_card = response[3];
                            if (error_card != 'no_error') {
                                $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + error_card + '</div></div>');
                            } else {
                                $('#checkout_error_message').html('');
                            }

                            if (error_coupon == 'coupon_error') {
                                $('#coupon_amount').html('$0');
                                $('#checkout_total').html('$' + cart_total);
                                $('#discount_coupon_mini_cart').addClass('error');
                                $('#coupon_response_mini_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                                $('#maincartcouponarea').html('');
                                //$('#coupon_response_mini_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                                $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Coupon Error! You can still process payment without coupon code!</div></div>');
                            } else {
                                $('#coupon_response_mini_cart').html('');
                                $('#checkout_error_message_coupon').html('');
                            }

                        } else {
                            //console.log('This is success');
                            // clear all error flags
                            $('#checkout_error_message_coupon').html('');
                            $('#checkout_error_message').html('');
                            $('.noproducterrors').html('');
                            $('#coupon_response_main_cart').html('');

                            var myLocation = window.location.href;
//                            console.log(myLocation);

                            window.location = myLocation;
                        }
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
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

    // mini cart functionality at job advertise page
    $('#apply_coupon_mini').click(function () {
        //console.log('Mini cart here');
        var coupon_code = $("input[id^='discount_coupon_mini_cart']").val().trim();
        if (coupon_code == "") {
            $('#discount_coupon_mini_cart').addClass('warning');
            $('#coupon_response_mini_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please provide coupon code</p>');
        } else {
            $('#discount_coupon_mini_cart').removeClass('warning');
            $('#coupon_response_mini_cart').html('<p class="coupon_success"><i class="fa fa-spinner fa-spin"></i> loading...</p>');
            //console.log('teek hai');
            myurl = "<?= base_url() ?>home/apply_coupon_code";
            $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code", minicart: "true"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if (data[0] == 'error') {
                        $('#discount_coupon_mini_cart').addClass('error');
                        $('#coupon_response_mini_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                    } else {
                        var js_coupon_code = data[1];
                        var js_coupon_discount = data[2];
                        var js_coupon_type = data[3];
                        var checkout_subtotal = $('.cart_total').html();
                        $('#discount_coupon_mini_cart').removeClass('error');
                        $('#coupon_response_mini_cart').html('<p class="coupon_success"><i class="fa fa-exclamation-circle"></i> Coupon code is succesfully applied!</p>');
                        //console.log('code: '+js_coupon_code+' discount: '+js_coupon_discount+' type: '+js_coupon_type+' subtotal: '+checkout_subtotal);
                        if (js_coupon_type != 'fixed') {
                            js_coupon_discount = ((checkout_subtotal * js_coupon_discount) / 100).toFixed(2);
                        }
                        var total_after_discount = (checkout_subtotal - js_coupon_discount).toFixed(2);
                        //$('#maincartcouponarea').html('<input type="hidden" name="coupon_type" value="' + js_coupon_type + '"><input type="hidden" name="coupon_discount" value="' + js_coupon_discount + '"><input type="hidden" name="coupon_code" value="' + js_coupon_code + '">');
                        fShowHideFreeCheckoutMini(total_after_discount);
                        fInsertRemoveCouponInfoSuccessMini(total_after_discount, js_coupon_code, js_coupon_discount, js_coupon_type);
                        if(total_after_discount > 0){

                        }else{
                            total_after_discount = 0;
                        }


                        $('#checkout_title_minicart').html('Sub-Total');
                        $('#show_coupon_amount_minicart').html('<td width="70%" colspan="4" style="text-align: right;"><b>Coupon (' + js_coupon_code + ')</b></td><td class="text-align"><p id="coupon_amount_mini">-$' + js_coupon_discount + '</p></td>');
                        $('#show_coupon_total_minicart').html('<td width="70%" colspan="4" style="text-align: right;"><b>Total</b></td><td class="text-align"><p id="checkout_total_mini">$' + total_after_discount + '</p></td>');
                    }
                }
            });
        }
    });
    // mini cart functionality checkout out button functions
    $('#checkout_cart_click_minicart').click(function () {
//        console.log('I am In');
        var coupon_code = $("input[id^='discount_coupon_mini_cart']").val().trim();
        if (coupon_code != "") {
            myurl = "<?= base_url() ?>home/apply_coupon_code";
            $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code", minicart: "true"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if (data[0] == 'error') {
                        // do nothing
                    } else {
                        var js_coupon_code = data[1];
                        var js_coupon_discount = data[2];
                        var js_coupon_type = data[3];
                        var checkout_subtotal = $('.cart_total').html();
                        //console.log('code: '+js_coupon_code+' discount: '+js_coupon_discount+' type: '+js_coupon_type+' subtotal: '+checkout_subtotal);
                        if (js_coupon_type != 'fixed') {
                            js_coupon_discount = ((checkout_subtotal * js_coupon_discount) / 100).toFixed(2);
                        }
                        var total_after_discount = (checkout_subtotal - js_coupon_discount).toFixed(2);

                        fShowHideFreeCheckoutMini(total_after_discount);
                        fInsertRemoveCouponInfoSuccessMini(total_after_discount, js_coupon_code, js_coupon_discount, js_coupon_type);

                        if(total_after_discount > 0){

                        }else{
                            total_after_discount = 0;
                        }
                        $('#checkout_title_minicart').html('Sub-Total');
                        $('#show_coupon_amount_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Coupon (' + js_coupon_code + ')</b></td><td class="text-align"><p id="coupon_amount">-$' + js_coupon_discount + '</p></td>');
                        $('#show_coupon_total_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Total</b></td><td class="text-align"><p id="checkout_total">$' + total_after_discount + '</p></td>');
                    }
                }
            });
        }
    });

    function fInsertRemoveCouponInfoSuccessMini(totalAmoutAfterDiscount, coupon_code, coupon_discount, coupon_type){
        if(totalAmoutAfterDiscount > 0){
            $('#maincartcouponarea_mini').html('<input type="hidden" name="coupon_type" value="' + coupon_type + '"><input type="hidden" name="coupon_discount" value="' + coupon_discount + '"><input type="hidden" name="coupon_code" value="' + coupon_code + '">');
            $('#maincartcouponarea_mini_free').html('');
        }else{
            $('#maincartcouponarea_mini_free').html('<input type="hidden" name="coupon_type" value="' + coupon_type + '"><input type="hidden" name="coupon_discount" value="' + coupon_discount + '"><input type="hidden" name="coupon_code" value="' + coupon_code + '">');
            $('#maincartcouponarea_mini').html('');
        }
    }

    function fShowHideFreeCheckoutMini(cartTotal){
        if(cartTotal > 0) {
            $('#checkout_total').html('$' + cartTotal);
            $('#cr_card_payment_mini').show();
            $('#free_no_payment_mini').hide();
            $('#is_free_checkout_mini').val('0');
        }else{
            $('#checkout_total').html('$ 0');
            $('#cr_card_payment_mini').hide();
            $('#free_no_payment_mini').show();
            $('#is_free_checkout_mini').val('1');
        }
    }

    function fProcessFreeCheckoutMini(){

        if(parseInt($('#is_free_checkout_mini').val()) == 1) {
            $('#btn-free-checkout').addClass('disabled-btn');
            $('#btn-free-checkout').prop('disabled');
            $('#free_spinner').show();

            var myUrl = "<?php echo base_url('misc/cc_apply_mini');?>";
            var DataToSend = $('#form_free_checkout').serialize();

            var myRequest;


            myRequest = $.ajax({
                url: myUrl,
                type: 'POST',
                data: DataToSend,
                dataType: "json"
            });

            myRequest.success(function (response) {
                //response = JSON.parse(data);
//                alert(response[0]);
                if (response[0] == 'error') {
//                    console.log('This is error');
                    error_product = response[1];
                    error_coupon = response[2];
                    error_card = response[3];
                    //console.log(error_array);
                    if (error_card != 'no_error') {
                        $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + error_card + '</div></div>');
                    } else {
                        $('#checkout_error_message').html('');
                    }

                    if (error_product == 'no_error') {
                        //console.log('no product error');
                        $('#checkout_error_message').html('');
                        $('.noproducterrors').html('');
                    } else {
//                        console.log('product has error');
                        error_product = error_product.split(",");
                        for (index = 0; index < error_product.length; ++index) {
                            var product_id = error_product[index].trim();
//                            console.log(index+': '+product_id);
                            $('#noproduct_' + product_id).html('It is no longer available');
                        }
                        $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Error: There was some error, Please check Market Place products!</div></div>');
                    }

                    if (error_coupon == 'coupon_error') {
                        $('#coupon_amount').html('$0');
                        var checkout_subtotal = $('#cart_subtotal_value').html();
                        //console.log('remove it');
                        $('#checkout_total').html('$' + checkout_subtotal);
                        $('#checkout_subtotal_value').html(checkout_subtotal);
                        $('#maincartcouponarea').html('');
                        $('#maincartcouponarea_free').html('');
                        $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                        $('#checkout_error_message_coupon').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Coupon Error! You can still process payment without coupon code!</div></div>');
                    } else {
                        $('#coupon_response_main_cart').html('');
                        $('#checkout_error_message_coupon').html('');
                    }

                } else {
//                    console.log('success reached');
                    //console.log('This is success');
                    // clear all error flags
                    $('#checkout_error_message_coupon').html('');
                    $('#checkout_error_message').html('');
                    $('.noproducterrors').html('');
                    $('#coupon_response_main_cart').html('');

                    var myLocation = window.location.href;
//                    console.log(myLocation);

                    window.location = myLocation;
                }
            });
        }
    }

    $(document).ready(function () {
        $('#free_no_payment_mini').hide();
    });
</script>

