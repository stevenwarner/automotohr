<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
               
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo $backUrl;?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back To References</a>

                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>


                                <div class="universal-form-style-v2 equipment-types">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                            <label>Reference Type</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="hr-select-dropdown">
                                                <select id="select_reference_type" name="select_reference_type" class="invoice-fields">
                                                    <option value="">Please Select</option>
                                                    <option <?php
                                                                if(!empty($reference)){
                                                                    if($reference['reference_type'] == 'work'){
                                                                        echo 'selected="selected" ';
                                                                    }
                                                                }?> value="work">Work Reference</option>
                                                    <option <?php
                                                                if(!empty($reference)){
                                                                    if($reference['reference_type'] == 'personal'){
                                                                        echo 'selected="selected" ';
                                                                    }
                                                                }?> value="personal">Personal Reference</option>
                                                    <!--<option <?php
                                                                /*
                                                                if(!empty($reference)){
                                                                    if($reference['reference_type'] == 'other'){
                                                                        echo 'selected="selected" ';
                                                                    }
                                                                }*/?> value="other">Other</option>-->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="no_reference_selected_info" class="no-equipment-select box-view">
                                    <span class="notes-not-found  no_note">Please Select a Reference Type.</span>
                                </div>
                                <div id="" class="universal-form-style-v2">
                                    <form id="form_reference_type_work" method="post">
                                        <div class="tagline-heading"><h4>Work Reference Details</h4></div>
                                        <input class="reference_type" type="hidden" value="<?php echo (!empty($reference)? $reference['reference_type'] : ''); ?>" id="reference_type" name="reference_type" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['sid'] : ''); ?>" id="sid" name="sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['company_sid'] : ''); ?>" id="company_sid" name="company_sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['user_sid'] : ''); ?>" id="user_sid" name="user_sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['users_type'] : ''); ?>" id="users_type" name="users_type" />
                                        <input type="hidden" value="save_work_reference" id="perform_action" name="perform_action" />

                                        <ul>
                                            <li class="form-col-50-left">
                                                <label for="reference_title">Title<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_title'] : ''); ?>" id="reference_title" name="reference_title" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_name">Name<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_name'] : ''); ?>" id="reference_name" name="reference_name" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="organization_name">Organization Name<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['organization_name'] : ''); ?>" id="organization_name" name="organization_name" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="department_name">Department Name </label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['department_name'] : ''); ?>" id="department_name" name="department_name" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="branch_name">Branch Name</label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['branch_name'] : ''); ?>" id="branch_name" name="branch_name" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_relation">Relationship<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_relation'] : ''); ?>" id="reference_relation" name="reference_relation" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="work_period_start">Worked From<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields start-date" value="<?php echo (!empty($reference)? my_date_format($reference['period_start']) : ''); ?>" id="work_period_start" name="work_period_start" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="work_period_end">Worked Till</label>
                                                <input type="text" class="invoice-fields end-date" value="<?php echo (!empty($reference)? my_date_format($reference['period_end']) : ''); ?>" id="work_period_end" name="work_period_end" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="reference_email">Email<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_email'] : ''); ?>" id="reference_email" name="reference_email" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_phone">Telephone<span class="staric">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text">+1</span>
                                                    </div>
                                                    <input type="text"  class="invoice-fields js-phone" value="<?php echo (!empty($reference)? phonenumber_format($reference['reference_phone'], true) : ''); ?>" id="reference_phone" name="reference_phone" />
                                                </div>
                                            </li>


                                            <li class="form-col-50-left" style="height: 175px;">
                                                <label for="">Other Information</label>
                                                <textarea type="text" class="invoice-fields-textarea" value="" id="work_other_information" name="work_other_information" ><?php echo (!empty($reference)? $reference['other_information'] : ''); ?></textarea>
                                            </li>
                                            <li class="form-col-50-right" style="height: 175px;">
                                                <div class="questionair_radio_container">
                                                    <label>Best Time to Call<span class="staric">*</span></label>
                                                    <input name="best_time_to_call" value="" type="radio" style="visibility: hidden;">
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="morning" id="best_time_to_call_morning" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'morning' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_morning">Morning</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="afternoon" id="best_time_to_call_afternoon" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'afternoon' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_afternoon">Afternoon</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="evening" id="best_time_to_call_evening" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'evening' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_evening">Evening</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="night" id="best_time_to_call_night" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'night' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_night">Night</label>
                                                </div>

                                            </li>



                                            <li class="form-col-50-left">
                                                <button type="button" class="submit-btn" value="Save" onclick="fSaveWorkReference();" >Save</button>
                                                <a class="submit-btn btn-cancel" value="Cancel" href="<?php echo $cancel_url; ?>" >Cancel</a>
                                            </li>
                                            <li class="form-col-50-right"></li>
                                        </ul>
                                    </form>
                                    <form id="form_reference_type_personal" method="post">
                                        <div class="tagline-heading"><h4>Personal Reference Details</h4></div>
                                        <input class="reference_type"  type="hidden" value="<?php echo (!empty($reference)? $reference['reference_type'] : ''); ?>" id="reference_type" name="reference_type" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['sid'] : ''); ?>" id="sid" name="sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['company_sid'] : ''); ?>" id="company_sid" name="company_sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['user_sid'] : ''); ?>" id="user_sid" name="user_sid" />
                                        <input type="hidden" value="<?php echo (!empty($reference)? $reference['users_type'] : ''); ?>" id="users_type" name="users_type" />
                                        <input type="hidden" value="save_personal_reference" id="perform_action" name="perform_action" />

                                        <ul>
                                            <li class="form-col-50-left">
                                                <label for="reference_title">Title<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_title'] : ''); ?>" id="reference_title" name="reference_title" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_name">Name<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_name'] : ''); ?>" id="reference_name" name="reference_name" />
                                            </li>

                                            <li class="form-col-50-left">
                                                <label for="reference_relation">Relationship<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_relation'] : ''); ?>" id="reference_relation" name="reference_relation" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="relationship_period">How Long Have You Known Him / Her?<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['period'] : ''); ?>" id="relationship_period" name="relationship_period" />
                                            </li>

                                            <li class="form-col-50-left">
                                                <label for="reference_email">Email<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo (!empty($reference)? $reference['reference_email'] : ''); ?>" id="reference_email" name="reference_email" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_phone">Telephone<span class="staric">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text">+1</span>
                                                    </div>
                                                    <input type="text" class="invoice-fields js-phone" value="<?php echo (!empty($reference)? phonenumber_format($reference['reference_phone'], true) : ''); ?>" id="reference_phone" name="reference_phone" />
                                                </div>
                                            </li>


                                            <li class="form-col-50-left" style="height: 175px;">
                                                <label for="">Other Information</label>
                                                <textarea type="text" class="invoice-fields-textarea" value="" id="work_other_information" name="work_other_information" ><?php echo (!empty($reference)? $reference['other_information'] : ''); ?></textarea>
                                            </li>
                                            <li class="form-col-50-right" style="height: 175px;">
                                                <div class="questionair_radio_container">
                                                    <label>Best Time to Call<span class="staric">*</span></label>
                                                    <input name="best_time_to_call" value="" type="radio" style="visibility: hidden;">
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="morning" id="best_time_to_call_morning" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'morning' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_morning">Morning</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="afternoon" id="best_time_to_call_afternoon" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'afternoon' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_afternoon">Afternoon</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="evening" id="best_time_to_call_evening" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'evening' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_evening">Evening</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="night" id="best_time_to_call_night" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'night' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_night">Night</label>
                                                </div>
                                            </li>



                                            <li class="form-col-50-left">
                                                <button type="button" class="submit-btn" value="Save" onclick="fSavepersonalReference();" >Save</button>
                                                <a class="submit-btn btn-cancel" value="Cancel" href="<?php echo $cancel_url; ?>" >Cancel</a>
                                            </li>
                                            <li class="form-col-50-right"></li>
                                        </ul>
                                    </form>
                                    <form id="form_reference_type_other" method="post">
                                        <div class="tagline-heading"><h4>Work Reference Details</h4></div>
                                        <input class="reference_type"  type="hidden" value="<?php echo_value_if_key_exists($reference, 'reference_type'); ?>" id="reference_type" name="reference_type" />
                                        <input type="hidden" value="<?php echo_value_if_key_exists($reference, 'sid'); ?>" id="sid" name="sid" />
                                        <input type="hidden" value="<?php echo_value_if_key_exists($reference, 'company_sid'); ?>" id="company_sid" name="company_sid" />
                                        <input type="hidden" value="<?php echo_value_if_key_exists($reference, 'user_sid'); ?>" id="user_sid" name="user_sid" />
                                        <input type="hidden" value="<?php echo_value_if_key_exists($reference, 'users_type'); ?>" id="users_type" name="users_type" />
                                        <input type="hidden" value="save_other_reference" id="perform_action" name="perform_action" />

                                        <ul>
                                            <li class="form-col-50-left">
                                                <label for="reference_title">Title<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo_value_if_key_exists($reference, 'reference_title'); ?>" id="reference_title" name="reference_title" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_name">Name<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo_value_if_key_exists($reference, 'reference_name'); ?>" id="reference_name" name="reference_name" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="reference_relation">Relationship<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo_value_if_key_exists($reference, 'reference_relation'); ?>" id="reference_relation" name="reference_relation" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="relationship_period">Relationship Period<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo_value_if_key_exists($reference, 'relationship_period'); ?>" id="relationship_period" name="relationship_period" />
                                            </li>
                                            <li class="form-col-50-left">
                                                <label for="reference_email">Email<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" value="<?php echo_value_if_key_exists($reference, 'reference_email'); ?>" id="reference_email" name="reference_email" />
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="reference_phone">Telephone<span class="staric">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text">+1</span>
                                                    </div>
                                                    <input type="text" class="invoice-fields js-phone" value="<?php echo_value_if_key_exists($reference, 'reference_phone'); ?>" id="reference_phone" name="reference_phone" />
                                                </div>
                                            </li>


                                            <li class="form-col-50-left" style="height: 175px;">
                                                <label for="">Other Information</label>
                                                <textarea type="text" class="invoice-fields-textarea"  id="work_other_information" name="work_other_information" ><?php echo_value_if_key_exists($reference, 'work_other_information'); ?></textarea>
                                            </li>
                                            <li class="form-col-50-right" style="height: 175px;">
                                                <div class="questionair_radio_container">
                                                    <label>Best Time to Call<span class="staric">*</span></label>
                                                    <input name="best_time_to_call" value="" type="radio" style="visibility: hidden;">
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="morning" id="best_time_to_call_morning" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'morning' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_morning">Morning</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="afternoon" id="best_time_to_call_afternoon" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'afternoon' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_afternoon">Afternoon</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="evening" id="best_time_to_call_evening" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'evening' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_evening">Evening</label>
                                                </div>
                                                <div class="questionair_radio_container">
                                                    <input type="radio" class="invoice-fields-radio" value="night" id="best_time_to_call_night" name="best_time_to_call" <?php echo (!empty($reference) && $reference['best_time_to_call'] == 'night' ? 'checked="checked"' : ''); ?> />
                                                    <label for="best_time_to_call_night">Night</label>
                                                </div>
                                            </li>



                                            <li class="form-col-50-left">
                                                <button type="button" class="submit-btn" value="Save" onclick="fSaveOtherReference();" >Save</button>
                                                <a class="submit-btn btn-cancel" value="Cancel" href="<?php echo $cancel_url; ?>" >Cancel</a>
                                            </li>
                                            <li class="form-col-50-right"></li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                    <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/additional-methods.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        //$('.date-picker-field').datepicker({dateFormat: 'mm-dd-yy'}).val();




        fValidateWorkReference();
        fValidatepersonalReference();
        fValidateOtherReference();

        $('#form_reference_type_work').hide();
        $('#form_reference_type_personal').hide();
        $('#form_reference_type_other').hide();

        $('#select_reference_type').on('change', function(){
            var CurrentType = $(this).val();
            console.log(CurrentType);
            $('.reference_type').val(CurrentType);
            switch (CurrentType){
                case 'work':
                    $('#no_reference_selected_info').hide();
                    $('#form_reference_type_work').show();
                    $('#form_reference_type_personal').hide();
                    $('#form_reference_type_other').hide();
                    break;
                case 'personal':
                    $('#no_reference_selected_info').hide();
                    $('#form_reference_type_work').hide();
                    $('#form_reference_type_personal').show();
                    $('#form_reference_type_other').hide();
                    break;
                case 'other':
                    $('#no_reference_selected_info').hide();
                    $('#form_reference_type_work').hide();
                    $('#form_reference_type_personal').hide();
                    $('#form_reference_type_other').show();
                    break;
                default :
                    $('#no_reference_selected_info').show();
                    $('#form_reference_type_work').hide();
                    $('#form_reference_type_personal').hide();
                    $('#form_reference_type_other').hide();
                    break;
            }
        }).trigger('change');

        fEnableDatePickerAndSetDateLimits('work_period_start', 'work_period_end');
        fEnableDatePickerAndSetDateLimits('personal_period_start', 'personal_period_end');



    });


    function fEnableDatePickerAndSetDateLimits(startDateInputId, endDateInputId){
        $('#' + startDateInputId).datepicker({
            changeYear: true,
            dateFormat: 'mm-dd-yy',
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy",selected);
                console.log(dt);
                dt.setDate(dt.getDate() + 1);
                $('#' + endDateInputId).datepicker("option", "minDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

        $('#' + endDateInputId).datepicker({
            changeYear: true,
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy",selected);
                console.log(dt);
                dt.setDate(dt.getDate() - 1);
                $('#' + startDateInputId).datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });
    }


    function fValidateWorkReference() {
        $('#form_reference_type_work').validate({
            rules: {
                reference_title: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                reference_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                organization_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                department_name: {
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                branch_name: {
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                reference_relation: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                work_period_start: {
                    required: true

                },
                work_period_end :{

                },
                reference_email:{
                    required: true,
                    pattern : /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                },
                reference_phone:{
                    required: true,
                    minlength: 2,
                    maxlength: 15,
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/
                    // pattern:/^[0-9\-]+$/
                },
                work_other_information: {
                    minlength : 2,
                    maxlength : 300,
                    pattern: /^[A-Za-z0-9\s\.,\.\?\-']+$/
                },
                best_time_to_call: {
                    required: true
                }
            },
            messages: {
                reference_title: {
                    required: 'Title is Required.',
                    minlength: 'Title must be atleast 2 characters long.',
                    maxlength: 'Title can be maximum 100 characters long.',
                    pattern: 'Title can contain alphabets and blank spaces only.'
                },
                reference_name: {
                    required: 'Reference name is Required.',
                    minlength: 'Reference name must be atleast 2 characters long.',
                    maxlength: 'Reference name can be maximum 100 characters long.',
                    pattern: 'Reference name can contain alphabets and blank spaces only.'
                },
                organization_name: {
                    required: 'Organization Name is Required.',
                    minlength: 'Organization Name must be atleast 2 characters long.',
                    maxlength: 'Organization Name can be maximum 100 characters long.',
                    pattern: 'Organization Name can contain alphabets and blank spaces only.'
                },
                department_name: {
                    minlength: 'Department Name must be atleast 2 characters long.',
                    maxlength: 'Department Name can be maximum 100 characters long.',
                    pattern: 'Department Name can contain alphabets and blank spaces only.'
                },
                branch_name: {
                    minlength: 'Branch Name must be atleast 2 characters long.',
                    maxlength: 'Branch Name can be maximum 100 characters long.',
                    pattern: 'Branch Name can contain alphabets and blank spaces only.'
                },
                reference_relation: {
                    required: 'Reference Relation is required.',
                    minlength: 'Reference Relation must be atleast 2 characters long.',
                    maxlength: 'Reference Relation can be maximum 100 chracters long.',
                    pattern: 'Reference Relation can contain alphabets and blank spaces only.'
                },
                work_period_start: {
                    required: 'Period Start is required.'
                },

                reference_email:{
                    required: 'Email is Required.',
                    pattern : 'Must be a valid email address.'
                },
                reference_phone:{
                    required: 'Reference Telephone Number is Required.',
                    minlength: 'Reference Telephone Number must be atleast 8 digits long',
                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                    // pattern:'Reference Telephone Number can contain Numbers and - only.'
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                },
                work_other_information: {
                    minlength : 'Other Information must be atleast 2 characters long.',
                    maxlength : 'Other Information can be maximum 300 characters long.',
                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                },
                best_time_to_call: {
                    required: 'You must specify best time to call.'
                }

            },
            errorPlacement: function(e, el){
                if($(el)[0].id == 'reference_phone') $(el).parent().after(e);
                else $(el).after(e);
            }
        });
    }
    function fSaveWorkReference(){
        fValidateWorkReference();
        if($('#form_reference_type_work').valid()){
            $('#form_reference_type_work').find('#js-phonenumber').remove();
            $('#form_reference_type_work').append('<input type="hidden" name="txt_phonenumber" value="+1'+($('#form_reference_type_work').find('.js-phone').val().replace(/\D/g, ''))+'" />');
            $('#form_reference_type_work').submit();
        }
    }



    function fValidatepersonalReference(){
        $('#form_reference_type_personal').validate({
            rules: {
                reference_title: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                reference_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },

                reference_relation: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                relationship_period:{
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z0-9\s\.']+$/
                },
                reference_email:{
                    required: true,
                    pattern : /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                },
                reference_phone:{
                    required: true,
                    minlength: 2,
                    maxlength: 15,
                    // pattern:/^[0-9\-]+$/
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/
                },
                work_other_information: {
                    minlength : 2,
                    maxlength : 300,
                    pattern: /^[A-Za-z0-9\s\.,\.\?\-']+$/
                },
                best_time_to_call: {
                    required: true
                }
            },
            messages: {
                reference_title: {
                    required: 'Title is Required.',
                    minlength: 'Title must be atleast 2 characters long.',
                    maxlength: 'Title can be maximum 100 characters long.',
                    pattern: 'Title can contain alphabets and blank spaces only.'
                },
                reference_name: {
                    required: 'Reference name is Required.',
                    minlength: 'Reference name must be atleast 2 characters long.',
                    maxlength: 'Reference name can be maximum 100 characters long.',
                    pattern: 'Reference name can contain alphabets and blank spaces only.'
                },

                program_name: {
                    minlength: 'Program Name must be atleast 2 characters long.',
                    maxlength: 'Program Name can be maximum 100 characters long.',
                    pattern: 'Program Name can contain alphabets and blank spaces only.'
                },
                reference_relation: {
                    required: 'Reference Relation is required.',
                    minlength: 'Reference Relation must be atleast 2 characters long.',
                    maxlength: 'Reference Relation can be maximum 100 chracters long.',
                    pattern: 'Reference Relation can contain alphabets and blank spaces only.'
                },
                relationship_period:{
                    required: 'Relationship Period is required.',
                    minlength: 'Relationship Period must be atleast 2 characters long.',
                    maxlength: 'Relationship Period can be maximum 100 chracters long.',
                    pattern: 'Relationship Period can contain Numbers alphabets and blank spaces only.'
                },

                reference_email:{
                    required: 'Email is Required.',
                    pattern : 'Must be a valid email address.'
                },
                reference_phone:{
                    required: 'Reference Telephone Number is Required.',
                    minlength: 'Reference Telephone Number must be atleast 2 digits long',
                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                    // pattern:'Reference Telephone Number can contain Numbers and - only.'
                },
                work_other_information: {
                    minlength : 'Other Information must be atleast 2 characters long.',
                    maxlength : 'Other Information can be maximum 300 characters long.',
                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                },
                best_time_to_call: {
                    required: 'You must specify best time to call.'
                }

            },
            errorPlacement: function(e, el){
                if($(el)[0].id == 'reference_phone') $(el).parent().after(e);
                else $(el).after(e);
            }
        });
    }
    function fSavepersonalReference(){
        fValidatepersonalReference();

        if($('#form_reference_type_personal').valid()){
            $('#form_reference_type_personal').find('#js-phonenumber').remove();
            $('#form_reference_type_personal').append('<input type="hidden" name="txt_phonenumber" value="+1'+($('#form_reference_type_personal').find('.js-phone').val().replace(/\D/g, ''))+'" />');
            $('#form_reference_type_personal').submit();
        }
    }


    function fValidateOtherReference(){
        $('#form_reference_type_other').validate({
            rules: {
                reference_title: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                reference_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },
                reference_relation: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /^[A-Za-z\s\.']+$/
                },

                reference_email:{
                    required: true,
                    pattern : /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                },
                reference_phone:{
                    required: true,
                    minlength: 2,
                    maxlength: 15,
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/
                    // pattern:/^[0-9\-]+$/
                },
                work_other_information: {
                    minlength : 2,
                    maxlength : 300,
                    pattern: /^[A-Za-z0-9\s\.,\.\?\-']+$/
                },
                best_time_to_call: {
                    required: true
                }
            },
            messages: {
                reference_title: {
                    required: 'Title is Required.',
                    minlength: 'Title must be atleast 2 characters long.',
                    maxlength: 'Title can be maximum 100 characters long.',
                    pattern: 'Title can contain alphabets and blank spaces only.'
                },
                reference_name: {
                    required: 'Reference name is Required.',
                    minlength: 'Reference name must be atleast 2 characters long.',
                    maxlength: 'Reference name can be maximum 100 characters long.',
                    pattern: 'Reference name can contain alphabets and blank spaces only.'
                },
                reference_relation: {
                    required: 'Reference Relation is required.',
                    minlength: 'Reference Relation must be atleast 2 characters long.',
                    maxlength: 'Reference Relation can be maximum 100 chracters long.',
                    pattern: 'Reference Relation can contain alphabets and blank spaces only.'
                },

                reference_email:{
                    required: 'Email is Required.',
                    pattern : 'Must be a valid email address.'
                },
                reference_phone:{
                    required: 'Reference Telephone Number is Required.',
                    minlength: 'Reference Telephone Number must be atleast 2 digits long',
                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                    // pattern:'Reference Telephone Number can contain Numbers and - only.'
                },
                work_other_information: {
                    minlength : 'Other Information must be atleast 2 characters long.',
                    maxlength : 'Other Information can be maximum 300 characters long.',
                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                },
                best_time_to_call: {
                    required: 'You must specify best time to call.'
                }

            },
            errorPlacement: function(e, el){
                if($(el)[0].id == 'reference_phone') $(el).parent().after(e);
                else $(el).after(e);
            }
        });
    }
    function fSaveOtherReference(){
        fValidateOtherReference();
        if($('#form_reference_type_other').valid()){
            $('#form_reference_type_other').find('#js-phonenumber').remove();
            $('#form_reference_type_other').append('<input type="hidden" name="txt_phonenumber" value="+1'+($('#form_reference_type_other').find('.js-phone').val().replace(/\D/g, ''))+'" />');
            $('#form_reference_type_other').submit();
        }

    }

    //
    var val = fpn($('.js-phone').val().trim());
    if(typeof(val) === 'object'){
        $('.js-phone').val(val.number);
        setCaretPosition($('.js-phone'), val.cur);
    }else $('.js-phone').val(val);


    $('.js-phone').keyup(function(event) {
        var val = fpn($(this).val().trim());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition($(this), val.cur);
        }else $(this).val(val);        
    });


    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        // 
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else{
            var af = '', an = '', cur = 1;
            if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
            if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
            if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
            if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
            if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
            if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
            if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
            if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
            if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
            if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

            if(is_return) return match === null ? false : true;

            return { number: default_number.replace(af, an), cur: cur };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if(elem != null) {
            if(elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            }
            else {
                if(elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }

</script>