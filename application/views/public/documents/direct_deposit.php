<div class="js-ddbox">
    <div class="form-wrp">
        <div class="row">
            <div class="col-xs-12">
                <h2>Direct Deposit Authorization</h2>
                <hr />
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-2">
                <p><strong>Instructions </strong></p>
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
            
            for($i = 0; $i < 2; $i++) {
                $account = isset($data[$i]) ? $data[$i] : [
                    'sid' => 0,
                    'account_title' => '',
                    'account_type' => '',
                    'financial_institution_name' => '',
                    'routing_transaction_number' => '',
                    'account_number' => '',
                    'account_percentage' => '',
                    'voided_cheque' => ''
                ];
        ?>
            <div class="panel panel-default">
                <div class="panel-heading"  data-toggle="collapse" aria-expanded="false" data-target="#account_detail_<?=$i+1;?>"><strong>Account Detail <?=$i+1;?></strong></div>
                <div id="account_detail_<?=$i+1;?>" class="panel-body signature-variations collapse">
                    <div class="js-dd-row">
                        <div class="row">
                            <div class="col-sm-2">
                                <p><strong>Account Title <span class="cs-required">*</span> </strong></p>
                                <br />
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control js-account-title validate_error" value="<?=$account['account_title'];?>" error_key="acc_tit_<?php echo $i; ?>"/>
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
                                    <input type="radio" class="js-account-type validate_error" name="actype<?=$i+1;?>" style="width: 20px;" value="checking" <?php echo $account['account_type'] == 'checking' ? 'checked="true"' : '';?> error_key="acc_typ_<?php echo $i; ?>" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="control control--radio">
                                    Saving
                                    <input type="radio" class="js-account-type validate_error" name="actype<?=$i+1;?>" style="width: 20px;" value="savings" <?php echo $account['account_type'] == 'savings' ? 'checked="true"' : '';?> error_key="acc_typ_<?php echo $i; ?>" />
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
                                <input type="text" class="form-control js-financial-institution-name validate_error" value="<?=$account['financial_institution_name'];?>" error_key="acc_fin_<?php echo $i; ?>" />
                                <span id="acc_fin_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <p><strong>Bank routing number (ABA number): <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control js-account-routing-number validate_error" value="<?=$account['routing_transaction_number'];?>" error_key="acc_brn_<?php echo $i; ?>" />
                                <span id="acc_brn_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <p><strong>Account number: <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-10">
                                <input type="text"  class="form-control js-account-number validate_error"  value="<?=$account['account_number'];?>" error_key="acc_no_<?php echo $i; ?>" />
                                <span id="acc_no_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-5">
                                <p><strong>Percentage or dollar amount to be deposited to this account: <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-7">
                                <input type="text"  class="form-control js-account-percentage validate_error" value="<?=$account['account_percentage'];?>" error_key="acc_per_<?php echo $i; ?>" />
                                <span id="acc_per_<?php echo $i; ?>" class="error_text_color"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p><strong>Upload Voided Check: <span class="cs-required">*</span></strong></p>
                            </div>
                            <div class="col-sm-6">
                                <!-- <div class="cs-uploader js-uploader"data-id="<?=$i+1;?>">
                                    <div class="cs-uploader-text">
                                        <p class="text-center">Click Here / Drop File </p>
                                        <p class="js-filename" data-id="<?=$i+1;?>"></p>
                                    </div>
                                </div> -->
                                <!-- <input type="file" class="js-image" data-id="<?=$i+1;?>"/> -->
                                <!-- <img width="100%" src="<?php //echo $account['voided_cheque'];?>"/> -->
                                <div class="form-group">
                                    <div class="upload-file form-control">
                                        <span class="selected-file" id="name_ddi_file">Upload Check</span>
                                        <input class="js-image validate_error" data-id="<?=$i+1;?>" accept="image/*" type="file" error_key="acc_pic_<?php echo $i; ?>">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </div> 
                                <span id="acc_pic_<?php echo $i; ?>" class="error_text_color"></span>   
                            </div>
                            <div class="col-sm-6 js-dd-image" data-id="<?=$i+1;?>">
                                <input type="hidden" class="js-account-voided-cheque" data-id="<?=$i+1;?>" value="<?php echo $account['voided_cheque'];?>" />
                                <img src="<?php echo AWS_S3_BUCKET_URL . $account['voided_cheque'];?>" alt="Voided Check" style="<?php echo isset($account) && !empty($account['voided_cheque']) ? '' : 'display: none'; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <br />
        <?php } ?>

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
                <input disabled="true" type="text" class="form-control" value="<?=isset($company_name) ? $company_name : '';?>" />
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
                <p><strong>Authorized signature: <span class="cs-required">*</span><strong></p>
                <p>
                <?php if(empty($user_signature)) { ?>
                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                <?php } else { ?>
                    <img style="max-height: <?=SIGNATURE_MAX_HEIGHT;?>" src="<?php echo $user_signature; ?>"  id="draw_upload_img" />
                    <a href="javascript:;" class="btn btn-info replace_signature">Replace Signature</a>
                <?php } ?>
                </p>
            </div>
            <div class="col-sm-6">
                <p><strong>Employee Number #: <span class="cs-required">*</span><strong></p>
                <p><input type="text" class="form-control" id="employee_number"  value="<?php echo $employee_number; ?>"  /></p>
            </div>
        </div>
    
        <div class="row">
            <div class="col-sm-6">
                <p><strong>Print name: <span class="cs-required">*</span><strong></p>
                <p><input type="text" class="form-control" id="print_name" value="<?php echo $print_name; ?>" /></p>
            </div>
            <div class="col-sm-6">
                <p><strong>Date: <span class="cs-required">*</span><strong></p>
                <p><input type="text" id="consent_date" class="form-control js-dd-date" value="<?php echo $last_update_date; ?>" readonly/></p>
            </div>
        </div>    
        <!--  -->
        <div class="btn-wrp full-width mrg-top-20 text-center">
            <a href="jaavscript:void(0)" class="btn btn-info btn-success pull-right js-dd-save green_panel_consent_btn">I CONSENT AND ACCEPT</a>
        </div>
    </div>
</div>
