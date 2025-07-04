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
                                        <span class="page-title"><i class="fa fa-users"></i>Manage Company Credit Cards</span>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                    </div>
                                    <div class="add-new-company card_div">
                                        <div class="heading-title page-title">
                                            <span class="page-title"><?php echo $page_title; ?></span>
                                            <div class="title-btn-wrp">
                                                <a class="hr-edit-btn" href="javascript:;" id="add_new_card">Add Credit Card</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-stripped table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th class="col-xs-3 text-center">Name on Card</th>
                                                                    <th class="col-xs-2 text-center">Credit Card Number</th>
                                                                    <th class="col-xs-1 text-center">Credit Card Type</th>
                                                                    <th class="col-xs-2 text-center">Expiration Date</th>
                                                                    <th class="col-xs-1 text-center">Is Default</th>
                                                                    <th colspan="4" class="col-xs-3 text-center">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if (empty($cards)) { ?>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <h3 class="no-data" style="text-align: center;">No Credit Card found! </h3>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <?php foreach ($cards as $card) { ?>
                                                                        <tr id="card_row_<?php echo $card['sid'] ?>">
                                                                            <td class="text-center"><?php echo $card["name_on_card"]; ?></td>
                                                                            <td class="text-center"><?php echo $card["number"]; ?></td>
                                                                            <td class="text-center"><?php echo ucfirst($card["type"]); ?></td>
                                                                            <td class="text-center"><?php   if(($card["expire_month"] != NULL || $card["expire_month"] != '') && is_numeric($card["expire_month"])) {
                                                                                                                echo date('F', mktime(0, 0, 0, $card["expire_month"], 10)) . '/' . $card["expire_year"];  
                                                                                                            } else {
                                                                                                                echo 'Not Available';
                                                                                                            } ?></td>
                                                                            <td class="text-center col-xs-1"><?php echo $card['is_default'] != NULL && $card['is_default'] != 0 ? '<span class="text-success">Yes</span>' :  '<span class="text-danger">No</span>'; ?></td>
                                                                            <td class="text-center col-xs-1">   <?php if ($card['is_default'] != NULL && $card['is_default'] != 0) {?>
                                                                                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm  btn-block" onclick="fMakeUndefaultCard(<?php echo $card['sid'] ?>)">Make Un-Default</a>
                                                                                                                <?php } else { ?>
                                                                                                                    <a class="btn btn-success btn-block btn-sm" href="javascript:void(0);" onclick="fMakeDefaultCard(<?php echo $card['sid'] ?>)">Make Default</a>
                                                                                                                <?php  } ?></td>
                                                                            <td>    <?php if($card['active'] == 0) { ?>
                                                                                            <form id="form_activate_card_<?php echo $card['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                                <input type="hidden" id="perform_action" name="perform_action" value="activate_card" />
                                                                                                <input type="hidden" id="card_sid" name="card_sid" value="<?php echo $card['sid']; ?>" />
                                                                                                <button type="submit" class="btn btn-block btn-success btn-sm">Activate</button>
                                                                                            </form>
                                                                                    <?php } else { ?>
                                                                                            <form id="form_deactivate_card_<?php echo $card['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                                <input type="hidden" id="perform_action" name="perform_action" value="deactivate_card" />
                                                                                                <input type="hidden" id="card_sid" name="card_sid" value="<?php echo $card['sid']; ?>" />
                                                                                                <button type="submit" class="btn btn-block btn-warning btn-sm">Deactivate</button>
                                                                                            </form>
                                                                                    <?php } ?></td>
                                                                            <td class="text-center col-xs-1">
                                                                                <a class="btn btn-success btn-block btn-sm" href="<?php echo base_url('manage_admin/misc/edit_card') . '/' . $card['sid']; ?>" >Edit</a>
                                                                            </td>
                                                                            <!--
                                                                            <td class="text-center col-xs-1">
                                                                                <a class="btn btn-danger btn-block btn-sm" href="javascript:void(0);"  onclick="fRemoveCard(<?php /*echo $card['sid'] */?>)">Remove Card</a>
                                                                            </td>
                                                                            -->
                                                                        </tr>
                                                                    <?php }
                                                                } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($cards)) { ?>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="heading-title page-title">
                                                            <h1 class="page-title">Send CC Update Request</h1>
                                                        </div>
                                                        <div class="hr-box">
                                                            <div class="hr-innerpadding">
                                                                <form id="form_send_update_cc_request_email" enctype="multipart/form-data" method="post" action="<?php echo current_url();?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_update_cc_request_email" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid;?>" />
                                                                    <input type="hidden" name="company_name" value="<?php echo $company_name;?>" />
                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <div class="field-row">
                                                                                <label for="email_address">Contact Name</label>
                                                                                <input data-rule-required="true" id="contact_name" name="contact_name" type="text" class="hr-form-fileds"  value=""/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-8">
                                                                            <div class="field-row">
                                                                                <label for="email_address">Email Address</label>
                                                                                <input data-rule-required="true" data-rule-email="true" id="email_address" name="email_address" type="email" class="hr-form-fileds"  value="<?php echo $company_email; ?>"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-4">
                                                                            <div class="field-row">
                                                                                <label for="email_template">Email Template</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="email_template">
                                                                                        <option value="UPDATE_CREDIT_CARD_REQUEST">Already Expired Card</option>
                                                                                        <option value="CREDIT_CARD_EXPIRATION_NOTIFICATION">May Expire Soon</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-5">
                                                                            <div class="field-row">
                                                                                <label for="card_no">Card No</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="card_no">
                                                                                        <?php foreach ($cards as $card) { ?>
                                                                                            <option value="<?php echo $card['sid'] ?>"><?php echo $card["number"]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-3">
                                                                            <div class="field-row">
                                                                                <label for="button">&nbsp;</label>
                                                                                <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_send_update_cc_notification_emil();">Send</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-company cc-management payment-area-wrp">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <form action="" method="POST" id="cc_setup_fee">
                                            <input type="hidden" name="save_card" value="save_card">
                                            <input type="hidden" name="perform_action" id="perform_action" value="save_card">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title">Credit Card Information</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php echo form_label('Name On Card <span class="hr-required">*</span>', 'name_on_card'); ?>
                                                        <?php echo form_input('name_on_card', set_value('name_on_card'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('name_on_card'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="number">Credit Card Number <span class="hr-required">*</span></label>
                                                        <?php echo form_input('number', set_value('number'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="country">Type <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="type" name="type" >
                                                                <option selected="selected" disabled>Type</option>
                                                                <option value="visa">Visa</option>
                                                                <option value="mastercard">Mastercard</option>
                                                                <option value="discover">Discover</option>
                                                                <option value="amex">Amex</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="expire_month">Expiration Month <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="expire_month" name="expire_month">
                                                                <option selected="selected" disabled>Select a Month</option>
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
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="expire_year">Expiration Year <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <?php $current_year = date('Y'); ?>
                                                            <select class="invoice-fields" id="expire_year" name="expire_year">
                                                                <option selected="selected" disabled>Select a Year</option>
                                                                <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <label class="control control--checkbox label-margin">Make Default
                                                                <input class="select-domain" type="checkbox" name="is_default" value="1" >
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title">Billing Information</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label>Address Line 1</label>
                                                        <input type="text" value="" name="address_1" class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label>Address Line 2</label>
                                                        <input type="text" value=""  name="address_2"  class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>City</label>
                                                        <input type="text" value="" name="city"  class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>State</label>
                                                        <input type="text" value=""  name="state"  class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Zip Code</label>
                                                        <input type="text" value=""  name="zipcode"  class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Country/Region</label>
                                                        <input type="text" value=""   name="country" class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label>Phone Number</label>
                                                        <input type="text" value=""  name="phone_number" class="invoice-fields">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                                    <input type="submit" value="Save"  onclick="return pp_confirm_setup_fee()"class="btn btn-success">
                                                    <input type="button" value="Cancel" id="cancel_button" class="btn black-btn"/>
                                                </div>
                                            </div>
                                        </form>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function fMakeDefaultCard(cardId) {
        alertify.defaults.glossary.title = 'Default Card';
        alertify.confirm("Are you sure you want to make this card default?",
                function () {
                    var myUrl = "<?php echo base_url('manage_admin/misc/my_ajax_responder'); ?>";
                    var dataToSend = {'card_id': cardId, 'perform_action': 'make_default_card'}
                    var myRequest;

                    myRequest = $.ajax({
                                    url: myUrl,
                                    type: 'POST',
                                    data: dataToSend
                                });

                    myRequest.done(function (response) {
                        location.reload();
                    });
                },
                function () {
                    //alertify.error('');
                });
    }

    function fMakeUndefaultCard(cardId) {
        alertify.defaults.glossary.title = 'Undefault Card';
        alertify.confirm("Are you sure you want to make this card undefault?",
                function () {
                    var myUrl = "<?php echo base_url('manage_admin/misc/my_ajax_responder'); ?>";
                    var dataToSend = {'card_id': cardId, 'perform_action': 'make_undefault_card'}
                    var myRequest;

                    myRequest = $.ajax({
                                    url: myUrl,
                                    type: 'POST',
                                    data: dataToSend
                                });

                    myRequest.done(function (response) {
//                        console.log(response);
                        location.reload();
                    });
                },
                function () {
                    //alertify.error('');
                });
    }

    function fRemoveCard(cardId) {
        alertify.defaults.glossary.title = 'Delete Card';
        alertify.confirm("Are you sure you want to delete this card?",
                function () {
                    var myUrl = "<?php echo base_url('manage_admin/misc/my_ajax_responder'); ?>";
                    var dataToSend = {'card_id': cardId, 'perform_action': 'delete_card'}
                    var myRequest;

                    myRequest = $.ajax({
                                    url: myUrl,
                                    type: 'POST',
                                    data: dataToSend
                                });
                                
                    $("#card_row_" + cardId).hide();
                    myRequest.done(function (response) {
                    // location.reload();
                    });
                },
                function () {
                    //alertify.error('');
                });
    }
    
    function pp_confirm_setup_fee() {
        $("#cc_setup_fee").validate({
            ignore: ":hidden:not(select)",
            rules: {
                name_on_card : {
                    required : true
                },
                number: {
                    required: true,
                    minlength: 12,
                    maxlength: 19,
                    digits: true
                },
                expire_month: {
                    required: true
                },
                expire_year: {
                    required: true
                },
                type: {
                    required: true
                }
            },
            messages: {
                name_on_card : {
                    required : 'Name on Card is required!'
                },
                number: {
                    required: 'Credit Card No is required!',
                    minlength: 'Invalid Card No',
                    maxlength: 'Invalid Card No',
                    digits: 'Invalid Card No'
                },
                expire_month: {
                    required: 'Expiration Month is required!'
                },
                expire_year: {
                    required: 'Expiration Year is required!'
                },
                type: {
                    required: 'Credit Card Type is required!'
                }
            },
            submitHandler: function (form) {
                console.log('success');
                form.submit();
            }
        });
    }
    
    function func_send_update_cc_notification_emil() {
        $('#form_send_update_cc_request_email').validate();

        if($('#form_send_update_cc_request_email').valid()) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to send <strong>Update Credit Card Details</strong> Request Email to this Company?',
                function () {
                    $('#form_send_update_cc_request_email').submit();
                }, function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'Yes', cancel: 'No'});
        }
    }

    $("#add_new_card").click(function () {
        $('.payment-area-wrp').fadeIn();
        $('.card_div').hide();
    });

    $("#cancel_button").click(function () {
        $('.card_div').fadeIn();
        $('.payment-area-wrp').hide();
    });
</script>