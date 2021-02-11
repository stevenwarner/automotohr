<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                    </div>
                    <form id="form_save_video" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" id="perform_action" name="perform_action" value="save_training_session_info" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="universal-form-style-v2">
                                    <ul>
                                        <li class="form-col-100">
                                            <?php $field_name = 'session_topic'?>
                                            <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? $training_session[$field_name] : ''; ?>
                                            <?php echo form_label('Session Topic <span class="hr-required">*</span>', $field_name); ?>
                                            <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '" data-rule-required="true"'); ?>
                                            <?php echo form_error($field_name); ?>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <?php $field_name = 'session_description'?>
                                            <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? $training_session[$field_name] : ''; ?>
                                            <?php echo form_label('Session Description', $field_name); ?>
                                            <?php echo form_textarea($field_name, set_value($field_name, $temp), 'class="invoice-fields autoheight" id="' . $field_name . '"'); ?>
                                            <?php echo form_error($field_name); ?>
                                        </li>
                                        <li class="form-col-100">
                                            <?php $field_name = 'session_location'?>
                                            <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? $training_session[$field_name] : ''; ?>
                                            <?php echo form_label('Session Address <span class="hr-required">*</span>', $field_name); ?>
                                            <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '" data-rule-required="true"'); ?>
                                            <?php echo form_error($field_name); ?>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <?php $field_name = 'session_date'?>
                                                    <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? date('m-d-Y', strtotime($training_session[$field_name])) : ''; ?>
                                                    <?php echo form_label('Date <span class="hr-required">*</span>', $field_name); ?>
                                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields datepicker" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                    <?php echo form_error($field_name); ?>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php $field_name = 'session_start_time'?>
                                                    <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? date('H:i', strtotime($training_session[$field_name])) : '00:00'; ?>
                                                    <?php echo form_label('Start Time <span class="hr-required">*</span>', $field_name); ?>
                                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                    <?php echo form_error($field_name); ?>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php $field_name = 'session_end_time'?>
                                                    <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? date('H:i', strtotime($training_session[$field_name])) : '23:59'; ?>
                                                    <?php echo form_label('End Time <span class="hr-required">*</span>', $field_name); ?>
                                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                    <?php echo form_error($field_name); ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <?php $field_name = 'employees_assigned_to'?>
                                            <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? $training_session[$field_name] : 'all'; ?>
                                            <?php echo form_label('Assigned To Employees', $field_name); ?>
                                            <?php $default_selected = $temp == 'all' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                All
                                                <input class="employees_assigned_to" type="radio" id="employees_assigned_to_all" name="employees_assigned_to" value="all" <?php echo set_radio($field_name, 'all', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <?php $default_selected = $temp == 'specific' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                Specific
                                                <input class="employees_assigned_to" type="radio" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific" <?php echo set_radio($field_name, 'specific', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </li>
                                        <!--<input class="employees_assigned_to" type="hidden" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific"  />-->
                                        <li class="form-col-100 autoheight">
                                            <?php $field_name = 'employees_assigned_sid'?>
                                            <?php echo form_label('Assigned To Employees', $field_name); ?>
                                            <div class="hr-select-dropdown">
                                                <select data-rule-required="true" name="employees_assigned_sid[]" id="employees_assigned_sid" multiple="multiple" >
                                                    <option value="">Please Select</option>
                                                    <?php if(!empty($employees)) { ?>
                                                        <?php foreach($employees as $employee) { ?>
                                                            <option <?php echo set_select($field_name, $employee['sid'], in_array($employee['sid'], $selected_employees)); ?>  value="<?php echo $employee['sid']; ?>" ><?php echo $employee['first_name'] . ' ' . $employee['last_name']?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <?php $field_name = 'online_video_sid'?>
                                            <?php echo form_label('Online Videos', $field_name); ?>
                                            <div class="hr-select-dropdown">
                                                <select name="online_video_sid[]" id="online_video_sid" multiple="multiple">
                                                    <option value="">Please Select</option>
                                                    <?php if(!empty($videos)) { ?>
                                                        <?php foreach($videos as $video) { ?>
                                                            <option <?php echo set_select($field_name, $video['sid'], in_array($video['sid'], $selected_videos)); ?>  value="<?php echo $video['sid']; ?>" ><?php echo $video['video_title']?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </li>
                                        <input type="hidden" name="form-change" id="form-change" value="0"/>

                                        <!--
                                        <li class="form-col-100 autoheight">
                                            <?php $field_name = 'applicants_assigned_to'?>
                                            <?php $temp = isset($training_session[$field_name]) && !empty($training_session[$field_name]) ? $training_session[$field_name] : 'all'; ?>
                                            <?php echo form_label('Assigned To Onboarding Employees', $field_name); ?>
                                            <?php $default_selected = $temp == 'all' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                All
                                                <input class="applicants_assigned_to" type="radio" id="applicants_assigned_to_all" name="applicants_assigned_to" value="all" <?php echo set_radio($field_name, 'all', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <?php $default_selected = $temp == 'specific' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                Specific
                                                <input class="applicants_assigned_to" type="radio" id="applicants_assigned_to_specific" name="applicants_assigned_to" value="specific" <?php echo set_radio($field_name, 'specific', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </li>
                                        -->
                                        <input class="applicants_assigned_to" type="hidden" id="applicants_assigned_to_specific" name="applicants_assigned_to" value="specific"  />
<!--                                        <li class="form-col-100 autoheight">-->
<!--                                            --><?php //$field_name = 'applicants_assigned_sid'?>
<!--                                            --><?php //echo form_label('Assigned To Onboarding Employees', $field_name); ?>
<!--                                            <div class="hr-select-dropdown">-->
<!--                                                <select data-rule-required="true" name="applicants_assigned_sid[]" id="applicants_assigned_sid" multiple="multiple" >-->
<!--                                                    <option value="">Please Select</option>-->
<!--                                                    --><?php //if(!empty($applicants)) { ?>
<!--                                                        --><?php //foreach($applicants as $applicant) { ?>
<!--                                                            <option --><?php //echo set_select($field_name, $applicant['sid'], in_array($applicant['sid'], $selected_applicants)); ?><!-- value="--><?php //echo $applicant['sid']; ?><!--" >--><?php //echo $applicant['first_name'] . ' ' . $applicant['last_name']?><!--</option>-->
<!--                                                        --><?php //} ?>
<!--                                                    --><?php //} ?>
<!--                                                </select>-->
<!--                                            </div>-->
<!--                                        </li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="<?php echo base_url('learning_center/training_sessions'); ?>" class="btn black-btn" type="submit">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#form_save_video').validate({
            ignore: '[disabled=disabled]'
        });

        $('select[multiple]').chosen();

        $('.employees_assigned_to').on('click', function () {
            if ($(this).prop('checked') == true) {
                var value = $(this).val();
                if (value == 'all') {
                    console.log('All');
                    $('#employees_assigned_sid').prop('disabled', true).trigger("chosen:updated");

                } else {
                    console.log('specific');
                    $('#employees_assigned_sid').prop('disabled', false).trigger("chosen:updated");
                }
            }

            $('#form_save_video').valid();
        });

        $('#online_video_sid').change(function(){
            $('#form-change').val(1);
        });
        $('#session_date').change(function(){
            $('#form-change').val(1);
        });
        $('#session_start_time').change(function(){
            $('#form-change').val(1);
        });
        $('#session_end_time').change(function(){
            $('#form-change').val(1);
        });
        $('#session_location').change(function(){
            $('#form-change').val(1);
        });
        $('#session_description').change(function(){
            $('#form-change').val(1);
        });
        $('#session_topic').change(function(){
            $('#form-change').val(1);
        });

        $('.applicants_assigned_to').on('click', function () {
            if ($(this).prop('checked') == true) {
                var value = $(this).val();
                if (value == 'all') {
                    console.log('All');
                    $('#applicants_assigned_sid').prop('disabled', true).trigger("chosen:updated");
                } else {
                    console.log('specific');
                    $('#applicants_assigned_sid').prop('disabled', false).trigger("chosen:updated");
                }
            }

            $('#form_save_video').valid();
        });

        $('input[type=radio]:checked').trigger('click');

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        });

        $('#session_start_time').datetimepicker({
            datepicker: false,
            format: 'H:i',
            //allowTimes: func_get_allowed_times(),
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#session_end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('#session_end_time').datetimepicker({
            datepicker: false,
            format: 'H:i',
            //allowTimes: func_get_allowed_times(),
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#session_start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });
    });
</script>