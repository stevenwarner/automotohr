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
                                        <h1 class="page-title"><i class="fa fa-group"></i> Corporate Group</h1>
                                        <a href="<?php echo base_url('manage_admin/automotive_groups'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Corporate Groups</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><?php echo $page_title; ?>&nbsp;<?php echo $automotive_group['group_name']; ?></h1>
                                            <a href="<?php echo base_url('manage_admin/automotive_groups/member_companies/' . $automotive_group['sid']); ?>" class="btn btn-success pull-right full-on-small"> Member Companies</a>
                                        </div>
                                        <form id="form_add_edit_member_company" action="<?php echo current_url();?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" id="automotive_group_sid" name="automotive_group_sid" value="<?php echo $automotive_group_sid; ?>" />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['company_name']) ? $member_company_info['company_name'] : ''); ?>
                                                        <label for="company_name">Company Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('company_name', set_value('company_name', $temp), 'class="hr-form-fileds" id="company_name"'); ?>
                                                        <?php echo form_error('company_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['location_country']) ? $member_company_info['location_country'] : ''); ?>
                                                        <?php $temp_state = (isset($member_company_info['location_state']) ? $member_company_info['location_state'] : 0); ?>
                                                        <label for="country">Country <span class="hr-required">*</span></label>
                                                        <?php $selected_state =  (isset($_POST['location_state']) ? set_value('location_state', $temp_state) : $temp_state); ?>
                                                        <div class="hr-select-dropdown">
                                                            <?php $is_default_us = ($temp == 227 ? true : false ); ?>
                                                            <?php $is_default_ca = ($temp == 38 ? true : false ); ?>

                                                            <select class="invoice-fields" id="location_country" name="location_country" onchange="getStates(this.value, <?php echo $states; ?>, <?php echo $selected_state?>); ">
                                                                <option value="">Please Select</option>
                                                                <option <?php echo set_select('location_country', 227, $is_default_us); ?> value="227">United States</option>
                                                                <option <?php echo set_select('location_country', 38, $is_default_ca); ?> value="38">Canada</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('location_country'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="state">State <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="location_state" name="location_state"  >
                                                                <option value="">Select State</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('location_state'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['location_address']) ? $member_company_info['location_address'] : ''); ?>
                                                        <label for="location_address">Address <span class="hr-required">*</span></label>
                                                        <?php echo form_input('location_address', set_value('location_address', $temp), 'class="hr-form-fileds" id="location_address"'); ?>
                                                        <?php echo form_error('location_address'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['location_city']) ? $member_company_info['location_city'] : ''); ?>
                                                        <label for="location_city">City <span class="hr-required">*</span></label>
                                                        <?php echo form_input('location_city', set_value('location_city', $temp), 'class="hr-form-fileds" id="location_city"'); ?>
                                                        <?php echo form_error('location_city'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['location_zipcode']) ? $member_company_info['location_zipcode'] : ''); ?>
                                                        <label for="location_zipcode">Zipcode</label>
                                                        <?php echo form_input('location_zipcode', set_value('location_zipcode', $temp), 'class="hr-form-fileds" id="location_zipcode"'); ?>
                                                        <?php echo form_error('location_zipcode'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['pri_contact_name']) ? $member_company_info['pri_contact_name'] : ''); ?>
                                                        <label for="pri_contact_name">Primary Contact Name</label>
                                                        <?php echo form_input('pri_contact_name', set_value('pri_contact_name', $temp), 'class="hr-form-fileds" id="pri_contact_name"'); ?>
                                                        <?php echo form_error('pri_contact_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['pri_contact_phone']) ? $member_company_info['pri_contact_phone'] : ''); ?>
                                                        <label for="pri_contact_phone">Primary Contact Phone</label>
                                                        <?php echo form_input('pri_contact_phone', set_value('pri_contact_phone', $temp), 'class="hr-form-fileds" id="pri_contact_phone"'); ?>
                                                        <?php echo form_error('pri_contact_phone'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['pri_contact_email']) ? $member_company_info['pri_contact_email'] : ''); ?>
                                                        <label for="location_city">Primary Contact Email</label>
                                                        <input type="email" class="hr-form-fileds" id="pri_contact_email" name="pri_contact_email" value="<?php echo set_value('pri_contact_email', $temp); ?>" />
                                                        <?php echo form_error('pri_contact_email'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['sec_contact_name']) ? $member_company_info['sec_contact_name'] : ''); ?>
                                                        <label for="sec_contact_name">Secondary Contact Name</label>
                                                        <?php echo form_input('sec_contact_name', set_value('sec_contact_name', $temp), 'class="hr-form-fileds" id="sec_contact_name"'); ?>
                                                        <?php echo form_error('sec_contact_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['sec_contact_phone']) ? $member_company_info['sec_contact_phone'] : ''); ?>
                                                        <label for="sec_contact_phone">Secondary Contact Phone</label>
                                                        <?php echo form_input('sec_contact_phone', set_value('sec_contact_phone', $temp), 'class="hr-form-fileds" id="sec_contact_phone"'); ?>
                                                        <?php echo form_error('sec_contact_phone'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['sec_contact_email']) ? $member_company_info['sec_contact_email'] : ''); ?>
                                                        <label for="sec_contact_email">Secondary Contact Email</label>
                                                        <input type="email" class="hr-form-fileds" id="sec_contact_email" name="sec_contact_email" value="<?php echo set_value('sec_contact_email', $temp); ?>" />
                                                        <?php echo form_error('sec_contact_email'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['is_registered_in_ahr']) ? $member_company_info['is_registered_in_ahr'] : 0); ?>
                                                        <?php $is_default_checked = ($temp == 1 ? true : false); ?>
                                                        <label></label>
                                                        <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                            Is Registered In <?php echo STORE_NAME; ?> <small class="text-success">( Check this checkbox if the company is already registered in <?php echo STORE_NAME; ?> and Select it from dropdown. )</small>
                                                            <input <?php echo set_checkbox('is_registered_in_ahr', 1, $is_default_checked)?> class="is_registered_in_ahr" id="is_registered_in_ahr" name="is_registered_in_ahr" value="1" type="checkbox">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($member_company_info['company_sid']) ? $member_company_info['company_sid'] : 0); ?>
                                                        <label for="company_sid">Assign Company</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="company_sid" name="company_sid"  disabled="disabled">
                                                                <option value="">Please Select</option>
                                                                <?php if(!empty($companies)) { ?>
                                                                    <?php foreach($companies as $company) { ?>
                                                                        <?php $is_default_selected = ($temp == $company['sid'] ? true : false ); ?>
                                                                        <option <?php echo set_select('company_sid', $company['sid'], $is_default_selected); ?> value="<?php echo $company['sid']; ?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row autoheight">
                                                        <?php $temp = (isset($member_company_info['short_description']) ? $member_company_info['short_description'] : ''); ?>
                                                        <label for="group_description">General Notes</label>
                                                        <?php echo form_textarea('short_description', set_value('short_description', $temp), 'class="hr-form-fileds field-row-autoheight" id="short_description"'); ?>
                                                        <?php echo form_error('short_description'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php if(isset($member_company_info['sid'])) { ?>
                                                        <input onclick="func_validate_and_submit();" type="button" value="Update Member Company" class="btn btn-success full-on-small" />
                                                    <?php } else { ?>
                                                        <input onclick="func_validate_and_submit();" type="button" value="Add Member Company" class="btn btn-success full-on-small" />
                                                    <?php } ?>
                                                    <a class="black-btn btn full-on-small" href="<?php echo base_url('manage_admin/automotive_groups/member_companies/' . $automotive_group['sid']); ?>">Cancel</a>
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
<script>
    $(document).ready(function () {
        $('#location_country').trigger('change');



        $('#is_registered_in_ahr').on('click', function () {
            enable_disable_compeny_dropdown();
        });

        enable_disable_compeny_dropdown();
    });

    function enable_disable_compeny_dropdown(){
        var is_checked = $('#is_registered_in_ahr').prop('checked');

        if(is_checked){
            $('#company_sid').prop('disabled', false);
            $('#company_sid').attr('data-rule-required', 'true');
        } else {
            $('#company_sid').prop('disabled', true);
            $('#company_sid').removeAttr('data-rule-required');
        }
    }

    function getStates(val, states, selected) {
        var html = '';
        if (val == '') {
            $('#location_state').html('<option value="0">Please Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                var selected_text = ' selected="selected" ';

                if(id == selected){
                    html += '<option ' + selected_text + ' value="' + id + '">' + name + '</option>';
                } else {
                    html += '<option value="' + id + '">' + name + '</option>';
                }
            }
            $('#location_state').html(html);
            $('#location_state').trigger('change');
        }
    }


    function func_validate_and_submit(){
        $('#form_add_edit_member_company').validate();
        if($('#form_add_edit_member_company').valid()){
            $('#form_add_edit_member_company').submit();
        }
    }
</script>