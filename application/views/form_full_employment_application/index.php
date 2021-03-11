<?php $this->load->view('main/static_header'); ?>
<?php $this->load->view('form_full_employment_application/company_privacy_policy'); ?>
<?php $this->load->view('form_full_employment_application/company_terms_of_use'); ?>
<body>
    <!-- Header Start -->
    <header class="header header-position">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                    <h2 style="color: #fff; text-align: center;"><?php echo $page_title; ?></h2>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
    <!--                <pre>-->
                    <!--                    --><?php //print_r($user_info);  ?>
                    <!--                    --><?php //echo validation_errors(); ?>
                    <!--                </pre>-->

                    <?php $signed_flag = ($request_details['status'] == 'signed' ? true : false ); ?>

                    <div class="end-user-wrp-outer">
                        <div class="end-user-agreement-wrp full-employement-form-v2">
                            <form id="fullemploymentapplication" enctype="multipart/form-data" method="post" autocomplete="none">
                                <div class="employement-application-form universal-form-style-v2">

                                    <ul>
                                        <div class="container-fluid">
                                            <label><span class="staric">Please Do Not Use Your Web Browser's AutoComplete Feature</span></label>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'first_name'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>First Name <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="first_name" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxNameMiddle'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Middle Name</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxNameMiddle" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'last_name'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Last Name <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="last_name" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'suffix'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Suffix </label>
                                                        <select <?php echo $disabled_check; ?> class="invoice-fields" name="suffix">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($suffix_values as $suffix_value) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $suffix_value['value']; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                <option <?php echo set_select('suffix', $cur_value, $def_selected) ?> value="<?php echo $suffix_value['value']; ?>"><?php echo $suffix_value['key']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('suffix'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxSSN'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Social Security Number <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="TextBoxSSN" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxDOB'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : (empty($birthDate) ? '' : $birthDate) ); ?>
                                                        <label>Date of Birth <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields startdate" required="required" readonly="" name="TextBoxDOB" value="<?php echo set_value($key, $def_value); ?>" id="TextBoxDOB" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="autoheight">
                                                        Your date of birth is required and may be used for purposes directly related to the background check process and will not be used for any other purpose. Failure to provide your date of birth may cause a delay in processing your application for employment.
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'email'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Email Address <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="email" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxAddressEmailConfirm'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Confirm Email Address <span class="staric">*</span></label>
                                                        <input autocomplete="new" <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="TextBoxAddressEmailConfirm" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                        <?php echo form_error($key); ?>
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
                                                            <label class="autoheight">Have you ever been employed with our company or our Affiliate companies?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> class="check_value" id="is_already_employed_yes" value="Yes" name="is_already_employed" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="is_already_employed_yes">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> class="check_value" id="is_already_employed_no" value="No" name="is_already_employed" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="is_already_employed_no">No</label>
                                                            </div>
                                                  <?php echo form_error('is_already_employed'); ?>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
<?php $key = 'previous_company_name'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <small class="autoheight">If yes, position held/what company or Affiliate company?</small>
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> name="previous_company_name" id="previous_company_name" maxlength="512" onkeyup="check_length('previous_company_name')" class="form-col-100 invoice-fields"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <div id="show_specific_error" style="color:red"></div>
                                                                <span id="license_guilty_details_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="previous_company_name_length">512</p>
                                                            </div>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'Location_Address'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Current Residence <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="Location_Address" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxAddressLenghtCurrent'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>How Long?</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressLenghtCurrent" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'Location_City'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>City <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="Location_City" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'Location_ZipCode'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Zip Code <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="Location_ZipCode" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'Location_Country'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <?php $country_id = $LC = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>, 'state')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'Location_State'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <?php $state_id = $LS = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="invoice-fields" name="Location_State" id="state">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                        <?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
                                                <?php /* $key = 'CheckBoxAddressInternationalCurrent'; */ ?>
                                                <?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
<?php /* $def_checked = ( $def_value == 1 ? true : false ); */ ?>
                                                        <div class="checkbox-field">
                                                            <input <?php /* echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalCurrent" name="CheckBoxAddressInternationalCurrent" value="1" type="checkbox" <?php /* echo $disabled_check; */ ?>>
                                                            <label for="CheckBoxAddressInternationalCurrent">Non USA Address</label>
                                                        </div>
<?php /* echo form_error('CheckBoxAddressInternationalCurrent'); */ ?>
                                                    </li>
                                                </div>-->
                                                <div class="bg-color">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxAddressStreetFormer1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Former Residence</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressStreetFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxAddressLenghtFormer1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>How Long?</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressLenghtFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxAddressCityFormer1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>City</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressCityFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxAddressZIPFormer1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Zip Code</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressZIPFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'DropDownListAddressCountryFormer1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                            <label>Country:</label>
                                                            <select class="invoice-fields" id="country_former1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former1')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'DropDownListAddressStateFormer1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $state_id = $def_value ?>
                                                            <label>State:</label>
                                                            <select class="invoice-fields" name="<?php echo $key; ?>" id="state_former1">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                    <?php /* $key = 'CheckBoxAddressInternationalFormer1'; */ ?>
<?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
<?php /* $def_checked = ( $def_value == 1 ? true : false ); */ ?>
                                                            <div class="checkbox-field">
                                                                <input <?php /* echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalFormer1" value="1" name="CheckBoxAddressInternationalFormer1" type="checkbox" <?php /* echo $disabled_check; */ ?>>
                                                                <label for="CheckBoxAddressInternationalFormer1">Non USA Address</label>
<?php /* echo form_error('CheckBoxAddressInternationalFormer1'); */ ?>
                                                            </div>
                                                        </li>
                                                    </div>-->
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'TextBoxAddressStreetFormer2'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Former Residence</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressStreetFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'TextBoxAddressLenghtFormer2'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>How Long?</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressLenghtFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'TextBoxAddressCityFormer2'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>City</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressCityFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'TextBoxAddressZIPFormer2'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Zip Code</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressZIPFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'DropDownListAddressCountryFormer2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="invoice-fields" id="country_former2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former2')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'DropDownListAddressStateFormer2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="invoice-fields" name="<?php echo $key; ?>" id="state_former2">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
<?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
<?php /* $key = 'CheckBoxAddressInternationalFormer2'; */ ?>
<?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
                                                <?php /* $def_checked = ( $def_value == 1 ? true : false ); */ ?>
                                                        <div class="checkbox-field">
                                                            <input <?php /* echo $readonly_check; */ ?> <?php /* echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalFormer2" value="1" name="CheckBoxAddressInternationalFormer2" type="checkbox" <?php /* echo $disabled_check; */ ?>>
                                                            <label for="CheckBoxAddressInternationalFormer2">Non USA Address</label>
<?php /* echo form_error('CheckBoxAddressInternationalFormer2'); */ ?>
                                                        </div>
                                                    </li>
                                                </div>-->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li>
                                                        <?php $key = 'TextBoxAddressStreetFormer3'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? ($user_info[$key]) : (isset($extra_info['other_email']) ? $extra_info['other_email'] : '') ); ?>
                                                        <label>Other Mailing Address</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressStreetFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxAddressCityFormer3'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : $user_info['Location_City'] ); ?>
                                                        <label>City</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressCityFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxAddressZIPFormer3'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Zip Code</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxAddressZIPFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'DropDownListAddressCountryFormer3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="invoice-fields" id="country_former3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former3')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'DropDownListAddressStateFormer3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="invoice-fields" name="<?php echo $key; ?>" id="state_former3">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                        } else {
                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'PhoneNumber'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : $user_info['PhoneNumber'] ); ?>
                                                        <label>Primary Telephone <span class="staric">*</span></label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" required="required" name="PhoneNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxTelephoneMobile'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Mobile Telephone </label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxTelephoneMobile" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxTelephoneOther'; ?>
<?php $def_value = (isset($user_info[$key]) && !empty($user_info[$key]) ? ($user_info[$key]) : (isset($extra_info['other_PhoneNumber']) ? $extra_info['other_PhoneNumber'] : '') ); ?>
                                                        <label>Other Telephone </label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxTelephoneOther" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
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
                                                            <?php $key = 'RadioButtonListPostionTime'; ?>
<?php $def_value = (isset($user_info[$key]) && !empty($user_info[$key]) ? $user_info[$key] : $user_info['employee_type'] ); ?>
<?php $full_time_selected = ($def_value == 'full_time' || $def_value == 'fulltime' || $def_value == 'full-time') ? true : false ; ?>
<?php $part_time_selected = ($def_value == 'part_time' || $def_value == 'parttime' || $def_value == 'part-time') ? true : false ; ?>
<?php $full_or_part_time_selected = ($def_value == 'full_or_parttime' ? true : false ); ?>

                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'full_time', $full_time_selected); ?> id="RadioButtonListPostionTime_0" value="full_time" name="RadioButtonListPostionTime" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListPostionTime_0">Full time</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'part_time', $part_time_selected); ?> id="RadioButtonListPostionTime_1" value="part_time" name="RadioButtonListPostionTime" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListPostionTime_1">Part time</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'full_or_parttime', $full_or_part_time_selected); ?> id="RadioButtonListPostionTime_2" value="full_or_parttime" name="RadioButtonListPostionTime" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListPostionTime_2">Full or Part time</label>
                                                            </div>
                                                            <?php echo form_error('RadioButtonListPostionTime'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <?php $key = 'TextBoxPositionDesired'; ?>
<?php $def_value = (isset($user_info[$key]) && !empty($user_info[$key]) ? ($user_info[$key]) : (isset($user_info['job_title']) ? $user_info['job_title'] : '') ); ?>
                                                            <label class="autoheight">If you want to apply for more than one position, please list them in this field.</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxPositionDesired" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error('TextBoxPositionDesired'); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxWorkBeginDate'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>What date can you begin work?</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields startdate" readonly="" name="TextBoxWorkBeginDate" value="<?php echo set_value($key, $def_value); ?>" id="dp1474003792803" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxWorkCompensation'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <label>Expected compensation</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxWorkCompensation" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li class="form-col-100 autoheight">
                                                        <label class="autoheight">Do you have transportation to/from work?</label>
                                                    </li>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListWorkTransportation'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListWorkTransportation_0" name="RadioButtonListWorkTransportation" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListWorkTransportation_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListWorkTransportation_1" name="RadioButtonListWorkTransportation" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListWorkTransportation_1">No</label>
                                                        </div>
<?php echo form_error('RadioButtonListWorkTransportation'); ?>
                                                    </li>
                                                </div>
                                                <div class="bg-color-v2">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Are you 18 years or older?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListWorkOver18'; ?>
<?php 
    if(isset($user_info[$key])){
        if($user_info[$key] == 'Yes') $yes_selected = true;
        else $no_selected = true;
    } else{
        if($above18 >= 18) $yes_selected = true;
        else $no_selected = true;
    }
?>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListWorkOver18_0" name="RadioButtonListWorkOver18" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListWorkOver18_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListWorkOver18_1" name="RadioButtonListWorkOver18" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListWorkOver18_1">No</label>
                                                            </div>
<?php echo form_error($key); ?>
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
<?php $key = 'RadioButtonListAliases'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListAliases_0" name="RadioButtonListAliases" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListAliases_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListAliases_1" name="RadioButtonListAliases" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListAliases_1">No</label>
                                                        </div>
                                                        <?php echo form_error($key); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="comment-area">
<?php $key = 'nickname_or_othername_details'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <small>If yes, please explain and indicate name(s):</small>
                                                        <textarea <?php echo $readonly_check; ?> name="nickname_or_othername_details" id="nickname_or_othername_details" class="form-col-100 invoice-fields" maxlength="512" onkeyup="check_length('nickname_or_othername_details')"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="nickname_or_othername_details_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="nickname_or_othername_details_length">512</p>
                                                        <p>When answering the following questions, do not include minor traffic infractions, ANY convictions for which the record has been sealed and/or expunged, and/or eradicated, any conviction for which probation has been successfully completed or otherwise discharged with the case having been judicially dismissed, any information regarding referrals to and/or participation in any pre-trial or post-trial diversion programs (California applicants only, do not include infractions involving marijuana offenses that occurred over two years ago). A conviction record will not necessarily be a bar to employment. Factors such as age, time of the offense, seriousness and nature of the violation, and rehabilitation will be taken into account.</p>
<?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <!--<div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
<?php /* $key = 'RadioButtonListCriminalWrongDoing'; */ ?>
<?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
<?php /* $yes_selected = ( $def_value == 'Yes' ? true : false ); */ ?>
<?php /* $no_selected = ( $def_value == 'No' ? true : false ); */ ?>
                                                            <label class="autoheight">Have you ever plead Guilty, No Contest, or been Convicted of a Misdemeanor and/or Felony?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php /* echo set_radio($key, 'Yes', $yes_selected); */ ?> value="Yes" id="RadioButtonListCriminalWrongDoing_0" name="RadioButtonListCriminalWrongDoing" type="radio" <?php /* echo $disabled_check; */ ?>>
                                                                <label for="RadioButtonListCriminalWrongDoing_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php /* echo set_radio($key, 'No', $no_selected); */ ?> value="No" id="RadioButtonListCriminalWrongDoing_1" name="RadioButtonListCriminalWrongDoing" type="radio" <?php /* echo $disabled_check; */ ?>>
                                                                <label for="RadioButtonListCriminalWrongDoing_1">No</label>
                                                            </div>
<?php /* echo form_error('RadioButtonListCriminalWrongDoing'); */ ?>
                                                        </li>
                                                    </div>
                                                </div>-->
                                                <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
<?php /* $key = 'RadioButtonListCriminalBail'; */ ?>
<?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
<?php /* $yes_selected = ( $def_value == 'Yes' ? true : false ); */ ?>
<?php /* $no_selected = ( $def_value == 'No' ? true : false ); */ ?>
                                                        <label class="autoheight">Have you been arrested for any matter for which you are now out on bail or have been released on your own recognizance pending trial?</label>
                                                        <div class="hr-radio-btns">
                                                            <input <?php /* echo set_radio($key, 'Yes', $yes_selected); */ ?> value="Yes" id="RadioButtonListCriminalBail_0" name="RadioButtonListCriminalBail" type="radio" <?php /* echo $disabled_check; */ ?>>
                                                            <label for="RadioButtonListCriminalBail_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php /* echo set_radio($key, 'No', $no_selected); */ ?> value="No" id="RadioButtonListCriminalBail_1" name="RadioButtonListCriminalBail" type="radio" <?php /* echo $disabled_check; */ ?>>
                                                            <label for="RadioButtonListCriminalBail_1">No</label>
                                                        </div>
<?php /* echo form_error('RadioButtonListCriminalBail'); */ ?>
                                                    </li>
                                                </div>-->
                                                <!--<div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
<?php /* $key = 'arrested_pending_trail_details'; */ ?>
<?php /* $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); */ ?>
                                                            <label class="autoheight">If yes to either of the above questions, provide dates and details for each, including the case number and court where your case is/was handled:</label>
                                                            <div class="comment-area">
                                                                <textarea <?php /* echo $readonly_check; */ ?> name="arrested_pending_trail_details" id="arrested_pending_trail_details" maxlength="512" onkeyup="check_length('arrested_pending_trail_details')" class="form-col-100 invoice-fields"><?php /* echo set_value($key, $def_value); */ ?></textarea>
                                                                <span id="arrested_pending_trail_details_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="arrested_pending_trail_details_length">512</p>
                                                            </div>
<?php /* echo form_error($key); */ ?>
                                                        </li>
                                                    </div>
                                                </div>-->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListDriversLicenseQuestion'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes'  || sizeof($drivers_license_details) ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                        <label class="autoheight">Driver's License: A valid driver's license may be a requirement for the position for which you have applied. If so, do you currently have a valid driver's license?</label>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListDriversLicenseQuestion_0" value="Yes" name="RadioButtonListDriversLicenseQuestion" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListDriversLicenseQuestion_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListDriversLicenseQuestion_1" value="No" name="RadioButtonListDriversLicenseQuestion" type="radio" <?php echo $disabled_check; ?>>
                                                            <label for="RadioButtonListDriversLicenseQuestion_1">No</label>
                                                        </div>
                                                        <?php echo form_error('RadioButtonListDriversLicenseQuestion'); ?>
                                                    </li>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxDriversLicenseNumber'; ?>
<?php $def_value = (isset($user_info[$key]) ? ($user_info[$key]) : (isset($drivers_license_details['license_number']) ? $drivers_license_details['license_number'] : '') ); ?>
                                                        <label>Driver's license number:</label>
                                                        <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxDriversLicenseNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                        <?php $key = 'TextBoxDriversLicenseExpiration'; ?>
<?php $def_value = (isset($user_info[$key]) ? ($user_info[$key]) : (isset($drivers_license_details['license_expiration_date']) ? $drivers_license_details['license_expiration_date'] : '') ); ?>
                                                        <label>Expiration date:</label>
                                                        <input <?php echo $readonly_check; ?> name="TextBoxDriversLicenseExpiration" class="invoice-fields startdate" value="<?php echo set_value($key, $def_value); ?>" id="dp1474003792804" type="text">
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
<?php $key = 'DropDownListDriversCountry'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) && !empty($user_info[$key]) ? ($user_info[$key]) : (sizeof($drivers_license_details) ? ($LC) : 0) ); ?>
                                                            <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="invoice-fields" id="country_dl" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_dl')">
                                                            <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <li>
                                                            <?php $key = 'DropDownListDriversState'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) && !empty($user_info[$key]) ? $user_info[$key] : $LS ); ?>
                                                            <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="invoice-fields" name="<?php echo $key; ?>" id="state_dl">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                        </select>
<?php echo form_error($key); ?>
                                                    </li>
                                                </div>

                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListDriversLicenseTraffic'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <label class="autoheight">Within the last 5 years, have you ever plead Guilty, No Contest, or been Convicted of any traffic violation(s)?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListDriversLicenseTraffic" value="Yes" name="RadioButtonListDriversLicenseTraffic" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListDriversLicenseTraffic">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListDriversLicenseTraffic_1" value="No" name="RadioButtonListDriversLicenseTraffic" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListDriversLicenseTraffic_1">No</label>
                                                            </div>
<?php echo form_error('RadioButtonListDriversLicenseTraffic'); ?>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
<?php $key = 'license_guilty_details'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <small class="autoheight">If yes , provide dates and details for each violation, including the case number and court where your case is/was handled:</small>
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> name="license_guilty_details" id="license_guilty_details" maxlength="512" onkeyup="check_length('license_guilty_details')" class="form-col-100 invoice-fields"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="license_guilty_details_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="license_guilty_details_length">512</p>
                                                            </div>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="education-level-block">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100">
                                                        <?php $key = 'TextBoxEducationHighSchoolName'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Education - High School</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationHighSchoolName" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        </li>
<?php echo form_error($key); ?>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Did you graduate?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListEducationHighSchoolGraduated'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationHighSchoolGraduated_0" name="RadioButtonListEducationHighSchoolGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEducationHighSchoolGraduated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationHighSchoolGraduated_1" name="RadioButtonListEducationHighSchoolGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEducationHighSchoolGraduated_1">No</label>
                                                            </div>
                                                                <?php echo form_error('RadioButtonListEducationHighSchoolGraduated'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxEducationHighSchoolCity'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationHighSchoolCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>


                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolCountry'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_ehs" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ehs')">
                                                                    <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolState'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ehs">
                                                                    <?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                            ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedMonthBegin'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Dates of Attendance</label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedMonthBegin">
                                                                    <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedYearBegin'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedYearBegin">
                                                                    <option value="">Please Select</option>
                                                                <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedMonthEnd'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedMonthEnd">
                                                                    <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedYearEnd'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationHighSchoolDateAttendedYearEnd">
                                                                    <option value="">Please Select</option>
                                                                <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="education-level-block">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100">
<?php $key = 'TextBoxEducationCollegeName'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>College/University</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationCollegeName" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <label class="autoheight">Did you graduate?</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListEducationCollegeGraduated'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                                <div class="hr-radio-btns">
                                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationCollegeGraduated_0" name="RadioButtonListEducationCollegeGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                    <label for="RadioButtonListEducationCollegeGraduated_0">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationCollegeGraduated_1" name="RadioButtonListEducationCollegeGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                    <label for="RadioButtonListEducationCollegeGraduated_1">No</label>
                                                                </div>
                                                                    <?php echo form_error('RadioButtonListEducationCollegeGraduated'); ?>
                                                            </li>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
<?php $key = 'TextBoxEducationCollegeCity'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label>City</label>
                                                                    <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationCollegeCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                                </li>
                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeCountry'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                        <?php $country_id = $def_value ?>
                                                                    <label>Country:</label>
                                                                    <select class="invoice-fields" id="country_ecc" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ecc')">
                                                                        <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                                    </select>
                                                                    <?php echo form_error($key); ?>
                                                                </li>
                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeState'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                        <?php $state_id = $def_value ?>
                                                                    <label>State:</label>
                                                                    <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ecc">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="">Select State</option> <?php
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                                ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                    </select>
                                                                    <?php echo form_error($key); ?>
                                                                </li>
                                                            </div>

                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeDateAttendedMonthBegin'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label>Dates of Attendance</label>
                                                                    <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationCollegeDateAttendedMonthBegin">
                                                                        <option vlaue="">Please Select</option>
                                                                    <?php foreach ($months as $month) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                    <?php } ?>
                                                                    </select>
<?php echo form_error($key); ?>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeDateAttendedYearBegin'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label></label>
                                                                    <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationCollegeDateAttendedYearBegin">
                                                                        <option value="">Please Select</option>
                                                                    <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                    </select>
                                                                    <?php echo form_error('DropDownListEducationCollegeDateAttendedYearBegin'); ?>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                <span class="date-range-text">to</span>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeDateAttendedMonthEnd'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label></label>
                                                                    <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationCollegeDateAttendedMonthEnd">
                                                                        <option vlaue="">Please Select</option>
                                                                    <?php foreach ($months as $month) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                    <?php } ?>
                                                                    </select>
<?php echo form_error('DropDownListEducationCollegeDateAttendedMonthEnd'); ?>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                        <?php $key = 'DropDownListEducationCollegeDateAttendedYearEnd'; ?>
                                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label></label>
                                                                    <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationCollegeDateAttendedYearEnd">
                                                                        <option value="">Please Select</option>
                                                                    <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                    </select>
                                                                    <?php echo form_error('DropDownListEducationCollegeDateAttendedYearEnd'); ?>
                                                                </li>
                                                            </div>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
<?php $key = 'TextBoxEducationCollegeMajor'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label>Major</label>
                                                                    <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationCollegeMajor" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
<?php $key = 'TextBoxEducationCollegeDegree'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <label>Degree Earned</label>
                                                                    <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationCollegeDegree" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="education-level-block">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100">
<?php $key = 'TextBoxEducationOtherName'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Other School</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationOtherName" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Did you graduate?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
<?php $key = 'RadioButtonListEducationOtherGraduated'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationOtherGraduated_0" name="RadioButtonListEducationOtherGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEducationOtherGraduated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationOtherGraduated_1" name="RadioButtonListEducationOtherGraduated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEducationOtherGraduated_1">No</label>
                                                            </div>
                                                                <?php echo form_error('RadioButtonListEducationOtherGraduated'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxEducationOtherCity'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationOtherCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherCountry'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_eo" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_eo')">
                                                                    <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherState'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_eo">
                                                                    <?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
                                                                } else {
                                                                    foreach ($active_states[$country_id] as $active_state) {
                                                                        ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
<?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>


                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherDateAttendedMonthBegin'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Dates of Attendance</label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationOtherDateAttendedMonthBegin">
                                                                    <option vlaue="">Please Select</option>
<?php foreach ($months as $month) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherDateAttendedYearBegin'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationOtherDateAttendedYearBegin">
                                                                    <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherDateAttendedMonthEnd'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationOtherDateAttendedMonthEnd">
                                                                    <option vlaue="">Please Select</option>
<?php foreach ($months as $month) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                    <?php $key = 'DropDownListEducationOtherDateAttendedYearEnd'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEducationOtherDateAttendedYearEnd">
                                                                    <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxEducationOtherMajor'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Major</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationOtherMajor" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxEducationOtherDegree'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Degree Earned</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationOtherDegree" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEducationProfessionalLicenseName'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Professional License Type</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationProfessionalLicenseName" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEducationProfessionalLicenseNumber'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>License Number</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEducationProfessionalLicenseNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyCountry">
                                                        <li>
                                                                <?php $key = 'TextBoxEducationProfessionalLicenseIssuingAgencyCountry'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $country_id = $def_value ?>
                                                            <label>Country:</label>
                                                            <select class="invoice-fields" id="country_eplia" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_eplia')">
                                                                <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyState">
                                                        <li>
                                                                <?php $key = 'TextBoxEducationProfessionalLicenseIssuingAgencyState'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <?php $state_id = $def_value ?>
                                                            <label>State:</label>
                                                            <select class="invoice-fields" name="<?php echo $key; ?>" id="state_eplia">
                                                            <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                            } else {
                                                                foreach ($active_states[$country_id] as $active_state) {
                                                                    ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerName1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Employment Current / Most Recent Employer</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerName1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerPosition1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Position/Title</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPosition1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerAddress1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Address</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerAddress1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerCity1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>City</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerCity1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry1">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerCountry1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $country_id = $def_value ?>
                                                            <label>Country:</label>
                                                            <select class="invoice-fields" id="country_ee1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee1')">
                                                                <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState1">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerState1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <?php $state_id = $def_value ?>
                                                            <label>State:</label>
                                                            <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ee1">
                                                            <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                        } else {
                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerPhoneNumber1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Telephone</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPhoneNumber1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Dates of Employment</label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1">
                                                                <option vlaue="">Please Select</option>
<?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
<?php } ?>
                                                            </select>
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1">
                                                                <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <span class="date-range-text">to</span>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1">
                                                                <option vlaue="">Please Select</option>
<?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
<?php } ?>
                                                            </select>
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1">
                                                                <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            --><?php //$key = 'TextBoxEmploymentEmployerCompensationBegin1'; ?>
<?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                            <label>Starting Compensation</label>-->
<!--                                                            <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin1" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error($key); ?>
<!--                                                        </li>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            --><?php //$key = 'TextBoxEmploymentEmployerCompensationEnd1'; ?>
<?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                            <label>Ending Compensation</label>-->
<!--                                                            <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd1" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error($key); ?>
<!--                                                        </li>-->
<!--                                                    </div>-->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerSupervisor1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Supervisor</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerSupervisor1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
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
<?php $key = 'RadioButtonListEmploymentEmployerContact1_0'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
                                                        <?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <label class="contact-to-employee">May we contact this employer?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact1_0" value="Yes" name="RadioButtonListEmploymentEmployerContact1_0" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact1_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact1_1" value="No" name="RadioButtonListEmploymentEmployerContact1_0" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact1_1">No</label>
                                                            </div>
                                                        </li>
<?php echo form_error('RadioButtonListEmploymentEmployerContact1_0'); ?>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                                <?php $key = 'TextBoxEmploymentEmployerReasonLeave1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerReasonLeave1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxEmploymentEmployerName2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Previous Employer</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerName2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxEmploymentEmployerPosition2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Position/Title</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPosition2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxEmploymentEmployerAddress2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Address</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerAddress2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error('TextBoxEmploymentEmployerAddress2'); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxEmploymentEmployerCity2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerCity2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry_2">
                                                            <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerCountry_2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_ee2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee2')">
                                                                    <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState_2">
                                                            <li>
                                                                    <?php $key = 'DropDownListEmploymentEmployerState_2'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                    <?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ee2">
<?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxEmploymentEmployerPhoneNumber2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Telephone</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPhoneNumber2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Dates of Employment</label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2">
                                                                    <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2">
                                                                    <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
    <?php $cur_value = $count; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2">
                                                                    <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label></label>
                                                                <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2">
                                                                    <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $count; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                        <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                                </select>
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
<!--                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                            <li>-->
<?php //$key = 'TextBoxEmploymentEmployerCompensationBegin2'; ?>
<!--                                                                --><?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                                <label>Starting Compensation</label>-->
<!--                                                                <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin2" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error($key); ?>
<!--                                                            </li>-->
<!--                                                        </div>-->
<!--                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                            <li>-->
<?php //$key = 'TextBoxEmploymentEmployerCompensationEnd2'; ?>
<!--                                                                --><?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                                <label>Ending Compensation</label>-->
<!--                                                                <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd2" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error($key); ?>
<!--                                                            </li>-->
<!--                                                        </div>-->
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li>
<?php $key = 'TextBoxEmploymentEmployerSupervisor2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Supervisor</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerSupervisor2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
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
<?php $key = 'RadioButtonListEmploymentEmployerContact2_0'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                                <label class="contact-to-employee">May we contact this employer?</label>
                                                                <div class="hr-radio-btns">
                                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact2_0" value="Yes" name="RadioButtonListEmploymentEmployerContact2_0" type="radio" <?php echo $disabled_check; ?>>
                                                                    <label for="RadioButtonListEmploymentEmployerContact2_0">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact2_1" value="No" name="RadioButtonListEmploymentEmployerContact2_0" type="radio" <?php echo $disabled_check; ?>>
                                                                    <label for="RadioButtonListEmploymentEmployerContact2_1">No</label>
                                                                </div>
<?php echo form_error('RadioButtonListEmploymentEmployerContact2_0'); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100 autoheight">
                                                            <?php $key = 'TextBoxEmploymentEmployerReasonLeave2'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerReasonLeave2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerName3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Previous Employer</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerName3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerPosition3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Position/Title</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPosition3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerAddress3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Address</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerAddress3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'TextBoxEmploymentEmployerCity3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>City</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerCity3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry3">
                                                        <li>
                                                            <?php $key = 'DropDownListEmploymentEmployerCountry3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                            <label>Country:</label>
                                                            <select class="invoice-fields" id="country_ee3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee3')">
                                                                <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
<?php } ?>
                                                            </select>
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState3">
                                                        <li>
                                                                <?php $key = 'DropDownListEmploymentEmployerState3'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $state_id = $def_value ?>
                                                            <label>State:</label>
                                                            <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ee3">
<?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
<?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>

                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerPhoneNumber3'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Telephone</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerPhoneNumber3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Dates of Employment</label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3">
                                                                <option vlaue="">Please Select</option>
                                                            <?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
    <?php $cur_value = $month; ?>
    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3">
                                                                <option value="">Please Select</option>
<?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
    <?php $def_selected = false; ?>
                                                                <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
<?php } ?>
                                                            </select>
                                                                <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <span class="date-range-text">to</span>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3">
                                                                <option vlaue="">Please Select</option>
<?php foreach ($months as $month) { ?>
    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $month; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false ); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                        <li>
                                                            <label></label>
                                                            <select <?php echo $disabled_check; ?> class="invoice-fields" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3">
                                                                <option value="">Please Select</option>
                                                            <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                    <option value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                            </select>
<?php echo form_error('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3'); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            --><?php //$key = 'TextBoxEmploymentEmployerCompensationBegin3'; ?>
<!--                                                            --><?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                            <label>Starting Compensation</label>-->
<!--                                                            <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationBegin3" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error('TextBoxEmploymentEmployerCompensationBegin3'); ?>
<!--                                                        </li>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
<!--                                                        <li>-->
<!--                                                            --><?php //$key = 'TextBoxEmploymentEmployerCompensationEnd3'; ?>
<!--                                                            --><?php //$def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<!--                                                            <label>Ending Compensation</label>-->
<!--                                                            <input --><?php //echo $readonly_check; ?><!-- class="invoice-fields" name="TextBoxEmploymentEmployerCompensationEnd3" value="--><?php //echo set_value($key, $def_value); ?><!--" type="text">-->
<?php //echo form_error($key); ?>
<!--                                                        </li>-->
<!--                                                    </div>-->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
<?php $key = 'TextBoxEmploymentEmployerSupervisor3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <label>Supervisor</label>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerSupervisor3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
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
                                                            <?php $key = 'RadioButtonListEmploymentEmployerContact3_0'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
<?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <label class="contact-to-employee">May we contact this employer?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact3_0" value="Yes" name="RadioButtonListEmploymentEmployerContact3_0" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact3_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact3_1" value="No" name="RadioButtonListEmploymentEmployerContact3_0" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEmployerContact3_1">No</label>
                                                            </div>
<?php echo form_error('RadioButtonListEmploymentEmployerContact3_0'); ?>
                                                        </li>

                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <?php $key = 'TextBoxEmploymentEmployerReasonLeave3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxEmploymentEmployerReasonLeave3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <?php $key = 'RadioButtonListEmploymentEverTerminated'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
                                                            <?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <label class="autoheight">Have you ever been laid off or terminated from any job or position? </label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEmploymentEverTerminated_0" name="RadioButtonListEmploymentEverTerminated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEverTerminated_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEmploymentEverTerminated_1" name="RadioButtonListEmploymentEverTerminated" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEverTerminated_1">No</label>
                                                            </div>
<?php echo form_error('RadioButtonListEmploymentEverTerminated'); ?>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
<?php $key = 'TextBoxEmploymentEverTerminatedReason'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <small class="autoheight">If yes, please explain:</small>
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxEmploymentEverTerminatedReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverTerminatedReason')" name="TextBoxEmploymentEverTerminatedReason"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxEmploymentEverTerminatedReason_remaining">128 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEverTerminatedReason_length">128</p>
                                                            </div>
<?php echo form_error('TextBoxEmploymentEverTerminatedReason'); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <?php $key = 'RadioButtonListEmploymentEverResign'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $yes_selected = ( $def_value == 'Yes' ? true : false ); ?>
                                                            <?php $no_selected = ( $def_value == 'No' ? true : false ); ?>
                                                            <label class="autoheight">Have you ever been asked to resign from any job or position?</label>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEmploymentEverResign_0" name="RadioButtonListEmploymentEverResign" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEverResign_0">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEmploymentEverResign_1" name="RadioButtonListEmploymentEverResign" type="radio" <?php echo $disabled_check; ?>>
                                                                <label for="RadioButtonListEmploymentEverResign_1">No</label>
                                                            </div>
<?php echo form_error('RadioButtonListEmploymentEverResign'); ?>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
<?php $key = 'TextBoxEmploymentEverResignReason'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <small class="autoheight">If yes, please explain:</small>
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxEmploymentEverResignReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverResignReason')" name="TextBoxEmploymentEverResignReason"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxEmploymentEverResignReason_remaining">128 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEverResignReason_length">128</p>
                                                            </div>
<?php echo form_error('TextBoxEmploymentEverResignReason'); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<?php $key = 'TextBoxEmploymentGaps'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Please explain any "gaps" in your employment history</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxEmploymentGaps" maxlength="512" onkeyup="check_length('TextBoxEmploymentGaps')" name="TextBoxEmploymentGaps"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxEmploymentGaps_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentGaps_length">512</p>
                                                            </div>
<?php echo form_error($key); ?>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<?php $key = 'TextBoxEmploymentEmployerNoContact'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">If you indicated that we may not contact an employer, please explain</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" maxlength="512" onkeyup="check_length('TextBoxEmploymentEmployerNoContact')" id="TextBoxEmploymentEmployerNoContact" name="TextBoxEmploymentEmployerNoContact"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxEmploymentEmployerNoContact_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxEmploymentEmployerNoContact_length">512</p>
                                                                <?php echo form_error($key); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceName1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>References Name</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceName1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceAcquainted1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>How do you know this reference?</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAcquainted1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceAddress1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Address</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAddress1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceCity1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceCity1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry1">
                                                            <li>
<?php $key = 'DropDownListReferenceCountry1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_ref1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref1')">
                                                                    <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState1">
                                                            <li>
                                                                    <?php $key = 'DropDownListReferenceState1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ref1">
<?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceTelephoneNumber1'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Telephone Number</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceTelephoneNumber1" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceEmail1'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>E-Mail</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceEmail1" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceName2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Name</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceName2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceAcquainted2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>How do you know this reference?</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAcquainted2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceAddress2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Address</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAddress2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceCity2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceCity2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry2">
                                                            <li>
<?php $key = 'DropDownListReferenceCountry2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_ref2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref2')">
                                                                    <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState2">
                                                            <li>
                                                                <?php $key = 'DropDownListReferenceState2'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ref2">
<?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
    <?php } ?>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>


                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceTelephoneNumber2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Telephone Number</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceTelephoneNumber2" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceEmail2'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>E-Mail</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceEmail2" value="<?php echo set_value($key, $def_value); ?>" type="email">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceName3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Name</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceName3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <?php $key = 'TextBoxReferenceAcquainted3'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>How do you know this reference?</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAcquainted3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceAddress3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Address</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceAddress3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceCity3'; ?>
                                                                    <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>City</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceCity3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry3">
                                                            <li>
<?php $key = 'DropDownListReferenceCountry3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <?php $country_id = $def_value ?>
                                                                <label>Country:</label>
                                                                <select class="invoice-fields" id="country_ref3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref3')">
                                                                    <option value="">Please Select</option>
<?php foreach ($active_countries as $active_country) { ?>
                                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                    <?php echo form_error($key); ?>
                                                            </li>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState3">
                                                            <li>
                                                                <?php $key = 'DropDownListReferenceState3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $state_id = $def_value ?>
                                                                <label>State:</label>
                                                                <select class="invoice-fields" name="<?php echo $key; ?>" id="state_ref3">
<?php if (empty($country_id)) { ?>
                                                                        <option value="">Select State</option> <?php
} else {
    foreach ($active_states[$country_id] as $active_state) {
        ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                </select>
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>


                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
<?php $key = 'TextBoxReferenceTelephoneNumber3'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>Telephone Number</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceTelephoneNumber3" value="<?php echo set_value($key, $def_value); ?>" type="text">
<?php echo form_error($key); ?>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                            <?php $key = 'TextBoxReferenceEmail3'; ?>
                                                            <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                                <label>E-Mail</label>
                                                                <input <?php echo $readonly_check; ?> class="invoice-fields" name="TextBoxReferenceEmail3" value="<?php echo set_value($key, $def_value); ?>" type="email">
<?php echo form_error($key); ?>
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
<?php $key = 'TextBoxAdditionalInfoWorkExperience'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkExperience" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkExperience')" name="TextBoxAdditionalInfoWorkExperience"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkExperience_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkExperience_length">512</p>
<?php echo form_error('TextBoxAdditionalInfoWorkExperience'); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="bg-color">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<?php $key = 'TextBoxAdditionalInfoWorkTraining'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Indicate if you have any special training or qualifications (include computer systems and programs) for the position for which you have applied</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkTraining" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkTraining')" name="TextBoxAdditionalInfoWorkTraining"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkTraining_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkTraining_length">512</p>
<?php echo form_error($key); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<?php $key = 'TextBoxAdditionalInfoWorkConsideration'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Indicate any additional information you would like us to consider</label>
                                                        </li>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="comment-area">
                                                                <textarea <?php echo $readonly_check; ?> class="form-col-100 invoice-fields" id="TextBoxAdditionalInfoWorkConsideration" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkConsideration')" name="TextBoxAdditionalInfoWorkConsideration"><?php echo set_value($key, $def_value); ?></textarea>
                                                                <span id="TextBoxAdditionalInfoWorkConsideration_remaining">512 Characters Left</span>
                                                                <p style="display: none;" id="TextBoxAdditionalInfoWorkConsideration_length">512</p>
                                                        <?php echo form_error($key); ?>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="autoheight">
                                                        <label>Applicant Statement</label>
                                                        <p>I understand that, to the extent permitted by law, the Company may require me to submit to a test for the presence of controlled substances in my system prior to employment. I also understand that, to the extent permitted by law, any offer of employment may be contingent upon the passing of a test for controlled substances and/or a physical examination performed by a doctor selected by the Company. I also understand that, to the extent permitted by law, I may be required to take other tests, such as personality and honesty tests, prior to employment. I understand that obtaining a bond may be a condition of employment and if required, I will be so advised either before my employment commences or as soon as possible after employment. Should a bond be required, I understand that I will need to immediately complete a bond application upon request of the Company. I understand that, in connection with this employment application, to the extent permitted by law, the Company may request an investigative consumer report.</p>
                                                        <p>I represent that all the information I have provided in this employment application, or other documentation submitted in connection with this employment application, and in any interview, is true and correct. All information is accurate and I have withheld nothing that would, if disclosed, result in an unfavorable employment decision. I understand that the Company shall have sole discretion in determining whether or not any inaccuracy, misrepresentation, or omission is material. I understand that if I am employed, and any information is later found to be false or incomplete in any respect, my employment may be immediately terminated. </p>
                                                    </li>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="application-check">
<?php $key = 'CheckBoxAgreement1786'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                            <?php $def_checked = ( $def_value == 1 ? true : false ); ?>
                                                        <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="my-check" value="1" name="CheckBoxAgreement1786" type="checkbox" <?php echo $disabled_check; ?>>
                                                        <div class="text">
                                                            <label for="my-check">
                                                                BY CHECKING THIS BOX, I REQUEST THAT I BE PROVIDED, FREE OF CHARGE, A COPY OF ANY REPORT GENERATED ON ME AS A RESULT OF MY APPLICATION.
                                                            </label>
                                                        </div>
<?php echo form_error('CheckBoxAgreement1786'); ?>
                                                    </div>
                                                    <div class="application-check">
                                                        <div class="text form-col-100">
                                                            IF YOU HAVE ANY QUESTIONS REGARDING THIS APPLICANT UNDERSTANDING, PLEASE ASK A COMPANY REPRESENTATIVE BEFORE SIGNING BELOW OR SUBMITTING THIS E-APPLICATION.
                                                        </div>
                                                    </div>
                                                    <div class="application-check">
                                                        <figure>
                                                    <?php $key = 'CheckBoxAgree'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
<?php $def_checked = ( $def_value == 1 ? true : false ); ?>
                                                            <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="my-check1" value="1" name="CheckBoxAgree" type="checkbox" <?php echo $disabled_check; ?>>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="my-check1">
                                                                I acknowledge that I have reviewed, and satisfy, any job requirements for the position for which I am applying. Furthermore, by signing below, or by clicking the "Submit" button, I acknowledge having read the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> &amp; <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> that includes the use of electronic signatures. By continuing I agree to be bound by the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a>. If you do not agree with the provisions of the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> &amp; <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> do not continue.
                                                            </label>
                                                        </div>
<?php echo form_error('CheckBoxAgree'); ?>
                                                    </div>
                                                </div>
                                                <div class="card-fields-row">
                                                        <?php $key = 'signature'; ?>
<?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                    <div class="col-lg-2">
                                                        <label class="signature-label">E-SIGNATURE</label>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input <?php echo $readonly_check; ?> class="signature-field" name="signature" id="signature" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <p>Please type your First and Last Name</p>
<?php echo form_error($key); ?>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label class="signature-label">DATE</label>
                                                    </div>
                                                    <div class="col-lg-4">
<?php                                                       $key = 'signature_date'; ?>
                                                                <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <div class="calendar-picker">
                                                            <input <?php echo $readonly_check; ?> type="text" class="invoice-fields startdate" readonly="" name="signature_date" id="signature_date" value="<?php echo set_value($key, $def_value); ?>" />
                                                        </div>
                                                                <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="full-width">
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
                                                            <?php if (!empty($ip_track)) { ?>
                                                                <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                            <?php } else { ?>
    <?php echo date('m/d/Y h:i A'); ?>
<?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="application-check"><!-- class="card-fields-row" -->
                                                        <figure>
                                                        <?php $key = 'CheckBoxTerms'; ?>
                                                        <?php $def_value = (isset($user_info[$key]) ? $user_info[$key] : '' ); ?>
                                                        <?php $def_checked = ( $def_value == 1 ? true : false ); ?>
                                                            <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="terms_check" value="1" name="CheckBoxTerms" type="checkbox" <?php echo $disabled_check; ?>>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="terms_check">
                                                                <strong>I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.</strong> <strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS FOR <?php echo isset($company_name) ? $company_name : 'Company'; ?></strong><br>
                                                            </label>
<?php                                                       echo form_error('CheckBoxTerms'); ?>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            1. Electronic Signature Agreement. By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide <?php echo isset($company_name) ? $company_name : 'Company'; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and <?php echo isset($company_name) ? $company_name : 'Company'; ?>. You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a <?php echo isset($company_name) ? $company_name : 'Company'; ?> service constitutes your agreement to be bound by the terms and conditions of the <?php echo isset($company_name) ? $company_name : 'Company'; ?> Disclosures and Agreements as they exist on the date of your E-Signature
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <li class="form-col-100 autoheight aligncenter">
                                                        <input <?php echo $disabled_check; ?> style="width: auto; padding: 4px 40px;"  class="site-btn full-emp-btn <?php echo ( $signed_flag ? 'disabled-btn' : '' ); ?>" onclick="validate_form()" value="I Agree" type="submit">
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </ul>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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

        function validate_form() {
            $("#fullemploymentapplication").validate({
                ignore: ":hidden:not(select)",
                rules: {
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
                    TextBoxSSN: {
                        required: true
                    },
                    TextBoxDOB: {
                        required: true
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
                },
                messages: {
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
                    TextBoxSSN: {
                        required: 'Social Security Number is required'
                    },
                    TextBoxDOB: {
                        required: 'Date of Birth is required'
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
                },
                submitHandler: function (form) {
                   var check_radio= $('input[name=is_already_employed]:checked').val();

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

        $('.startdate').datepicker({dateFormat: 'mm-dd-yy', changeMonth: true, changeYear: true, yearRange: "-100:+50", }).val();

<?php   if ($signed_flag == false) { ?>
            $(document).ready(function () {
                $('.startdate').datepicker({
                    dateFormat: 'mm/dd/yy',
                    changeYear: true
                }).val();

                //Disable Autocomplete
                $('input,select,textarea').each(function () {
                    $(this).attr('autocomplete', 'none');
                });
            });
<?php   } ?>

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
    </script>
</body>
</html>