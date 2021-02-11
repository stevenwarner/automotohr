<?php

/*$load_view = 'old';

if ($this->session->userdata('logged_in')) {
    if (!isset($session)) {
        $session = $this->session->userdata('logged_in');
    }
    $access_level = $session['employer_detail']['access_level'];

    $public_sections = array();
    $public_sections[] = 'home';
    $public_sections[] = 'services';
    $public_sections[] = 'turnover_cost_calculator';
    $public_sections[] = 'login';
    $public_sections[] = 'contact_us';

    $uri_segment_01 = strtolower($this->uri->segment(1));

    if ($access_level == 'Employee' && !empty($uri_segment_01) && !in_array($uri_segment_01, $public_sections)) {
        $load_view = 'new';
    }

    if ($uri_segment_01 == 'my_profile' ||
        $uri_segment_01 == 'login_password' ||
        $uri_segment_01 == 'incident_reporting_system' ||
        $uri_segment_01 == 'calendar' ||
        $uri_segment_01 == 'direct_deposit' ||
        $uri_segment_01 == 'e_signature'
    ) {
        $load_view = 'new';
    }
}
d
$company_sid = $session['employer_detail']['parent_sid'];*/

    $company_sid = isset($session['company_detail']['sid']) && !empty($session['company_detail']['sid']) ? $session['company_detail']['sid'] : 'logout';
    if($company_sid != 'logout'){
        $footer_logo_data  = get_footer_logo_data($company_sid);
        $footer_logo_status = $footer_logo_data['footer_powered_by_logo'];
        $footer_logo_type = $footer_logo_data['footer_logo_type'];
        $footer_logo_text = $footer_logo_data['footer_logo_text'];
        $footer_logo_image = $footer_logo_data['footer_logo_image'];

        $footer_copyright_data  = get_footer_copyright_data($company_sid);
        $copyright_status = $footer_copyright_data['copyright_company_status'];
        $copyright_company_name = $footer_copyright_data['copyright_company_name'];
    
    } else {
        
        $copyright_status = '';
        $footer_logo_status = '';
    }
    
?>
<?php //if($load_view == 'old' || !check_blue_panel_status($company_sid)) { ?>
    <div class="container-fluid visible-xs-block">
        <div class="dash-box service-contacts">
            <div class="admin-info">
                <h2>Need help? Contact one of our Talent Network Partners at</h2>
                <div class="profile-pic-area">
                    <div class="form-col-100">
                        <ul class="admin-contact-info">
                            <li>
                                <label>Sales Support</label>
                                <span><i class="fa fa-phone"></i> <?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                                <span><a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo TALENT_NETWORK_SALES_EMAIL; ?></a></span>
                            </li>
                            <li>
                                <label>Technical Support</label>
                                <span><i class="fa fa-phone"></i> <?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                                <span><a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<footer class="footer">
<?php

$class = strtolower($this->router->fetch_class());
$method = strtolower($this->router->fetch_method());
?>
    <div class="copyright hidden-print">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="copy-right-text">
                        <?php 
                            if ($copyright_status == 1) {
                                $company_name = $copyright_company_name;
                            } else {
                                $company_name = STORE_NAME;
                            }
                        ?>
                        <p>Copyright &copy; <?php echo date('Y') .' '. $company_name; ?> All Rights Reserved</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 text-center">
                    <?php if ($footer_logo_status == 1) { ?>
                        <a class="<?php if ($footer_logo_type == 'text') { echo 'copy-right-text text-white'; } else { echo 'footer-text-logo';}?>" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            <?php if ($footer_logo_type == 'default') { ?>
                                Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                            <?php } else if ($footer_logo_type == 'text') { ?>
                                Powered by <?php echo $footer_logo_text; ?>
                            <?php } else if ($footer_logo_type == 'logo') { ?>
                                Powered by <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>" class="upload_logo_image">
                            <?php } ?>
                        </a>
                    <?php } else { ?>
                        <a class="footer-text-logo" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                        </a>
                    <?php } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="social-links">
                        <ul>
                            <li><a class="google-plus" href="<?php
                                $g_url = get_slug_data('google_plus_url', 'settings');
                                if (!empty($g_url)) {
                                    echo $g_url;
                                } else {
                                    echo "https://plus.google.com/u/0/b/102383789585278120218/+Automotosocialjobs/posts";
                                }
                                ?>"  target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a class="twitter"  href="<?php
                                $t_url = get_slug_data('twitter_url', 'settings');
                                if (!empty($t_url)) {
                                    echo $t_url;
                                } else {
                                    echo "https://twitter.com/AutomotoSocial";
                                }
                                ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a class="facebook"  href="<?php
                                $f_url = get_slug_data('facebook_url', 'settings');
                                if (!empty($f_url)) {
                                    echo $f_url;
                                } else {
                                    echo "https://www.facebook.com/automotosocialjobs";
                                }
                                ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>                            
                            <li><a class="linkedin"  href="<?php
                                $l_url = get_slug_data('linkedin_url', 'settings');
                                if (!empty($l_url)) {
                                    echo $l_url;
                                } else {
                                    echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                }
                                ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <?php
                                $y_url = get_slug_data('youtube_url', 'settings');
                                if (!empty($y_url)) {
                            ?>
                            <li><a class="youtube"  href="<?php echo $y_url; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
                            <?php } ?>
                            <?php
                                $i_url = get_slug_data('instagram_url', 'settings');
                                if (!empty($i_url)) {
                            ?>
                            <li><a class="instagram"  href="<?php echo $i_url; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <?php } ?>
                            <?php   $gl_url = get_slug_data('glassdoor_url', 'settings');
                                    if (!empty($gl_url)) { ?>
                                    <li><a class="glassdoor"  href="<?php echo $gl_url; ?>" target="_blank"><img src="<?= base_url() ?>assets/images/glassdoor.png"></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-center" id="photo_gallery_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-header modal-header-bg" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Photo Gallery</h4>
            </div>
            <div class="modal-content" id='modal-content-div' style="border-radius:0 0 6px 6px; border:none;">
                <div id="document_modal_body" class="modal-body"></div>
                <div id="document_modal_footer" class="modal-footer"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</footer>
<span id="get_footer_url" style="display:none;"><?php echo base_url(); ?></span>
<div class="cart-bg outer-cart-overlay"></div>
<div class="cart-bg inner-cart-overlay"></div>
<div id="base_url_footer" style="display: none;"><?= base_url() ?></div>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.enable-bs-tooltip').each(function () {
                $(this).tooltip();
            });
            $('#free_no_payment').hide();
        });

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'de,es,fr,pt,it,zh-CN,zh-TW',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <?php   if ($this->session->userdata('logged_in')) { ?>
    <script type="text/javascript">
        function remove_cart_item(id) {
            alertify.confirm("Please Confirm Delete", "Are you sure you want to remove market place product from your cart?",
                function () {
                    url = "<?= base_url() ?>home/remove_cart_item";
                    $.post(url, {sid: id, action: "remove_cart_item"})
                        .done(function (data) {
                            $('#viewcart_' + id).hide();
                            $('#checkoutcart_' + id).remove();
                            var total_rows = $('#cart_count').val();
                            total_rows = total_rows - 1;
                            var product_total = $('#product_total_' + id).html();
                            var cart_subtotal = $('#cart_subtotal_value').html();
                            var remain_cart_subtotal = cart_subtotal - product_total;
                            $('#cart_subtotal').html('$' + remain_cart_subtotal);
                            $('#checkout_subtotal').html('$' + remain_cart_subtotal);
                            $('#cart_subtotal_value').html(remain_cart_subtotal);
                            $('#cart_count').val(total_rows);
                            $('#cart_total_top').html(total_rows);
                            $('#cart_total_inner').html('Your cart (' + total_rows + ' items )');
                            $('#removeitfromcart_' + id).html('');
                            $('#removeitfromcart_free_' + id).html('');

                            if (total_rows <= 0) {
                                $('#show_no_cart').html('<article><div class="text"><p>No Market place product found!</p></div></article>');
                                $('#hide_cart_footer').hide();
                            }

                            alertify.notify(data, 'success');
                        });
                },
                function () {
                    alertify.error('Cancelled');
                });
        }

        // main header cart functions to apply coupon
        $('#apply_coupon').click(function () {
            var coupon_code = $("input[id^='discount_coupon_main_cart']").val().trim();

            if (coupon_code == "") {
                $('#discount_coupon_main_cart').addClass('warning');
                $('#coupon_response_main_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please provide coupon code</p>');
            } else {
                $('#coupon_spinner').show();
                $('#discount_coupon_main_cart').removeClass('warning');
                myurl = "<?= base_url() ?>home/apply_coupon_code";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {coupon_code: coupon_code, action: "apply_coupon_code"},
                    dataType: "json",
                    success: function (data) {
                        $('#coupon_spinner').hide();
                        if (data[0] == 'error') {
                            $('#discount_coupon_main_cart').addClass('error');
                            $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                        } else {
                            $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-spinner fa-spin"></i> loading...</p>');
                            var js_coupon_code = data[1];
                            var js_coupon_discount = data[2];
                            var js_coupon_type = data[3];
                            var checkout_subtotal = $('#cart_subtotal_value').html();

                            if (js_coupon_type != 'fixed') {
                                js_coupon_discount = ((checkout_subtotal * js_coupon_discount) / 100).toFixed(2);
                            }

                            var total_after_discount = (checkout_subtotal - js_coupon_discount).toFixed(2);
                            fInsertRemoveCouponInfoSuccess(total_after_discount, js_coupon_code, js_coupon_discount, js_coupon_type);
                            $('#show_coupon_amount').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Coupon (' + js_coupon_code + ')</b></td><td class="text-align"><p id="coupon_amount">-$' + js_coupon_discount + '</p></td>');
                            $('#show_coupon_total').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Total</b></td><td class="text-align"><p id="checkout_total">$' + total_after_discount + '</p></td>');
                            $('#discount_coupon_main_cart').removeClass('error');
                            $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-exclamation-circle"></i> Coupon code is succesfully applied!</p>');
                            fShowHideFreeCheckout(total_after_discount);
                            $('#checkout_title').html('Sub-Total');
                            $('#coupon_code').html(js_coupon_code);
                        }
                    }
                });
            }
        });

        $('#checkout_cart_click').click(function () {
            var coupon_code = $('#coupon_code').html();

            if (coupon_code != "") {
                myurl = "<?= base_url() ?>home/apply_coupon_code";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {coupon_code: coupon_code, action: "apply_coupon_code"},
                    dataType: "json",
                    success: function (data) {
                        if (data[0] == 'error') {
                            // do nothing
                        } else {
                            var js_coupon_code = data[1];
                            var js_coupon_discount = data[2];
                            var js_coupon_type = data[3];
                            var checkout_subtotal = $('#cart_subtotal_value').html();

                            if (js_coupon_type != 'fixed') {
                                js_coupon_discount = ((checkout_subtotal * js_coupon_discount) / 100).toFixed(2);
                            }

                            var total_after_discount = checkout_subtotal - js_coupon_discount;
                            $('#show_coupon_amount').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Coupon (' + js_coupon_code + ')</b></td><td class="text-align"><p id="coupon_amount">-$' + js_coupon_discount + '</p></td>');
                            $('#show_coupon_total').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Total</b></td><td class="text-align"><p id="checkout_total">$' + total_after_discount + '</p></td>');
                            $('#checkout_total').html('$' + total_after_discount);
                            $('#checkout_title').html('Sub-Total');
                            $('#checkout_title').html(js_coupon_code);
                            fShowHideFreeCheckout(total_after_discount);
                        }
                    }
                });
            }
        });

        function fProcessFreeCheckout() {
            if (parseInt($('#is_free_checkout').val()) == 1) {
                $('#btn-free-checkout').addClass('disabled-btn');
                $('#btn-free-checkout').prop('disabled');
                var myUrl = "<?php echo base_url('misc/cc_apply_main'); ?>";
                var DataToSend = $('#form_free_checkout').serialize();
                var myRequest;

                myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: DataToSend,
                    dataType: "json"
                });

                myRequest.success(function (response) {
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
                            $('#maincartcouponarea_free').html('');
                            $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                            $('#checkout_error_message_coupon').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Coupon Error! You can still process payment without coupon code!</div></div>');
                        } else {
                            $('#coupon_response_main_cart').html('');
                            $('#checkout_error_message_coupon').html('');
                        }
                    } else {
                        $('#checkout_error_message_coupon').html('');
                        $('#checkout_error_message').html('');
                        $('.noproducterrors').html('');
                        $('#coupon_response_main_cart').html('');

                        var myLocation = window.location.href;
                        window.location = myLocation;
                    }
                });
            }
        }

        function fInsertRemoveCouponInfoSuccess(totalAmoutAfterDiscount, coupon_code, coupon_discount, coupon_type) {
            if (totalAmoutAfterDiscount > 0) {
                $('#maincartcouponarea').html('<input type="hidden" name="coupon_type" value="' + coupon_type + '"><input type="hidden" name="coupon_discount" value="' + coupon_discount + '"><input type="hidden" name="coupon_code" value="' + coupon_code + '">');
                $('#maincartcouponarea_free').html('');
            } else {
                $('#maincartcouponarea_free').html('<input type="hidden" name="coupon_type" value="' + coupon_type + '"><input type="hidden" name="coupon_discount" value="' + coupon_discount + '"><input type="hidden" name="coupon_code" value="' + coupon_code + '">');
                $('#maincartcouponarea').html('');
            }
        }

        function fShowHideFreeCheckout(cartTotal) {
            if (cartTotal > 0) {
                $('#checkout_total').html('$' + cartTotal);
                $('#cr_card_payment').show();
                $('#free_no_payment').hide();
                $('#is_free_checkout').val('0');
            } else {
                $('#checkout_total').html('$ 0');
                $('#cr_card_payment').hide();
                $('#free_no_payment').show();
                $('#is_free_checkout').val('1');
            }
        }

        function pp_confirm_main() {
            $('#cc_send').prop('disabled');
            $('#cc_send').addClass('disabled-btn');
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
                        url: "<?= base_url() ?>misc/cc_apply_main",
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
                                    $('#maincartcouponarea_free').html('');
                                    $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>');
                                    $('#checkout_error_message_coupon').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Coupon Error! You can still process payment without coupon code!</div></div>');
                                } else {
                                    $('#coupon_response_main_cart').html('');
                                    $('#checkout_error_message_coupon').html('');
                                }
                            } else { // clear all error flags
                                $('#checkout_error_message_coupon').html('');
                                $('#checkout_error_message').html('');
                                $('.noproducterrors').html('');
                                $('#coupon_response_main_cart').html('');
                                var myLocation = window.location.href;
                                window.location = myLocation;
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
        //check_ccd_mini
    </script>
<?php } ?>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/executive.js"></script>
<?php   if($this->uri->segment(1) == '' || $this->uri->segment(1) == 'services' || $this->uri->segment(1) == 'demo' || $this->uri->segment(1) == 'schedule_your_free_demo' || $this->uri->segment(1) == 'thank_you') { ?>
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NCRGM56');</script>

            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NCRGM56"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php   } ?>            
        </body>
</html>