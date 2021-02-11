<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">                        
                        <div class="payment-area-wrp">
                            <?php echo form_open_multipart('', array('id' => 'cc_setup_fee')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php echo "Edit credit card"; ?></span>
                                </div>
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <div class="form-title-section title-row">
                                                <h2>Credit Card Information:</h2>                       
                                            </div>
                                            <li class="form-col-100">
                                                <label for="name_on_card">Name on Card <span class="staric">*</span></label>
                                                <input type="text" id="name_on_card" name="name_on_card" value="<?php echo $card['name_on_card']; ?>" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="number">Credit Card Number</label>
                                                <p style="padding:13px;" class="invoice-fields"> <?php echo $card['number']; ?></p>
                                            </li> 
                                            <li class="form-col-50-right">
                                                <label for="number">Card Type</label>
                                                <p style="padding:13px;" class="invoice-fields"> <?php echo ucfirst($card['type']); ?></p>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Expiration Month <span class="staric">*</span></label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="expire_year" name="expire_month">
                                                        <option selected="selected" disabled>Select a Month</option>
                                                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                            <option value="<?php echo $i; ?>" <?php
                                                            if ($card['expire_month'] == $i) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo $i; ?></option>
                                                                <?php } ?>
                                                    </select>   
                                                </div>								
                                            </li>
                                            <li class="form-col-50-right">	
                                                <label>Year <span class="staric">*</span></label>									
                                                <div class="hr-select-dropdown">
                                                    <?php $current_year = date('Y'); ?>
                                                    <select class="invoice-fields" id="expire_year" name="expire_year">
                                                        <option selected="selected" disabled>Select a Year</option>
                                                        <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                            <option value="<?php echo $i; ?>" <?php
                                                            if ($card['expire_year'] == $i) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo $i; ?></option>
                                                                <?php } ?>
                                                    </select>   
                                                </div>		
                                            </li>
                                            <div class="form-title-section title-row">
                                                <h2>Billing Information:</h2>                       
                                            </div>
                                            <li class="form-col-100">
                                                <label>Address Line 1</label>
                                                <input type="text" value="<?php echo $card['address_details']['address_1']; ?>" name="address_1" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100">
                                                <label>Address Line 2</label>
                                                <input type="text" value="<?php echo $card['address_details']['address_2']; ?>" name="address_2" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>City</label>
                                                <input type="text" value="<?php echo $card['address_details']['city']; ?>" name="city" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>State</label>
                                                <input type="text" value="<?php echo $card['address_details']['state']; ?>" name="state" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Zip Code</label>
                                                <input type="text" value="<?php echo $card['address_details']['zipcode']; ?>" name="zipcode" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Country/Region</label>
                                                <input type="text" value="<?php echo $card['address_details']['country']; ?>" name="country" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100">
                                                <label>Phone Number</label>
                                                <input type="text" value="<?php echo $card['address_details']['phone_number']; ?>" name="phone_number" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="update_card" value="update_card">
                                                <input type="submit" value="Save"  onclick="return pp_confirm_setup_fee()"class="submit-btn">
                                                <a href="<?php echo base_url('cc_management'); ?>" class="submit-btn btn-cancel">Cancel</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
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
                                                                    $("#card_row_" + cardId).hide();
                                                                    myRequest.done(function (response) {
                                                                        console.log(response);
                                                                        //location.reload();
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
                                                    $("#add_new_card").click(function () {
                                                        $('.payment-area-wrp').fadeIn();
                                                        $('.card_div').hide();
                                                    });


                                                    $("#cancel_button").click(function () {
                                                        $('.card_div').fadeIn();
                                                        $('.payment-area-wrp').hide();
                                                    });

</script>