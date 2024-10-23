<?php
$check_1 = isset($data[0]['voided_cheque']) && !empty($data[0]['voided_cheque']) ? $data[0]['voided_cheque'] : '';
$check_2 = isset($data[1]['voided_cheque']) && !empty($data[1]['voided_cheque']) ? $data[1]['voided_cheque'] : '';;
$instruction = '';
$employee_sid = '';
$print_name = '';
$last_update_date = '';
$user_signature = '';
$user_sid = isset($dd_user_sid) ? $dd_user_sid : '';
$user_print_name = ucwords($cn['first_name'] . ' ' . $cn['last_name']);
$user_primary_signature = isset($users_sign_info) && isset($users_sign_info['signature_bas64_image']) ? $users_sign_info['signature_bas64_image'] : '';

for ($i = 0; $i < 1; $i++) {
    $instruction = isset($data[$i]) && isset($data[$i]['instructions']) ? $data[$i]['instructions'] : '';
    $employee_sid = isset($data[$i]) && isset($data[$i]['employee_number']) ? $data[$i]['employee_number'] : $user_sid;
    $print_name = isset($data[$i]) && isset($data[$i]['print_name']) ? $data[$i]['print_name'] : $user_print_name;
    $last_update_date = isset($data[$i]) && isset($data[$i]['consent_date']) ? date("m/d/Y", strtotime($data[$i]['consent_date'])) : date("m/d/Y");
    $user_signature = isset($data[$i]) && isset($data[$i]['user_signature']) && !empty($data[$i]['user_signature']) ? $data[$i]['user_signature'] : $user_primary_signature;
}
//
$summery = "";
//
if (count($data) == 1) {
    $accountTitle = $data[0]['account_title'];
    $amount = $data[0]['account_percentage'];
    //
    if ($data[0]['deposit_type'] == "amount") {
        $summery = "The sum of $".$amount." will be deposited into the account under the name of ".$accountTitle.".";
    } else {
        $summery = "The full amount will be deposited into the account under the name of ".$accountTitle.".";
    }
    
} else if (count($data) == 2) {
    $accountTitle1 = $data[0]['account_title'];
    $amount1 = $data[0]['account_percentage'];
    $accountTitle2 = $data[1]['account_title'];
    $amount2 = $data[1]['account_percentage'];
    //
    if ($data[0]['deposit_type'] == "amount") {
        $summery = "The sum of $".$amount1." will be deposited into the account under the name of ".$accountTitle1.", with the remainder going into the account under the name of ".$accountTitle2.".";
    } else {
        $summery = $amount1."% of the amount will be deposited into the account under the name of ".$accountTitle1." and the remaining ".$amount2."% will go into the account under the name of ".$accountTitle2.".";
    }
}


?>
<style>
    .js-ddbox .row {
        margin-bottom: 20px;
    }

    .error_text_color {
        color: red;
        font-weight: 500;
    }
</style>

<div class="js-ddbox">
    <div class="form-wrp">
        <div class="row">
            <div class="col-xs-12">
                <h2>Direct Deposit Authorization</h2>
                <hr />
                <h4 class="cs-required"><?=$summery?></h4>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12">
                <p class="text-danger" id='js-Multi-account-msg'></p>
            </div>

        </div>


        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-2">
                <p><strong>Instructions </strong> <i class="fa fa-info-circle jsPop" aria-hidden="true" title="Note" data-content='Employee can set a note/instruction for the employer. For instance, "I will add the voided check next week." '></i></p>
            </div>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="instructions" value="<?php echo $instruction; ?>" />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <p><strong>Employee:</strong> Fill out and return to your employer</p>
                <p><strong>Employer:</strong> Save for your files only.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <p class="text-justify">This document must be signed by employees requesting automatic deposit of paychecks and retained on file by the employer. Employees must attach a voided check for each of their accounts to help verify their account numbers and bank routing numbers.</p>
            </div>
        </div>

        <?php
        for ($i = 0; $i < 2; $i++) {
            $account = isset($data[$i]) ? $data[$i] : [
                'sid' => 0,
                'account_title' => '',
                'account_type' => '',
                'financial_institution_name' => '',
                'routing_transaction_number' => '',
                'account_number' => '',
                'account_percentage' => '',
                'voided_cheque' => '',
                'deposit_type' => isset($data[0]['deposit_type']) ? $data[0]['deposit_type'] : "percentage"
            ];

            $notifyUser = $i == 1 ? 'jsNotifyUser' : "";
        ?>
            <div class="panel panel-default">
                <div class="panel-heading" style="cursor: pointer;" data-toggle="collapse" aria-expanded="<?= $i == 0 ? "true" : "false"; ?>" data-target="#account_detail_<?= $i + 1; ?>">
                    <span class="glyphicon glyphicon-plus pull-right font_plus_sign"></span>
                    <h4 style="color:#cc1100"><strong>Account Detail <?= $i + 1; ?> <?= $i == 0 ? '<span class="cs-required">*</span>  <span class="pull-right" style="color:green !important;">Primary Account</span>' : ""; ?></strong></h4>
                </div>
                <div id="account_detail_<?= $i + 1; ?>" class="panel-body signature-variations  <?= $i == 0 ? "" : "collapse"; ?>">
                    <div class="js-dd-row">
                        <div class="row">
                            <div class="col-sm-2">
                                <p><strong>Account Title <span class="cs-required">*</span> </strong></p>
                                <br />
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control js-account-title validate_error <?= $notifyUser ?>" value="<?= $account['account_title']; ?>" id="jsAccountTitle_<?php echo $i; ?>" error_key="acc_tit_<?php echo $i; ?>" />
                                <span id="acc_tit_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                            <input type="hidden" id="table_row_id_<?php echo $i; ?>" value="<?php echo $account['sid'] ?>">
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p><strong>Account Type: <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-2">
                                <label class="control control--radio">
                                    Checking
                                    <input type="radio" class="js-account-type validate_error" name="actype<?= $i + 1; ?>" style="width: 20px;" value="checking" <?php echo $account['account_type'] == 'checking' ? 'checked="true"' : ''; ?> error_key="acc_typ_<?php echo $i; ?>" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="control control--radio">
                                    Saving
                                    <input type="radio" class="js-account-type validate_error" name="actype<?= $i + 1; ?>" style="width: 20px;" value="savings" <?php echo $account['account_type'] == 'savings' ? 'checked="true"' : ''; ?> error_key="acc_typ_<?php echo $i; ?>" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <span id="acc_typ_<?php echo $i; ?>" class="error_text_color"></span>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <p><strong>Financial Institution (Bank) Name <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control js-financial-institution-name validate_error <?= $notifyUser ?>" value="<?= $account['financial_institution_name']; ?>" error_key="acc_fin_<?php echo $i; ?>" />
                                <span id="acc_fin_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <p><strong>Bank routing number (ABA number): <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control js-account-routing-number validate_error <?= $notifyUser ?>" value="<?= $account['routing_transaction_number']; ?>" error_key="acc_brn_<?php echo $i; ?>" />
                                <span id="acc_brn_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <p><strong>Account number: <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control js-account-number validate_error <?= $notifyUser ?>" value="<?= $account['account_number']; ?>" error_key="acc_no_<?php echo $i; ?>" />
                                <span id="acc_no_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <?php if ($i == 0) { ?>
                            <div class="row">
                                <div class="col-sm-2">
                                    <p><strong>Deposit Type: <span class="cs-required">*</span></strong></p>
                                </div>
                                <div class="col-sm-10">
                                    <div class="hr-select-dropdown">
                                        <select id="deposit_type_<?php echo $i; ?>" name="deposit_type_<?php echo $i; ?>" class="form-control js-deposit_type" data-id="<?php echo $i; ?>">
                                            <option value="percentage" <?php echo isset($account['deposit_type']) && $account['deposit_type'] == 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                                            <option value="amount" <?php echo isset($account['deposit_type']) && $account['deposit_type'] != 'percentage' ? 'selected' : ''; ?>>Amount</option>
                                        </select>
                                    </div>
                                    <span id="EDT_<?php echo $i; ?>" class="error_text_color"></span>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-5">
                                <?php
                                if (isset($account['deposit_type']) && $account['deposit_type'] == 'percentage') {
                                    $DTL = "Percentage value to be deposited to this account:";
                                    $ADDON = '%';
                                } else if (isset($account['deposit_type']) && $account['deposit_type'] == 'amount') {
                                    $DTL = "Dollar amount to be deposited to this account:";
                                    $ADDON = '$';
                                } else {
                                    $DTL = "Percentage value to be deposited to this account:";
                                    $ADDON = '%';
                                }
                                ?>
                                <p><strong><span id="DTT_<?php echo $i; ?>" class="js-DDT"><?php echo $DTL; ?></span> <span class="cs-required">*</span></strong></p>


                            </div>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon js-account-percentage-addon" id="js-account-percentage-addon_<?php echo $i; ?>"><?= $ADDON; ?></div>
                                    <input type="text" class="form-control js-account-percentage validate_error <?= $notifyUser ?>" value="<?= $account['account_percentage']; ?>" error_key="acc_per_<?php echo $i; ?>" id="DTV_<?php echo $i; ?>" />
                                </div>
                                <span id="acc_per_<?php echo $i; ?>" class="error_text_color"></span>
                                <p><strong><span id="DT_remaining_amount_msg_<?php echo $i; ?>" class="text-danger"></span></strong></p>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p><strong>Upload Voided Check:</strong></p>
                            </div>
                            <div class="col-sm-12 ">
                                <input class="js-image validate_error hidden" id="jsVoidedCheck<?= $i + 1; ?>" data-id="<?= $i + 1; ?>" type="file" />
                                <!-- <div class="cs-uploader js-uploader"data-id="<?= $i + 1; ?>">
                                    <div class="cs-uploader-text">
                                        <p class="text-center">Click Here / Drop File </p>
                                        <p class="js-filename" data-id="<?= $i + 1; ?>"></p>
                                    </div>
                                </div> -->
                                <!-- <input type="file" class="js-image" data-id="<?= $i + 1; ?>"/> -->
                                <!-- <img width="100%" src="<?php //echo $account['voided_cheque'];
                                                            ?>"/> -->
                                <div class="form-group hidden">
                                    <div class="upload-file form-control">
                                        <span class="selected-file" id="name_ddi_file">Upload Check</span>
                                        <input class="js-image validate_error" data-id="<?= $i + 1; ?>" accept="image/*" type="file" error_key="acc_pic_<?php echo $i; ?>">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </div>
                                <span id="acc_pic_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
            <br />
        <?php } ?>

        <div class="clearfix"></div>
        <hr />

        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <p><strong>Authorization</strong> (enter your company name in the blank space below)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <p>This authorizes</p>
            </div>
            <div class="col-sm-8">
                <input disabled="true" type="text" class="form-control" value="<?= isset($company_name) ? $company_name : ''; ?>" />
            </div>
            <div class="col-sm-2">
                <p>(the “Company”)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p class="text-justify">to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-sm-6">
                <p><strong>Authorized signature: <span class="cs-required">*</span></strong></p>
                <p>
                    <?php if (empty($user_signature)) { ?>
                        <?php $this->load->view('static-pages/e_signature_button'); ?>
                    <?php } else { ?>
                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT; ?>" src="<?php echo $user_signature; ?>" id="draw_upload_img" />
                        <a href="javascript:;" class="btn btn-info replace_signature">Replace Signature</a>
                    <?php } ?>
                </p>
            </div>
            <div class="col-sm-6">
                <p><strong>Employee Number #: </strong></p>
                <p><input type="text" class="form-control" id="employee_number" value="<?php echo !empty($employee_number) ? $employee_number : $dd_user_sid; ?>" /></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <p><strong>Print name: <span class="cs-required">*</span></strong></p>
                <p><input type="text" class="form-control" id="print_name" value="<?php echo $print_name; ?>" /></p>
            </div>
            <div class="col-sm-6">
                <p><strong>Date: <span class="cs-required">*</span></strong></p>
                <p><input type="text" id="consent_date" class="form-control js-dd-date" value="<?php echo $last_update_date; ?>" readonly /></p>
            </div>
        </div>
        <!--  -->
        <div class="btn-wrp full-width mrg-top-20 text-center">
            <a href="jaavscript:void(0)" class="btn btn-info btn-success pull-right js-dd-save green_panel_consent_btn">I CONSENT AND ACCEPT</a>
        </div>
        <input type="hidden" id="users_type" name="users_type" value="<?php echo $dd_user_type; ?>" />
        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $dd_user_sid; ?>" />
        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_id; ?>" />
        <input type="hidden" id="user_previous_signature" value="<?php echo $user_signature; ?>" />
        <input type="hidden" id="send_email_notification" value="<?php echo $send_email_notification; ?>" />
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait, while we are processing your request.
        </div>
    </div>
</div>

<div id="jsDDModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Bank routing number/Account Number confirmation</h4>
            </div>
            <div id="jsDDModalBody" class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <strong>Account 1</strong>
                                </th>    
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table table-bordered table-condensed table-hover">
                                        <tbody>
                                            <tr>
                                                <th>Confirm bank routing number (ABA number) <span class="cs-required">*</span></th>
                                                <td>
                                                    <input type="text" class="form-control js-account-routing-number" id="jsRoutingNumber1" value=""/>
                                                    <span id="jsRoutingNumber1Error" class="error_text_color"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Confirm account Number <span class="cs-required">*</span></th>
                                                <td>
                                                    <input type="text" class="form-control js-account-number" id="jsAccountNumber1" value=""/>
                                                    <span id="jsAccountNumber1Error" class="error_text_color"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive hidden" id="jsAccountTwo">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <strong>Account 2</strong>
                                </th>    
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table table-bordered table-condensed table-hover">
                                        <tbody>
                                            <tr>
                                                <th>Confirm bank routing number (ABA number) <span class="cs-required">*</span></th>
                                                <td>
                                                    <input type="text" class="form-control js-account-routing-number" id="jsRoutingNumber2" value=""/>
                                                    <span id="jsRoutingNumber2Error" class="error_text_color"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Confirm account Number <span class="cs-required">*</span></th>
                                                <td>
                                                    <input type="text" class="form-control js-account-number" id="jsAccountNumber2" value=""/>
                                                    <span id="jsAccountNumber2Error" class="error_text_color"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="jsDDModalFooter" class="modal-footer">
                <button class="btn btn-black csF16 csB7" data-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-orange csF16 csB7 jsDDConfirmNumbers">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('static-pages/e_signature_popup'); ?>

<style>
    .cs-required {
        color: #cc1100;
        font-weight: bolder;
        font-size: 20px;
    }

    .cs-uploader {
        position: relative;
        border: 2px dashed #ccc;
        min-height: 200px;
        cursor: pointer;
    }

    .cs-uploader .cs-uploader-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        text-align: center;
    }

    .cs-uploader .cs-uploader-text p {
        font-weight: bolder;
        font-size: 20px;
    }

    .cs-uploader .cs-uploader-text p:nth-child(2) {
        font-size: 14px;
        color: #cc1100;
    }

    .cs-uploader img {
        position: absolute;
        left: 0;
        top: 0;
        opacity: .7;
        height: 200px;
        font-weight: bolder;
    }

    .js-dd-image img {
        display: block;
        margin: auto;
        max-width: 100%;
    }

    .validation_error {
        color: red;
    }
</style>

<!--  -->
<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>">
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>

<script>
    $(function() {
        
        $('.js-deposit_type').change(function(e) {
            if ($(this).val() == "percentage") {
                $(".js-DDT").text("Percentage value to be deposited to this account:");
                $('.js-account-percentage-addon').text('%')
                $('.js-deposit_type').val('percentage');
                //
                displayAmountNote("hide");


            } else if ($(this).val() == "amount") {
                $(".js-DDT").text("Dollar amount to be deposited to this account:");
                $('.js-account-percentage-addon').text('$')
                $('.js-deposit_type').val('amount');
               
                if ($('#DTV_1').val() != '' && $('#DTV_1').val() != '0') {
                    displayAmountNote("show");
                }
            }
        });

        $('.jsNotifyUser').keyup(function(e) {
            var accountType = $('#deposit_type_0').val();
            //
            if (accountType == 'amount') {
                displayAmountNote("show");
            } else {
                displayAmountNote("hide");
            }
            
        });

        $(".js-account-routing-number").bind("paste", function(e){
            e.preventDefault(); // Prevent the paste action
            alertify.alert("<b>Pasting is not permitted for the bank routing number (ABA number).</b>").set({
                title: "WARNING !"
            });
        });

        $(".js-account-number").bind("paste", function(e){
            e.preventDefault(); // Prevent the paste action
            alertify.alert("<b>Pasting is not permitted for the account number.</b>").set({
                title: "WARNING !"
            });
        });

        //
        $('#DTV_0').change(function(e) {
            let depositType = $('#deposit_type_0').val();
            let account__amount = $('#DTV_0').val();

            if (depositType == 'amount' && $('#DTV_1').val() != '' && $('#DTV_1').val() != '0') {
                displayAmountNote("show");
            }

            if ((depositType == 'amount' )&& (account__amount == '' || account__amount == '0')) {
                displayAmountNote("hide");
            }

        });

        function displayAmountNote(type) {
            //
            if (type == 'show') {
                var accountTitle1 = $('#jsAccountTitle_0').val() ? $('#jsAccountTitle_0').val() : 'Account1';
                var accountTitle2 = $('#jsAccountTitle_1').val() ? $('#jsAccountTitle_1').val() : 'Account2';
                //
                $('#DT_remaining_amount_msg_0').text('Note: The sum of $' + $('#DTV_0').val() + ' will be deposited into the account under the name of "'+accountTitle1+'", with the remainder going into the account under the name of "'+ accountTitle2+'"');
                $('#DT_remaining_amount_msg_1').text('Note: The sum of $' + $('#DTV_0').val() + ' will be deposited into the account under the name of "'+accountTitle1+'", with the remainder going into the account under the name of "'+ accountTitle2+'"');
            } else {
                $('#DT_remaining_amount_msg_0').text('');
                $('#DT_remaining_amount_msg_1').text('');
            }
        }


        //
        var filo = {};
        //'"'
        var form_data = '';

        $('.js-dd-date').datepicker({
            minDate: 0,
            maxDate: 0
        });

        //
        $('.js-uploader').on('click', function(e) {
            //
            e.preventDefault();
            $(this).parent().find('.js-image').trigger('click');
        });

        $('.jsPop').popover({
            html: true,
            placement: "top"
        });
        
        $('.jsDDConfirmNumbers').on('click', function() {
            confirmValidation();
        });

        $('#jsRoutingNumber1').blur(function() {
            if ($('#jsRoutingNumber1').val() != form_data[0]['accountRoutingNumber']) {
                $('#jsRoutingNumber1Error').text('Provided ABA number not matched');
            } else if ($('#jsRoutingNumber1').val() == form_data[0]['accountRoutingNumber']) {
                $('#jsRoutingNumber1Error').text('');
            }
        });

        $('#jsAccountNumber1').blur(function() {
            if ($('#jsAccountNumber1').val() != form_data[0]['accountNumber']) {
                $('#jsAccountNumber1Error').text('Provided account number not matched');
            } else if ($('#jsAccountNumber1').val() == form_data[0]['accountNumber']) {
                $('#jsAccountNumber1Error').text('');
            }
        });

        $('#jsRoutingNumber2').blur(function() {
            if ($('#jsRoutingNumber2').val() != form_data[1]['accountRoutingNumber']) {
                $('#jsRoutingNumber2Error').text('Provided ABA number not matched');
            } else if ($('#jsRoutingNumber2').val() == form_data[1]['accountRoutingNumber']) {
                $('#jsRoutingNumber2Error').text('');
            }
        });

        $('#jsAccountNumber2').blur(function() {
            if ($('#jsAccountNumber2').val() != form_data[1]['accountNumber']) {
                $('#jsAccountNumber2Error').text('Provided account number not matched');
            } else if ($('#jsAccountNumber2').val() == form_data[1]['accountNumber']) {
                $('#jsAccountNumber2Error').text('');
            }
        });

        function confirmValidation () {
            //
            var record_error = 0;
            var errorArray = [];
            //
            if (!$('#jsRoutingNumber1').val().length) {
                errorArray.push('Routing number for account 1 is required.');
            }
            //
            if (!$('#jsAccountNumber1').val().length) {
                errorArray.push('Account number for account 1 is required.');
            }
            //
            if ($('#jsRoutingNumber1').val() != form_data[0]['accountRoutingNumber']) {
                $('#jsRoutingNumber1Error').text('Provided ABA number not matched');
                record_error = 1;
            } else if ($('#jsRoutingNumber1').val() == form_data[0]['accountRoutingNumber']) {
                $('#jsRoutingNumber1Error').text('');
            }

            if ($('#jsAccountNumber1').val() != form_data[0]['accountNumber']) {
                $('#jsAccountNumber1Error').text('Provided account number not matched');
                record_error = 1;
            } else if ($('#jsAccountNumber1').val() == form_data[0]['accountNumber']) {
                $('#jsAccountNumber1Error').text('');
            }

            if (form_data[1]) {
                //
                if (!$('#jsRoutingNumber2').val().length) {
                    errorArray.push('Routing number for account 2 is required.');
                }
                //
                if (!$('#jsAccountNumber2').val().length) {
                    errorArray.push('Account number for account 2 is required.');
                }
                //
                if ($('#jsRoutingNumber2').val() != form_data[1]['accountRoutingNumber']) {
                    $('#jsRoutingNumber2Error').text('Provided ABA number not matched');
                    record_error = 1;
                } else if ($('#jsRoutingNumber2').val() == form_data[1]['accountRoutingNumber']) {
                    $('#jsRoutingNumber2Error').text('');
                }

                if ($('#jsAccountNumber2').val() != form_data[1]['accountNumber']) {
                    $('#jsAccountNumber2Error').text('Provided account number not matched');
                    record_error = 1;
                } else if ($('#jsAccountNumber2').val() == form_data[1]['accountNumber']) {
                    $('#jsAccountNumber2Error').text('');
                }

            }
            //
            if (errorArray.length) {
                return alertify.alert(
                    "ERROR!",
                    getErrorsStringFromArray(errorArray)
                );
            }
            //
            if (record_error == 0) {
                $("#jsDDModal").modal('hide');
                submit_form_data();
            } 
        }

        function getErrorsStringFromArray(errorArray, errorMessage) {
            return (
                "<strong><p>" +
                (errorMessage ?
                    errorMessage :
                    "Please, resolve the following errors") +
                "</p></strong><br >" +
                errorArray.join("<br />")
            );
        }


        // //
        // $('.js-image').on('change', function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();
        //     filo = e.target.files[0];
        //     let id = $(this).data('id') ;
        //     // Set filename
        //     $('.js-account-voided-cheque[data-id="'+( $(this).data('id') )+'"]').val('');
        //     $('.js-filename[data-id="'+( $(this).data('id') )+'"]').text(filo['name']).show();
        //     // Load uploaded image into box
        //     let fr = new FileReader();
        //     fr.readAsDataURL(e.target.files[0]);
        //     fr.onload = function(e) { 
        //         $('.js-dd-image[data-id="'+( id )+'"]').find('img').prop('src', this.result);
        //         $('.js-dd-image[data-id="'+( id )+'"]').find('img').show();
        //     };
        // });


        //
        $('.js-dd-save').click(function(e) {
            e.preventDefault();

            // Validate Data
            var employee_number = $('#employee_number').val();
            var print_name = $('#print_name').val();
            var consent_date = $('#consent_date').val();
            var instructions = $('#instructions').val();
            var signature_flag = 0;
            //
            var todayDate = new Date('<?php echo date('m/d/Y'); ?>');
            var consentDate = new Date(consent_date);
            //

            if (todayDate.getTime() > consentDate.getTime()) {
                alertify.alert('Please Provide Today Date').set({
                    title: "WARNING !"
                });
                return exit;
            }
            //
            <?php if (empty($user_signature)) { ?>
                var drawn_signature = $('#drawn_signature').val();

                if (drawn_signature == '' || drawn_signature == null) {
                    signature_flag = 1;
                } else {
                    $('#user_previous_signature').val(drawn_signature);
                }
            <?php } ?>
            if (print_name != '' && consent_date != '' && signature_flag != 1) {
                getData();
            } else {
                if (signature_flag == 1) {
                    alertify.alert('Please Provide E-Signature').set({
                        title: "WARNING !"
                    });
                } else if (print_name == '') {
                    alertify.alert('Please Provide print Name').set({
                        title: "WARNING !"
                    });
                } else if (consent_date == '') {
                    alertify.alert('Please Provide Date').set({
                        title: "WARNING !"
                    });
                }

            }
            //
        });

        //
        function getData() {
            //
            var megaOBJ = [];
            var record_error = 0;

            var obj_length = '<?php echo $i; ?>';
            //
            $('.js-dd-row').map(function(el, i) {

                var account_title = $(this).find('.js-account-title').val();
                var account_type = $(this).find('.js-account-type:checked').val();
                var financial_institution_name = $(this).find('.js-financial-institution-name').val();
                var account_routing_no = $(this).find('.js-account-routing-number').val();
                var account_number = $(this).find('.js-account-number').val();
                var deposit_type = $(this).find('.js-deposit_type').val();
                var account_percentage = $(this).find('.js-account-percentage').val();
                var account_cheque = $(`#jsVoidedCheck${el+1}`).mFileUploader('get');
                var old_account_cheque = $(this).find('.js-account-voided-cheque').val();
                //

                if (account_title == '' && account_type == undefined && account_routing_no == '' && account_number == '' && account_percentage == '' && financial_institution_name == '') {
                    if (el == 0) {
                        $('#acc_tit_0').text('Please Provide Title');
                        $('#acc_typ_0').text('Please Select Type');
                        $('#acc_fin_0').text('Please Provide Financial Institution Name');
                        $('#acc_brn_0').text('Please Provide ABA number');
                        $('#acc_no_0').text('Please Provide Account Number');
                        $('#acc_per_0').text('Please Provide Percentage');
                        if (deposit_type == 0) {
                            $('#EDT_0').text('Please select deposit type');
                        }
                        // $('#acc_pic_0').text('Please Provide Voided Check Image');
                        alertify.alert('Please Provide Account 1 Detail').set({
                            title: "WARNING !"
                        });
                        record_error = 1
                    }
                } else if (
                    account_title != '' || 
                    (el == 0 && account_type != undefined)  || 
                    account_routing_no != '' || 
                    account_number != '' || 
                    (el == 0 && account_percentage != '') ||
                    (el == 1 && deposit_type == 'percentage') ||
                    financial_institution_name != ''
                ) {

                    var tit_flag = 0;
                    var typ_flag = 0;
                    var ac_brn_flag = 0;
                    var ac_no_flag = 0;
                    var pre_flag = 0;
                    var pic_flag = 0;
                    var fin_flag = 0;
                    var dt_flag = 0;

                    if (account_title == '') {
                        tit_flag = 1;
                        $('#acc_tit_' + el).text('Please Provide Title');
                    } else {
                        $('#acc_tit_' + el).text('');
                    }

                    if (account_type == undefined) {
                        typ_flag = 1;
                        //
                        $('#acc_typ_' + el).text('Please Select Type');
                    } else {
                        $('#acc_typ_' + el).text('');
                    }

                    if (financial_institution_name == '') {
                        fin_flag = 1;
                        $('#acc_fin_' + el).text('Please Provide Financial Institution Name');
                    } else {
                        $('#acc_fin_' + el).text('');
                    }

                    var pattern = /\d{9}/;
                    if (account_routing_no == '') {
                        ac_brn_flag = 1;
                        $('#acc_brn_' + el).text('Please Provide ABA number');
                    } else if (!account_routing_no.match(pattern) || account_routing_no.length != 9) {  
                        ac_brn_flag = 1;
                        $('#acc_brn_' + el).text('Please provide 9 digits Only');  
                    } else {
                        $('#acc_brn_' + el).text('');
                    }

                    if (account_number == '') {
                        ac_no_flag = 1;
                        $('#acc_no_' + el).text('Please Provide Account Number');
                    } else {
                        $('#acc_no_' + el).text('');
                    }

             
                    if (account_percentage == '') {
                        pre_flag = 1;
                        $('#acc_per_' + el).text('Please Provide Percentage');
                    } else {
                        $('#acc_per_' + el).text('');
                    }

                    if (deposit_type == 0) {
                        dt_flag = 1;
                        $('#EDT_' + el).text('Please select deposit type');
                    } else {
                        $('#EDT_' + el).text('');
                    }

                    //
                    if (el == 1) {
                        let dptVal1 = $("#deposit_type_1").val();

                        if (dptVal1 == "amount") {
                            dt_flag = 0;
                            pre_flag = 0;
                            $('#acc_per_' + el).text('');
                            $('#DTV_' + el).val(0);
                            acc_per_0
                            record_error = 0;
                        }
                        //
                    }

                    if (tit_flag == 0 && typ_flag == 0 && ac_no_flag == 0 && pre_flag == 0 && pic_flag == 0 && fin_flag == 0 && dt_flag == 0 && ac_brn_flag == 0) {
                        let obj = {};

                        obj.accountTitle = account_title;
                        obj.accountType = account_type;
                        obj.financial_institution_name = financial_institution_name;
                        obj.accountRoutingNumber = account_routing_no;
                        obj.accountNumber = account_number;
                        obj.depositType = deposit_type;
                        obj.accountPercentage = account_percentage;
                        if (account_type != undefined) {
                            obj.accountVoidedCheque = account_cheque;
                        }

                        megaOBJ.push(obj);


                    } else {
                        var account_no = el + 1;
                        record_error = 1
                        alertify.alert('Please Provide Data for Account ' + account_no + ' Detail').set({
                            title: "WARNING !"
                        });
                    }

                }

                if (account_cheque !== undefined && account_cheque.hasError !== undefined && account_cheque.hasError === true) {
                    alertify.alert(
                        'WARNING!',
                        `Invalid uploaded file for account ${el+1}`,
                        () => {}
                    );
                    record_error = 1;
                }
                
                // if (obj_length == (el + 1) && record_error == 0) {
                //     form_data = megaOBJ;
                //       submit_form_data();
                // }

            });


            if (megaOBJ.length > 1) {
                if (megaOBJ[1]['depositType'] == undefined) {
                    megaOBJ[1]['depositType'] = megaOBJ[0]['depositType'];
                }
                //
                if (megaOBJ[0]['depositType'] != megaOBJ[1]['depositType']) {
                    alertify.alert('Deposit Type should be the same in Account 1 and Account 2 ').set({
                        title: "WARNING !"
                    });
                    record_error = 1;
                }

                //               
              
                let totalPercentage = parseFloat(megaOBJ[0]['accountPercentage']) + parseFloat(megaOBJ[1]['accountPercentage']);

                if (megaOBJ[0]['depositType'] == 'percentage' && megaOBJ[1]['depositType'] == 'percentage') {

                    if (totalPercentage > 100 || totalPercentage < 100 || !totalPercentage) {
                        alertify.alert('To ensure that the total percentage value deposited into Account 1 and Account 2 should be 100%').set({
                            title: "WARNING !"
                        });
                        record_error = 1;
                    }
                }
            }

            //

            if (megaOBJ.length == 1) {
                //
                let totalPercentage = parseFloat(megaOBJ[0]['accountPercentage']);

                if (megaOBJ[0]['depositType'] == 'percentage') {

                    if (totalPercentage != 100) {
                        alertify.alert('To ensure that the total percentage value deposited into Account 1 is 100%').set({
                            title: "WARNING !"
                        });
                        record_error = 1;
                    }

                }

                if (!totalPercentage) {
                    alertify.alert('Ensure that a deposited amount is numeric.').set({
                        title: "WARNING !"
                    });
                    record_error = 1;
                }
            }
            //  if (obj_length == (el + 1) && record_error == 0) {


            if (record_error == 0) {
                //
                if (megaOBJ.length > 1) {
                    $("#jsAccountTwo").removeClass("hidden");
                }
                //
                form_data = megaOBJ;
                //
                $("#jsRoutingNumber1").val("");
                $("#jsAccountNumber1").val("");
                $("#jsRoutingNumber2").val("");
                $("#jsAccountNumber2").val("");
                $("#jsDDModal").modal('show');
            }

        }

        //
        function getExtension(n) {
            let t = n.split('.');
            return t[t.length - 1];
        }

        $('.validate_error').on('click', function() {
            var error_key = $(this).attr('error_key');
            $('#' + error_key).text('');
        })

        function submit_form_data() {
            var message = "Are you sure you want to consent and save information?<br>";
            //
            if (form_data.length == 1 && form_data[0]['depositType'] == "amount") {
                message += "<br>In the case of a single bank account, we recommend to use split by percentage to ensure the full amount is captured.</br>";
            }
            //
            if (form_data.length == 1) {
                var accountTitle = form_data[0]['accountTitle'];
                var amount = form_data[0]['accountPercentage'];
                //
                if (form_data[0]['depositType'] == "amount") {
                    message += '<br>The sum of <strong>$'+amount+'</strong> will be deposited into the account under the name of <strong>"'+accountTitle+'"</strong>.';
                } else {
                    message += '<br>The full amount will be deposited into the account under the name of <strong>"'+accountTitle+'"</strong>.';
                }
                
            } else if (form_data.length == 2) {
                $accountTitle1 = form_data[0]['accountTitle'];
                $amount1 = form_data[0]['accountPercentage'];
                $accountTitle2 = form_data[1]['accountTitle'];
                $amount2 = form_data[1]['accountPercentage'];
                //
                if (form_data[0]['depositType'] == "amount") {
                    message += '<br>The sum of <strong>$'+$amount1+'</strong> will be deposited into the account under the name of <strong>"'+$accountTitle1+'"</strong>, with the remainder going into the account under the name of <strong>"'+$accountTitle2+'"</strong>.';
                } else {
                    message += '<br><strong>'+$amount1+'%</strong> of the amount will be deposited into the account under the name of <strong>"'+$accountTitle1+'"</strong> and the remaining <strong>'+$amount2+'%</strong> will go into the account under the name of <strong>"'+$accountTitle2+'"</strong>.';
                }
            }
            //
            alertify.confirm(
                'Are you Sure?',
                message,
                function() {
                    $('#my_loader').show();
                    $('#js-dd-save').addClass('disabled-btn');
                    $('#js-dd-save').prop('disabled', true);


                    var company_sid = $('#company_sid').val();
                    var user_sid = $('#users_sid').val();
                    var user_type = $('#users_type').val();
                    var employee_number = $('#employee_number').val();
                    var print_name = $('#print_name').val();
                    var consent_date = $('#consent_date').val();
                    var instructions = $('#instructions').val();
                    var drawn_signature = $('#user_previous_signature').val();
                    var send_email_notification = $('#send_email_notification').val();

                    //
                    let an = $('#send_email_notification').val();

                    $.each(form_data, function(i, v) {
                        // This will stop sending multi emails
                        // for a single DDI form
                        if (i == 0 && form_data.length == 2 && an == 'yes') send_email_notification = 'no';
                        if (i == 1 && form_data.length == 2 && an == 'yes') send_email_notification = 'yes';

                        var record_sid = $('#table_row_id_' + i).val();

                        var new_form_data = new FormData();
                        new_form_data.append('account_code', i + 1);
                        new_form_data.append('record_sid', record_sid);
                        new_form_data.append('company_sid', company_sid);
                        new_form_data.append('user_sid', user_sid);
                        new_form_data.append('user_type', user_type);
                        new_form_data.append('instructions', instructions)
                        new_form_data.append('account_title', v.accountTitle);
                        new_form_data.append('account_type', v.accountType);
                        new_form_data.append('financial_institution_name', v.financial_institution_name);
                        new_form_data.append('routing_transaction_number', v.accountRoutingNumber);
                        new_form_data.append('account_number', v.accountNumber);
                        new_form_data.append('deposit_type', v.depositType);
                        new_form_data.append('account_percentage', v.accountPercentage);

                        if (v.accountVoidedCheque != undefined) {
                            new_form_data.append('upload_img', v.accountVoidedCheque);
                        }

                        new_form_data.append('employee_number', employee_number);
                        new_form_data.append('print_name', print_name);
                        new_form_data.append('consent_date', consent_date);
                        new_form_data.append('drawn_signature', drawn_signature);
                        new_form_data.append('send_email_notification', send_email_notification);

                        $.ajax({
                            url: '<?= base_url('direct_deposit/ajax_handler') ?>',
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'post',
                            data: new_form_data,
                            success: function(data) {
                                if (form_data.length == (i + 1)) {
                                    $('#my_loader').hide();
                                    $('#js-dd-save').removeClass('disabled-btn');
                                    $('#js-dd-save').prop('disabled', false);

                                    alertify.alert('SUCCESS!', 'New information has been saved', function() {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function() {}
                        });
                    });
                },
                function() {
                    // alertify.alert('WARNING!', 'Canceled',function () {

                    // });
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });


        }

        var currentSignature = '';
        getCurrentSignature();

        $(document).on('click', '.replace_signature', function() {
            //
            $('#draw_upload_img').attr('src', currentSignature);
            $('#user_previous_signature').val(currentSignature);
            $('.replace_signature').hide();
        });

        function getCurrentSignature() {
            //
            var form_data = new FormData();
            form_data.append('company_sid', <?php echo $company_id; ?>);
            form_data.append('user_sid', <?php echo $dd_user_sid; ?>);
            form_data.append('user_type', '<?php echo $dd_user_type; ?>');
            //
            $.ajax({
                url: '<?= base_url('direct_deposit/get_e_signature') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    if (data !== '') {

                        var obj = jQuery.parseJSON(data);
                        var signature_bas64_image = obj.signature_bas64_image;
                        currentSignature = signature_bas64_image;
                        //
                        if (signature_bas64_image == "<?= $user_signature; ?>") {
                            $('.replace_signature').remove();
                        } else {
                            $('.replace_signature').add();
                        }
                    }

                },
                error: function() {}
            });
        }


        // 
        $('#jsVoidedCheck1').mFileUploader({
            fileLimit: '10MB', // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'pptx'],
            text: 'Click / Drag to upload', // (Optional)
            onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
            onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
            placeholderImage: '<?php echo $check_1; ?>' // Default is empty ('') but can be set any image  (Optional)
        })

        // 
        $('#jsVoidedCheck2').mFileUploader({
            fileLimit: '10MB', // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'pptx'],
            text: 'Click / Drag to upload', // (Optional)
            onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
            onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
            placeholderImage: '<?php echo $check_2; ?>' // Default is empty ('') but can be set any image  (Optional)
        })

        $(document).ready(function () {
            <?php if ($data[0]['deposit_type'] == 'amount' && $data[1]['deposit_type'] == 'amount') { ?>
                displayAmountNote("show");
            <?php } ?>



            <?php if ($data[0]['deposit_type'] == 'amount' && $data[1]['deposit_type'] == 'amount') { ?>
                displayAmountNote("show");
            <?php } ?>
        })  

    })  
</script>