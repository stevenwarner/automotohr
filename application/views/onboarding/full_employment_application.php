<div class="onboarding-body-wrp">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Full employment application</h1>
                </div>
                <?php if (isset($unique_sid)) { ?>
                    <!--                    <div class="btn-wrp">-->
                    <!--                        <div class="row">-->
                    <!--                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">-->
                    <!--                                <a href="--><?php //echo base_url('onboarding/dependents/' . $unique_sid); 
                                                                    ?>
                    <!--" class="btn btn-success btn-block"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>-->
                    <!--                            </div>-->
                    <!--                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">-->
                    <!--                                <!--<a href="javascript:;" class="btn btn-danger btn-block">Skip</a>-->
                    <!--                            </div>-->
                    <!--                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">-->
                    <!--                                <a href="--><?php //echo base_url('onboarding/eeoc_form/' . $unique_sid); 
                                                                    ?>
                    <!--" class="btn btn-success btn-block">Next&nbsp;<i class="fa fa-arrow-right"></i></a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                <?php } ?>
                <div class="hr-box">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="panel-body">
                        <div class="form-wrp">
                            <form id="fullemploymentapplication" enctype="multipart/form-data" method="post">
                                <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo isset($applicant_sid) ? $applicant_sid : ''; ?>" />
                                <div class="container-fluid">
                                    <label><span class="staric">Please Do Not Use Your Web Browser's AutoComplete Feature</span></label>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'first_name'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>First Name <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="first_name" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxNameMiddle'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Middle Name</label>
                                                <input class="form-control" name="TextBoxNameMiddle" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'last_name'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Last Name <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="last_name" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'suffix'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Suffix </label>
                                                <select class="form-control" name="suffix">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($suffix_values as $suffix_value) { ?>
                                                        <?php $def_selected = false; ?>
                                                        <?php $cur_value = $suffix_value['value']; ?>
                                                        <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                        <option <?php echo set_select('suffix', $cur_value, $def_selected) ?> value="<?php echo $suffix_value['value']; ?>"><?php echo $suffix_value['key']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('suffix'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxSSN'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Social Security Number <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="TextBoxSSN" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxDOB'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Date of Birth <span class="staric">*</span></label>
                                                <input class="form-control startdate" required="required" name="TextBoxDOB" value="<?php echo set_value($key, $def_value); ?>" id="TextBoxDOB" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="autoheight form-group">
                                                Your date of birth is required and may be used for purposes directly related to the background check process and will not be used for any other purpose. Failure to provide your date of birth may cause a delay in processing your application for employment.
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'email'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Email Address <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="email" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressEmailConfirm'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Confirm Email Address <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="TextBoxAddressEmailConfirm" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                Your email address is required and is used for purposes directly related to the application process and/or legally required notifications. Your email address will not be shared or used for any other purpose.
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'Location_Address'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Current Residence <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="Location_Address" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressLenghtCurrent'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>How Long?</label>
                                                <input class="form-control" name="TextBoxAddressLenghtCurrent" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'Location_City'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>City <span class="staric">*</span></label>
                                                <input class="form-control" name="Location_City" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'Location_ZipCode'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Zip Code <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="Location_ZipCode" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'Location_Country'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $country_id = $def_value ?>
                                                <label>Country:</label>
                                                <select class="form-control" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>, 'state')">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'Location_State'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $state_id = $def_value ?>
                                                <label>State:</label>
                                                <select class="form-control" name="Location_State" id="state">
                                                    <?php if (empty($country_id)) { ?>
                                                        <option value="">Select State</option> <?php
                                                                                            } else {
                                                                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                            <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php /*$key = 'CheckBoxAddressInternationalCurrent'; */ ?>
                                                    <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                    <?php /*$def_checked = ( $def_value == 1 ? true : false );*/ ?>
                                                    <div class="checkbox-field">
                                                        <input <?php /*echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalCurrent" name="CheckBoxAddressInternationalCurrent" value="1" type="checkbox" <?php /*echo $disabled_check; */ ?>>
                                                        <label for="CheckBoxAddressInternationalCurrent">Non USA Address</label>
                                                    </div>
                                                    <?php /*echo form_error('CheckBoxAddressInternationalCurrent'); */ ?>
                                                </div>
                                            </div>-->
                                        <div class="bg-color">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxAddressStreetFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Former Residence</label>
                                                    <input class="form-control" name="TextBoxAddressStreetFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxAddressLenghtFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>How Long?</label>
                                                    <input class="form-control" name="TextBoxAddressLenghtFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxAddressCityFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>City</label>
                                                    <input class="form-control" name="TextBoxAddressCityFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxAddressZIPFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Zip Code</label>
                                                    <input class="form-control" name="TextBoxAddressZIPFormer1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListAddressCountryFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $country_id = $def_value ?>
                                                    <label>Country:</label>
                                                    <select class="form-control" id="country_former1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former1')">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListAddressStateFormer1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $state_id = $def_value ?>
                                                    <label>State:</label>
                                                    <select class="form-control" name="<?php echo $key; ?>" id="state_former1">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option> <?php
                                                                                                } else {
                                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php /*$key = 'CheckBoxAddressInternationalFormer1'; */ ?>
                                                        <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                        <?php /*$def_checked = ( $def_value == 1 ? true : false );*/ ?>
                                                        <div class="checkbox-field">
                                                            <input <?php /*echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalFormer1" value="1" name="CheckBoxAddressInternationalFormer1" type="checkbox" <?php /*echo $disabled_check; */ ?>>
                                                            <label for="CheckBoxAddressInternationalFormer1">Non USA Address</label>
                                                            <?php /*echo form_error('CheckBoxAddressInternationalFormer1'); */ ?>
                                                        </div>
                                                    </div>
                                                </div>-->
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressStreetFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Former Residence</label>
                                                <input class="form-control" name="TextBoxAddressStreetFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressLenghtFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>How Long?</label>
                                                <input class="form-control" name="TextBoxAddressLenghtFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressCityFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>City</label>
                                                <input class="form-control" name="TextBoxAddressCityFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressZIPFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Zip Code</label>
                                                <input class="form-control" name="TextBoxAddressZIPFormer2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListAddressCountryFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $country_id = $def_value ?>
                                                <label>Country:</label>
                                                <select class="form-control" id="country_former2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former2')">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListAddressStateFormer2'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $state_id = $def_value ?>
                                                <label>State:</label>
                                                <select class="form-control" name="<?php echo $key; ?>" id="state_former2">
                                                    <?php if (empty($country_id)) { ?>
                                                        <option value="">Select State</option> <?php
                                                                                            } else {
                                                                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                            <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php /*$key = 'CheckBoxAddressInternationalFormer2'; */ ?>
                                                    <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                    <?php /*$def_checked = ( $def_value == 1 ? true : false );*/ ?>
                                                    <div class="checkbox-field">
                                                        <input <?php /*echo $readonly_check; */ ?> <?php /*echo set_checkbox($key, 1, $def_checked); */ ?> id="CheckBoxAddressInternationalFormer2" value="1" name="CheckBoxAddressInternationalFormer2" type="checkbox" <?php /*echo $disabled_check; */ ?>>
                                                        <label for="CheckBoxAddressInternationalFormer2">Non USA Address</label>
                                                        <?php /*echo form_error('CheckBoxAddressInternationalFormer2'); */ ?>
                                                    </div>
                                                </div>
                                            </div>-->
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressStreetFormer3'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Other Mailing Address</label>
                                                <input class="form-control" name="TextBoxAddressStreetFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressCityFormer3'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>City</label>
                                                <input class="form-control" name="TextBoxAddressCityFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxAddressZIPFormer3'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Zip Code</label>
                                                <input class="form-control" name="TextBoxAddressZIPFormer3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListAddressCountryFormer3'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $country_id = $def_value ?>
                                                <label>Country:</label>
                                                <select class="form-control" id="country_former3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_former3')">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListAddressStateFormer3'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $state_id = $def_value ?>
                                                <label>State:</label>
                                                <select class="form-control" name="<?php echo $key; ?>" id="state_former3">
                                                    <?php if (empty($country_id)) { ?>
                                                        <option value="">Select State</option> <?php
                                                                                            } else {
                                                                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                            <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'PhoneNumber'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Primary Telephone <span class="staric">*</span></label>
                                                <input class="form-control" required="required" name="PhoneNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxTelephoneMobile'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Mobile Telephone </label>
                                                <input class="form-control" name="TextBoxTelephoneMobile" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxTelephoneOther'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Other Telephone </label>
                                                <input class="form-control" name="TextBoxTelephoneOther" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="autoheight">The position I am applying for is:</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListPostionTime'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $full_time_selected = ($def_value == 'full_time' ? true : false); ?>
                                                    <?php $part_time_selected = ($def_value == 'part_time' ? true : false); ?>
                                                    <?php $full_or_part_time_selected = ($def_value == 'full_or_parttime' ? true : false); ?>

                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'full_time', $full_time_selected); ?> id="RadioButtonListPostionTime_0" value="full_time" name="RadioButtonListPostionTime" type="radio">
                                                        <label for="RadioButtonListPostionTime_0">Full time</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'part_time', $part_time_selected); ?> id="RadioButtonListPostionTime_1" value="part_time" name="RadioButtonListPostionTime" type="radio">
                                                        <label for="RadioButtonListPostionTime_1">Part time</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'full_or_parttime', $full_or_part_time_selected); ?> id="RadioButtonListPostionTime_2" value="full_or_parttime" name="RadioButtonListPostionTime" type="radio">
                                                        <label for="RadioButtonListPostionTime_2">Full or Part time</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListPostionTime'); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxPositionDesired'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label class="autoheight">If you want to apply for more than one position, please list them in this field.</label>
                                                    <input class="form-control" name="TextBoxPositionDesired" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error('TextBoxPositionDesired'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxWorkBeginDate'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>What date can you begin work?</label>
                                                <input class="form-control startdate" readonly="" name="TextBoxWorkBeginDate" value="<?php echo set_value($key, $def_value); ?>" id="dp1474003792803" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxWorkCompensation'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Expected compensation</label>
                                                <input class="form-control" name="TextBoxWorkCompensation" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label class="autoheight">Do you have transportation to/from work?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'RadioButtonListWorkTransportation'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListWorkTransportation_0" name="RadioButtonListWorkTransportation" type="radio">
                                                    <label for="RadioButtonListWorkTransportation_0">Yes</label>
                                                </div>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListWorkTransportation_1" name="RadioButtonListWorkTransportation" type="radio">
                                                    <label for="RadioButtonListWorkTransportation_1">No</label>
                                                </div>
                                                <?php echo form_error('RadioButtonListWorkTransportation'); ?>
                                            </div>
                                        </div>
                                        <div class="bg-color-v2">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="autoheight">Are you 18 years or older?</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListWorkOver18'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListWorkOver18_0" name="RadioButtonListWorkOver18" type="radio">
                                                        <label for="RadioButtonListWorkOver18_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListWorkOver18_1" name="RadioButtonListWorkOver18" type="radio">
                                                        <label for="RadioButtonListWorkOver18_1">No</label>
                                                    </div>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-9">
                                            <div class="form-group">
                                                <label class="autoheight">Have you ever used or been known by any other names, including nicknames?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                            <div class="form-group">
                                                <?php $key = 'RadioButtonListAliases'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListAliases_0" name="RadioButtonListAliases" type="radio">
                                                    <label for="RadioButtonListAliases_0">Yes</label>
                                                </div>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListAliases_1" name="RadioButtonListAliases" type="radio">
                                                    <label for="RadioButtonListAliases_1">No</label>
                                                </div>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="comment-area">
                                                <?php $key = 'nickname_or_othername_details'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <small>If yes, please explain and indicate name(s):</small>
                                                <textarea name="nickname_or_othername_details" id="nickname_or_othername_details" class="form-control" maxlength="512" onkeyup="check_length('nickname_or_othername_details')"><?php echo set_value($key, $def_value); ?></textarea>
                                                <span id="nickname_or_othername_details_remaining">512 Characters Left</span>
                                                <p style="display: none;" id="nickname_or_othername_details_length">512</p>
                                                <p>When answering the following questions, do not include minor traffic infractions, ANY convictions for which the record has been sealed and/or expunged, and/or eradicated, any conviction for which probation has been successfully completed or otherwise discharged with the case having been judicially dismissed, any information regarding referrals to and/or participation in any pre-trial or post-trial diversion programs (California applicants only, do not include infractions involving marijuana offenses that occurred over two years ago). A conviction record will not necessarily be a bar to employment. Factors such as age, time of the offense, seriousness and nature of the violation, and rehabilitation will be taken into account.</p>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>
                                        <!--<div class="bg-color">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php /*$key = 'RadioButtonListCriminalWrongDoing'; */ ?>
                                                        <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                        <?php /*$yes_selected = ( $def_value == 'Yes' ? true : false ); */ ?>
                                                        <?php /*$no_selected = ( $def_value == 'No' ? true : false ); */ ?>
                                                        <label class="autoheight">Have you ever plead Guilty, No Contest, or been Convicted of a Misdemeanor and/or Felony?</label>
                                                        <div class="hr-radio-btns">
                                                            <input <?php /*echo set_radio($key, 'Yes', $yes_selected);*/ ?> value="Yes" id="RadioButtonListCriminalWrongDoing_0" name="RadioButtonListCriminalWrongDoing" type="radio" <?php /*echo $disabled_check; */ ?>>
                                                            <label for="RadioButtonListCriminalWrongDoing_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php /*echo set_radio($key, 'No', $no_selected);*/ ?> value="No" id="RadioButtonListCriminalWrongDoing_1" name="RadioButtonListCriminalWrongDoing" type="radio" <?php /*echo $disabled_check; */ ?>>
                                                            <label for="RadioButtonListCriminalWrongDoing_1">No</label>
                                                        </div>
                                                        <?php /*echo form_error('RadioButtonListCriminalWrongDoing'); */ ?>
                                                    </div>
                                                </div>
                                            </div>-->
                                        <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php /*$key = 'RadioButtonListCriminalBail'; */ ?>
                                                    <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                    <?php /*$yes_selected = ( $def_value == 'Yes' ? true : false ); */ ?>
                                                    <?php /*$no_selected = ( $def_value == 'No' ? true : false ); */ ?>
                                                    <label class="autoheight">Have you been arrested for any matter for which you are now out on bail or have been released on your own recognizance pending trial?</label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php /*echo set_radio($key, 'Yes', $yes_selected);*/ ?> value="Yes" id="RadioButtonListCriminalBail_0" name="RadioButtonListCriminalBail" type="radio" <?php /*echo $disabled_check; */ ?>>
                                                        <label for="RadioButtonListCriminalBail_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php /*echo set_radio($key, 'No', $no_selected);*/ ?> value="No" id="RadioButtonListCriminalBail_1" name="RadioButtonListCriminalBail" type="radio" <?php /*echo $disabled_check; */ ?>>
                                                        <label for="RadioButtonListCriminalBail_1">No</label>
                                                    </div>
                                                    <?php /*echo form_error('RadioButtonListCriminalBail'); */ ?>
                                                </div>
                                            </div>-->
                                        <!--<div class="bg-color">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php /*$key = 'arrested_pending_trail_details'; */ ?>
                                                        <?php /*$def_value = (isset($application[$key]) ? $application[$key] : '' );*/ ?>
                                                        <label class="autoheight">If yes to either of the above questions, provide dates and details for each, including the case number and court where your case is/was handled:</label>
                                                        <div class="comment-area">
                                                            <textarea <?php /*echo $readonly_check; */ ?> name="arrested_pending_trail_details" id="arrested_pending_trail_details" maxlength="512" onkeyup="check_length('arrested_pending_trail_details')" class="form-col-100 invoice-fields"><?php /*echo set_value($key, $def_value); */ ?></textarea>
                                                            <span id="arrested_pending_trail_details_remaining">512 Characters Left</span>
                                                            <p style="display: none;" id="arrested_pending_trail_details_length">512</p>
                                                        </div>
                                                        <?php /*echo form_error($key); */ ?>
                                                    </div>
                                                </div>
                                            </div>-->
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <?php $key = 'RadioButtonListDriversLicenseQuestion'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                <label class="autoheight">Driver's License: A valid driver's license may be a requirement for the position for which you have applied. If so, do you currently have a valid driver's license?</label>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListDriversLicenseQuestion_0" value="Yes" name="RadioButtonListDriversLicenseQuestion" type="radio">
                                                    <label for="RadioButtonListDriversLicenseQuestion_0">Yes</label>
                                                </div>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListDriversLicenseQuestion_1" value="No" name="RadioButtonListDriversLicenseQuestion" type="radio">
                                                    <label for="RadioButtonListDriversLicenseQuestion_1">No</label>
                                                </div>
                                                <?php echo form_error('RadioButtonListDriversLicenseQuestion'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxDriversLicenseNumber'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Driver's license number:</label>
                                                <input class="form-control" name="TextBoxDriversLicenseNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxDriversLicenseExpiration'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Expiration date:</label>
                                                <input name="TextBoxDriversLicenseExpiration" class="form-control startdate" value="<?php echo set_value($key, $def_value); ?>" id="dp1474003792804" type="text">
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListDriversCountry'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $country_id = $def_value ?>
                                                <label>Country:</label>
                                                <select class="form-control" id="country_dl" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_dl')">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                        <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'DropDownListDriversState'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $state_id = $def_value ?>
                                                <label>State:</label>
                                                <select class="form-control" name="<?php echo $key; ?>" id="state_dl">
                                                    <?php if (empty($country_id)) { ?>
                                                        <option value="">Select State</option> <?php
                                                                                            } else {
                                                                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                            <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>

                                        <div class="bg-color">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListDriversLicenseTraffic'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <label class="autoheight">Within the last 5 years, have you ever plead Guilty, No Contest, or been Convicted of any traffic violation(s)?</label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListDriversLicenseTraffic" value="Yes" name="RadioButtonListDriversLicenseTraffic" type="radio">
                                                        <label for="RadioButtonListDriversLicenseTraffic">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListDriversLicenseTraffic_1" value="No" name="RadioButtonListDriversLicenseTraffic" type="radio">
                                                        <label for="RadioButtonListDriversLicenseTraffic_1">No</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListDriversLicenseTraffic'); ?>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <?php $key = 'license_guilty_details'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <small class="autoheight">If yes , provide dates and details for each violation, including the case number and court where your case is/was handled:</small>
                                                    <div class="comment-area">
                                                        <textarea name="license_guilty_details" id="license_guilty_details" maxlength="512" onkeyup="check_length('license_guilty_details')" class="form-control"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="license_guilty_details_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="license_guilty_details_length">512</p>
                                                    </div>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                            <div class="education-level-block">-->
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <?php $key = 'TextBoxEducationHighSchoolName'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <label>Education - High School</label>
                                                <input class="form-control" name="TextBoxEducationHighSchoolName" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                            </div>
                                            <?php echo form_error($key); ?>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label class="autoheight">Did you graduate?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php $key = 'RadioButtonListEducationHighSchoolGraduated'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationHighSchoolGraduated_0" name="RadioButtonListEducationHighSchoolGraduated" type="radio">
                                                    <label for="RadioButtonListEducationHighSchoolGraduated_0">Yes</label>
                                                </div>
                                                <div class="hr-radio-btns">
                                                    <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationHighSchoolGraduated_1" name="RadioButtonListEducationHighSchoolGraduated" type="radio">
                                                    <label for="RadioButtonListEducationHighSchoolGraduated_1">No</label>
                                                </div>
                                                <?php echo form_error('RadioButtonListEducationHighSchoolGraduated'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationHighSchoolCity'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>City</label>
                                                    <input class="form-control" name="TextBoxEducationHighSchoolCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>


                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolCountry'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $country_id = $def_value ?>
                                                    <label>Country:</label>
                                                    <select class="form-control" id="country_ehs" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ehs')">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolState'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $state_id = $def_value ?>
                                                    <label>State:</label>
                                                    <select class="form-control" name="<?php echo $key; ?>" id="state_ehs">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option> <?php
                                                                                                } else {
                                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedMonthBegin'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Dates of Attendance</label>
                                                    <select class="form-control" name="DropDownListEducationHighSchoolDateAttendedMonthBegin">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedYearBegin'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEducationHighSchoolDateAttendedYearBegin">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $count; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                <span class="date-range-text">to</span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedMonthEnd'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEducationHighSchoolDateAttendedMonthEnd">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEducationHighSchoolDateAttendedYearEnd'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEducationHighSchoolDateAttendedYearEnd">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $count; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                            </div>-->
                                        <div class="bg-color">
                                            <div class="education-level-block">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEducationCollegeName'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>College/University</label>
                                                        <input class="form-control" name="TextBoxEducationCollegeName" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="autoheight">Did you graduate?</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'RadioButtonListEducationCollegeGraduated'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                        <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationCollegeGraduated_0" name="RadioButtonListEducationCollegeGraduated" type="radio">
                                                            <label for="RadioButtonListEducationCollegeGraduated_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationCollegeGraduated_1" name="RadioButtonListEducationCollegeGraduated" type="radio">
                                                            <label for="RadioButtonListEducationCollegeGraduated_1">No</label>
                                                        </div>
                                                        <?php echo form_error('RadioButtonListEducationCollegeGraduated'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'TextBoxEducationCollegeCity'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label>City</label>
                                                            <input class="form-control" name="TextBoxEducationCollegeCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeCountry'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <?php $country_id = $def_value ?>
                                                            <label>Country:</label>
                                                            <select class="form-control" id="country_ecc" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ecc')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeState'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <?php $state_id = $def_value ?>
                                                            <label>State:</label>
                                                            <select class="form-control" name="<?php echo $key; ?>" id="state_ecc">
                                                                <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                                                        } else {
                                                                                                            foreach ($active_states[$country_id] as $active_state) { ?>
                                                                        <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeDateAttendedMonthBegin'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label>Dates of Attendance</label>
                                                            <select class="form-control" name="DropDownListEducationCollegeDateAttendedMonthBegin">
                                                                <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $month; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeDateAttendedYearBegin'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label></label>
                                                            <select class="form-control" name="DropDownListEducationCollegeDateAttendedYearBegin">
                                                                <option value="">Please Select</option>
                                                                <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $count; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('DropDownListEducationCollegeDateAttendedYearBegin'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <span class="date-range-text">to</span>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeDateAttendedMonthEnd'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label></label>
                                                            <select class="form-control" name="DropDownListEducationCollegeDateAttendedMonthEnd">
                                                                <option vlaue="">Please Select</option>
                                                                <?php foreach ($months as $month) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $month; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('DropDownListEducationCollegeDateAttendedMonthEnd'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'DropDownListEducationCollegeDateAttendedYearEnd'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label></label>
                                                            <select class="form-control" name="DropDownListEducationCollegeDateAttendedYearEnd">
                                                                <option value="">Please Select</option>
                                                                <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                    <?php $def_selected = false; ?>
                                                                    <?php $cur_value = $count; ?>
                                                                    <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                    <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('DropDownListEducationCollegeDateAttendedYearEnd'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-col-100">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'TextBoxEducationCollegeMajor'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label>Major</label>
                                                            <input class="form-control" name="TextBoxEducationCollegeMajor" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key = 'TextBoxEducationCollegeDegree'; ?>
                                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                            <label>Degree Earned</label>
                                                            <input class="form-control" name="TextBoxEducationCollegeDegree" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                            <?php echo form_error($key); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="education-level-block">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationOtherName'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Other School</label>
                                                    <input class="form-control" name="TextBoxEducationOtherName" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="autoheight">Did you graduate?</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListEducationOtherGraduated'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEducationOtherGraduated_0" name="RadioButtonListEducationOtherGraduated" type="radio">
                                                        <label for="RadioButtonListEducationOtherGraduated_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEducationOtherGraduated_1" name="RadioButtonListEducationOtherGraduated" type="radio">
                                                        <label for="RadioButtonListEducationOtherGraduated_1">No</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListEducationOtherGraduated'); ?>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEducationOtherCity'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>City</label>
                                                        <input class="form-control" name="TextBoxEducationOtherCity" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherCountry'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="form-control" id="country_eo" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_eo')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherState'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="form-control" name="<?php echo $key; ?>" id="state_eo">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherDateAttendedMonthBegin'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Dates of Attendance</label>
                                                        <select class="form-control" name="DropDownListEducationOtherDateAttendedMonthBegin">
                                                            <option vlaue="">Please Select</option>
                                                            <?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherDateAttendedYearBegin'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEducationOtherDateAttendedYearBegin">
                                                            <option value="">Please Select</option>
                                                            <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <span class="date-range-text">to</span>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherDateAttendedMonthEnd'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEducationOtherDateAttendedMonthEnd">
                                                            <option vlaue="">Please Select</option>
                                                            <?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEducationOtherDateAttendedYearEnd'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEducationOtherDateAttendedYearEnd">
                                                            <option value="">Please Select</option>
                                                            <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEducationOtherMajor'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Major</label>
                                                        <input class="form-control" name="TextBoxEducationOtherMajor" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEducationOtherDegree'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Degree Earned</label>
                                                        <input class="form-control" name="TextBoxEducationOtherDegree" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationProfessionalLicenseName'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Professional License Type</label>
                                                    <input class="form-control" name="TextBoxEducationProfessionalLicenseName" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationProfessionalLicenseNumber'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>License Number</label>
                                                    <input class="form-control" name="TextBoxEducationProfessionalLicenseNumber" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyCountry">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationProfessionalLicenseIssuingAgencyCountry'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $country_id = $def_value ?>
                                                    <label>Country:</label>
                                                    <select class="form-control" id="country_eplia" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_eplia')">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 TextBoxEducationProfessionalLicenseIssuingAgencyState">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEducationProfessionalLicenseIssuingAgencyState'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $state_id = $def_value ?>
                                                    <label>State:</label>
                                                    <select class="form-control" name="<?php echo $key; ?>" id="state_eplia">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option> <?php
                                                                                                } else {
                                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerName1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Employment Current / Most Recent Employer</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerName1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerPosition1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Position/Title</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerPosition1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerAddress1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Address</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerAddress1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerCity1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>City</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerCity1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry1">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerCountry1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $country_id = $def_value ?>
                                                    <label>Country:</label>
                                                    <select class="form-control" id="country_ee1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee1')">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState1">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerState1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $state_id = $def_value ?>
                                                    <label>State:</label>
                                                    <select class="form-control" name="<?php echo $key; ?>" id="state_ee1">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option> <?php
                                                                                                } else {
                                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerPhoneNumber1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Telephone</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerPhoneNumber1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Dates of Employment</label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $count; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1'); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                <span class="date-range-text">to</span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $count; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <!--                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                            <!--                                                <div class="form-group">-->
                                            <!--                                                    --><?php //$key = 'TextBoxEmploymentEmployerCompensationBegin1'; 
                                                                                                        ?>
                                            <!--                                                    --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                        ?>
                                            <!--                                                    <label>Starting Compensation</label>-->
                                            <!--                                                    <input  class="form-control" name="TextBoxEmploymentEmployerCompensationBegin1" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                                ?>
                                            <!--" type="text">-->
                                            <!--                                                    --><?php //echo form_error($key); 
                                                                                                        ?>
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                            <!--                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                            <!--                                                <div class="form-group">-->
                                            <!--                                                    --><?php //$key = 'TextBoxEmploymentEmployerCompensationEnd1'; 
                                                                                                        ?>
                                            <!--                                                    --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                        ?>
                                            <!--                                                    <label>Ending Compensation</label>-->
                                            <!--                                                    <input  class="form-control" name="TextBoxEmploymentEmployerCompensationEnd1" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                            ?>
                                            <!--" type="text">-->
                                            <!--                                                    --><?php //echo form_error($key); 
                                                                                                        ?>
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerSupervisor1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Supervisor</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerSupervisor1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                <div class="form-group">
                                                    <label>Reason for Leaving</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListEmploymentEmployerContact1_0'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <label class="contact-to-employee">May we contact this employer?</label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact1_0" value="Yes" name="RadioButtonListEmploymentEmployerContact1_0" type="radio">
                                                        <label for="RadioButtonListEmploymentEmployerContact1_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact1_1" value="No" name="RadioButtonListEmploymentEmployerContact1_0" type="radio">
                                                        <label for="RadioButtonListEmploymentEmployerContact1_1">No</label>
                                                    </div>
                                                </div>
                                                <?php echo form_error('RadioButtonListEmploymentEmployerContact1_0'); ?>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerReasonLeave1'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerReasonLeave1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerName2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Previous Employer</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerName2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerPosition2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Position/Title</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerPosition2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerAddress2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Address</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerAddress2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error('TextBoxEmploymentEmployerAddress2'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerCity2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>City</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerCity2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry_2">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerCountry_2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="form-control" id="country_ee2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee2')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState_2">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerState_2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="form-control" name="<?php echo $key; ?>" id="state_ee2">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerPhoneNumber2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Telephone</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerPhoneNumber2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Dates of Employment</label>
                                                        <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2">
                                                            <option vlaue="">Please Select</option>
                                                            <?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2">
                                                            <option value="">Please Select</option>
                                                            <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <span class="date-range-text">to</span>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2">
                                                            <option vlaue="">Please Select</option>
                                                            <?php foreach ($months as $month) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $month; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label></label>
                                                        <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2">
                                                            <option value="">Please Select</option>
                                                            <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                                <?php $def_selected = false; ?>
                                                                <?php $cur_value = $count; ?>
                                                                <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                                <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <!--                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                                <!--                                                    <div class="form-group">-->
                                                <!--                                                        --><?php //$key = 'TextBoxEmploymentEmployerCompensationBegin2'; 
                                                                                                                ?>
                                                <!--                                                        --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                                ?>
                                                <!--                                                        <label>Starting Compensation</label>-->
                                                <!--                                                        <input  class="form-control" name="TextBoxEmploymentEmployerCompensationBegin2" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                                        ?>
                                                <!--" type="text">-->
                                                <!--                                                        --><?php //echo form_error($key); 
                                                                                                                ?>
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                                <!--                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                                <!--                                                    <div class="form-group">-->
                                                <!--                                                        --><?php //$key = 'TextBoxEmploymentEmployerCompensationEnd2'; 
                                                                                                                ?>
                                                <!--                                                        --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                                ?>
                                                <!--                                                        <label>Ending Compensation</label>-->
                                                <!--                                                        <input  class="form-control" name="TextBoxEmploymentEmployerCompensationEnd2" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                                    ?>
                                                <!--" type="text">-->
                                                <!--                                                        --><?php //echo form_error($key); 
                                                                                                                ?>
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerSupervisor2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Supervisor</label>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerSupervisor2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                    <div class="form-group">
                                                        <label>Reason for Leaving</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                    <div class="form-group">
                                                        <?php $key = 'RadioButtonListEmploymentEmployerContact2_0'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                        <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                        <label class="contact-to-employee">May we contact this employer?</label>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact2_0" value="Yes" name="RadioButtonListEmploymentEmployerContact2_0" type="radio">
                                                            <label for="RadioButtonListEmploymentEmployerContact2_0">Yes</label>
                                                        </div>
                                                        <div class="hr-radio-btns">
                                                            <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact2_1" value="No" name="RadioButtonListEmploymentEmployerContact2_0" type="radio">
                                                            <label for="RadioButtonListEmploymentEmployerContact2_1">No</label>
                                                        </div>
                                                        <?php echo form_error('RadioButtonListEmploymentEmployerContact2_0'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxEmploymentEmployerReasonLeave2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <input class="form-control" name="TextBoxEmploymentEmployerReasonLeave2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerName3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Previous Employer</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerName3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerPosition3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Position/Title</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerPosition3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerAddress3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Address</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerAddress3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerCity3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>City</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerCity3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerCountry3">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerCountry3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $country_id = $def_value ?>
                                                    <label>Country:</label>
                                                    <select class="form-control" id="country_ee3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ee3')">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                            <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListEmploymentEmployerState3">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerState3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $state_id = $def_value ?>
                                                    <label>State:</label>
                                                    <select class="form-control" name="<?php echo $key; ?>" id="state_ee3">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option> <?php
                                                                                                } else {
                                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerPhoneNumber3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Telephone</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerPhoneNumber3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Dates of Employment</label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $count; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                <span class="date-range-text">to</span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php $key = 'DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3">
                                                        <option vlaue="">Please Select</option>
                                                        <?php foreach ($months as $month) { ?>
                                                            <?php $def_selected = false; ?>
                                                            <?php $cur_value = $month; ?>
                                                            <?php $def_selected = ($def_value == $cur_value ? true : false); ?>
                                                            <option <?php echo set_select($key, $cur_value, $def_selected); ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                <div class="form-group">
                                                    <label></label>
                                                    <select class="form-control" name="DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3">
                                                        <option value="">Please Select</option>
                                                        <?php for ($count = $starting_year_loop; $count <= intval(date('Y')); $count++) { ?>
                                                            <option value="<?php echo $count; ?>"><?php echo $count; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <!--                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                            <!--                                                <div class="form-group">-->
                                            <!--                                                    --><?php //$key = 'TextBoxEmploymentEmployerCompensationBegin3'; 
                                                                                                        ?>
                                            <!--                                                    --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                        ?>
                                            <!--                                                    <label>Starting Compensation</label>-->
                                            <!--                                                    <input  class="form-control" name="TextBoxEmploymentEmployerCompensationBegin3" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                                ?>
                                            <!--" type="text">-->
                                            <!--                                                    --><?php //echo form_error('TextBoxEmploymentEmployerCompensationBegin3'); 
                                                                                                        ?>
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                            <!--                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                            <!--                                                <div class="form-group">-->
                                            <!--                                                    --><?php //$key = 'TextBoxEmploymentEmployerCompensationEnd3'; 
                                                                                                        ?>
                                            <!--                                                    --><?php //$def_value = (isset($application[$key]) ? $application[$key] : '' );
                                                                                                        ?>
                                            <!--                                                    <label>Ending Compensation</label>-->
                                            <!--                                                    <input  class="form-control" name="TextBoxEmploymentEmployerCompensationEnd3" value="--><?php //echo set_value($key, $def_value); 
                                                                                                                                                                                            ?>
                                            <!--" type="text">-->
                                            <!--                                                    --><?php //echo form_error($key); 
                                                                                                        ?>
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerSupervisor3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <label>Supervisor</label>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerSupervisor3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                                                <div class="form-group">
                                                    <label>Reason for Leaving</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-7">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListEmploymentEmployerContact3_0'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <label class="contact-to-employee">May we contact this employer?</label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> id="RadioButtonListEmploymentEmployerContact3_0" value="Yes" name="RadioButtonListEmploymentEmployerContact3_0" type="radio">
                                                        <label for="RadioButtonListEmploymentEmployerContact3_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> id="RadioButtonListEmploymentEmployerContact3_1" value="No" name="RadioButtonListEmploymentEmployerContact3_0" type="radio">
                                                        <label for="RadioButtonListEmploymentEmployerContact3_1">No</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListEmploymentEmployerContact3_0'); ?>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'TextBoxEmploymentEmployerReasonLeave3'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <input class="form-control" name="TextBoxEmploymentEmployerReasonLeave3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListEmploymentEverTerminated'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <label class="autoheight">Have you ever been laid off or terminated from any job or position? </label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEmploymentEverTerminated_0" name="RadioButtonListEmploymentEverTerminated" type="radio">
                                                        <label for="RadioButtonListEmploymentEverTerminated_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEmploymentEverTerminated_1" name="RadioButtonListEmploymentEverTerminated" type="radio">
                                                        <label for="RadioButtonListEmploymentEverTerminated_1">No</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListEmploymentEverTerminated'); ?>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <?php $key = 'TextBoxEmploymentEverTerminatedReason'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <small class="autoheight">If yes, please explain:</small>
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxEmploymentEverTerminatedReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverTerminatedReason')" name="TextBoxEmploymentEverTerminatedReason"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxEmploymentEverTerminatedReason_remaining">128 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxEmploymentEverTerminatedReason_length">128</p>
                                                    </div>
                                                    <?php echo form_error('TextBoxEmploymentEverTerminatedReason'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <?php $key = 'RadioButtonListEmploymentEverResign'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $yes_selected = ($def_value == 'Yes' ? true : false); ?>
                                                    <?php $no_selected = ($def_value == 'No' ? true : false); ?>
                                                    <label class="autoheight">Have you ever been asked to resign from any job or position?</label>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'Yes', $yes_selected); ?> value="Yes" id="RadioButtonListEmploymentEverResign_0" name="RadioButtonListEmploymentEverResign" type="radio">
                                                        <label for="RadioButtonListEmploymentEverResign_0">Yes</label>
                                                    </div>
                                                    <div class="hr-radio-btns">
                                                        <input <?php echo set_radio($key, 'No', $no_selected); ?> value="No" id="RadioButtonListEmploymentEverResign_1" name="RadioButtonListEmploymentEverResign" type="radio">
                                                        <label for="RadioButtonListEmploymentEverResign_1">No</label>
                                                    </div>
                                                    <?php echo form_error('RadioButtonListEmploymentEverResign'); ?>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <?php $key = 'TextBoxEmploymentEverResignReason'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <small class="autoheight">If yes, please explain:</small>
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxEmploymentEverResignReason" maxlength="128" onkeyup="check_length('TextBoxEmploymentEverResignReason')" name="TextBoxEmploymentEverResignReason"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxEmploymentEverResignReason_remaining">128 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxEmploymentEverResignReason_length">128</p>
                                                    </div>
                                                    <?php echo form_error('TextBoxEmploymentEverResignReason'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $key = 'TextBoxEmploymentGaps'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <div class="form-group">
                                                    <label class="autoheight">Please explain any "gaps" in your employment history</label>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxEmploymentGaps" maxlength="512" onkeyup="check_length('TextBoxEmploymentGaps')" name="TextBoxEmploymentGaps"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxEmploymentGaps_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxEmploymentGaps_length">512</p>
                                                    </div>
                                                    <?php echo form_error($key); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $key = 'TextBoxEmploymentEmployerNoContact'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <div class="form-group">
                                                    <label class="autoheight">If you indicated that we may not contact an employer, please explain</label>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <div class="comment-area">
                                                        <textarea class="form-control" maxlength="512" onkeyup="check_length('TextBoxEmploymentEmployerNoContact')" id="TextBoxEmploymentEmployerNoContact" name="TextBoxEmploymentEmployerNoContact"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxEmploymentEmployerNoContact_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxEmploymentEmployerNoContact_length">512</p>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceName1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>References Name</label>
                                                        <input class="form-control" name="TextBoxReferenceName1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAcquainted1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>How do you know this reference?</label>
                                                        <input class="form-control" name="TextBoxReferenceAcquainted1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAddress1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Address</label>
                                                        <input class="form-control" name="TextBoxReferenceAddress1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceCity1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>City</label>
                                                        <input class="form-control" name="TextBoxReferenceCity1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry1">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceCountry1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="form-control" id="country_ref1" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref1')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState1">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceState1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="form-control" name="<?php echo $key; ?>" id="state_ref1">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceTelephoneNumber1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Telephone Number</label>
                                                        <input class="form-control" name="TextBoxReferenceTelephoneNumber1" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceEmail1'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>E-Mail</label>
                                                        <input class="form-control" name="TextBoxReferenceEmail1" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceName2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Name</label>
                                                        <input class="form-control" name="TextBoxReferenceName2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAcquainted2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>How do you know this reference?</label>
                                                        <input class="form-control" name="TextBoxReferenceAcquainted2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAddress2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Address</label>
                                                        <input class="form-control" name="TextBoxReferenceAddress2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceCity2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>City</label>
                                                        <input class="form-control" name="TextBoxReferenceCity2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry2">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceCountry2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="form-control" id="country_ref2" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref2')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState2">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceState2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="form-control" name="<?php echo $key; ?>" id="state_ref2">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceTelephoneNumber2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Telephone Number</label>
                                                        <input class="form-control" name="TextBoxReferenceTelephoneNumber2" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceEmail2'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>E-Mail</label>
                                                        <input class="form-control" name="TextBoxReferenceEmail2" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceName3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Name</label>
                                                        <input class="form-control" name="TextBoxReferenceName3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAcquainted3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>How do you know this reference?</label>
                                                        <input class="form-control" name="TextBoxReferenceAcquainted3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceAddress3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Address</label>
                                                        <input class="form-control" name="TextBoxReferenceAddress3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceCity3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>City</label>
                                                        <input class="form-control" name="TextBoxReferenceCity3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceCountry3">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceCountry3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $country_id = $def_value ?>
                                                        <label>Country:</label>
                                                        <select class="form-control" id="country_ref3" name="<?php echo $key; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'state_ref3')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($key, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 DropDownListReferenceState3">
                                                    <div class="form-group">
                                                        <?php $key = 'DropDownListReferenceState3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <?php $state_id = $def_value ?>
                                                        <label>State:</label>
                                                        <select class="form-control" name="<?php echo $key; ?>" id="state_ref3">
                                                            <?php if (empty($country_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($key, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-col-100">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceTelephoneNumber3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>Telephone Number</label>
                                                        <input class="form-control" name="TextBoxReferenceTelephoneNumber3" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $key = 'TextBoxReferenceEmail3'; ?>
                                                        <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                        <label>E-Mail</label>
                                                        <input class="form-control" name="TextBoxReferenceEmail3" value="<?php echo set_value($key, $def_value); ?>" type="email">
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="autoheight">Additional Information</label>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <?php $key = 'TextBoxAdditionalInfoWorkExperience'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxAdditionalInfoWorkExperience" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkExperience')" name="TextBoxAdditionalInfoWorkExperience"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxAdditionalInfoWorkExperience_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxAdditionalInfoWorkExperience_length">512</p>
                                                        <?php echo form_error('TextBoxAdditionalInfoWorkExperience'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-color">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $key = 'TextBoxAdditionalInfoWorkTraining'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <div class="form-group">
                                                    <label class="autoheight">Indicate if you have any special training or qualifications (include computer systems and programs) for the position for which you have applied</label>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxAdditionalInfoWorkTraining" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkTraining')" name="TextBoxAdditionalInfoWorkTraining"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxAdditionalInfoWorkTraining_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxAdditionalInfoWorkTraining_length">512</p>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-100">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $key = 'TextBoxAdditionalInfoWorkConsideration'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <div class="form-group">
                                                    <label class="autoheight">Indicate any additional information you would like us to consider</label>
                                                </div>
                                                <div class="form-group autoheight">
                                                    <div class="comment-area">
                                                        <textarea class="form-control" id="TextBoxAdditionalInfoWorkConsideration" maxlength="512" onkeyup="check_length('TextBoxAdditionalInfoWorkConsideration')" name="TextBoxAdditionalInfoWorkConsideration"><?php echo set_value($key, $def_value); ?></textarea>
                                                        <span id="TextBoxAdditionalInfoWorkConsideration_remaining">512 Characters Left</span>
                                                        <p style="display: none;" id="TextBoxAdditionalInfoWorkConsideration_length">512</p>
                                                        <?php echo form_error($key); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <label>Applicant Statement</label>
                                                <p>I understand that, to the extent permitted by law, the Company may require me to submit to a test for the presence of controlled substances in my system prior to employment. I also understand that, to the extent permitted by law, any offer of employment may be contingent upon the passing of a test for controlled substances and/or a physical examination performed by a doctor selected by the Company. I also understand that, to the extent permitted by law, I may be required to take other tests, such as personality and honesty tests, prior to employment. I understand that obtaining a bond may be a condition of employment and if required, I will be so advised either before my employment commences or as soon as possible after employment. Should a bond be required, I understand that I will need to immediately complete a bond application upon request of the Company. I understand that, in connection with this employment application, to the extent permitted by law, the Company may request an investigative consumer report.</p>
                                                <p>I represent that all the information I have provided in this employment application, or other documentation submitted in connection with this employment application, and in any interview, is true and correct. All information is accurate and I have withheld nothing that would, if disclosed, result in an unfavorable employment decision. I understand that the Company shall have sole discretion in determining whether or not any inaccuracy, misrepresentation, or omission is material. I understand that if I am employed, and any information is later found to be false or incomplete in any respect, my employment may be immediately terminated. </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="application-check">
                                                <?php $key = 'CheckBoxAgreement1786'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <?php $def_checked = ($def_value == 1 ? true : false); ?>
                                                <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="my-check" value="1" name="CheckBoxAgreement1786" type="checkbox">
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
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $def_checked = ($def_value == 1 ? true : false); ?>
                                                    <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="my-check1" value="1" name="CheckBoxAgree" type="checkbox">
                                                </figure>
                                                <div class="text">
                                                    <label for="my-check1">
                                                        I acknowledge that I have reviewed, and satisfy, any job requirements for the position for which I am applying. Furthermore, by signing below, or by clicking the "Submit" button, I acknowledge having read the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> &amp; <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> that includes the use of electronic signatures. By continuing I agree to be bound by the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a>. If you do not agree with the provisions of the <a type="button" data-toggle="modal" data-target="#terms_of_use_modal" href="javascript:;">Terms of Use</a> &amp; <a type="button" data-toggle="modal" data-target="#privacy_policy_modal" href="javascript:;">Privacy Policy</a> do not continue.
                                                    </label>
                                                </div>
                                                <?php echo form_error('CheckBoxAgree'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group autoheight">
                                            <?php $key = 'signature'; ?>
                                            <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                            <div class="col-lg-2">
                                                <label class="signature-label">E-SIGNATURE</label>
                                            </div>
                                            <div class="col-lg-5">
                                                <input class="signature-field" name="signature" id="signature" value="<?php echo set_value($key, $def_value); ?>" type="text">
                                                <p>Please type your First and Last Name</p>
                                                <?php echo form_error($key); ?>
                                            </div>
                                            <div class="col-lg-1">
                                                <label class="signature-label">DATE</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <?php $key = 'signature_date'; ?>
                                                <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                <div class="calendar-picker">
                                                    <input type="text" class="form-control startdate" name="signature_date" id="signature_date" value="<?php echo set_value($key, $def_value); ?>" />
                                                </div>
                                                <?php echo form_error($key); ?>
                                            </div>
                                        </div>



                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="application-check">
                                                <!-- class="card-fields-row" -->
                                                <figure>
                                                    <?php $key = 'CheckBoxTerms'; ?>
                                                    <?php $def_value = (isset($application[$key]) ? $application[$key] : ''); ?>
                                                    <?php $def_checked = ($def_value == 1 ? true : false); ?>
                                                    <input <?php echo set_checkbox($key, 1, $def_checked); ?> id="terms_check" value="1" name="CheckBoxTerms" type="checkbox">
                                                </figure>
                                                <div class="text">
                                                    <label for="terms_check">
                                                        <strong>I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.</strong> <strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS FOR <?php echo isset($company_name) ? $company_name : 'Company'; ?></strong><br>
                                                    </label>
                                                    <?php echo form_error('CheckBoxTerms'); ?>
                                                </div>
                                                <div class="col-lg-12">
                                                    1. Electronic Signature Agreement. By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide <?php echo isset($company_name) ? $company_name : 'Company'; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and <?php echo isset($company_name) ? $company_name : 'Company'; ?>. You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a <?php echo isset($company_name) ? $company_name : 'Company'; ?> service constitutes your agreement to be bound by the terms and conditions of the <?php echo isset($company_name) ? $company_name : 'Company'; ?> Disclosures and Agreements as they exist on the date of your E-Signature
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                                <button type="submit" onclick="validate_form();" class="btn btn-success btn-block">Save</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if (isset($unique_sid)) { ?>
                    <!--                    <div class="btn-wrp">-->
                    <!--                        <div class="row">-->
                    <!--                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">-->
                    <!--                                <a href="--><?php //echo base_url('onboarding/dependents/' . $unique_sid); 
                                                                    ?>
                    <!--" class="btn btn-success btn-block"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>-->
                    <!--                            </div>-->
                    <!--                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">-->
                    <!--                                <!--<a href="javascript:;" class="btn btn-danger btn-block">Skip</a>-->
                    <!--                            </div>-->
                    <!--                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">-->
                    <!--                                <a href="--><?php //echo base_url('onboarding/eeoc_form/' . $unique_sid); 
                                                                    ?>
                    <!--" class="btn btn-success btn-block">Next&nbsp;<i class="fa fa-arrow-right"></i></a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                <?php } ?>
            </div>

            <?php if (!isset($unique_sid)) { ?>
                <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); 
                ?>
                <!-- </div> -->
            <?php } ?>

            <?php if (isset($unique_sid)) { ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <!--                    --><?php //$this->load->view('onboarding/onboarding_left_menu'); 
                                                ?>
                </div>
            <?php } ?>
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
        console.log(user_text + ' = ' + text_length + " LEFT: " + text_left);
        $('#' + id + '_remaining').html(text_left + ' Characters Left');
    }

    $(document).ready(function() {
        $('.startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();

        $('.startdate').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        //Disable Autocomplete
        $('input,select,textarea').each(function() {
            $(this).attr('autocomplete', 'off');
        });

        validate_form();
    });

    function validate_form() {
        $("#fullemploymentapplication").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\'-]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\'-]+$/
                },
                TextBoxNameMiddle: {
                    pattern: /^[a-zA-Z0-9\'-]+$/
                },
                email: {
                    required: true,
                    email: true
                },
                TextBoxAddressEmailConfirm: {
                    equalTo: '[name="email"]'
                },
                Location_City: {
                    required: true
                },
                CheckBoxAgreement1786: {
                    required: true
                },
                CheckBoxAgree: {
                    required: true
                },
                signature: {
                    required: true
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
                TextBoxTelephonePrimary: {
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
            submitHandler: function(form) {
                form.submit();
            }
        });
    }
</script>