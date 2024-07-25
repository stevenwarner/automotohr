<!doctype html>
<html lang="en">

<head>
    <?php $class = strtolower($this->router->fetch_class()); ?>
    <?php $method = $this->router->fetch_method(); ?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" /> -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= main_url("public/v1/plugins/bootstrap/css/bootstrap.min.css?v=3.0"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/v1/plugins/fontawesome/4/font-awesome.min.css?v=3.0') ?>">
    <?php if ($loadView) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.min.css?v=3.0') ?>">
    <?php } else { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css?v=3.0.0') ?>">
    <?php } ?>
    <?php if ($loadJsFiles) { ?>
        <script src="<?= main_url("public/v1/plugins/jquery/jquery-3.7.1.min.js?v=3.0"); ?>"></script>
        <script src="<?= getPlugin("validator", "js"); ?>"></script>
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/v1/plugins/ms_modal/main.css?v=3.0') ?>">
    <?= $pageCSS ? GetCss($pageCSS) : ''; ?>
    <!-- CSS bundles -->
    <?= $appCSS ?? ""; ?>
    <?= bundleCSS([
        "v1/app/css/global",
    ], "public/v1/app/", "global", true); ?>
</head>

<body>

    <?php if ($loadView) { ?>

        <div class="wrapper-outer">
            <!--  -->
            <header class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                            <div class="logo">
                                <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>
                                    <a href="javascript:;">
                                        <img src="<?= getImageURL($session['company_detail']['Logo']); ?>" alt="company logo" />
                                    </a>
                                    <p><?= $session['company_detail']['CompanyName']; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10 pull-right cs-full-width">
                            <div class="row">
                                <nav class="navbar navigation">
                                    <div class="navbar-header">
                                        <button type="button" data-target="#main_nav" data-toggle="collapse" class="navbar-toggle">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="menu-title">Menu</span>
                                        </button>
                                    </div>
                                    <div id="main_nav" class="collapse navbar-collapse">

                                        <ul class="nav navbar-nav pull-right">
                                            <li>
                                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Quick Links&nbsp;&nbsp;<span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="<?php echo base_url('employee_management_system'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Dashboard</a></li>
                                                    <?php if ((isset($employerData) && $employerData['access_level'] != 'Employee') || (isset($loggedInEmployee) && $loggedInEmployee['access_level'] != 'Employee')) { ?>
                                                        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Management Dashboard</a></li>
                                                    <?php } ?>

                                                    <?php if (in_array('login_password', $securityDetails) || check_access_permissions_for_view($securityDetails, 'login_password')) { ?>
                                                        <li><a href="<?php echo base_url('login_password'); ?>"><i class="fa fa-fw fa-unlock"></i>&nbsp;&nbsp;Login Credentials</a></li>
                                                    <?php } ?>

                                                    <li><a href="<?php echo base_url('hr_documents_management/my_documents'); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;&nbsp;Documents</a></li>
                                                    <li><a href="<?php echo base_url('calendar/my_events'); ?>"><i class="fa fa-fw fa-calendar"></i>&nbsp;&nbsp;Calendar</a></li>
                                                    <li><a href="<?php echo base_url('e_signature'); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;&nbsp;E Signature</a></li>
                                                    <li><a href="<?php echo base_url('my_referral_network'); ?>"><i class="fa fa-fw fa-link"></i>&nbsp;&nbsp;My Referral Network</a></li>

                                                    <?php $incident = $this->session->userdata('incident_config');
                                                    if ($incident > 0) { ?>
                                                        <li><a href="<?php echo base_url('incident_reporting_system'); ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Incidents</a></li>
                                                    <?php } ?>

                                                    <li><a href="<?php echo base_url('private_messages'); ?>"><i class="fa fa-fw fa-envelope"></i>&nbsp;&nbsp;Private Messages</a></li>
                                                    <li><a href="<?php echo base_url('list_announcements'); ?>"><i class="fa fa-fw fa-bullhorn"></i>&nbsp;&nbsp;Announcements</a></li>
                                                    <li><a href="<?php echo base_url('learning_center/my_learning_center'); ?>"><i class="fa fa-fw fa-graduation-cap"></i>&nbsp;&nbsp;My Learning Center</a></li>

                                                    <?php
                                                    if (isset($session['safety_sheet_flag']) &&  $session['safety_sheet_flag'] > 0) { ?>
                                                        <li><a href="<?php echo base_url('safety_sheets'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;Safety Sheets</a></li>
                                                    <?php } ?>

                                                    <?php $comply_status = $session["company_detail"]["complynet_status"];
                                                    $loggedInEmployee_status = isset($employerData) ? $employerData["complynet_status"] : $loggedInEmployee["complynet_status"];
                                                    $access_level  = isset($loggedInEmployee) ? $loggedInEmployee['access_level'] : $employerData['access_level'];
                                                    if (check_access_permissions_for_view($securityDetails, 'complynet') && $comply_status && $access_level != 'Employee' && $loggedInEmployee_status) { ?>
                                                        <?php $complyNetLink = getComplyNetLink($this->session->userdata('logged_in')['company_detail']['sid'], $this->session->userdata('logged_in')['employer_detail']['sid']); ?>
                                                        <?php if ($complyNetLink) { ?>
                                                            <li><a href="<?= base_url('cn/redirect'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;ComplyNet</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li><a href="<?php echo base_url('logout'); ?>"><i class="fa fa-fw fa-lock"></i>&nbsp;&nbsp;Logout</a></li>
                                        </ul>

                                        <div class="pull-right notify-me">
                                            <button class="notification-bell" data-toggle="dropdown">
                                                <i class="fa fa-bell" style="color: #0000ff;"></i>
                                                <span class="notification-count count-increament" id="js-notification-count">0</span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu dropdown-menu-wide" id="js-notification-box"></ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php $this->load->view("main/employee_strip"); ?>

        <?php } else { ?>
            <div id="wrapper">
                <header class="header header-position">
                    <div class="container-fluid">
                        <div class="row hidden-print">
                            <div class="col-md-12">
                                <div class="<?php
                                            if (
                                                $class != 'demo'
                                            ) {
                                                echo " hr-lanugages";
                                            } else {
                                                echo " hr-lanugages-new";
                                            } ?>">
                                    <div id="google_translate_element"></div>
                                </div>
                                <div class="country-flag">
                                    <ul>
                                        <li>
                                            <img src="<?= base_url() ?>assets/images/usa.png" alt="USA">
                                        </li>
                                        <li>
                                            <img src="<?= base_url() ?>assets/images/canada.png" alt="Canada">
                                        </li>
                                        <?php $this->load->view("v1/attendance/partials/clocks/green/header"); ?>
                                    </ul>
                                </div>
                                <?php if (!$this->session->userdata('logged_in')) { ?>
                                    <div class="schedule-demo-btn-wrp <?php
                                                                        if ($this->uri->segment(1) == '') { /* home-demo-btn */
                                                                        } ?>">
                                        <ul>
                                            <?php if ($this->uri->segment(1) != 'demo' && $this->uri->segment(1) != 'schedule_your_free_demo') { ?>
                                                <li><a href="<?= base_url('schedule_your_free_demo') ?>"><i class="fa fa-calendar"></i>Schedule Your FREE DEMO</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <div class="logo">
                                    <?php if ($this->uri->segment(1) == 'demo' || $this->uri->segment(1) == 'schedule_your_free_demo' || $this->uri->segment(1) == 'thank_you' || $this->uri->segment(1) == 'affiliate-program' || $this->uri->segment(1) == 'can-we-send-you-a-check-every-month') { ?>
                                        <a href="<?= base_url() ?>">
                                            <img class="" src="<?= base_url() ?>assets/images/affiliates/ahr_logo_demo_new.png">
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= base_url() ?>">
                                            <img src="<?= base_url() ?>assets/images/ahr_logo_138X80_wt.png">
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php if ($this->session->userdata('logged_in')) { ?>
                                    <?php $get_cart_content = $this->session->userdata('logged_in'); ?>
                                    <?php if (isset($get_cart_content['cart']) && !empty($get_cart_content['cart'])) { ?>
                                        <?php $cart_content = $get_cart_content['cart']; ?>
                                        <?php $cart_count = count($cart_content); ?>
                                        <?php $sub_total = 0; ?>
                                        <?php $has_coupon = false;
                                        $coupon_data = array();

                                        if ($this->session->userdata('coupon_data')) {
                                            $coupon_data = $this->session->userdata('coupon_data');
                                            if (!empty($coupon_data)) {
                                                $has_coupon = true;
                                            }
                                        }

                                        if ($this->uri->segment(1) != 'add_listing_advertise' && $this->uri->segment(1) != 'schedule_your_free_demo' && $this->uri->segment(1) != 'demo' && $this->uri->segment(1) != 'thank_you') { ?>
                                            <div class="cart-area">
                                                <div class="cart-header">
                                                    <button class="cart-button">
                                                        <span id="cart_total_top"><?php echo $cart_count; ?></span>
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </button>
                                                    <div class="view-cart outter-view-cart" id="view-cart">
                                                        <div class="cart-header-inner">
                                                            <p id="cart_total_inner">Your cart (<?php echo $cart_count; ?>items)</p>
                                                        </div>
                                                        <div class="cart-body" id="show_no_cart">
                                                            <input type="hidden" name="cart_count" id="cart_count" value="<?php echo $cart_count; ?>">
                                                            <?php foreach ($cart_content as $key => $value) { ?>
                                                                <article id="viewcart_<?php echo $value['sid']; ?>">
                                                                    <figure>
                                                                        <img src="<?php echo AWS_S3_BUCKET_URL;
                                                                                    if (!empty($value['product_image'])) {
                                                                                        echo $value['product_image'];
                                                                                    } else { ?>default_pic-ySWxT.jpg<?php } ?>">
                                                                    </figure>
                                                                    <div class="text">
                                                                        <p><?php echo $value['name']; ?></p>
                                                                        <?php $serialized_extra_info = unserialize($value['serialized_extra_info']); ?>
                                                                        <?php if (empty($value['price'])) {
                                                                            $cart_price = 0;
                                                                        } else {
                                                                            $cart_price = $value['price'];
                                                                        }

                                                                        $no_of_days = $value['no_of_days'];

                                                                        if ($no_of_days == 0) {
                                                                            $product_total = $value['qty'] * $cart_price;
                                                                            //echo '<p>'.$value['qty'] . ' x $' . $cart_price.' = '. '$' . $product_total .'</p>';
                                                                            echo '<p>$' . $cart_price . ' x ' . $value['qty'] . ' Qty = ' . '$' . $product_total . '</p>';
                                                                        } else {
                                                                            $product_total = $no_of_days * $cart_price;
                                                                            echo '<p>$' . $cart_price . ' x ' . $no_of_days . ' day(s) = ' . '$' . $product_total . '</p>';
                                                                        }

                                                                        $sub_total += $product_total; ?>

                                                                        <p style="display: none;" id="product_total_<?php echo $value['sid']; ?>"><?php echo $product_total; ?></p>
                                                                    </div>
                                                                    <a class="remove-item-btn" href="javascript:;" onclick="remove_cart_item(<?php echo $value['sid']; ?>)">X</a><!-- function defined at footer -->
                                                                </article>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="cart-footer" id="hide_cart_footer">
                                                            <div class="sub-total-count">
                                                                <label>Sub-Total:</label>
                                                                <p style="display: none;" id="cart_subtotal_value"><?php echo $sub_total; ?></p>
                                                                <p id="cart_subtotal"><?php echo '$' . $sub_total; ?></p>
                                                            </div>
                                                            <div class="check-out-btn">
                                                                <a data-toggle="modal" id="checkout_cart_click" data-target="#checkout_cart_model" href="javascript:;">Checkout</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Cart checkout Modal *** START *** -->
                                            <div id="checkout_cart_model" class="modal fade" role="dialog">
                                                <div class="modal-dialog checkout_cart_model">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Market Place Product Checkout</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="universal-form-style-v2 payment-area">
                                                                <ul>
                                                                    <div class="row">
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="action" value="cart_checkout">
                                                                            <input type="hidden" id="total" name="total" value="">
                                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                <div class="table-responsive table-outer">
                                                                                    <div class="product-detail-area">
                                                                                        <table>
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <td colspan="2" width="60%">Item </td>
                                                                                                    <td class="text-align">Qty / Day(s) </td>
                                                                                                    <td class="text-align">Price</td>
                                                                                                    <td class="text-align">Sub Total </td>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php foreach ($cart_content as $key => $value) { ?>
                                                                                                    <tr id="checkoutcart_<?php echo $value['sid']; ?>">
                                                                                                        <td>
                                                                                                            <figure>
                                                                                                                <?php if (!empty($value['product_image'])) { ?>
                                                                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $value['product_image']; ?>">
                                                                                                                <?php } ?>
                                                                                                            </figure>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <h3 class="details-title--polite"><?php echo $value['name']; ?></h3>
                                                                                                            <p class="error noproducterrors" id="noproduct_<?php echo $value['sid']; ?>"></p>
                                                                                                        </td>
                                                                                                        <?php if (empty($value['price'])) {
                                                                                                            $cart_price = 0;
                                                                                                        } else {
                                                                                                            $cart_price = $value['price'];
                                                                                                        }

                                                                                                        $no_of_days = $value['no_of_days'];

                                                                                                        if ($no_of_days == 0) {
                                                                                                            $product_total = $value['qty'] * $cart_price;
                                                                                                            $total_qty = $value['qty'];
                                                                                                        } else {
                                                                                                            $product_total = $value['qty'] * $cart_price * $no_of_days;
                                                                                                            $total_qty = $no_of_days;
                                                                                                        } ?>
                                                                                                        <td class="text-align"><?php echo $total_qty; ?></td>
                                                                                                        <td class="text-align"><?php echo '$' . $cart_price; ?></td>
                                                                                                        <td class="text-align"><?php echo '$' . $product_total; ?></td>
                                                                                                    </tr>
                                                                                                <?php } ?>
                                                                                            </tbody>
                                                                                            <tfoot>
                                                                                                <tr>
                                                                                                    <td colspan="2" width="60%">
                                                                                                        &nbsp;</td>
                                                                                                    <td>&nbsp;</td>
                                                                                                    <td class="text-align">
                                                                                                        <b id="checkout_title">
                                                                                                            <?php if ($has_coupon) {
                                                                                                                echo 'Sub-Total';
                                                                                                            } else {
                                                                                                                echo 'Total';
                                                                                                            } ?>
                                                                                                        </b>
                                                                                                    </td>
                                                                                                    <td class="text-align">
                                                                                                        <p id="checkout_subtotal"><?php echo '$' . $sub_total; ?></p>
                                                                                                    </td>
                                                                                                    <p style="display: none;" id="checkout_subtotal_value"><?php echo $sub_total; ?></p>
                                                                                                    <p style="display: none;" id="coupon_code">
                                                                                                        <?php if ($has_coupon) {
                                                                                                            echo $coupon_data['coupon_code'];
                                                                                                        } ?>
                                                                                                    </p>
                                                                                                </tr>
                                                                                                <?php if ($has_coupon) { ?>
                                                                                                    <?php $coupon_type = $coupon_data['coupon_type']; ?>
                                                                                                    <?php if ($coupon_type == 'fixed') {
                                                                                                        $coupon_discount = $coupon_data['coupon_discount'];
                                                                                                    } else {
                                                                                                        $coupon_discount = round((($sub_total * $coupon_data['coupon_discount']) / 100), 2);
                                                                                                    } ?>
                                                                                                    <tr id="show_coupon_amount">
                                                                                                        <td colspan="2" width="60%"> &nbsp;</td>
                                                                                                        <td>&nbsp;</td>
                                                                                                        <td class="text-align"><b>Coupon (<?php echo $coupon_data['coupon_code']; ?>)</b></td>
                                                                                                        <td class="text-align">
                                                                                                            <p id="coupon_amount"> -$<?php echo $coupon_discount; ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr id="show_coupon_total">
                                                                                                        <td colspan="2" width="60%"> &nbsp;</td>
                                                                                                        <td>&nbsp;</td>
                                                                                                        <td class="text-align">
                                                                                                            <b>Total</b>
                                                                                                        </td>
                                                                                                        <?php $final_total = round(($sub_total - $coupon_discount), 2); ?>
                                                                                                        <p style="display: none;" id="checkout_subtotal_value"><?php echo $final_total; ?></p>
                                                                                                        <td class="text-align">
                                                                                                            <p id="checkout_total"><?php echo '$' . $final_total; ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php } else { ?>
                                                                                                    <tr id="show_coupon_amount"></tr>
                                                                                                    <tr id="show_coupon_total"></tr>
                                                                                                <?php } ?>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <div class="form-col-100">
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <li>
                                                                                    <label>Discount Coupon</label>
                                                                                    <input type="text" value="" name="discount_coupon" id="discount_coupon_main_cart" class="invoice-fields">
                                                                                    <div id="coupon_response_main_cart"></div>
                                                                                </li>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <li>
                                                                                    <label>&nbsp;</label>
                                                                                    <input type="button" id="apply_coupon" value="Apply Coupon" class="submit-btn">
                                                                                    <div id="coupon_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i>
                                                                                        Processing...
                                                                                    </div>
                                                                                </li>

                                                                            </div>
                                                                        </div>
                                                                        <div id="free_no_payment">
                                                                            <div class="form-col-100">
                                                                                <form id="form_free_checkout">
                                                                                    <?php foreach ($cart_content as $key => $value) { ?>
                                                                                        <div id="removeitfromcart_free_<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][mid]" value="<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][id]" value="<?php echo $value['product_sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][qty]" value="<?php echo $value['qty']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][name]" value="<?php echo $value['name']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][no_of_days]" value="<?php echo $value['no_of_days']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][price]" value="<?php echo $value['price']; ?>">
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    <div id="maincartcouponarea_free">
                                                                                        <?php if ($has_coupon) { ?>
                                                                                            <input type="hidden" name="coupon_code" value="<?php echo $coupon_data['coupon_code']; ?>">
                                                                                            <input type="hidden" name="coupon_type" value="<?php echo $coupon_data['coupon_type']; ?>">
                                                                                            <input type="hidden" name="coupon_discount" value="<?php echo $coupon_data['coupon_discount']; ?>">
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                    <div class="col-xs-12">
                                                                                        <input id="btn-free-checkout" class="submit-btn" style="width: 50%; margin:0 auto;" type="button" id="free_order_btn" name="free_order_btn" value="Process Free Order" onclick="fProcessFreeCheckout();" />
                                                                                        <div id="free_spinner" class="spinner hide"> <i class="fa fa-refresh fa-spin"></i>
                                                                                            Processing...
                                                                                        </div>
                                                                                        <input type="hidden" id="is_free_checkout" name="is_free_checkout" value="0" />
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div id="cr_card_payment">
                                                                            <div class="form-col-100">
                                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                    <li>
                                                                                        <label>Payment with</label>
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select name="p_with_main" id="p_with_main" onchange="check_ccd(this.value)" class="invoice-fields">
                                                                                                <option value="new">Add new credit card </option>
                                                                                                <?php $get_data = $this->session->userdata('logged_in');
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
                                                                                    <div class="payment-method"><img src="<?= base_url() ?>assets/images/payment-img.jpg">
                                                                                    </div>
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
                                                                                                <label>Expiration Month<span class="staric">*</span></label>
                                                                                                <div class="hr-select-dropdown">
                                                                                                    <select id="cc_expire_month" name="cc_expire_month" class="invoice-fields">
                                                                                                        <option value=""></option>
                                                                                                        <option value="01">01 </option>
                                                                                                        <option value="02">02 </option>
                                                                                                        <option value="03">03 </option>
                                                                                                        <option value="04">04 </option>
                                                                                                        <option value="05">05 </option>
                                                                                                        <option value="06">06 </option>
                                                                                                        <option value="07">07 </option>
                                                                                                        <option value="08">08 </option>
                                                                                                        <option value="09">09 </option>
                                                                                                        <option value="10">10 </option>
                                                                                                        <option value="11">11 </option>
                                                                                                        <option value="12">12 </option>
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
                                                                                                <input id="cc_ccv" type="text" name="cc_ccv" value="" class="invoice-fields">
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
                                                                                    <?php foreach ($cart_content as $key => $value) { ?>
                                                                                        <div id="removeitfromcart_<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="process_credit_card" id="process_credit_card" value="1">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][mid]" value="<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][id]" value="<?php echo $value['product_sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][qty]" value="<?php echo $value['qty']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][name]" value="<?php echo $value['name']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][no_of_days]" value="<?php echo $value['no_of_days']; ?>">
                                                                                            <?php if (empty($value['price'])) {
                                                                                                $cart_price = 0;
                                                                                            } else {
                                                                                                $cart_price = $value['price'];
                                                                                            }

                                                                                            $product_total = $value['qty'] * $cart_price; ?>
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][price]" value="<?php echo $cart_price; ?>">
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    <div id="maincartcouponarea">
                                                                                        <?php if ($has_coupon) { ?>
                                                                                            <input type="hidden" name="coupon_code" value="<?php echo $coupon_data['coupon_code']; ?>">
                                                                                            <input type="hidden" name="coupon_type" value="<?php echo $coupon_data['coupon_type']; ?>">
                                                                                            <input type="hidden" name="coupon_discount" value="<?php echo $coupon_data['coupon_discount']; ?>">
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                    <div class="btn-panel">
                                                                                        <input type="submit" id="cc_send" value="Confirm payment" onclick="return pp_confirm_main()" class="submit-btn">
                                                                                        <div id="cc_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i>
                                                                                            Processing...
                                                                                        </div>
                                                                                    </div>
                                                                                    <p id="checkout_error_message"></p>
                                                                                    <p id="checkout_error_message_coupon"></p>
                                                                                </div>
                                                                            </div>
                                                                            <?php echo form_close(); ?>
                                                                        </div>
                                                                    </div>
                                                                </ul>
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
                                                                    <span class="payment-secured">Powered by <strong>Paypal</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Cart checkout Modal *** END *** -->
                                    <?php
                                        }
                                    } ?>
                                <?php } ?>
                                <nav class="navigation">
                                    <?php
                                    if ($this->uri->segment(1) == 'schedule_your_free_demo' || $this->uri->segment(1) == 'demo') {
                                    ?>
                                        <div id="main-navigation">
                                            <ul id="menu-primary-menu" class="nav navbar-nav navbar-right">
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-about" title="About">
                                                        About
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-join" title="Join">
                                                        Join
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-contact" title="Contact">
                                                        Contact
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <?php if (null !== ($this->session->userdata('logged_in'))) { ?>
                                            <div class="pull-left notify-me">
                                                <button class="notification-bell" data-toggle="dropdown">
                                                    <i class="fa fa-bell"></i>
                                                    <span class="notification-count count-increament" id="js-notification-count">0</span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu dropdown-menu-wide" id="js-notification-box"></ul>
                                            </div>
                                            <ul id="menus">
                                                <li class="active"><a href="javascript:void(0)">Quick Links<i class="fa fa-angle-down"></i></a>
                                                    <ul>
                                                        <li>
                                                            <a <?php if (base_url(uri_string()) == site_url('dashboard')) {
                                                                    echo 'class="active_header_nav"';
                                                                } ?> href="<?php echo base_url('dashboard'); ?>">
                                                                <figure><i class="fa fa-th"></i></figure> Dashboard
                                                            </a>
                                                        </li>
                                                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('employee_management_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('employee_management_system'); ?>">
                                                                    <figure><i class="fa fa-th"></i></figure> EMS
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'add_listing')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('add_listing')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('add_listing') ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Create A New Job
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--1-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'market_place')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('market_place')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('market_place') ?>">
                                                                    <figure><i class="fa fa-shopping-cart"></i></figure>
                                                                    My Marketplace
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--2-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'my_listings')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('my_listings') || $this->uri->segment(1) == 'edit_listing' || $this->uri->segment(1) == 'clone_listing') {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('my_listings/active') ?>">
                                                                    <figure><i class="fa fa-list-alt"></i></figure>
                                                                    My Jobs
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--3-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'application_tracking')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('application_tracking_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>">
                                                                    <figure><i class="fa fa-line-chart"></i></figure>
                                                                    Application Tracking
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--4-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'my_events')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('calendar/my_events')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('calendar/my_events') ?>">
                                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                                    Calender / Events
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--5-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'private_messages')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('private_messages')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('private_messages') ?>">
                                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                                    Private Message
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--6-->
                                                        <?php
                                                        $canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
                                                        ?>
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'employee_management') || $canAccessDocument) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('employee_management') || base_url(uri_string()) == site_url('invite_colleagues')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>employee_management">
                                                                    <figure><i class="fa fa-users"></i></figure>
                                                                    Employee / Team Members
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--7-->
                                                        <?php if (check_company_ems_status($this->session->userdata('logged_in')['company_detail']['sid'])) { ?>
                                                        <?php } else { ?>
                                                            <?php if (check_access_permissions_for_view($securityDetails, 'hr_documents')) { ?>
                                                                <li>
                                                                    <a <?php if (base_url(uri_string()) == site_url('hr_documents') || base_url(uri_string()) == site_url('add_hr_document')) {
                                                                            echo 'class="active_header_nav"';
                                                                        } ?> href="<?php echo base_url('hr_documents') ?>">
                                                                        <figure><i class="fa fa-file"></i></figure>
                                                                        Admin HR Documents
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>

                                                        <!--9-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'my_settings')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('my_settings')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('my_settings') ?>">
                                                                    <figure><i class="fa fa-sliders"></i></figure>
                                                                    Settings
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--10-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'screening_questionnaires')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('screening_questionnaires')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('screening_questionnaires') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Candidate Questionnaires
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--11-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'video_interview_system')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('video_interview_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('video_interview_system') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Video Interview System
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'interview_questionnaire')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('interview_questionnaire')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>interview_questionnaire">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Interview Questionnaires
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--12-->
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'background_check')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('background_check')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('accurate_background'); ?>">
                                                                    <figure><i class="fa fa-file"></i></figure>
                                                                    Background Checks Report
                                                                </a>
                                                            </li>
                                                        <?php } ?>


                                                        <?php $this->load->view("v1/attendance/partials/clocks/green/quick_links"); ?>



                                                        <?php if (check_access_permissions_for_view($securityDetails, 'support_tickets')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('support_tickets')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>support_tickets">
                                                                    <figure><i class="fa fa-tags"></i></figure>
                                                                    Support Tickets
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] && check_access_permissions_for_view($securityDetails, 'ems_portal')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('manage_ems') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Manage EMS
                                                                </a>
                                                            </li>
                                                        <?php  } ?>

                                                        <li>
                                                            <?php $get_data = $this->session->userdata('logged_in');
                                                            $sub_domain_url = db_get_sub_domain($get_data['company_detail']['sid']); ?>
                                                            <a href="<?php echo base_url('authorized_document'); ?>">
                                                                <figure><i class="fa fa-clipboard"></i></figure>
                                                                Assigned Documents
                                                            </a>
                                                        </li>

                                                        <?php $comply_status = $data["session"]["company_detail"]["complynet_status"];
                                                        $employee_comply_status = $session["employer_detail"]["complynet_status"];
                                                        ?>
                                                        <?php if (check_access_permissions_for_view($securityDetails, 'complynet') && $comply_status && $employee_comply_status) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <?php $complyNetLink = getComplyNetLink($this->session->userdata('logged_in')['company_detail']['sid'], $this->session->userdata('logged_in')['employer_detail']['sid']); ?>
                                                                <?php if ($complyNetLink) { ?>
                                                                    <a href="<?= base_url('cn/redirect'); ?>">
                                                                        <figure><i class=" fa fa-book"></i></figure>
                                                                        ComplyNet
                                                                    </a>
                                                                <?php } ?>
                                                            </li>
                                                        <?php  } ?>

                                                        <?php if (check_resource_permission($session['company_detail']['sid']) && check_access_permissions_for_view($securityDetails, 'resource_center_panel')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('resource_center')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>resource_center">
                                                                    <figure><i class="fa fa-files-o"></i></figure>
                                                                    Resource Center
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php
                                                        $pto_user_access = get_pto_user_access($session['employer_detail']['parent_sid'], $session['employer_detail']['sid']);
                                                        ?>
                                                        <?php if (checkIfAppIsEnabled('timeoff') && $pto_user_access['quick_link'] == 1) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo $pto_user_access['url']; ?>">
                                                                    <figure><i class="fa fa-clock-o"></i></figure>
                                                                    Time Off
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('performance-management/dashboard'); ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Performance Management
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('performance-management/goals'); ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Goals
                                                                </a>
                                                            </li>
                                                        <?php  } ?>

                                                        <?php if (isPayrollOrPlus() && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>

                                                            <li>
                                                                <a href="<?= base_url('settings/shifts/manage'); ?>">
                                                                    <figure><i class="fa fa-calendar"></i></figure>Manage Shifts
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php if (checkIfAppIsEnabled('payroll')) { ?>
                                                            <?php
                                                            $isCompanyOnPayroll = isCompanyLinkedWithGusto($session['company_detail']['sid']);
                                                            $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
                                                            ?>

                                                            <?php if ($isCompanyOnPayroll && $isTermsAgreed && isPayrollOrPlus()) { ?>
                                                                <li>
                                                                    <a href="<?= base_url('payroll/dashboard'); ?>">
                                                                        <figure><i class="fa fa-dollar" aria-hidden="true"></i></figure>
                                                                        Payroll Dashboard
                                                                    </a>
                                                                </li>
                                                            <?php } ?>

                                                        <?php } ?>

                                                        <?php if (checkIfAppIsEnabled(MODULE_LMS)) { ?>
                                                            <!--  -->
                                                            <?php if ($session['employer_detail']['access_level_plus'] == 1) { ?>
                                                                <li>
                                                                    <a href="<?= base_url('lms/courses/company_courses'); ?>">
                                                                        <figure><i class="fa fa-file" aria-hidden="true"></i></figure>
                                                                        Course Management
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <li>
                                                            <?php $get_data = $this->session->userdata('logged_in');
                                                            $sub_domain_url = db_get_sub_domain($get_data['company_detail']['sid']); ?>
                                                            <a href="<?php echo STORE_PROTOCOL_SSL . $sub_domain_url; ?>" target="_blank">
                                                                <figure><i class="fa fa-globe"></i></figure>
                                                                Career Website
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li><a href="<?= base_url('logout') ?>">Logout</a></li>
                                            </ul>
                                        <?php } else { ?>
                                            <div class="site-login-btn">
                                                <ul>
                                                    <li>&nbsp;</li>
                                                    <li><a href="<?= base_url('login') ?>">Login</a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    <?php
                                    }
                                    ?>
                                </nav>
                                <!--  got questions START -->
                                <?php //if ($class == 'dashboard' || $class == 'screening_questionnaires' || $class == 'settings' || $class == 'users' || $class == 'eeo' || $class == 'demo' || $class == 'application_tracking' || $class == 'market_place' || $class == 'manual_candidate' || $class == 'private_messages' || $class == 'xml_export' || $class == 'employee_management' || $class == 'appearance') {  
                                ?>
                                <?php //}   
                                ?>
                            </div>
                        </div>
                    </div>
                </header>
            <?php } ?>