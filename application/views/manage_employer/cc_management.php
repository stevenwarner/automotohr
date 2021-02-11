<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="card_div">
                        <div class="dashboard-conetnt-wrp">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                            <div class="btn-wrp">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8"></div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <a href="javascript:;" id="add_new_card" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Credit Card</a><br/>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table-outer">
                                <table class="table table-bordered table-stripped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-5">Name on Card</th>
                                            <th class="col-xs-4">Credit Card Number</th>
                                            <th class="col-xs-1 text-center">Type</th>
                                            <th class="col-xs-1 text-center">Date</th>
                                            <th class="col-xs-1 text-center">Default</th>
                                            <th colspan="2" class="text-center">Action</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <?php   if(empty($cards)) { ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <h3 class="text-center">No Credit Card found! </h3>
                                                    </td>
                                                </tr>
                                    <?php   } else { ?>
                                                <form method="POST" name="ej_form" id="ej_form">
                                                    <?php foreach ($cards as $card) { ?>
                                                        <tr id="card_row_<?php echo $card['sid'] ?>">
                                                            <td> <?php echo $card["name_on_card"]; ?> </td>
                                                            <td> <?php echo $card["number"]; ?> </td>
                                                            <td class="text-center"><?php echo ucfirst($card["type"]); ?> </td>
                                                            <td class="text-center"><?php 
                                                            if($card["expire_month"] == 'xx'){
                                                                echo 'xx/' . $card["expire_year"]; 
                                                            } else {
                                                                echo date('F', mktime(0, 0, 0, $card["expire_month"], 10)) . '/' . $card["expire_year"]; 
                                                            }
                                                            ?> </td>
                                                            <td class="text-center"><?php   if ($card['is_default'] != NULL && $card['is_default'] != 0) {
                                                                            echo "Yes";
                                                                        } else {
                                                                            echo "No";
                                                                        } ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ($card['is_default'] != NULL && $card['is_default'] != 0) { ?>
                                                                            <a class="btn btn-secondary disabled" href="javascript:void(0);">Make default</a>
                                                                <?php } else { ?>
                                                                            <a class="btn btn-success" href="javascript:;" onclick="fMakeDefaultCard(<?php echo $card['sid'] ?>)">Make default</a>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="btn btn-success" href="<?php echo base_url('edit_card') . '/' . $card['sid']; ?>">Edit</a>
                                                            </td>
                                                            <!--
                                                            <td class="text-center">
                                                                <a class="btn btn-danger" href="javascript:;"  onclick="fRemoveCard(<?php /*echo $card['sid'] */?>)">Remove Card</a>
                                                            </td>
                                                            -->
                                                        </tr>
                                                    <?php } ?>
                                                </form>
                                    <?php   } ?>                                           
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="add-credit-card-container payment-area-wrp">
                            <?php echo form_open_multipart('', array('id' => 'cc_setup_fee')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php echo "Add new credit card"; ?></span>
                                </div>
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <div class="form-title-section title-row">
                                                <h2>Credit Card Information:</h2>                       
                                            </div>
                                            <li class="form-col-100">
                                                <label for="name_on_card">Name on Card <span class="staric">*</span></label>
                                                <input type="text" id="name_on_card" name="name_on_card" value="" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="number">Credit Card Number <span class="staric">*</span></label>
                                                <input type="text" name="number" value="" class="invoice-fields">
                                            </li> 
                                            <li class="form-col-50-right">
                                                <label>Card Type <span class="staric">*</span></label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="type" name="type">
                                                            <option selected="selected" disabled>Type</option>
                                                            <option value="visa">Visa</option>
                                                            <option value="mastercard">Mastercard</option>
                                                            <option value="discover">Discover</option>
                                                            <option value="amex">Amex</option>
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Expiration Month <span class="staric">*</span></label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="expire_month" name="expire_month">
                                                            <option selected="selected" disabled>Expiration Month</option>
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
                                            <li class="form-col-50-right">	
                                                <label>Year <span class="staric">*</span></label>									
                                                <div class="hr-select-dropdown">
                                                    <?php $current_year = date('Y'); ?>
                                                    <select class="invoice-fields" id="expire_year" name="expire_year">
                                                        <option selected="selected" disabled>Year</option>
                                                        <?php   for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php   } ?>
                                                    </select>   
                                                </div>		
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">Make Default
                                                    <input class="select-domain" type="checkbox" name="is_default" value="1" >
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <div class="form-title-section title-row">
                                                <h2>Billing Information:</h2>                       
                                            </div>
                                            <li class="form-col-100">
                                                <label>Address Line 1</label>
                                                <input type="text" value="" name="address_1" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100">
                                                <label>Address Line 2</label>
                                                <input type="text" value="" name="address_2" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>City</label>
                                                <input type="text" value="" name="city" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>State</label>
                                                <input type="text" value="" name="state" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Zip Code</label>
                                                <input type="text" value="" name="zipcode" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Country/Region</label>
                                                <input type="text" value="" name="country" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100">
                                                <label>Phone Number</label>
                                                <input type="text" value="" name="phone_number" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="save_card" value="save_card">
                                                <input type="submit" value="Save"  onclick="return pp_confirm_setup_fee()"class="submit-btn">
                                                <input type="button" value="Cancel" id="cancel_button" class="submit-btn btn-cancel"/>
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
                        //console.log(response);
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
                //console.log('success');
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