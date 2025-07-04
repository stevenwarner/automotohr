<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="add-new-company  payment-area-wrp">
                                <form action="" method="POST" id="cc_setup_fee">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $card['company_sid']; ?>" />
                                    <input type="hidden" id="card_number" name="card_number" value="<?php echo $card['number']; ?>" />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title">Edit Credit Card Details</h1>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <?php echo form_label('Name On Card <span class="hr-required">*</span>', 'name_on_card'); ?>
                                                <?php echo form_input('name_on_card', set_value('name_on_card', $card['name_on_card']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Name On Card is Required!"'); ?>
                                                <?php echo form_error('name_on_card'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">  
                                            <div class="field-row">
                                                <label for="number">Credit Card Number</label>
                                                <p class="hr-form-fileds" style="padding: 8px;"> <?php echo $card['number']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row">
                                                <label for="number">Credit Card Type</label>
                                                <p class="hr-form-fileds" style="padding: 8px;"> <?php echo ucfirst($card['type']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row">
                                                <label for="expire_month">Expiration Month <span class="hr-required">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="expire_year" name="expire_month">
                                                        <option selected="selected" disabled>Select a Month</option>
                                                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                            <option value="<?php echo $i; ?>" <?php echo $card['expire_month'] == $i ? 'selected="selected"' : '';?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row">
                                                <label for="expire_year">Expiration Year <span class="hr-required">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <?php $current_year = date('Y'); ?>
                                                    <select class="invoice-fields" id="expire_year" name="expire_year">
                                                        <option selected="selected" disabled>Select a Year</option>
                                                        <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                            <option value="<?php echo $i; ?>" <?php echo $card['expire_year'] == $i ? 'selected="selected"' : ''; ?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="field-row">
                                                <label>&nbsp;</label>
                                                <label class="control control--checkbox font-normal">
                                                    Default
                                                    <input <?php echo $card['is_default'] == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="is_default" name="is_default" value="1" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title">Billing Information</h1>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label>Address Line 1</label>
                                                <input type="text" name="address_1" value="<?php echo $card['address_details']['address_1']; ?>" class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label>Address Line 2</label>
                                                <input type="text" value="<?php echo $card['address_details']['address_2']; ?>"  name="address_2"  class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label>City</label>
                                                <input type="text" value="<?php echo $card['address_details']['city']; ?>" name="city"  class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label>State</label>
                                                <input type="text" value="<?php echo $card['address_details']['state']; ?>"  name="state"  class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label>Zip Code</label>
                                                <input type="text" value="<?php echo $card['address_details']['zipcode']; ?>"  name="zipcode"  class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label>Country/Region</label>
                                                <input type="text" value="<?php echo $card['address_details']['country']; ?>"   name="country" class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label>Phone Number</label>
                                                <input type="text" value="<?php echo $card['address_details']['phone_number']; ?>"  name="phone_number" class="invoice-fields">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                            <input type="hidden" name="update_card" value="update_card">
                                            <input type="submit" value="Update"  onclick="return pp_confirm_setup_fee()"class="site-btn">
                                            <a href="<?php echo base_url('manage_admin/misc/cc_management') . '/' . $card['company_sid']; ?>" class="black-btn">Cancel</a>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function fMakeDefaultCard(cardId) {
        alertify.defaults.glossary.title = 'Default Card';
        alertify.confirm("Are you sure you want to make this card default?",
                function () {
                    var myUrl = "<?php echo base_url('misc/my_ajax_responder'); ?>";

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

    function fRemoveCard(cardId) {
        alertify.defaults.glossary.title = 'Delete Card';
        alertify.confirm("Are you sure you want to delete this card?",
                function () {
                    var myUrl = "<?php echo base_url('misc/my_ajax_responder'); ?>";

                    var dataToSend = {'card_id': cardId, 'perform_action': 'delete_card'}

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
    function pp_confirm_setup_fee() {
        $("#cc_setup_fee").validate({
            ignore: ":hidden:not(select)",
            rules: {
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

</script>