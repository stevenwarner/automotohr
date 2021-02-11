
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="well well-sm">
                <h2>Important Notice!</h2>
                <h4>Please contact the references you are using and let them know that they will be receiving an email reference questionnaire and possibly a call from one of our company managers. Inform your reference of the urgency of completing the questionnaire.</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <?php if (isset($current_references)) { ?>

                <h2>Currently Saved References</h2>
                <hr />
                <div class="table-responsive table-outer">
                    <div class="table-wrp">
                        <table class="table table-hover table-stripped">
                            <thead>
                                <tr>
                                    <th class="col-xs-1">Sr. #</th>
                                    <th class="col-xs-1">Type</th>
                                    <th class="col-xs-1">Title</th>
                                    <th class="col-xs-3">Name</th>
                                    <th class="col-xs-2">Phone</th>
                                    <th class="col-xs-4">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; ?>
                                <?php foreach ($current_references as $current_reference) { ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo ucwords($current_reference['reference_type']); ?></td>
                                        <td><?php echo ucwords($current_reference['reference_title']); ?></td>
                                        <td><?php echo ucwords($current_reference['reference_name']); ?></td>
                                        <td><?php echo ucwords($current_reference['reference_phone']); ?></td>
                                        <td><?php echo ucwords($current_reference['reference_email']); ?></td>
                                    </tr>
                                    <?php $count++; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="universal-form-style-v2 equipment-types">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-6">
                                <h2 class="pull-left">Add New Reference</h2>
                            </div>
                            <div class="col-xs-6">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <hr />
                            </div>
                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">                            
                        <label>Reference Type</label>
                        <div class="hr-select-dropdown">
                            <select id="select_reference_type" name="select_reference_type" class="invoice-fields">
                                <option value="">Please Select</option>
                                <option value="work" selected="selected">Work Reference</option>
                                <option value="personal">Personal Reference</option>
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
                    <input class="reference_type" type="hidden" value="" id="reference_type" name="reference_type" />
                    <input type="hidden" value="" id="sid" name="sid" />
                    <input type="hidden" value="" id="company_sid" name="company_sid" />
                    <input type="hidden" value="" id="user_sid" name="user_sid" />
                    <input type="hidden" value="" id="users_type" name="users_type" />
                    <input type="hidden" value="save_work_reference" id="perform_action" name="perform_action" />

                    <ul>
                        <li class="form-col-50-left">
                            <label for="reference_title">Title<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_title" name="reference_title" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="reference_name">Name<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_name" name="reference_name" />
                        </li>
                        <li class="form-col-50-left">
                            <label for="organization_name">Organization Name<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="organization_name" name="organization_name" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="department_name">Department Name </label>
                            <input type="text" class="invoice-fields" value="" id="department_name" name="department_name" />
                        </li>
                        <li class="form-col-50-left">
                            <label for="branch_name">Branch Name</label>
                            <input type="text" class="invoice-fields" value="" id="branch_name" name="branch_name" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="reference_relation">Relationship<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_relation" name="reference_relation" />
                        </li>
                        <li class="form-col-50-left">
                            <label for="work_period_start">Worked From<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields start-date" value="" id="work_period_start" name="work_period_start" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="work_period_end">Worked Till</label>
                            <input type="text" class="invoice-fields end-date" value="" id="work_period_end" name="work_period_end" />
                        </li>
                        <li class="form-col-50-left">
                            <label for="reference_email">Email<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_email" name="reference_email" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="reference_phone">Telephone<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_phone" name="reference_phone" />
                        </li>


                        <li class="form-col-50-left" style="height: 175px;">
                            <label for="">Other Information</label>
                            <textarea type="text" class="invoice-fields-textarea" value="" id="work_other_information" name="work_other_information" ></textarea>
                        </li>
                        <li class="form-col-50-right" style="height: 175px;">
                            <div class="questionair_radio_container">
                                <label>Best Time to Call<span class="staric">*</span></label>
                                <input name="best_time_to_call" value="" type="radio" style="visibility: hidden;">
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="morning" id="best_time_to_call_morning" name="best_time_to_call"  />
                                <label for="best_time_to_call_morning">Morning</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="afternoon" id="best_time_to_call_afternoon" name="best_time_to_call"  />
                                <label for="best_time_to_call_afternoon">Afternoon</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="evening" id="best_time_to_call_evening" name="best_time_to_call"  />
                                <label for="best_time_to_call_evening">Evening</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="night" id="best_time_to_call_night" name="best_time_to_call"  />
                                <label for="best_time_to_call_night">Night</label>
                            </div>

                        </li>



                        <li class="form-col-50-left">
                            <button type="button" class="submit-btn" value="Save" onclick="fSaveWorkReference();" >Save</button>

                        </li>
                        <li class="form-col-50-right"></li>
                    </ul>
                </form>
                <form id="form_reference_type_personal" method="post">
                    <div class="tagline-heading"><h4>Personal Reference Details</h4></div>
                    <input class="reference_type"  type="hidden" value="" id="reference_type" name="reference_type" />
                    <input type="hidden" value="" id="sid" name="sid" />
                    <input type="hidden" value="" id="company_sid" name="company_sid" />
                    <input type="hidden" value="" id="user_sid" name="user_sid" />
                    <input type="hidden" value="" id="users_type" name="users_type" />
                    <input type="hidden" value="save_personal_reference" id="perform_action" name="perform_action" />

                    <ul>
                        <li class="form-col-50-left">
                            <label for="reference_title">Title<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_title" name="reference_title" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="reference_name">Name<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_name" name="reference_name" />
                        </li>

                        <li class="form-col-50-left">
                            <label for="reference_relation">Relationship<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_relation" name="reference_relation" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="relationship_period">How Long Have You Known Him / Her?<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="relationship_period" name="relationship_period" />
                        </li>

                        <li class="form-col-50-left">
                            <label for="reference_email">Email<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_email" name="reference_email" />
                        </li>
                        <li class="form-col-50-right">
                            <label for="reference_phone">Telephone<span class="staric">*</span></label>
                            <input type="text" class="invoice-fields" value="" id="reference_phone" name="reference_phone" />
                        </li>


                        <li class="form-col-50-left" style="height: 175px;">
                            <label for="">Other Information</label>
                            <textarea type="text" class="invoice-fields-textarea" value="" id="work_other_information" name="work_other_information" ></textarea>
                        </li>
                        <li class="form-col-50-right" style="height: 175px;">
                            <div class="questionair_radio_container">
                                <label>Best Time to Call<span class="staric">*</span></label>
                                <input name="best_time_to_call" value="" type="radio" style="visibility: hidden;">
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="morning" id="best_time_to_call_morning" name="best_time_to_call"  />
                                <label for="best_time_to_call_morning">Morning</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="afternoon" id="best_time_to_call_afternoon" name="best_time_to_call"  />
                                <label for="best_time_to_call_afternoon">Afternoon</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="evening" id="best_time_to_call_evening" name="best_time_to_call"  />
                                <label for="best_time_to_call_evening">Evening</label>
                            </div>
                            <div class="questionair_radio_container">
                                <input type="radio" class="invoice-fields-radio" value="night" id="best_time_to_call_night" name="best_time_to_call"  />
                                <label for="best_time_to_call_night">Night</label>
                            </div>
                        </li>
                        <li class="form-col-50-left">
                            <button type="button" class="submit-btn" value="Save" onclick="fSavepersonalReference();" >Save</button>
                        </li>
                        <li class="form-col-50-right"></li>
                    </ul>
                </form>
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

                                        $('#select_reference_type').on('change', function () {
                                            var CurrentType = $(this).val();
                                            console.log(CurrentType);
                                            $('.reference_type').val(CurrentType);
                                            switch (CurrentType) {
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


                                    function fEnableDatePickerAndSetDateLimits(startDateInputId, endDateInputId) {
                                        $('#' + startDateInputId).datepicker({
                                            changeYear: true,
                                            dateFormat: 'mm-dd-yy',
                                            onSelect: function (selected) {
                                                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
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
                                                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
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
                                                work_period_end: {
                                                },
                                                reference_email: {
                                                    required: true,
                                                    pattern: /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                                                },
                                                reference_phone: {
                                                    required: true,
                                                    minlength: 2,
                                                    maxlength: 15,
                                                    pattern: /^[0-9\-]+$/
                                                },
                                                work_other_information: {
                                                    minlength: 2,
                                                    maxlength: 300,
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
                                                reference_email: {
                                                    required: 'Email is Required.',
                                                    pattern: 'Must be a valid email address.'
                                                },
                                                reference_phone: {
                                                    required: 'Reference Telephone Number is Required.',
                                                    minlength: 'Reference Telephone Number must be atleast 8 digits long',
                                                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                                                    pattern: 'Reference Telephone Number can contain Numbers and - only.'
                                                },
                                                work_other_information: {
                                                    minlength: 'Other Information must be atleast 2 characters long.',
                                                    maxlength: 'Other Information can be maximum 300 characters long.',
                                                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                                                },
                                                best_time_to_call: {
                                                    required: 'You must specify best time to call.'
                                                }

                                            }
                                        });
                                    }
                                    function fSaveWorkReference() {
                                        fValidateWorkReference();
                                        if ($('#form_reference_type_work').valid()) {
                                            $('#form_reference_type_work').submit();
                                        }
                                    }



                                    function fValidatepersonalReference() {
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
                                                relationship_period: {
                                                    required: true,
                                                    minlength: 2,
                                                    maxlength: 100,
                                                    pattern: /^[A-Za-z0-9\s\.']+$/
                                                },
                                                reference_email: {
                                                    required: true,
                                                    pattern: /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                                                },
                                                reference_phone: {
                                                    required: true,
                                                    minlength: 2,
                                                    maxlength: 15,
                                                    pattern: /^[0-9\-]+$/
                                                },
                                                work_other_information: {
                                                    minlength: 2,
                                                    maxlength: 300,
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
                                                relationship_period: {
                                                    required: 'Relationship Period is required.',
                                                    minlength: 'Relationship Period must be atleast 2 characters long.',
                                                    maxlength: 'Relationship Period can be maximum 100 chracters long.',
                                                    pattern: 'Relationship Period can contain Numbers alphabets and blank spaces only.'
                                                },
                                                reference_email: {
                                                    required: 'Email is Required.',
                                                    pattern: 'Must be a valid email address.'
                                                },
                                                reference_phone: {
                                                    required: 'Reference Telephone Number is Required.',
                                                    minlength: 'Reference Telephone Number must be atleast 2 digits long',
                                                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                                                    pattern: 'Reference Telephone Number can contain Numbers and - only.'
                                                },
                                                work_other_information: {
                                                    minlength: 'Other Information must be atleast 2 characters long.',
                                                    maxlength: 'Other Information can be maximum 300 characters long.',
                                                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                                                },
                                                best_time_to_call: {
                                                    required: 'You must specify best time to call.'
                                                }

                                            }
                                        });
                                    }
                                    function fSavepersonalReference() {
                                        fValidatepersonalReference();

                                        if ($('#form_reference_type_personal').valid()) {
                                            $('#form_reference_type_personal').submit();
                                        }
                                    }


                                    function fValidateOtherReference() {
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
                                                reference_email: {
                                                    required: true,
                                                    pattern: /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                                                },
                                                reference_phone: {
                                                    required: true,
                                                    minlength: 2,
                                                    maxlength: 15,
                                                    pattern: /^[0-9\-]+$/
                                                },
                                                work_other_information: {
                                                    minlength: 2,
                                                    maxlength: 300,
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
                                                reference_email: {
                                                    required: 'Email is Required.',
                                                    pattern: 'Must be a valid email address.'
                                                },
                                                reference_phone: {
                                                    required: 'Reference Telephone Number is Required.',
                                                    minlength: 'Reference Telephone Number must be atleast 2 digits long',
                                                    maxlength: 'Reference Telephone Number can only be 15 digits long.',
                                                    pattern: 'Reference Telephone Number can contain Numbers and - only.'
                                                },
                                                work_other_information: {
                                                    minlength: 'Other Information must be atleast 2 characters long.',
                                                    maxlength: 'Other Information can be maximum 300 characters long.',
                                                    pattern: 'Other Information can contain Alphabets, Numbers and blank space only.'
                                                },
                                                best_time_to_call: {
                                                    required: 'You must specify best time to call.'
                                                }

                                            }
                                        });
                                    }
                                    function fSaveOtherReference() {
                                        fValidateOtherReference();
                                        if ($('#form_reference_type_other').valid()) {
                                            $('#form_reference_type_other').submit();
                                        }

                                    }

</script>