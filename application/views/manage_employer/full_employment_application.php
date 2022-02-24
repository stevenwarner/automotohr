<?php 
    //
    $dob = (isset($employer["dob"]) && !empty($employer["dob"]) && $employer["dob"] != '0000-00-00') ? date('m-d-Y', strtotime(str_replace('-', '/', $employer["dob"]))) : '';
    // Only convert if PP or ALP
    // checkes are on
    if($_ssv){
        // When EF is not saved
        // Convert Social Security Number
        $employer['ssn'] = ssvReplace($employer['ssn']);
        // Convert Date Of Birth
        // will accept the format YYYY-MM-DD
        $dob = $dob != '' ? ssvReplace($dob, true) : $dob;
        //
        if(isset($drivers_license_details['license_number'])) $drivers_license_details['license_number'] =  ssvReplace($drivers_license_details['license_number']);
        // if(isset($drivers_license_details['license_expiration_date'])) $drivers_license_details['license_expiration_date'] = ssvReplace($drivers_license_details['license_expiration_dat0'], true);
        // When EF is saved
        if(isset($formpost['TextBoxDOB'])) $formpost['TextBoxDOB'] = ssvReplace($formpost['TextBoxDOB'], true);
        if(isset($formpost['TextBoxSSN'])) $formpost['TextBoxSSN'] = ssvReplace($formpost['TextBoxSSN']);
        if(isset($formpost['TextBoxDriversLicenseNumber'])) $formpost['TextBoxDriversLicenseNumber'] = ssvReplace($formpost['TextBoxDriversLicenseNumber']);
        // if(isset($formpost['TextBoxDriversLicenseExpiration'])) $formpost['TextBoxDriversLicenseExpiration'] = ssvReplace($formpost['TextBoxDriversLicenseExpiration'], true);
    }

    //
    $from_cntrl = [];
    unset($from_cntrl);

?>

<?php $this->load->view('form_full_employment_application/company_privacy_policy'); ?>
<?php $this->load->view('form_full_employment_application/company_terms_of_use'); ?> 
<?php //if($load_view == 'old') {  ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow margin-top">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="job-title-text"> 
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                                <p><span class="staric">Please Do Not Use Your Web Browser's AutoComplete Feature</span></p>
                            </div>
                            <?php if ($full_employment_app_print) { ?>
                                <div class="box-view reports-filtering">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');">
                                                    <i class="fa fa-print" aria-hidden="true"></i> 
                                                    Print Form
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="dashboard-conetnt-wrp" id="print_div">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php echo form_open('', array('id' => 'fullemploymentapplication')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="employement-application-form universal-form-style-v2">
                                    <ul>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>First Name <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="text" required="required" name="first_name" value="<?php
                                                        if (isset($formpost['first_name'])) {
                                                            echo $formpost['first_name'];
                                                        } else {
                                                            echo $employer['first_name'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('first_name'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Middle Name</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxNameMiddle" value="<?php
                                                        if (isset($formpost['TextBoxNameMiddle'])) {
                                                            echo $formpost['TextBoxNameMiddle'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxNameMiddle'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Last Name <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="text" required="required" name="last_name" value="<?php
                                                        if (isset($formpost['last_name'])) {
                                                            echo $formpost['last_name'];
                                                        } else {
                                                            echo $employer['last_name'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('last_name'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Suffix </label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="suffix">
                                                                <option value="" selected="selected"></option>
                                                                <option value="JR" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == 'JR') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Junior</option>
                                                                <option value="SR" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == 'SR') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Senior</option>
                                                                <option value="2" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == '2') {
                                                                    echo "selected";
                                                                }
                                                                ?>>II</option>
                                                                <option value="3" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == '3') {
                                                                    echo "selected";
                                                                }
                                                                ?>>III</option>
                                                                <option value="4" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == '4') {
                                                                    echo "selected";
                                                                }
                                                                ?>>IV</option>
                                                                <option value="V" <?php
                                                                if (isset($formpost['suffix']) && $formpost['suffix'] == 'V') {
                                                                    echo "selected";
                                                                }
                                                                ?>>V</option>
                                                            </select>
                                                        </div>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Social Security Number <?=$ssn_required == 1 ? '<span class="staric">*</span>' : '';?> </label>
                                                        <input class="invoice-fields" type="text" name="TextBoxSSN"  <?=$ssn_required == 1 ? 'required="true"' : ''?> value="<?php
                                                        if (isset($formpost['TextBoxSSN'])) {
                                                            echo $formpost['TextBoxSSN'];
                                                        } else if($employer['ssn'] != ''){
                                                            echo $employer['ssn'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxSSN'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Date of Birth <?=$dob_required == 1 ? '<span class="staric">*</span>' : '';?></label>
                                                        <input class="invoice-fields" id="dob"  readonly="" type="text" name="TextBoxDOB"  <?=$dob_required == 1 ? 'required="true"' : ''?> value="<?php
                                                        if (isset($formpost['TextBoxDOB'])) {
                                                            echo $formpost['TextBoxDOB'];
                                                        }
                                                        else if($dob){
                                                            
                                                            echo $dob;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxDOB'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="autoheight">
                                                        Your date of birth is required and may be used for purposes directly related to the background check process and will not be used for any other purpose. Failure to provide your date of birth may cause a delay in processing your application for employment.
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Email Address <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="email" required="required" name="email" value="<?php
                                                        if (isset($employer['email'])) {
                                                            echo $employer['email'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('email'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Confirm Email Address <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="email" required="required" name="TextBoxAddressEmailConfirm" value="<?php
                                                        if (isset($formpost['TextBoxAddressEmailConfirm']) && !empty($formpost['TextBoxAddressEmailConfirm'])) {
                                                            echo $formpost['TextBoxAddressEmailConfirm'];
                                                        } else {
                                                            echo $employer['email'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressEmailConfirm'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="autoheight">
                                                        Your email address is required and is used for purposes directly related to the application process and/or legally required notifications. Your email address will not be shared or used for any other purpose.
                                                    </li>
                                                </div> 
                                                

                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                         <?php 
                                                                $key = 'is_already_employed'; 
                                                                if (isset($user_info[$key])) {
                                                                    $def_value = $user_info[$key];
                                                                    $yes_selected = ( $def_value == 'Yes' ? true : false );
                                                                    $no_selected = ( $def_value == 'No' ? true : false ); 
                                                                } else {
                                                                    $yes_selected = false;
                                                                    $no_selected = true; 
                                                                }
                                                            ?>
                                                            <label class="autoheight">Have you ever been employed with our company or our Affiliate companies?<?=$affiliate ?  ' <span class="staric">*</span>' : ''; ?></label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="is_already_employed_yes" class="check_value validate_affiliate_company" value="Yes" name="is_already_employed"
                                                                <?php
                                                                    echo set_radio($key, 'Yes', $yes_selected);
                                                                // if (isset($formpost['is_already_employed']) && $formpost['is_already_employed'] == 'Yes') {
                                                                //     echo " checked";
                                                                // }
                                                                ?>>
                                                                <label for="is_already_employed_yes">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="is_already_employed_no" class="check_value validate_affiliate_company" value="No" name="is_already_employed"
                                                                <?php
                                                                    echo set_radio($key, 'No', $no_selected);
                                                                // if (isset($formpost['is_already_employed']) && $formpost['is_already_employed'] == 'No') {
                                                                //     echo " checked";
                                                                // }
                                                                ?>>
                                                                <label for="is_already_employed_no">No</label>
                                                            </div>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <small class="autoheight">If yes, position held/what company or Affiliate company?<?=$affiliate ? ' <span class="staric yacr">*</span>' : '';?></small>
                                                            <div class="comment-area">
                                                                <textarea name="previous_company_name" id="previous_company_name" maxlength="512" onkeyup="check_length('previous_company_name')" class="form-col-100 invoice-fields"><?php
                                                                    if (isset($formpost['previous_company_name'])) {
                                                                        echo $formpost['previous_company_name'];
                                                                    }
                                                                    ?></textarea>
                                                                     <div id="show_specific_error" style="color:red"></div>
                                                                <span id="license_guilty_details_remaining">512 Characters Left</span>
<?php echo form_error('license_guilty_details'); ?>
                                                                <p style="display: none;" id="previous_company_name">512</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li>
                                                        <label>Current Residence <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="text" required="required" name="Location_Address" value="<?php
                                                        if (isset($formpost['Location_Address']) && !empty($formpost['Location_Address'])) {
                                                            echo $formpost['Location_Address'];
                                                        } else {
                                                            echo $employer['Location_Address'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('Location_Address'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>How Long?</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressLenghtCurrent" value="<?php
                                                        if (isset($formpost['TextBoxAddressLenghtCurrent'])) {
                                                            echo $formpost['TextBoxAddressLenghtCurrent'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressLenghtCurrent'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>City <span class="staric">*</span></label>
                                                        <input class="invoice-fields" type="text" name="Location_City" value="<?php
                                                        if (isset($formpost['Location_City']) && !empty($formpost['Location_City'])) {
                                                            echo $formpost['Location_City'];
                                                        } else {
                                                            echo $employer['Location_City'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('Location_City'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Zip Code <span class="staric">*</span></label>
                                                        <input class="invoice-fields" required="required" type="text" name="Location_ZipCode" value="<?php
                                                        if (isset($formpost['Location_ZipCode']) && !empty($formpost['Location_ZipCode'])) {
                                                            echo $formpost['Location_ZipCode'];
                                                        } else {
                                                            echo $employer['Location_ZipCode'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('Location_ZipCode'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php 
                                                                                                              
                                                        if(isset($formpost['Location_Country'])){

                                                        }else{
                                                            $formpost['Location_Country']=' ';
                                                        } 
                                                      
                                                        $country_id = $LC = isset($employer['Location_Country']) ? $employer['Location_Country'] : $formpost['Location_Country']; 
                                                        ?>
                                                        <label>Country:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>, 'state')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select('Location_Country', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('Location_Country'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php 
                                                        if(isset($formpost['Location_State'])){}else{$formpost['Location_State']='';}
                                                        $state_id = $LS = isset($employer['Location_State']) ? $employer['Location_State'] : $formpost['Location_State']; ?>
                                                        <label>State:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="Location_State" id="state">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('Location_State', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
<?php } ?>
                                                            </select>
                                                        </div>
<?php echo form_error('Location_State'); ?>
                                                    </li>
                                                </div>

                                                <!--
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
                                                        <div class="checkbox-field">
                                                            <input id="CheckBoxAddressInternationalCurrent" type="checkbox" name="CheckBoxAddressInternationalCurrent" value="1" 
                                                <?php /* if (isset($formpost['CheckBoxAddressInternationalCurrent']) && $formpost['CheckBoxAddressInternationalCurrent'] == 1) {
                                                  echo " checked";
                                                  } else if ($employer["Location_Country"] != 227) {
                                                  echo " checked";
                                                  }
                                                 */ ?>>
                                                            <label for="CheckBoxAddressInternationalCurrent">Non USA Address</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                -->
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
                                                            <label>Former Residence</label>
                                                            <input class="invoice-fields" type="text" name="TextBoxAddressStreetFormer1" value="<?php
                                                            if (isset($formpost['TextBoxAddressStreetFormer1'])) {
                                                                echo $formpost['TextBoxAddressStreetFormer1'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('TextBoxAddressStreetFormer1'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>How Long?</label>
                                                            <input class="invoice-fields" type="text" name="TextBoxAddressLenghtFormer1" value="<?php
                                                            if (isset($formpost['TextBoxAddressLenghtFormer1'])) {
                                                                echo $formpost['TextBoxAddressLenghtFormer1'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('TextBoxAddressLenghtFormer1'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Zip Code</label>
                                                            <input class="invoice-fields" type="text" name="TextBoxAddressZIPFormer1" value="<?php
                                                            if (isset($formpost['TextBoxAddressZIPFormer1'])) {
                                                                echo $formpost['TextBoxAddressZIPFormer1'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('TextBoxAddressZIPFormer1'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City</label>
                                                            <input class="invoice-fields" type="text" name="TextBoxAddressCityFormer1" value="<?php
                                                            if (isset($formpost['TextBoxAddressCityFormer1'])) {
                                                                echo $formpost['TextBoxAddressCityFormer1'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('TextBoxAddressCityFormer1'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressCountryFormer1">
                                                        <li>
                                                            <?php $country_id = isset($formpost['DropDownListAddressCountryFormer1']) ? $formpost['DropDownListAddressCountryFormer1'] : 0; ?>
                                                            <label>Country:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="former_country" name="DropDownListAddressCountryFormer1" onchange="getStates(this.value, <?php echo $states; ?>, 'former_state')">
                                                                    <option value="">Please Select</option>
                                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListAddressCountryFormer1', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('DropDownListAddressCountryFormer1'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressStateFormer1">
                                                        <li>
                                                            <?php $state_id = isset($formpost['DropDownListAddressStateFormer1']) ? $formpost['DropDownListAddressStateFormer1'] : 0; ?>
                                                            <label>State:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListAddressStateFormer1" id="former_state">
                                                                    <?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                            ?>
                                                                            <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListAddressStateFormer1', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                        <?php } ?>
<?php } ?>
                                                                </select>
                                                            </div>
<?php echo form_error('DropDownListAddressStateFormer1'); ?>
                                                        </li>
                                                    </div>

                                                    <!--
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="checkbox-field">
                                                                <input id="CheckBoxAddressInternationalFormer1" value="1" type="checkbox" name="CheckBoxAddressInternationalFormer1"
                                                    <?php /* if (isset($formpost['CheckBoxAddressInternationalFormer1']) && $formpost['CheckBoxAddressInternationalFormer1'] == 1) {
                                                      echo " checked";
                                                      }
                                                     */ ?>>
                                                                <label for="CheckBoxAddressInternationalFormer1">Non USA Address</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    -->

                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li>
                                                        <label>Former Residence</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressStreetFormer2" value="<?php
                                                        if (isset($formpost['TextBoxAddressStreetFormer2'])) {
                                                            echo $formpost['TextBoxAddressStreetFormer2'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressStreetFormer2'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>How Long?</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressLenghtFormer2" value="<?php
                                                        if (isset($formpost['TextBoxAddressLenghtFormer2'])) {
                                                            echo $formpost['TextBoxAddressLenghtFormer2'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressLenghtFormer2'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Zip Code</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressZIPFormer2" value="<?php
                                                        if (isset($formpost['TextBoxAddressZIPFormer2'])) {
                                                            echo $formpost['TextBoxAddressZIPFormer2'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressZIPFormer2'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>City</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressCityFormer2" value="<?php
                                                        if (isset($formpost['TextBoxAddressCityFormer2'])) {
                                                            echo $formpost['TextBoxAddressCityFormer2'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressCityFormer2'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressCountryFormer2">
                                                    <li>
                                                        <?php $country_id = isset($formpost['DropDownListAddressCountryFormer2']) ? $formpost['DropDownListAddressCountryFormer2'] : 0; ?>
                                                        <label>Country:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="former2_country" name="DropDownListAddressCountryFormer2" onchange="getStates(this.value, <?php echo $states; ?>, 'former2_state')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select('DropDownListAddressCountryFormer2', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('DropDownListAddressCountryFormer2'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressStateFormer2">
                                                    <li>
                                                        <?php $state_id = isset($formpost['DropDownListAddressStateFormer2']) ? $formpost['DropDownListAddressStateFormer2'] : 0; ?>
                                                        <label>State:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="DropDownListAddressStateFormer2" id="former2_state">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListAddressStateFormer2', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
<?php } ?>
                                                            </select>
                                                        </div>
<?php echo form_error('DropDownListAddressStateFormer2'); ?>
                                                    </li>
                                                </div>

                                                <!--
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
                                                        <div class="checkbox-field">
                                                            <input id="CheckBoxAddressInternationalFormer2" value="1" type="checkbox" name="CheckBoxAddressInternationalFormer2"
                                                <?php /* if (isset($formpost['CheckBoxAddressInternationalFormer2']) && $formpost['CheckBoxAddressInternationalFormer2'] == 1) {
                                                  echo " checked";
                                                  }
                                                 */ ?>>
                                                            <label for="CheckBoxAddressInternationalFormer2">Non USA Address</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                -->

                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Other Mailing Address</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressStreetFormer3" value="<?php
                                                        if (isset($formpost['TextBoxAddressStreetFormer3']) && !empty($formpost['TextBoxAddressStreetFormer3'])) {
                                                            echo $formpost['TextBoxAddressStreetFormer3'];
                                                        }elseif(isset($employer['extra_info']['other_email'])){
                                                            echo $employer['extra_info']['other_email'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressStreetFormer3'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Zip Code</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressZIPFormer3" value="<?php
                                                        if (isset($formpost['TextBoxAddressZIPFormer3'])) {
                                                            echo $formpost['TextBoxAddressZIPFormer3'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressZIPFormer3'); ?>
                                                    </li>
                                                </div>



                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Primary Telephone <span class="staric">*</span></label>
                                                        <input class="invoice-fields" required="required" type="text" name="PhoneNumber" value="<?php echo isset($formpost['PhoneNumber']) && !empty($formpost['PhoneNumber']) ? $formpost['PhoneNumber'] : $employer['PhoneNumber']; ?>" id="PhoneNumber">
                                                        <?php echo form_error('PhoneNumber'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Mobile Telephone </label>
                                                        <input class="invoice-fields" type="text" name="TextBoxTelephoneMobile" value="<?php echo isset($formpost['TextBoxTelephoneMobile']) ? $formpost['TextBoxTelephoneMobile'] : '';?>">
                                                        <?php echo form_error('TextBoxTelephoneMobile'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Other Telephone </label>
                                                        <input class="invoice-fields" type="text" name="TextBoxTelephoneOther" value="<?php echo isset($formpost['TextBoxTelephoneOther']) && !empty($formpost['TextBoxTelephoneOther']) ? ($formpost['TextBoxTelephoneOther']) : (isset($employer['extra_info']['other_PhoneNumber']) ? $employer['extra_info']['other_PhoneNumber'] : '');?>">
                                                        <?php echo form_error('TextBoxTelephoneOther'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <li>
                                                        <label>City</label>
                                                        <input class="invoice-fields" type="text" name="TextBoxAddressCityFormer3" value="<?php
                                                        if (isset($formpost['TextBoxAddressCityFormer3'])) {
                                                            echo $formpost['TextBoxAddressCityFormer3'];
                                                        }
                                                        ?>">
                                                               <?php echo form_error('TextBoxAddressCityFormer3'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressCountryFormer3">
                                                    <li>
                                                        <?php $country_id = isset($formpost['DropDownListAddressCountryFormer3']) ? $formpost['DropDownListAddressCountryFormer3'] : 0; ?>
                                                        <label>Country:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="former3_country" name="DropDownListAddressCountryFormer3" onchange="getStates(this.value, <?php echo $states; ?>, 'former3_state')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select('DropDownListAddressCountryFormer3', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('DropDownListAddressCountryFormer3'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListAddressStateFormer3">
                                                    <li>
                                                        <?php $state_id = isset($formpost['DropDownListAddressStateFormer3']) ? $formpost['DropDownListAddressStateFormer3'] : 0; ?>
                                                        <label>State:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="DropDownListAddressStateFormer3" id="former3_state">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListAddressStateFormer3', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
<?php } ?>
                                                            </select>
                                                        </div>
<?php echo form_error('DropDownListAddressStateFormer3'); ?>
                                                    </li>
                                                </div>


                                                <div class="bg-color">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">The position I am applying for is:</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input id="RadioButtonListPostionTime_0" type="radio" value="full_time" name="RadioButtonListPostionTime"
                                                                <?php
                                                                if ((isset($formpost['RadioButtonListPostionTime']) && $formpost['RadioButtonListPostionTime'] == 'full_time') || $employer['employee_type'] == 'full-time' || $employer['employee_type'] == 'fulltime'){
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListPostionTime_0">Full time</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input id="RadioButtonListPostionTime_1" type="radio" value="part_time" name="RadioButtonListPostionTime"
                                                                <?php
                                                                if ((isset($formpost['RadioButtonListPostionTime']) && $formpost['RadioButtonListPostionTime'] == 'part_time') || $employer['employee_type'] == 'part-time' || $employer['employee_type'] == 'parttime'){
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListPostionTime_1">Part time</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input id="RadioButtonListPostionTime_2" type="radio" value="full_or_parttime" name="RadioButtonListPostionTime"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListPostionTime']) && $formpost['RadioButtonListPostionTime'] == 'full_or_parttime') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListPostionTime_2">Full or Part time</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">If you want to apply for more than one position, please list them in this field.</label>
                                                            <input class="invoice-fields" type="text" name="TextBoxPositionDesired" value="<?php
                                                            if (isset($formpost['TextBoxPositionDesired']) && !empty($formpost['TextBoxPositionDesired'])) {
                                                                echo $formpost['TextBoxPositionDesired'];
                                                            }elseif(isset($from_cntrl) && $from_cntrl == 'emp'){
                                                                echo $employer['job_title'];
                                                            }
                                                            ?>">
<?php echo form_error('TextBoxPositionDesired'); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>What date can you begin work?</label>
                                                        <input type="text" class="invoice-fields startdate" readonly="" name="TextBoxWorkBeginDate" value="<?php
                                                        if (isset($formpost['TextBoxWorkBeginDate'])) {
                                                            echo $formpost['TextBoxWorkBeginDate'];
                                                        }
                                                        ?>">
<?php echo form_error('TextBoxWorkBeginDate'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Expected compensation</label>
                                                        <input type="text" class="invoice-fields" name="TextBoxWorkCompensation" value="<?php
                                                        if (isset($formpost['TextBoxWorkCompensation'])) {
                                                            echo $formpost['TextBoxWorkCompensation'];
                                                        }
                                                        ?>">
<?php echo form_error('TextBoxWorkCompensation'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li class="form-col-100 autoheight">
                                                        <label class="autoheight">Do you have transportation to/from work?</label>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="Yes" id="RadioButtonListWorkTransportation_0" name="RadioButtonListWorkTransportation"
                                                            <?php
                                                            if (isset($formpost['RadioButtonListWorkTransportation']) && $formpost['RadioButtonListWorkTransportation'] == 'Yes') {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListWorkTransportation_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="No" id="RadioButtonListWorkTransportation_1" name="RadioButtonListWorkTransportation"
                                                            <?php
                                                            if (isset($formpost['RadioButtonListWorkTransportation']) && $formpost['RadioButtonListWorkTransportation'] == 'No') {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListWorkTransportation_1">No</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                <div class="bg-color-v2">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Are you 18 years or older?<?=$eight_plus ?  ' <span class="staric">*</span>' : ''; ?></label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListWorkOver18_0"  name="RadioButtonListWorkOver18"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListWorkOver18'])) {
                                                                    if($formpost['RadioButtonListWorkOver18'] == 'Yes') echo " checked";
                                                                }elseif(isset($above18) && $above18 >= 18){
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListWorkOver18_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListWorkOver18_1" name="RadioButtonListWorkOver18"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListWorkOver18'])) {
                                                                    if($formpost['RadioButtonListWorkOver18'] == 'No') echo " checked";
                                                                }elseif(isset($above18) && $above18 < 18){
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListWorkOver18_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-9">
                                                    <li class="form-col-100 autoheight">
                                                        <label class="autoheight">Have you ever used or been known by any other names, including nicknames?</label>
                                                    </li>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="Yes" id="RadioButtonListAliases_0" name="RadioButtonListAliases"
                                                            <?php
                                                            if (isset($formpost['RadioButtonListAliases']) && $formpost['RadioButtonListAliases'] == 'Yes') {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListAliases_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="No" id="RadioButtonListAliases_1" name="RadioButtonListAliases"
                                                            <?php
                                                            if (isset($formpost['RadioButtonListAliases']) && $formpost['RadioButtonListAliases'] == 'No') {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListAliases_1">No</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="comment-area">
                                                        <small>If yes, please explain and indicate name(s):</small>
                                                        <textarea name="nickname_or_othername_details" id="nickname_or_othername_details" class="form-col-100 invoice-fields" maxlength="512" onkeyup="check_length('nickname_or_othername_details')"><?php
                                                            if (isset($formpost['nickname_or_othername_details'])) {
                                                                echo $formpost['nickname_or_othername_details'];
                                                            }
                                                            ?></textarea>
                                                        <span id="nickname_or_othername_details_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="nickname_or_othername_details_length">512</p>
                                                        <p>When answering the following questions, do not include minor traffic infractions, ANY convictions for which the record has been sealed and/or expunged, and/or eradicated, any conviction for which probation has been successfully completed or otherwise discharged with the case having been judicially dismissed, any information regarding referrals to and/or participation in any pre-trial or post-trial diversion programs (California applicants only, do not include infractions involving marijuana offenses that occurred over two years ago). A conviction record will not necessarily be a bar to employment. Factors such as age, time of the offense, seriousness and nature of the violation, and rehabilitation will be taken into account.</p>
                                                    </div>
                                                </div>
                                                <!--
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Have you ever plead Guilty, No Contest, or been Convicted of a Misdemeanor and/or Felony?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListCriminalWrongDoing_0" name="RadioButtonListCriminalWrongDoing"
                                                <?php
                                                if (isset($formpost['RadioButtonListCriminalWrongDoing']) && $formpost['RadioButtonListCriminalWrongDoing'] == 'Yes') {
                                                    echo " checked";
                                                }
                                                ?>>
                                                                <label for="RadioButtonListCriminalWrongDoing_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListCriminalWrongDoing_1" name="RadioButtonListCriminalWrongDoing"
                                                <?php
                                                if (isset($formpost['RadioButtonListCriminalWrongDoing']) && $formpost['RadioButtonListCriminalWrongDoing'] == 'No') {
                                                    echo " checked";
                                                }
                                                ?>>
                                                                <label for="RadioButtonListCriminalWrongDoing_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                -->
                                                <!--
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
                                                        <label class="autoheight">Have you been arrested for any matter for which you are now out on bail or have been released on your own recognizance pending trial?</label>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="Yes" id="RadioButtonListCriminalBail_0" name="RadioButtonListCriminalBail"
                                                <?php
                                                if (isset($formpost['RadioButtonListCriminalBail']) && $formpost['RadioButtonListCriminalBail'] == 'Yes') {
                                                    echo " checked";
                                                }
                                                ?>>
                                                            <label for="RadioButtonListCriminalBail_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="No" id="RadioButtonListCriminalBail_1" name="RadioButtonListCriminalBail"
                                                <?php
                                                if (isset($formpost['RadioButtonListCriminalBail']) && $formpost['RadioButtonListCriminalBail'] == 'No') {
                                                    echo " checked";
                                                }
                                                ?>>
                                                            <label for="RadioButtonListCriminalBail_1">No</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                -->
                                                <!--
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">If yes to either of the above questions, provide dates and details for each, including the case number and court where your case is/was handled:</label>
                                                            <div class="comment-area">
                                                                <textarea name="arrested_pending_trail_details" id="arrested_pending_trail_details" maxlength="512" onkeyup="check_length('arrested_pending_trail_details')" class="form-col-100 invoice-fields"><?php
                                                if (isset($formpost['arrested_pending_trail_details'])) {
                                                    echo $formpost['arrested_pending_trail_details'];
                                                }
                                                ?></textarea>
                                                                <span id="arrested_pending_trail_details_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="arrested_pending_trail_details_length">512</p>
<?php echo form_error('arrested_pending_trail_details'); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
                                                        <?php 
                                                            $key = 'RadioButtonListDriversLicenseQuestion';
                                                            $def_value = (isset($formpost[$key]) ? $formpost[$key] : '' );
                                                            $enabled_check = "";
                                                            if (empty($def_value)) {
                                                                $enabled_check = "checked='checked'";
                                                            }
                                                        ?>
                                                        <label class="autoheight">Driver's License: A valid driver's license may be a requirement for the position for which you have applied. If so, do you currently have a valid driver's license?<?=$d_license ? ' <span class="staric">*</span>' : '';?></label>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" id="RadioButtonListDriversLicenseQuestion_0" value="Yes" name="RadioButtonListDriversLicenseQuestion" class="validate_driving_license"
                                                            <?php
                                                            if ((isset($formpost['RadioButtonListDriversLicenseQuestion']) && $formpost['RadioButtonListDriversLicenseQuestion'] == 'Yes') || sizeof($drivers_license_details)) {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListDriversLicenseQuestion_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" id="RadioButtonListDriversLicenseQuestion_1" value="No" name="RadioButtonListDriversLicenseQuestion" class="validate_driving_license"
                                                            <?php
                                                            if (isset($formpost['RadioButtonListDriversLicenseQuestion']) && $formpost['RadioButtonListDriversLicenseQuestion'] == 'No') {
                                                                echo " checked";
                                                            } else {
                                                                echo $enabled_check;
                                                            }
                                                            ?>>
                                                            <label for="RadioButtonListDriversLicenseQuestion_1">No</label>
                                                        </div>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Driver's license number:<?=$d_license ? ' <span class="staric dllr">*</span>' : '';?></label>
                                                        <input type="text"  class="invoice-fields" name="TextBoxDriversLicenseNumber" id="TextBoxDriversLicenseNumber" value="<?php
                                                        if (isset($formpost['TextBoxDriversLicenseNumber']) && !empty($formpost['TextBoxDriversLicenseNumber']) && $formpost['TextBoxDriversLicenseNumber'] != NULL) {
                                                            echo $formpost['TextBoxDriversLicenseNumber'];
                                                        }elseif(isset($drivers_license_details['license_number'])){
                                                            // echo $drivers_license_details['license_number'];
                                                        }
                                                        ?>">
                                                    </li>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <label>Expiration date:<?=$d_license ? ' <span class="staric dllr">*</span>' : '';?></label>
                                                        <input type="text" id="TextBoxDriversLicenseExpiration"  name="TextBoxDriversLicenseExpiration" class="invoice-fields startdate" value="<?php
                                                        if (isset($formpost['TextBoxDriversLicenseExpiration']) && !empty($formpost['TextBoxDriversLicenseExpiration']) && $formpost['TextBoxDriversLicenseExpiration'] != NULL) {
                                                            echo $formpost['TextBoxDriversLicenseExpiration'];
                                                        }elseif(isset($drivers_license_details['license_expiration_date'])){
                                                            echo $drivers_license_details['license_expiration_date'];
                                                        }
                                                        ?>">
                                                    </li>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 DropDownListDriversCountry">
                                                    <li>
                                                        <?php $country_id = (isset($formpost['DropDownListDriversCountry']) && !empty($formpost['DropDownListDriversCountry']) ? ($formpost['DropDownListDriversCountry']) : (sizeof($drivers_license_details) ? ($LC) : 0)); ?>
                                                        <label>Country:<?=$d_license ? ' <span class="staric dllr">*</span>' : '';?></label>
                                                        <div class="hr-select-dropdown">
                                                            <select  class="invoice-fields" id="DropDownListDriversCountry" name="DropDownListDriversCountry" onchange="getStates(this.value, <?php echo $states; ?>, 'DropDownListDriversState')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select('DropDownListDriversCountry', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
<?php echo form_error('DropDownListDriversCountry'); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 DropDownListDriversState">
                                                    <li>
<?php $state_id = isset($formpost['DropDownListDriversState']) && !empty($formpost['DropDownListDriversState']) ? $formpost['DropDownListDriversState'] : $LS; ?>
                                                        <label>State:<?=$d_license ? ' <span class="staric dllr">*</span>' : '';?></label>
                                                        <div class="hr-select-dropdown">
                                                            <select  class="invoice-fields" name="DropDownListDriversState" id="DropDownListDriversState">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListDriversState', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
                                                        <?php } ?>
                                                            </select>
                                                        </div>
<?php echo form_error('DropDownListDriversState'); ?>
                                                    </li>
                                                </div>



                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Within the last 5 years, have you ever plead Guilty, No Contest, or been Convicted of any traffic violation(s)?<?=$d_license ? ' <span class="staric dllr">*</span>' : '';?></label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListDriversLicenseTraffic" value="Yes" name="RadioButtonListDriversLicenseTraffic" 
                                                                <?php
                                                                if (isset($formpost['RadioButtonListDriversLicenseTraffic']) && $formpost['RadioButtonListDriversLicenseTraffic'] == 'Yes') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListDriversLicenseTraffic">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListDriversLicenseTraffic_1" value="No" name="RadioButtonListDriversLicenseTraffic"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListDriversLicenseTraffic']) && $formpost['RadioButtonListDriversLicenseTraffic'] == 'No') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListDriversLicenseTraffic_1">No</label>
                                                            </div>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <small class="autoheight">If yes, provide dates and details for each violation, including the case number and court where your case is/was handled:</small>
                                                            <div class="comment-area">
                                                                <textarea name="license_guilty_details" id="license_guilty_details" maxlength="512" onkeyup="check_length('license_guilty_details')" class="form-col-100 invoice-fields"><?php
                                                                    if (isset($formpost['license_guilty_details'])) {
                                                                        echo $formpost['license_guilty_details'];
                                                                    }
                                                                    ?></textarea>
                                                                <span id="license_guilty_details_remaining">512 Characters Left</span>
<?php echo form_error('license_guilty_details_violation'); ?>
                                                                <p style="display: none;" id="license_guilty_details_length">512</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="education-level-block">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100">
                                                            <label>Education - High School</label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEducationHighSchoolName" name="TextBoxEducationHighSchoolName" value="<?php
                                                            if (isset($formpost['TextBoxEducationHighSchoolName'])) {
                                                                echo $formpost['TextBoxEducationHighSchoolName'];
                                                            }
                                                            ?>">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Did you graduate?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListEducationHighSchoolGraduated_0" name="RadioButtonListEducationHighSchoolGraduated" 
                                                                <?php
                                                                if (isset($formpost['RadioButtonListEducationHighSchoolGraduated']) && $formpost['RadioButtonListEducationHighSchoolGraduated'] == 'Yes') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListEducationHighSchoolGraduated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListEducationHighSchoolGraduated_1" name="RadioButtonListEducationHighSchoolGraduated" 
                                                                <?php
                                                                if (isset($formpost['RadioButtonListEducationHighSchoolGraduated']) && $formpost['RadioButtonListEducationHighSchoolGraduated'] == 'No') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListEducationHighSchoolGraduated_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>

                                                    <div class="form-col-100">

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationHighSchoolCountry">
                                                            <li>
<?php $country_id = isset($formpost['DropDownListEducationHighSchoolCountry']) ? $formpost['DropDownListEducationHighSchoolCountry'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="former3_country" name="DropDownListEducationHighSchoolCountry" onchange="getStates(this.value, <?php echo $states; ?>, 'edhs_state')">
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListEducationHighSchoolCountry', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListEducationHighSchoolCountry'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationHighSchoolState">
                                                            <li>
<?php $state_id = isset($formpost['DropDownListEducationHighSchoolState']) ? $formpost['DropDownListEducationHighSchoolState'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationHighSchoolState" id="edhs_state">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                                        } else {
                                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                                ?>
                                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListEducationHighSchoolState', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListEducationHighSchoolState'); ?>
                                                            </li>
                                                        </div>


                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEducationHighSchoolCity" value="<?php
                                                                       if (isset($formpost['TextBoxEducationHighSchoolCity'])) {
                                                                           echo $formpost['TextBoxEducationHighSchoolCity'];
                                                                       }
                                                                       ?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Dates of Attendance</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedMonthBegin">
                                                                        <option vlaue="January" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'January') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?> >January</option>
                                                                        <option value="February" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'February') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>February</option>
                                                                        <option value="March" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'March') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>March</option>
                                                                        <option value="April" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'April') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>April</option>
                                                                        <option value="May" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'May') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>May</option>
                                                                        <option value="June" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'June') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>June</option>
                                                                        <option value="July" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'July') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>July</option>
                                                                        <option value="August" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'August') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>August</option>
                                                                        <option value="September" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'September') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>September</option>
                                                                        <option value="October" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'October') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>October</option>
                                                                        <option value="November" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'November') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthBegin'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedYearBegin">
                                                                                <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
                                                                                        <?php if (isset($formpost['DropDownListEducationHighSchoolDateAttendedYearBegin']) && $formpost['DropDownListEducationHighSchoolDateAttendedYearBegin'] == $i) { ?>
                                                                                        selected
                                                                            <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedMonthEnd">
                                                                        <option vlaue="January" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'January') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?> >January</option>
                                                                        <option value="February" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'February') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>February</option>
                                                                        <option value="March" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'March') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>March</option>
                                                                        <option value="April" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'April') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>April</option>
                                                                        <option value="May" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'May') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>May</option>
                                                                        <option value="June" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'June') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>June</option>
                                                                        <option value="July" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'July') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>July</option>
                                                                        <option value="August" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'August') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>August</option>
                                                                        <option value="September" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'September') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>September</option>
                                                                        <option value="October" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'October') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>October</option>
                                                                        <option value="November" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'November') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedMonthEnd'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedYearEnd">
                                                                                <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
                                                                                        <?php if (isset($formpost['DropDownListEducationHighSchoolDateAttendedYearEnd']) && $formpost['DropDownListEducationHighSchoolDateAttendedYearEnd'] == $i) { ?>
                                                                                        selected
                                                                            <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="education-level-block">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100">
                                                                <label>College/University</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEducationCollegeName" value="<?php
                                                                       if (isset($formpost['TextBoxEducationCollegeName'])) {
                                                                           echo $formpost['TextBoxEducationCollegeName'];
                                                                       }
                                                                       ?>">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <label class="autoheight">Did you graduate?</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="Yes" id="RadioButtonListEducationCollegeGraduated_0" name="RadioButtonListEducationCollegeGraduated"
                                                                    <?php
                                                                           if (isset($formpost['RadioButtonListEducationCollegeGraduated']) && $formpost['RadioButtonListEducationCollegeGraduated'] == 'Yes') {
                                                                               echo " checked";
                                                                           }
                                                                           ?>>
                                                                    <label for="RadioButtonListEducationCollegeGraduated_0">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="No" id="RadioButtonListEducationCollegeGraduated_1" name="RadioButtonListEducationCollegeGraduated"
                                                                    <?php
                                                                           if (isset($formpost['RadioButtonListEducationCollegeGraduated']) && $formpost['RadioButtonListEducationCollegeGraduated'] == 'No') {
                                                                               echo " checked";
                                                                           }
                                                                           ?>>
                                                                    <label for="RadioButtonListEducationCollegeGraduated_1">No</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="form-col-100">

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationCollegeCountry">
                                                                <li>
<?php $country_id = isset($formpost['DropDownListEducationCollegeCountry']) ? $formpost['DropDownListEducationCollegeCountry'] : 0; ?>
                                                                    <label>Country:</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="ec_country" name="DropDownListEducationCollegeCountry" onchange="getStates(this.value, <?php echo $states; ?>, 'ec_state')">
                                                                            <option value="">Please Select</option>
                                                                            <?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListEducationCollegeCountry', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
<?php echo form_error('DropDownListEducationCollegeCountry'); ?>
                                                                </li>
                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationCollegeState">
                                                                <li>
                                                                            <?php $state_id = isset($formpost['DropDownListEducationCollegeState']) ? $formpost['DropDownListEducationCollegeState'] : 0; ?>
                                                                    <label>State:</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="DropDownListEducationCollegeState" id="ec_state">
                                                                            <?php if (empty($country_id)) { ?>
                                                                                <option value="">Select State</option> <?php
                                                                        } else {
                                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                                    ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                    <option <?php echo set_select('DropDownListEducationCollegeState', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                        <?php } ?>
<?php } ?>
                                                                        </select>
                                                                    </div>
<?php echo form_error('DropDownListEducationCollegeState'); ?>
                                                                </li>
                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>City</label>
                                                                    <input type="text" class="invoice-fields" name="TextBoxEducationCollegeCity" value="<?php
if (isset($formpost['TextBoxEducationCollegeCity'])) {
    echo $formpost['TextBoxEducationCollegeCity'];
}
?>">
                                                                </li>
                                                            </div>

                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Dates of Attendance</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="DropDownListEducationCollegeDateAttendedMonthBegin">
                                                                            <option vlaue="January" <?php
if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'January') {
    echo "selected";
}
?> >January</option>
                                                                            <option value="February" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'February') {
                                                                                echo "selected";
                                                                            }
?>>February</option>
                                                                            <option value="March" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'March') {
                                                                                echo "selected";
                                                                            }
?>>March</option>
                                                                            <option value="April" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'April') {
                                                                                echo "selected";
                                                                            }
?>>April</option>
                                                                            <option value="May" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'May') {
                                                                                echo "selected";
                                                                            }
?>>May</option>
                                                                            <option value="June" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'June') {
                                                                                echo "selected";
                                                                            }
?>>June</option>
                                                                            <option value="July" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'July') {
                                                                                echo "selected";
                                                                            }
?>>July</option>
                                                                            <option value="August" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'August') {
                                                                                echo "selected";
                                                                            }
?>>August</option>
                                                                            <option value="September" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'September') {
                                                                                echo "selected";
                                                                            }
?>>September</option>
                                                                            <option value="October" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'October') {
                                                                                echo "selected";
                                                                            }
?>>October</option>
                                                                            <option value="November" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'November') {
                                                                                echo "selected";
                                                                            }
?>>November</option>
                                                                            <option value="December" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthBegin']) && $formpost['DropDownListEducationCollegeDateAttendedMonthBegin'] == 'December') {
                                                                                echo "selected";
                                                                            }
?>>December</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="DropDownListEducationCollegeDateAttendedYearBegin">
                                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                                <option value="<?php echo $i; ?>"
                                                                                <?php if (isset($formpost['DropDownListEducationCollegeDateAttendedYearBegin']) && $formpost['DropDownListEducationCollegeDateAttendedYearBegin'] == $i) { ?>
                                                                                            selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                                </option>
<?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                <span class="date-range-text">to</span>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="DropDownListEducationCollegeDateAttendedMonthEnd">
                                                                            <option vlaue="January" <?php
if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'January') {
    echo "selected";
}
?> >January</option>
                                                                            <option value="February" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'February') {
                                                                                echo "selected";
                                                                            }
?>>February</option>
                                                                            <option value="March" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'March') {
                                                                                echo "selected";
                                                                            }
?>>March</option>
                                                                            <option value="April" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'April') {
                                                                                echo "selected";
                                                                            }
?>>April</option>
                                                                            <option value="May" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'May') {
                                                                                echo "selected";
                                                                            }
?>>May</option>
                                                                            <option value="June" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'June') {
                                                                                echo "selected";
                                                                            }
?>>June</option>
                                                                            <option value="July" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'July') {
                                                                                echo "selected";
                                                                            }
?>>July</option>
                                                                            <option value="August" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'August') {
                                                                                echo "selected";
                                                                            }
?>>August</option>
                                                                            <option value="September" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'September') {
                                                                                echo "selected";
                                                                            }
?>>September</option>
                                                                            <option value="October" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'October') {
                                                                                echo "selected";
                                                                            }
?>>October</option>
                                                                            <option value="November" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'November') {
                                                                                echo "selected";
                                                                            }
?>>November</option>
                                                                            <option value="December" <?php
                                                                            if (isset($formpost['DropDownListEducationCollegeDateAttendedMonthEnd']) && $formpost['DropDownListEducationCollegeDateAttendedMonthEnd'] == 'December') {
                                                                                echo "selected";
                                                                            }
?>>December</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="DropDownListEducationCollegeDateAttendedYearEnd">
                                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                                <option value="<?php echo $i; ?>"
                                                                                <?php if (isset($formpost['DropDownListEducationCollegeDateAttendedYearEnd']) && $formpost['DropDownListEducationCollegeDateAttendedYearEnd'] == $i) { ?>
                                                                                            selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                                </option>
<?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Major</label>
                                                                    <input type="text" class="invoice-fields" name="TextBoxEducationCollegeMajor" value="<?php
if (isset($formpost['TextBoxEducationCollegeMajor'])) {
    echo $formpost['TextBoxEducationCollegeMajor'];
}
?>">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Degree Earned</label>
                                                                    <input type="text" class="invoice-fields" name="TextBoxEducationCollegeDegree" value="<?php
if (isset($formpost['TextBoxEducationCollegeDegree'])) {
    echo $formpost['TextBoxEducationCollegeDegree'];
}
?>">
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="education-level-block">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100">
                                                            <label>Other School</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEducationOtherName" value="<?php
if (isset($formpost['TextBoxEducationOtherName'])) {
    echo $formpost['TextBoxEducationOtherName'];
}
?>">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Did you graduate?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListEducationOtherGraduated_0" name="RadioButtonListEducationOtherGraduated"
                                                                       <?php
                                                                       if (isset($formpost['RadioButtonListEducationOtherGraduated']) && $formpost['RadioButtonListEducationOtherGraduated'] == 'Yes') {
                                                                           echo " checked";
                                                                       }
                                                                       ?>>
                                                                <label for="RadioButtonListEducationOtherGraduated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListEducationOtherGraduated_1" name="RadioButtonListEducationOtherGraduated"
                                                                       <?php
                                                                       if (isset($formpost['RadioButtonListEducationOtherGraduated']) && $formpost['RadioButtonListEducationOtherGraduated'] == 'No') {
                                                                           echo " checked";
                                                                       }
                                                                       ?>>
                                                                <label for="RadioButtonListEducationOtherGraduated_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationOtherCountry">
                                                            <li>
                                                                        <?php $country_id = isset($formpost['DropDownListEducationOtherCountry']) ? $formpost['DropDownListEducationOtherCountry'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="eo_country" name="DropDownListEducationOtherCountry" onchange="getStates(this.value, <?php echo $states; ?>, 'eo_state')">
                                                                        <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListEducationOtherCountry', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListEducationOtherCountry'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEducationOtherState">
                                                            <li>
                                                                        <?php $state_id = isset($formpost['DropDownListEducationOtherState']) ? $formpost['DropDownListEducationOtherState'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationOtherState" id="eo_state">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                                        } else {
                                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                                ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListEducationOtherState', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListEducationOtherState'); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEducationOtherCity" value="<?php
if (isset($formpost['TextBoxEducationOtherCity'])) {
    echo $formpost['TextBoxEducationOtherCity'];
}
?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Dates of Attendance</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationOtherDateAttendedMonthBegin">
                                                                        <option vlaue="January" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'January') {
                                                                            echo "selected";
                                                                        }
                                                                        ?> >January</option>
                                                                        <option value="February" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'February') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>February</option>
                                                                        <option value="March" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'March') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>March</option>
                                                                        <option value="April" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'April') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>April</option>
                                                                        <option value="May" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'May') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>May</option>
                                                                        <option value="June" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'June') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>June</option>
                                                                        <option value="July" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'July') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>July</option>
                                                                        <option value="August" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'August') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>August</option>
                                                                        <option value="September" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'September') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>September</option>
                                                                        <option value="October" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'October') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>October</option>
                                                                        <option value="November" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'November') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEducationOtherDateAttendedMonthBegin']) && $formpost['DropDownListEducationOtherDateAttendedMonthBegin'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                        ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationOtherDateAttendedYearBegin">
                                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
                                                                            <?php if (isset($formpost['DropDownListEducationOtherDateAttendedYearBegin']) && $formpost['DropDownListEducationOtherDateAttendedYearBegin'] == $i) { ?>
                                                                                        selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationOtherDateAttendedMonthEnd">
                                                                        <option vlaue="January" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'January') {
                                                                            echo "selected";
                                                                        }
                                                                        ?> >January</option>
                                                                        <option value="February" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'February') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>February</option>
                                                                        <option value="March" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'March') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>March</option>
                                                                        <option value="April" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'April') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>April</option>
                                                                        <option value="May" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'May') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>May</option>
                                                                        <option value="June" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'June') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>June</option>
                                                                        <option value="July" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'July') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>July</option>
                                                                        <option value="August" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'August') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>August</option>
                                                                        <option value="September" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'September') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>September</option>
                                                                        <option value="October" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'October') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>October</option>
                                                                        <option value="November" <?php
                                                                        if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'November') {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEducationOtherDateAttendedMonthEnd']) && $formpost['DropDownListEducationOtherDateAttendedMonthEnd'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                        ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEducationOtherDateAttendedYearEnd">
                                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
                                                                            <?php if (isset($formpost['DropDownListEducationOtherDateAttendedYearEnd']) && $formpost['DropDownListEducationOtherDateAttendedYearEnd'] == $i) { ?>
                                                                                        selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Major</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEducationOtherMajor" value="<?php
if (isset($formpost['TextBoxEducationOtherMajor'])) {
    echo $formpost['TextBoxEducationOtherMajor'];
}
?>">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Degree Earned</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEducationOtherDegree" value="<?php
if (isset($formpost['TextBoxEducationOtherDegree'])) {
    echo $formpost['TextBoxEducationOtherDegree'];
}
?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Professional License Type</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEducationProfessionalLicenseName" value="<?php
if (isset($formpost['TextBoxEducationProfessionalLicenseName'])) {
    echo $formpost['TextBoxEducationProfessionalLicenseName'];
}
?>">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>License Number</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEducationProfessionalLicenseNumber" value="<?php
if (isset($formpost['TextBoxEducationProfessionalLicenseNumber'])) {
    echo $formpost['TextBoxEducationProfessionalLicenseNumber'];
}
?>">
                                                        </li>
                                                    </div>



                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyCountry">
                                                        <li>
                                                                    <?php $country_id = isset($formpost['TextBoxEducationProfessionalLicenseIssuingAgencyCountry']) ? $formpost['TextBoxEducationProfessionalLicenseIssuingAgencyCountry'] : 0; ?>
                                                            <label>Issuing Country:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="r3_country" name="TextBoxEducationProfessionalLicenseIssuingAgencyCountry" onchange="getStates(this.value, <?php echo $states; ?>, 'plia_state')">
                                                                    <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('TextBoxEducationProfessionalLicenseIssuingAgencyCountry', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('TextBoxEducationProfessionalLicenseIssuingAgencyCountry'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyState">
                                                        <li>
                                                                    <?php $state_id = isset($formpost['TextBoxEducationProfessionalLicenseIssuingAgencyState']) ? $formpost['TextBoxEducationProfessionalLicenseIssuingAgencyState'] : 0; ?>
                                                            <label>Issuing State:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="TextBoxEducationProfessionalLicenseIssuingAgencyState" id="plia_state">
                                                                    <?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                            ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('TextBoxEducationProfessionalLicenseIssuingAgencyState', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                </select>
                                                            </div>
<?php echo form_error('TextBoxEducationProfessionalLicenseIssuingAgencyState'); ?>
                                                        </li>
                                                    </div>


                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Employment Current / Most Recent Employer<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerName1" name="TextBoxEmploymentEmployerName1" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerName1']) && !empty($formpost['TextBoxEmploymentEmployerName1'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerName1'];
                                                                }elseif(isset($from_cntrl) && $from_cntrl == 'emp'){
                                                                    echo $employee['first_name'].' '.$employee['last_name'];
                                                                }
                                                                ?>">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Position/Title<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerPosition1" name="TextBoxEmploymentEmployerPosition1" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerPosition1']) && !empty($formpost['TextBoxEmploymentEmployerPosition1'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerPosition1'];
                                                                }elseif(isset($from_cntrl) && $from_cntrl == 'emp'){
                                                                    echo $employee['access_level'];
                                                                }
                                                                ?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
                                                            <label>Address<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerAddress1" name="TextBoxEmploymentEmployerAddress1" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerAddress1']) && !empty($formpost['TextBoxEmploymentEmployerAddress1'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerAddress1'];
                                                                }elseif(isset($from_cntrl) && $from_cntrl == 'emp'){
                                                                    echo $employee['Location_Address'];
                                                                }
                                                            ?>">
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry1">
                                                        <li>
                                                                    <?php $country_id = isset($formpost['DropDownListEmploymentEmployerCountry1']) && !empty($formpost['DropDownListEmploymentEmployerCountry1']) ? ($formpost['DropDownListEmploymentEmployerCountry1']) : (isset($from_cntrl) && $from_cntrl == 'emp' ? $employee['Location_Country'] : 0); ?>
                                                            <label>Country:<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="DropDownListEmploymentEmployerCountry1" name="DropDownListEmploymentEmployerCountry1" onchange="getStates(this.value, <?php echo $states; ?>, 'DropDownListEmploymentEmployerState1')">
                                                                    <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListEmploymentEmployerCountry1', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('DropDownListEmploymentEmployerCountry1'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState1">
                                                        <li>
                                                                    <?php $state_id = isset($formpost['DropDownListEmploymentEmployerState1']) && !empty($formpost['DropDownListEmploymentEmployerState1']) ? ($formpost['DropDownListEmploymentEmployerState1']) : (isset($from_cntrl) && $from_cntrl == 'emp' ? $employee['Location_State'] : 0); ?>
                                                            <label>State:<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerState1" id="DropDownListEmploymentEmployerState1">
                                                                    <?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListEmploymentEmployerState1', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('DropDownListEmploymentEmployerState1'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerCity1" name="TextBoxEmploymentEmployerCity1" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerCity1']) && !empty($formpost['TextBoxEmploymentEmployerCity1'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerCity1'];
                                                            }elseif(isset($from_cntrl) && $from_cntrl == 'emp'){
                                                                echo $employee['Location_City'];
                                                            }
                                                            ?>">
                                                        </li>
                                                    </div>

                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Telephone<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <div class="input-group">
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerPhoneNumber1" name="TextBoxEmploymentEmployerPhoneNumber1" value="<?php echo isset($formpost['TextBoxEmploymentEmployerPhoneNumber1']) && !empty($formpost['TextBoxEmploymentEmployerPhoneNumber1']) ? ($formpost['TextBoxEmploymentEmployerPhoneNumber1']) : (isset($from_cntrl) && $from_cntrl == 'emp' ? $employee['PhoneNumber'] : '');?>" />
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Dates of Employment<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" 
                                                                id="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1">
                                                                    <option vlaue="January" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'January') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'January')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?> >January</option>
                                                                    <option value="February" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'February') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'February')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>February</option>
                                                                    <option value="March" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'March') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'March')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>March</option>
                                                                    <option value="April" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'April') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'April')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>April</option>
                                                                    <option value="May" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'May') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'May')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>May</option>
                                                                    <option value="June" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'June') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'June')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>June</option>
                                                                    <option value="July" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'July') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'July')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>July</option>
                                                                    <option value="August" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'August') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'August')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>August</option>
                                                                    <option value="September" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'September') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'September')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>September</option>
                                                                    <option value="October" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'October') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'October')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>October</option>
                                                                    <option value="November" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'November') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'November')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>November</option>
                                                                    <option value="December" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] == 'December') || (isset($from_cntrl) && $from_cntrl == 'emp' && $month == 'December')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>December</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1">
                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                        <option value="<?php echo $i; ?>"
    <?php if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'] == $i) || (isset($from_cntrl) && $from_cntrl == 'emp' && $year == $i)) { ?>
                                                                                    selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                        </option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <span class="date-range-text">to</span>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1">
                                                                    <option vlaue="January" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'January') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'January')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?> >January</option>
                                                                    <option value="February" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'February') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'February')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>February</option>
                                                                    <option value="March" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'March') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'March')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>March</option>
                                                                    <option value="April" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'April') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'April')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>April</option>
                                                                    <option value="May" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'May') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'May')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>May</option>
                                                                    <option value="June" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'June') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'June')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>June</option>
                                                                    <option value="July" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'July') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'July')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>July</option>
                                                                    <option value="August" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'August') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'August')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>August</option>
                                                                    <option value="September" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'September') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'September')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>September</option>
                                                                    <option value="October" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'October') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'October')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>October</option>
                                                                    <option value="November" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'November') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'November')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>November</option>
                                                                    <option value="December" <?php
                                                                    if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] == 'December') || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_month == 'December')) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>December</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1">
                                                                    <?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                        <option value="<?php echo $i; ?>"
    <?php if ((isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'] == $i) || (isset($from_cntrl) && $from_cntrl == 'emp' && $cur_year == $i)) { ?>
                                                                                    selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                        </option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            <label>Starting Compensation</label>-->
<!--                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin1" value="--><?php
//if (isset($formpost['TextBoxEmploymentEmployerCompensationBegin1'])) {
//    echo $formpost['TextBoxEmploymentEmployerCompensationBegin1'];
//}
//?><!--">-->
<!--                                                        </li>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            <label>Ending Compensation</label>-->
<!--                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd1" value="--><?php
//                                                            if (isset($formpost['TextBoxEmploymentEmployerCompensationEnd1'])) {
//                                                                echo $formpost['TextBoxEmploymentEmployerCompensationEnd1'];
//                                                            }
//?><!--">-->
<!--                                                        </li>-->
<!--                                                    </div>-->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
                                                            <label>Supervisor</label>
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerSupervisor1" name="TextBoxEmploymentEmployerSupervisor1" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerSupervisor1'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerSupervisor1'];
                                                            }
?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                        <li class="autoheight">
                                                            <label>Reason for Leaving<?=$l_employment ? ' <span class="staric">*</span>' : '';?></label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                        <li class="autoheight">
                                                            <label class="contact-to-employee">May we contact this employer?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListEmploymentEmployerContact1_0" value="Yes" name="RadioButtonListEmploymentEmployerContact1_0"
<?php
if (isset($formpost['RadioButtonListEmploymentEmployerContact1_0']) && $formpost['RadioButtonListEmploymentEmployerContact1_0'] == 'Yes') {
    echo " checked";
}
?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact1_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListEmploymentEmployerContact1_1" value="No" name="RadioButtonListEmploymentEmployerContact1_0"
<?php
if (isset($formpost['RadioButtonListEmploymentEmployerContact1_0']) && $formpost['RadioButtonListEmploymentEmployerContact1_0'] == 'No') {
    echo " checked";
}
?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact1_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <input type="text" class="invoice-fields" id="TextBoxEmploymentEmployerReasonLeave1" name="TextBoxEmploymentEmployerReasonLeave1" value="<?php
if (isset($formpost['TextBoxEmploymentEmployerReasonLeave1'])) {
    echo $formpost['TextBoxEmploymentEmployerReasonLeave1'];
}
?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Previous Employer</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerName2" value="<?php
if (isset($formpost['TextBoxEmploymentEmployerName2'])) {
    echo $formpost['TextBoxEmploymentEmployerName2'];
}
?>">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Position/Title</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerPosition2" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerPosition2'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerPosition2'];
                                                                }
?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerAddress2" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerAddress2'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerAddress2'];
                                                                }
?>">
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry_2">
                                                            <li>
                                                                        <?php $country_id = isset($formpost['DropDownListEmploymentEmployerCountry_2']) ? $formpost['DropDownListEmploymentEmployerCountry_2'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="ee2_country" name="DropDownListEmploymentEmployerCountry_2" onchange="getStates(this.value, <?php echo $states; ?>, 'ee2_state')">
                                                                        <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListEmploymentEmployerCountry_2', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListEmploymentEmployerCountry_2'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState_2">
                                                            <li>
                                                                        <?php $state_id = isset($formpost['DropDownListEmploymentEmployerState_2']) ? $formpost['DropDownListEmploymentEmployerState_2'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEmploymentEmployerState_2" id="ee2_state">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                                ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListEmploymentEmployerState_2', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                                <?php echo form_error('DropDownListEmploymentEmployerState_2'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCity2" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerCity2'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerCity2'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerPhoneNumber2" value="<?php echo isset($formpost['TextBoxEmploymentEmployerPhoneNumber2']) ? $formpost['TextBoxEmploymentEmployerPhoneNumber2'] : '';?>" />
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Dates of Employment</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2">
                                                                        <option vlaue="January" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'January') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?> >January</option>
                                                                        <option value="February" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'February') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>February</option>
                                                                        <option value="March" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'March') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>March</option>
                                                                        <option value="April" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'April') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>April</option>
                                                                        <option value="May" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'May') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>May</option>
                                                                        <option value="June" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'June') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>June</option>
                                                                        <option value="July" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'July') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>July</option>
                                                                        <option value="August" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'August') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>August</option>
                                                                        <option value="September" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'September') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>September</option>
                                                                        <option value="October" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'October') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>October</option>
                                                                        <option value="November" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'November') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2">
<?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
    <?php if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2'] == $i) { ?>
                                                                                        selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2">
                                                                        <option vlaue="January" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'January') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?> >January</option>
                                                                        <option value="February" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'February') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>February</option>
                                                                        <option value="March" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'March') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>March</option>
                                                                        <option value="April" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'April') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>April</option>
                                                                        <option value="May" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'May') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>May</option>
                                                                        <option value="June" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'June') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>June</option>
                                                                        <option value="July" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'July') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>July</option>
                                                                        <option value="August" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'August') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>August</option>
                                                                        <option value="September" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'September') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>September</option>
                                                                        <option value="October" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'October') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>October</option>
                                                                        <option value="November" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'November') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>November</option>
                                                                        <option value="December" <?php
                                                                                if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'] == 'December') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>December</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2">
<?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                            <option value="<?php echo $i; ?>"
    <?php if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2'] == $i) { ?>
                                                                                        selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                            </option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
<!--                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                            <li>-->
<!--                                                                <label>Starting Compensation</label>-->
<!--                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin2" value="--><?php
//if (isset($formpost['TextBoxEmploymentEmployerCompensationBegin2'])) {
//    echo $formpost['TextBoxEmploymentEmployerCompensationBegin2'];
//}
//?><!--">-->
<!--                                                            </li>-->
<!--                                                        </div>-->
<!--                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                            <li>-->
<!--                                                                <label>Ending Compensation</label>-->
<!--                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd2" value="--><?php
//                                                                if (isset($formpost['TextBoxEmploymentEmployerCompensationEnd2'])) {
//                                                                    echo $formpost['TextBoxEmploymentEmployerCompensationEnd2'];
//                                                                }
//?><!--">-->
<!--                                                            </li>-->
<!--                                                        </div>-->
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li>
                                                                <label>Supervisor</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerSupervisor2" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerSupervisor2'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerSupervisor2'];
                                                                }
?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                            <li class="autoheight">
                                                                <label>Reason for Leaving</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                            <li class="autoheight">
                                                                <label class="contact-to-employee">May we contact this employer?</label>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="RadioButtonListEmploymentEmployerContact2_0" value="Yes" name="RadioButtonListEmploymentEmployerContact2_0"
                                                                    <?php
                                                                    if (isset($formpost['RadioButtonListEmploymentEmployerContact2_0']) && $formpost['RadioButtonListEmploymentEmployerContact2_0'] == 'Yes') {
                                                                        echo " checked";
                                                                    }
                                                                    ?>>
                                                                    <label for="RadioButtonListEmploymentEmployerContact2_0">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="RadioButtonListEmploymentEmployerContact2_1" value="No" name="RadioButtonListEmploymentEmployerContact2_0"
<?php
if (isset($formpost['RadioButtonListEmploymentEmployerContact2_0']) && $formpost['RadioButtonListEmploymentEmployerContact2_0'] == 'No') {
    echo " checked";
}
?>>
                                                                    <label for="RadioButtonListEmploymentEmployerContact2_1">No</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100 autoheight">
                                                                <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerReasonLeave2" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerReasonLeave2'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerReasonLeave2'];
                                                                }
?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Previous Employer</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerName3" value="<?php
                                                                if (isset($formpost['TextBoxEmploymentEmployerName3'])) {
                                                                    echo $formpost['TextBoxEmploymentEmployerName3'];
                                                                }
?>">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Position/Title</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerPosition3" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerPosition3'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerPosition3'];
                                                            }
?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Address</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerAddress3" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerAddress3'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerAddress3'];
                                                            }
?>">
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry3">
                                                        <li>
                                                                    <?php $country_id = isset($formpost['DropDownListEmploymentEmployerCountry3']) ? $formpost['DropDownListEmploymentEmployerCountry3'] : 0; ?>
                                                            <label>Country:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="ee3_country" name="DropDownListEmploymentEmployerCountry3" onchange="getStates(this.value, <?php echo $states; ?>, 'ee3_state')">
                                                                    <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('DropDownListEmploymentEmployerCountry3', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                                </select>
                                                            </div>
<?php echo form_error('DropDownListEmploymentEmployerCountry3'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState3">
                                                        <li>
                                                                    <?php $state_id = isset($formpost['DropDownListEmploymentEmployerState3']) ? $formpost['DropDownListEmploymentEmployerState3'] : 0; ?>
                                                            <label>State:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerState3" id="ee3_state">
<?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListEmploymentEmployerState3', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('DropDownListEmploymentEmployerState3'); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCity3" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerCity3'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerCity3'];
                                                            }
                                                            ?>">
                                                        </li>
                                                    </div>

                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Telephone</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerPhoneNumber3" value="<?php echo isset($formpost['TextBoxEmploymentEmployerPhoneNumber3']) ? $formpost['TextBoxEmploymentEmployerPhoneNumber3'] : '';?>" />
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Dates of Employment</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3">
                                                                    <option vlaue="January" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'January') {
                                                                        echo "selected";
                                                                    }
                                                            ?> >January</option>
                                                                    <option value="February" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'February') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>February</option>
                                                                    <option value="March" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'March') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>March</option>
                                                                    <option value="April" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'April') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>April</option>
                                                                    <option value="May" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'May') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>May</option>
                                                                    <option value="June" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'June') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>June</option>
                                                                    <option value="July" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'July') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>July</option>
                                                                    <option value="August" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'August') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>August</option>
                                                                    <option value="September" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'September') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>September</option>
                                                                    <option value="October" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'October') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>October</option>
                                                                    <option value="November" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'November') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>November</option>
                                                                    <option value="December" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'] == 'December') {
                                                                        echo "selected";
                                                                    }
                                                            ?>>December</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3">
<?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                        <option value="<?php echo $i; ?>"
    <?php if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3'] == $i) { ?>
                                                                                    selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                        </option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <span class="date-range-text">to</span>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3">
                                                                    <option vlaue="January" <?php
if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'January') {
    echo "selected";
}
?> >January</option>
                                                                    <option value="February" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'February') {
                                                                        echo "selected";
                                                                    }
?>>February</option>
                                                                    <option value="March" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'March') {
                                                                        echo "selected";
                                                                    }
?>>March</option>
                                                                    <option value="April" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'April') {
                                                                        echo "selected";
                                                                    }
?>>April</option>
                                                                    <option value="May" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'May') {
                                                                        echo "selected";
                                                                    }
?>>May</option>
                                                                    <option value="June" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'June') {
                                                                        echo "selected";
                                                                    }
?>>June</option>
                                                                    <option value="July" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'July') {
                                                                        echo "selected";
                                                                    }
?>>July</option>
                                                                    <option value="August" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'August') {
                                                                        echo "selected";
                                                                    }
?>>August</option>
                                                                    <option value="September" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'September') {
                                                                        echo "selected";
                                                                    }
?>>September</option>
                                                                    <option value="October" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'October') {
                                                                        echo "selected";
                                                                    }
?>>October</option>
                                                                    <option value="November" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'November') {
                                                                        echo "selected";
                                                                    }
?>>November</option>
                                                                    <option value="December" <?php
                                                                    if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'] == 'December') {
                                                                        echo "selected";
                                                                    }
?>>December</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <label></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3">
<?php for ($i = $starting_year_loop; $i <= date('Y'); $i++) { ?>
                                                                        <option value="<?php echo $i; ?>"
    <?php if (isset($formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3']) && $formpost['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3'] == $i) { ?>
                                                                                    selected
    <?php } ?>>
    <?php echo $i; ?>
                                                                        </option>
<?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            <label>Starting Compensation</label>-->
<!--                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin3" value="--><?php
//if (isset($formpost['TextBoxEmploymentEmployerCompensationBegin3'])) {
//    echo $formpost['TextBoxEmploymentEmployerCompensationBegin3'];
//}
//?><!--">-->
<!--                                                        </li>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            <label>Ending Compensation</label>-->
<!--                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd3" value="--><?php
//                                                            if (isset($formpost['TextBoxEmploymentEmployerCompensationEnd3'])) {
//                                                                echo $formpost['TextBoxEmploymentEmployerCompensationEnd3'];
//                                                            }
//?><!--">-->
<!--                                                        </li>-->
<!--                                                    </div>-->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
                                                            <label>Supervisor</label>
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerSupervisor3" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerSupervisor3'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerSupervisor3'];
                                                            }
?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                        <li class="autoheight">
                                                            <label>Reason for Leaving</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                        <li class="autoheight">
                                                            <label class="contact-to-employee">May we contact this employer?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListEmploymentEmployerContact3_0" value="Yes" name="RadioButtonListEmploymentEmployerContact3_0"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListEmploymentEmployerContact3_0']) && $formpost['RadioButtonListEmploymentEmployerContact3_0'] == 'Yes') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact3_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="RadioButtonListEmploymentEmployerContact3_1" value="No" name="RadioButtonListEmploymentEmployerContact3_0"
<?php
if (isset($formpost['RadioButtonListEmploymentEmployerContact3_0']) && $formpost['RadioButtonListEmploymentEmployerContact3_0'] == 'No') {
    echo " checked";
}
?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact3_1">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <input type="text" class="invoice-fields" name="TextBoxEmploymentEmployerReasonLeave3" value="<?php
                                                            if (isset($formpost['TextBoxEmploymentEmployerReasonLeave3'])) {
                                                                echo $formpost['TextBoxEmploymentEmployerReasonLeave3'];
                                                            }
?>">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Have you ever been laid off or terminated from any job or position? </label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListEmploymentEverTerminated_0" name="RadioButtonListEmploymentEverTerminated"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListEmploymentEverTerminated']) && $formpost['RadioButtonListEmploymentEverTerminated'] == 'Yes') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListEmploymentEverTerminated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListEmploymentEverTerminated_1" name="RadioButtonListEmploymentEverTerminated"
<?php
if (isset($formpost['RadioButtonListEmploymentEverTerminated']) && $formpost['RadioButtonListEmploymentEverTerminated'] == 'No') {
    echo " checked";
}
?>>
                                                                <label for="RadioButtonListEmploymentEverTerminated_1">No</label>
                                                            </div>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <small class="autoheight">If yes, please explain:</small>
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxEmploymentEverTerminatedReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverTerminatedReason')" name="TextBoxEmploymentEverTerminatedReason"><?php
                                                                    if (isset($formpost['license_guilty_details'])) {
                                                                        echo $formpost['license_guilty_details'];
                                                                    }
?></textarea>
                                                                <span id="TextBoxEmploymentEverTerminatedReason_remaining">128 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEverTerminatedReason_length">128</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Have you ever been asked to resign from any job or position?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="Yes" id="RadioButtonListEmploymentEverResign_0" name="RadioButtonListEmploymentEverResign"
                                                                <?php
                                                                if (isset($formpost['RadioButtonListEmploymentEverResign']) && $formpost['RadioButtonListEmploymentEverResign'] == 'Yes') {
                                                                    echo " checked";
                                                                }
                                                                ?>>
                                                                <label for="RadioButtonListEmploymentEverResign_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="No" id="RadioButtonListEmploymentEverResign_1" name="RadioButtonListEmploymentEverResign"
<?php
if (isset($formpost['RadioButtonListEmploymentEverResign']) && $formpost['RadioButtonListEmploymentEverResign'] == 'No') {
    echo " checked";
}
?>>
                                                                <label for="RadioButtonListEmploymentEverResign_1">No</label>
                                                            </div>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <small class="autoheight">If yes, please explain:</small>
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxEmploymentEverResignReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverResignReason')" name="TextBoxEmploymentEverResignReason"><?php
                                                                    if (isset($formpost['TextBoxEmploymentEverResignReason'])) {
                                                                        echo $formpost['TextBoxEmploymentEverResignReason'];
                                                                    }
?></textarea>
                                                                <span id="TextBoxEmploymentEverResignReason_remaining">128 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEverResignReason_length">128</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Please explain any "gaps" in your employment history</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxEmploymentGaps" maxlength="512" onkeyup="check_length('TextBoxEmploymentGaps')" name="TextBoxEmploymentGaps"><?php
                                                                    if (isset($formpost['TextBoxEmploymentGaps'])) {
                                                                        echo $formpost['TextBoxEmploymentGaps'];
                                                                    }
?></textarea>
                                                                <span id="TextBoxEmploymentGaps_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentGaps_length">512</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">If you indicated that we may not contact an employer, please explain</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" maxlength="512" onkeyup="check_length('TextBoxEmploymentEmployerNoContact')" id="TextBoxEmploymentEmployerNoContact" name="TextBoxEmploymentEmployerNoContact"><?php
                                                                    if (isset($formpost['TextBoxEmploymentEmployerNoContact'])) {
                                                                        echo $formpost['TextBoxEmploymentEmployerNoContact'];
                                                                    }
?></textarea>
                                                                <span id="TextBoxEmploymentEmployerNoContact_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEmployerNoContact_length">512</p>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>References Name</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceName1" value="<?php
                                                                    if(isset($employer['referred_by_name'])){}else{$employer['referred_by_name']='';}

                                                                    if (isset($formpost['TextBoxReferenceName1'])) {
                                                                        echo $formpost['TextBoxReferenceName1'];
                                                                    }else{
                                                                        echo $employer['referred_by_name'];
                                                                    }
?> ">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>How do you know this reference?</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAcquainted1" value="<?php
                                                                if (isset($formpost['TextBoxReferenceAcquainted1'])) {
                                                                    echo $formpost['TextBoxReferenceAcquainted1'];
                                                                }
?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAddress1" value="<?php
                                                                if (isset($formpost['TextBoxReferenceAddress1'])) {
                                                                    echo $formpost['TextBoxReferenceAddress1'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceCountry1">
                                                            <li>
<?php $country_id = isset($formpost['DropDownListReferenceCountry1']) ? $formpost['DropDownListReferenceCountry1'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="r1_country" name="DropDownListReferenceCountry1" onchange="getStates(this.value, <?php echo $states; ?>, 'r1_state')">
                                                                        <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListReferenceCountry1', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
                                                                        <?php echo form_error('DropDownListReferenceCountry1'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceState1">
                                                            <li>
                                                                        <?php $state_id = isset($formpost['DropDownListReferenceState1']) ? $formpost['DropDownListReferenceState1'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListReferenceState1" id="r1_state">
                                                                <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListReferenceState1', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <?php echo form_error('DropDownListReferenceState1'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceCity1" value="<?php
                                                                if (isset($formpost['TextBoxReferenceCity1'])) {
                                                                    echo $formpost['TextBoxReferenceCity1'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone Number</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceTelephoneNumber1" value="<?php echo isset($formpost['TextBoxReferenceTelephoneNumber1']) ? $formpost['TextBoxReferenceTelephoneNumber1'] : '';?>" />
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>E-Mail</label>
                                                                <input type="email" class="invoice-fields" name="TextBoxReferenceEmail1" value="<?php
                                                                       
                                                                       if(isset($employer['referred_by_email'])){}else{$employer['referred_by_email']='';}
                                                                       if (isset($formpost['TextBoxReferenceEmail1'])) {
                                                                           echo $formpost['TextBoxReferenceEmail1'];
                                                                       }else{
                                                                           echo $employer['referred_by_email'];
                                                                       }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Name</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceName2" value="<?php
                                                                if (isset($formpost['TextBoxReferenceName2'])) {
                                                                    echo $formpost['TextBoxReferenceName2'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>How do you know this reference?</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAcquainted2" value="<?php
                                                                       if (isset($formpost['TextBoxReferenceAcquainted2'])) {
                                                                           echo $formpost['TextBoxReferenceAcquainted2'];
                                                                       }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAddress2" value="<?php
                                                                if (isset($formpost['TextBoxReferenceAddress2'])) {
                                                                    echo $formpost['TextBoxReferenceAddress2'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceCountry2">
                                                            <li>
                                                                <?php $country_id = isset($formpost['DropDownListReferenceCountry2']) ? $formpost['DropDownListReferenceCountry2'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="r2_country" name="DropDownListReferenceCountry2" onchange="getStates(this.value, <?php echo $states; ?>, 'r2_state')">
                                                                        <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListReferenceCountry2', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                                        <?php echo form_error('DropDownListReferenceCountry2'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceState2">
                                                            <li>
                                                                        <?php $state_id = isset($formpost['DropDownListReferenceState2']) ? $formpost['DropDownListReferenceState2'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListReferenceState2" id="r2_state">
                                                                <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListReferenceState2', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
                                                                       <?php echo form_error('DropDownListReferenceState2'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceCity2" value="<?php
                                                                       if (isset($formpost['TextBoxReferenceCity2'])) {
                                                                           echo $formpost['TextBoxReferenceCity2'];
                                                                       }
                                                                       ?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone Number</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceTelephoneNumber2" value="<?php echo isset($formpost['TextBoxReferenceTelephoneNumber2']) ? $formpost['TextBoxReferenceTelephoneNumber2'] : '';?>" />
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>E-Mail</label>
                                                                <input type="email" class="invoice-fields" name="TextBoxReferenceEmail2" value="<?php
                                                                if (isset($formpost['TextBoxReferenceEmail2'])) {
                                                                    echo $formpost['TextBoxReferenceEmail2'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Name</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceName3" value="<?php
                                                                if (isset($formpost['TextBoxReferenceName3'])) {
                                                                    echo $formpost['TextBoxReferenceName3'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>How do you know this reference?</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAcquainted3" value="<?php
                                                                if (isset($formpost['TextBoxReferenceAcquainted3'])) {
                                                                    echo $formpost['TextBoxReferenceAcquainted3'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceAddress3" value="<?php
                                                                if (isset($formpost['TextBoxReferenceAddress3'])) {
                                                                    echo $formpost['TextBoxReferenceAddress3'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceCountry3">
                                                            <li>
                                                                <?php $country_id = isset($formpost['DropDownListReferenceCountry3']) ? $formpost['DropDownListReferenceCountry3'] : 0; ?>
                                                                <label>Country:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="r3_country" name="DropDownListReferenceCountry3" onchange="getStates(this.value, <?php echo $states; ?>, 'r3_state')">
                                                                        <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select('DropDownListReferenceCountry3', $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                    </select>
                                                                </div>
                                                                        <?php echo form_error('DropDownListReferenceCountry3'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 DropDownListReferenceState3">
                                                            <li>
                                                                        <?php $state_id = isset($formpost['DropDownListReferenceState3']) ? $formpost['DropDownListReferenceState3'] : 0; ?>
                                                                <label>State:</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="DropDownListReferenceState3" id="r3_state">
<?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select('DropDownListReferenceState3', $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                    </select>
                                                                </div>
<?php echo form_error('DropDownListReferenceState3'); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceCity3" value="<?php
if (isset($formpost['TextBoxReferenceCity3'])) {
    echo $formpost['TextBoxReferenceCity3'];
}
?>">
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone Number</label>
                                                                <input type="text" class="invoice-fields" name="TextBoxReferenceTelephoneNumber3" value="<?php echo isset($formpost['TextBoxReferenceTelephoneNumber3']) ? $formpost['TextBoxReferenceTelephoneNumber3'] : '';?>" />
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>E-Mail</label>
                                                                <input type="email" class="invoice-fields" name="TextBoxReferenceEmail3" value="<?php
                                                                if (isset($formpost['TextBoxReferenceEmail3'])) {
                                                                    echo $formpost['TextBoxReferenceEmail3'];
                                                                }
                                                                ?>">
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Additional Information</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkExperience" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkExperience')" name="TextBoxAdditionalInfoWorkExperience"><?php
                                                                if (isset($formpost['TextBoxAdditionalInfoWorkExperience'])) {
                                                                    echo $formpost['TextBoxAdditionalInfoWorkExperience'];
                                                                }
                                                                ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkExperience_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkExperience_length">512</p>
                                                                    <?php echo form_error('TextBoxAdditionalInfoWorkExperience'); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Indicate if you have any special training or qualifications (include computer systems and programs) for the position for which you have applied</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkTraining" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkTraining')" name="TextBoxAdditionalInfoWorkTraining"><?php
                                                                    if (isset($formpost['TextBoxAdditionalInfoWorkTraining'])) {
                                                                        echo $formpost['TextBoxAdditionalInfoWorkTraining'];
                                                                    }
                                                                    ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkTraining_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkTraining_length">512</p>
                                                                    <?php echo form_error('TextBoxAdditionalInfoWorkTraining'); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Indicate any additional information you would like us to consider</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkConsideration" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkConsideration')" name="TextBoxAdditionalInfoWorkConsideration"><?php
                                                                    if (isset($formpost['TextBoxAdditionalInfoWorkConsideration'])) {
                                                                        echo $formpost['TextBoxAdditionalInfoWorkConsideration'];
                                                                    }
                                                                    ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkConsideration_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkConsideration_length">512</p>
<?php echo form_error('TextBoxAdditionalInfoWorkConsideration'); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="autoheight">
                                                        <label>Applicant Statement</label>
                                                        <p>WE ARE AN EQUAL OPPORTUNITY EMPLOYER</p>
                                                        <p>I agree that any dispute between me and the Company related to my application for employment, or my employment, if selected, will be resolved through mutually binding arbitration in accordance with the Company's Arbitration Policy and Procedures. I Understand that I have the right to review the Arbitration Policy and Procedures prior to signing this Statement.</p>
                                                        <p>I understand that if I am hired, My employment will be for no definite period, regardless of the period of payment of my wages. I further understand that I am employed on an employed at will, basis which means that I have the right to terminate my employment at any time with or without notice, and the Company has the same right. No one other than the President of the Company has authority to modify this relationship or make any agreement to the contrary. Any such modification or agreement must be in writing and signed by the Company President.</p>
                                                        <p>I further understand that if employed I will be on a 90 day introductory period, and that termination for unsatisfactory performance during that period will not result in any Company responsibility for unemployment benefits. I further understand that completion of the introductory period dos not confer any expectation of continued employment, and that if employed, my employment will be for no definite period and employed at will.</p>
                                                        <p>I understand that, to the extent permitted by law, the Company may require me to submit to a test for the presence of controlled substances in my system prior to employment. I also understand that, to the extent permitted by law, any offer of employment may be contingent upon the passing of a test for controlled substances and/or a physical examination performed by a doctor selected by the Company. I also understand that, to the extent permitted by law, I may be required to take other tests, such as personality and honesty tests, prior to employment. I understand that obtaining a bond may be a condition of employment and if required, I will be so advised either before my employment commences or as soon as possible after employment. Should a bond be required, I understand that I will need to immediately complete a bond application upon request of the Company. I understand that, in connection with this employment application, to the extent permitted by law, the Company may request an investigative consumer report.</p>
                                                        <p>I represent that all the information I have provided in this employment application, or other documentation submitted in connection with this employment application, and in any interview, is true and correct. All information is accurate and I have withheld nothing that would, if disclosed, result in an unfavorable employment decision. I understand that the Company shall have sole discretion in determining whether or not any inaccuracy, misrepresentation, or omission is material. I understand that if I am employed, and any information is later found to be false or incomplete in any respect, my employment may be immediately terminated. </p>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="application-check">
                                                        <input id="my-check" type="checkbox" value="1" name="CheckBoxAgreement1786" <?php
if (isset($formpost['CheckBoxAgreement1786']) && $formpost['CheckBoxAgreement1786'] == 1) {
    echo " checked";
}
?>>
                                                        <div class="text">
                                                            <label for="my-check">
                                                                BY CHECKING THIS BOX, I REQUEST THAT I BE PROVIDED, FREE OF CHARGE, A COPY OF ANY REPORT GENERATED ON ME AS A RESULT OF MY APPLICATION. 
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="application-check">
                                                        <div class="text form-col-100">
                                                            IF YOU HAVE ANY QUESTIONS REGARDING THIS APPLICANT UNDERSTANDING, PLEASE ASK A COMPANY REPRESENTATIVE BEFORE SIGNING BELOW OR SUBMITTING THIS E-APPLICATION. 
                                                        </div>
                                                    </div>
                                                    <div class="application-check">
                                                        <figure>
                                                            <input id="my-check1" type="checkbox" value="1" name="CheckBoxAgree" <?php
if (isset($formpost['CheckBoxAgree']) && $formpost['CheckBoxAgree'] == 1) {
    echo " checked";
}
?>>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="my-check1">
                                                                I acknowledge that I have reviewed, and satisfy, any job requirements for the position for which I am applying. Furthermore, by signing below, or by clicking the "Submit" button, I acknowledge having read the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> & <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> that includes the use of electronic signatures. By continuing I agree to be bound by the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a>. If you do not agree with the provisions of the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> & <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> do not continue.  
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-fields-row">
                                                    <div class="col-lg-2">
                                                        <label class="signature-label">E-SIGNATURE</label>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <?php $def_value = (isset($formpost['signature']) ? $formpost['signature'] : '' ); ?>
                                                        <input class="signature-field" name="signature" id="signature" value="<?php echo set_value('signature', $def_value); ?>" type="text">
                                                        <p>Please type your First and Last Name</p>
<?php echo form_error('signature'); ?>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label class="signature-label">DATE</label>
                                                    </div>
                                                    <div class="col-lg-4">
<?php $def_value = (isset($formpost['signature_date']) ? $formpost['signature_date'] : '' ); ?>
                                                        <div class="calendar-picker">
                                                            <input  type="text" class="invoice-fields startdate" name="signature_date" id="signature_date" value="<?php echo set_value('signature_date', $def_value); ?>" />
                                                        </div>
                                                                <?php echo form_error('signature_date'); ?>
                                                    </div>
                                                </div>

                                                <div class="full-width">
<?php if (!empty($ip_track)) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <label class="" style="font-size:14px; padding: 0 20px;">IP Address</label>
                                                            </div>
                                                            <div class="col-lg-5">
                                                                <span>
    <?php if (!empty($ip_track)) { ?>
                                                                        <?php echo $ip_track['ip_address']; ?>
                                                                    <?php } else { ?>
                                                                        <?php echo getUserIP(); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <label class="" style="font-size:14px; padding: 0 20px;">Date/Time</label>
                                                            </div>
                                                            <div class="col-lg-5">
                                                                <span>
    <?php                                           if (!empty($ip_track)) { ?>
        <?php                                               echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
    <?php                                           } else { ?>
                                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                                <?php } ?>
                                                                </span>
                                                            </div>
                                                        </div>
<?php                                               } ?>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="application-check"><!-- class="card-fields-row" -->
                                                        <figure>
<?php                                                       $key = 'CheckBoxTerms'; ?>
<?php                                                       $def_value = (isset($formpost[$key]) ? $formpost[$key] : '' ); ?>
<?php                                                       $def_checked = ( $def_value == 1 ? true : false ); ?>
                                                            <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="terms_check" value="1" name="CheckBoxTerms" type="checkbox" <?php // echo $disabled_check;  ?>>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="terms_check">
                                                                <strong>I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.</strong> <strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS FOR <?php echo $company_name; ?></strong><br>
                                                            </label>
<?php                                                   echo form_error('CheckBoxTerms'); ?>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            1. Electronic Signature Agreement. By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide <?php echo $company_name; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and <?php echo $company_name; ?>. You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a <?php echo $company_name; ?> service constitutes your agreement to be bound by the terms and conditions of the <?php echo $company_name; ?> Disclosures and Agreements as they exist on the date of your E-Signature
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight aligncenter">
                                                        <input type="hidden" name="action" value="true">
                                                        <input type="hidden" name="sid" value="<?php echo $employer['sid']; ?>">
                                                        <input type="submit" class="submit-btn" onclick="validate_form()" value="Submit Application">
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
<?php                       echo form_close(); ?>
                        </div>
                    </div>
                </div>
<?php           $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
        $(document).ready(function () { //Disable Autocomplete
            $('input,select,textarea').each(function () {
                $(this).attr('autocomplete', 'off');
            });

            if("<?=$eight_plus?>" == 1){
                var IAEvalue = $('input[name="is_already_employed"]:checked').val();

                if (IAEvalue == "Yes") {
                    $("#previous_company_name").prop('required',true);
                    $(".yacr").show();
                } else {
                    $("#previous_company_name").prop('required',false);
                    $(".yacr").hide();
                }
            }

            if("<?=$d_license?>" == 1){
                var DLvalue = $('input[name="RadioButtonListDriversLicenseQuestion"]:checked').val();
                console.log(DLvalue)
                if (DLvalue == "Yes") {
                    $("#TextBoxDriversLicenseNumber").prop('required',true);
                    $("#TextBoxDriversLicenseExpiration").prop('required',true);
                    $("#DropDownListDriversCountry").prop('required',true);
                    $("#DropDownListDriversState").prop('required',true);
                    $("#license_guilty_details").prop('required',true);
                    $(".dllr").show();
                } else {
                    console.log("here")
                    $("#TextBoxDriversLicenseNumber").prop('required',false);
                     $("#TextBoxDriversLicenseExpiration").prop('required',false);
                    $("#DropDownListDriversCountry").prop('required',false);
                    $("#DropDownListDriversState").prop('required',false);
                    $("#license_guilty_details").prop('required',false);
                    $(".dllr").hide();
                }
            } 

        });

        function getStates(val, states, select_id) {
            var html = '';

            if (val == '') {
                $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
            } else {
                allstates = states[val];
                html += '<option value="">Select State</option>';
                for (var i = 0; i < allstates.length; i++) {
                    var id = allstates[i].sid;
                    var name = allstates[i].state_name;
                    html += '<option value="' + id + '">' + name + '</option>';
                }
                $('#' + select_id).html(html);
                $('#' + select_id).trigger('change');
            }
        }

        //
        var rules = {
                    first_name: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\ '-]+$/
                    },
                    last_name: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\ '-]+$/
                    },
                    TextBoxNameMiddle: {
                        pattern: /^[a-zA-Z0-9\ '-]+$/
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    TextBoxAddressEmailConfirm: {
                        required: true,
                        equalTo: '[name="email"]'
                    },
                    Location_Address:{
                        required: true
                    },
                    Location_City: {
                        required: true
                    },
                    Location_ZipCode: {
                        required: true
                    },
                    PhoneNumber: {
                        required: true
                    },
                    CheckBoxAgreement1786: {
                        required: true
                    },
                    CheckBoxAgree: {
                        required: true
                    },
                    signature: {
                        required: true,
                        pattern: /[a-zA-Z0-9]/
                    },
                    signature_date: {
                        required: true
                    },
                    CheckBoxTerms: {
                        required: true
                    }
                };
        var messages = {
                    first_name: {
                        required: 'First Name is required',
                        pattern: 'invalid first name'
                    },
                    last_name: {
                        required: 'Last Name is required',
                        pattern: 'invalid last name'
                    },
                    TextBoxNameMiddle: {
                        pattern: 'invalid middle name'
                    },
                    email: {
                        required: 'E-Mail address is required',
                        email: 'Invalid E-Mail address'
                    },
                    TextBoxAddressEmailConfirm: {
                        equalTo: 'Confirm E-mail does not match E-mail'
                    },
                    Location_City: {
                        required: 'City is required'
                    },
                    Location_Address: {
                        required: 'Current Address is required'
                    },
                    Location_ZipCode: {
                        required: 'ZipCode is required'
                    },
                    PhoneNumber: {
                        required: 'Primary Telephone No is required'
                    },
                    CheckBoxAgreement1786: {
                        required: 'Please Read and check'
                    },
                    CheckBoxAgree: {
                        required: 'Please Read and acknowledge'
                    },
                    signature: {
                        required: 'Please Sign The Document'
                    },
                    signature_date: {
                        required: 'Signature Date is Required'
                    },
                    CheckBoxTerms: {
                        required: 'Please read our Terms of Acceptance'
                    }
                };

    //
    if("<?=$ssn_required?>" == 1){
        rules['TextBoxSSN'] = { required: true };
        messages['TextBoxSSN'] = { required: "Social Security Number is required." };
    }
    //
    if("<?=$dob_required?>" == 1){
        rules['TextBoxDOB'] = { required: true };
        messages['TextBoxDOB'] = { required: "Date of Birth is required." };
    }
    
    //
    if("<?=$eight_plus?>" == 1){
        rules['RadioButtonListWorkOver18'] = { required: true };
        messages['RadioButtonListWorkOver18'] = { required: "This field is required." };
    }
    
    //
    // if("<?=$affiliate?>" == 1){
        //     rules['previous_company_name'] = { required: true };
        //     messages['previous_company_name'] = { required: "This field is required." };
        // }
        if("<?=$affiliate?>" == 1){
            $(".validate_affiliate_company").on("change", function(){
                var value = $('input[name="is_already_employed"]:checked').val();

                if (value == "Yes") {
                    $("#previous_company_name").prop('required',true);
                    $(".yacr").show();
                } else {
                    $("#previous_company_name").prop('required',false);
                    $(".yacr").hide();
                }
                
            });
        }
        
        //
        if("<?=$d_license?>" == 1){
            $(".validate_driving_license").on("change", function(){
                var DLvalue = $('input[name="RadioButtonListDriversLicenseQuestion"]:checked').val();
                if (DLvalue == "Yes") {
                    $("#TextBoxDriversLicenseNumber").prop('required',true);
                    $("#TextBoxDriversLicenseExpiration").prop('required',true);
                    $("#DropDownListDriversCountry").prop('required',true);
                    $("#DropDownListDriversState").prop('required',true);
                    $(".dllr").show();
                } else {
                    $("#TextBoxDriversLicenseNumber").prop('required',false);
                     $("#TextBoxDriversLicenseExpiration").prop('required',false);
                    $("#DropDownListDriversCountry").prop('required',false);
                    $("#DropDownListDriversState").prop('required',false);
                    $(".dllr").hide();
                }
            });   
            
            $('[name="RadioButtonListDriversLicenseTraffic"]').on("change", function(){
                var DLvalue = $('input[name="RadioButtonListDriversLicenseTraffic"]:checked').val();
                if (DLvalue == "Yes") {
                    $("#license_guilty_details").prop('required',true);
                } else {
                    $("#license_guilty_details").prop('required',false);
                }
            });   
            // rules['TextBoxDriversLicenseNumber'] = { required: true };
            // rules['TextBoxDriversLicenseExpiration'] = { required: true };
            // rules['DropDownListDriversCountry'] = { required: true };
            // rules['DropDownListDriversState'] = { required: true };
            // rules['license_guilty_details'] = { required: true };
            // messages['TextBoxDriversLicenseNumber'] = { required: "This field is required." };
            // messages['TextBoxDriversLicenseExpiration'] = { required: "This field is required." };
            // messages['DropDownListDriversCountry'] = { required: "This field is required." };
            // messages['DropDownListDriversState'] = { required: "This field is required." };
            // messages['license_guilty_details'] = { required: "This field is required." };
        }
    
    //
    if("<?=$l_employment?>" == 1){
        rules['TextBoxEmploymentEmployerName1'] = { required: true };
        rules['TextBoxEmploymentEmployerPosition1'] = { required: true };
        rules['TextBoxEmploymentEmployerAddress1'] = { required: true };
        rules['DropDownListEmploymentEmployerCountry1'] = { required: true };
        rules['DropDownListEmploymentEmployerState1'] = { required: true };
        rules['TextBoxEmploymentEmployerCity1'] = { required: true };
        rules['TextBoxEmploymentEmployerPhoneNumber1'] = { required: true };
        rules['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] = { required: true };
        rules['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'] = { required: true };
        rules['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] = { required: true };
        rules['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'] = { required: true };
        rules['TextBoxEmploymentEmployerSupervisor1'] = { required: true };
        rules['TextBoxEmploymentEmployerReasonLeave1'] = { required: true };
        messages['TextBoxEmploymentEmployerName1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerPosition1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerAddress1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerCountry1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerState1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerCity1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerPhoneNumber1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'] = { required: "This field is required." };
        messages['DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerSupervisor1'] = { required: "This field is required." };
        messages['TextBoxEmploymentEmployerReasonLeave1'] = { required: "This field is required." };
    }

        function validate_form() {
            $("#fullemploymentapplication").validate({
                ignore: ":hidden:not(select)",
                rules: rules,
                messages: messages,
                submitHandler: function (form) {
                   
                    var check_radio=$('input[name=is_already_employed]:checked').val();
                       if(check_radio=="Yes")
                    {
                        if($("#previous_company_name").val().length > 1)
                        {
                           form.submit();
                        }else{
                          
                             $("#show_specific_error").text("kindly provide previous company or affiliate company name");
                        }
                    }else{
                          form.submit();
                    }
                }
            });
        }

        $('.startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50"
        }).val();

        $('#dob').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            maxDate: 0
        }).val();

        function check_length(id) {
            var get_allowed_length = id + '_length';
            var text_allowed = $('#' + get_allowed_length).html();
            var user_text = $('#' + id).val();
            var newLines = user_text.match(/(\r\n|\n|\r)/g);
            var addition = 0;

            if (newLines != null) {
                addition = newLines.length;
            }

            var text_length = user_text.length + addition;
            var text_left = text_allowed - text_length;
            $('#' + id + '_remaining').html(text_left + ' Characters Left');
        }

        function print_page(elem) {
            $("table").removeClass("horizontal-scroll");
            var data = ($(elem).html());
            var mywindow = window.open('', '<?php echo $title; ?>', 'height=800,width=1200');
            mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/print.css'); ?>" type="text/css" />');
            mywindow.document.write('</head><body style="margin-top:0%;display:block;height:100%">');
            mywindow.document.write('<div class="main-content"><div class="dashboard-wrp"><div class="container-fluid"><div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-8"><div class="row">');
            mywindow.document.write(data);
            mywindow.document.write('</div></div></div></div></div></div>');
            mywindow.document.write('</body></html>');
            mywindow.document.write('<scr' + 'ipt>function printings() { window.print(); window.close(); }</scr' + 'ipt>');
            mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
            mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).on("load", function() { setTimeout(printings, 1000); });</scr' + 'ipt>');
            mywindow.document.close();
            mywindow.focus();

            $("table").addClass("horizontal-scroll");
        }
</script>