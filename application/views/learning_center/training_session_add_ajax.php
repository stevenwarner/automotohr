<?php
    $this->config->load('calendar_config');
    $calendar_opt = $this->config->item('calendar_opt');
    // _e($training_session);

    $session_topic = isset($training_session) ? $training_session['session_topic'] : '';
    $session_description = isset($training_session) ? $training_session['session_description'] : '';
    $session_location    = isset($training_session) ? $training_session['session_location'] : '';
    $session_date        = isset($training_session) ? $training_session['session_date'] : date('m-d-Y', strtotime('+1 day'));
    $session_start_time  = isset($training_session) ? $training_session['session_start_time'] : $calendar_opt['default_start_time'];
    $session_end_time    = isset($training_session) ? $training_session['session_end_time'] : $calendar_opt['default_end_time'];

    if(isset($training_session)){
        $old_date = $training_session['session_date'];
        $session_date = reset_datetime(array(
            'datetime' => $old_date.$training_session['session_start_time'],
            'from_format' => 'm-d-Yh:i A',
            'format' => 'm-d-Y',
            '_this' => $this
        ));
        $session_start_time = reset_datetime(array(
            'datetime' => $old_date.$training_session['session_start_time'],
            'from_format' => 'm-d-Yh:i A',
            'format' => 'h:iA',
            '_this' => $this
        ));
        $session_end_time = reset_datetime(array(
            'datetime' => $old_date.$training_session['session_end_time'],
            'from_format' => 'm-d-Yh:i A',
            'format' => 'h:iA',
            '_this' => $this
        ));
    }

    $allChecked = 'checked="true"';
    $specificChecked = '';
    $noneChecked = '';
    $selected_videos = $selected_employees = array();

    if(isset($training_session) && $training_session['employees_assigned_to'] == 'all') $allChecked = 'checked="true"';
    if(isset($training_session) && $training_session['employees_assigned_to'] == 'specific') $specificChecked = 'checked="true"';
    if(isset($training_session) && count($training_session['employees']) != count($employees)){
        $allChecked = '';
        $specificChecked = 'checked="true"';
    }
    if(isset($training_session) && count($training_session['employees']) == count($employees)){
        $allChecked = 'checked="true"';
        $specificChecked = '';
    }

    if(isset($training_session) && $allChecked == '') {
        if (!empty($training_session['employees'])) {
            foreach ($training_session['employees'] as $key => $value) {
                $selected_employees[$value['user_sid']] = true;
            }
        } else {
            $specificChecked = '';
            $noneChecked = 'checked="true"';
        }
    }

    if(isset($training_session) && $training_session['online_video_sid'] != '') {
        $selected_videos = explode(',', $training_session['online_video_sid']);
    }
?>

<!-- Loader -->
<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait...</div>
    </div>
</div>

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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                    </div>
                    <form id="js-training-session" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" id="perform_action" name="perform_action" value="save_training_session_info" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                        <!--  -->
                        <div class="form-group">
                            <label>Session Topic <span class="hr-required">*</span></label>
                            <input type="text" class="form-control invoice-fields input-lg" id="session_topic" data-rule-required="true" value="<?=$session_topic;?>">
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group autoheight">
                            <label>Session Description</label>
                            <textarea class="form-control invoice-fields autoheight input-lg" id="session_description" rows="8"><?=$session_description;?></textarea>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <label>Session Address</label>
                            <div class="row" style="padding-top: 10px;">
                                <div class="col-xs-12">
                                    <label class="control control--radio">
                                        <span id="label_address_type">Address</span>
                                        <input id="js-address-input" value="new" class="js-address-type" name="address_type" type="radio" checked="true" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;
                                    &nbsp;
                                    <label class="control control--radio ">
                                        Company Addresses
                                        <input id="js-address-select" value="saved" class="js-address-type" name="address_type" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <br />

                            <div id="js-address-input-box" class="row">
                               <div class="col-xs-12">
                                   <div class="form-group autoheight">
                                       <input class="form-control input-lg" id="js-address-text" type="text" data-rule-required="true" value="<?=$session_location;?>" />
                                   </div>
                               </div>
                            </div>
                            <div id="js-address-select-box" class="row"  style="display: none;">
                               <div class="col-xs-12">
                                   <div class="form-group autoheight">
                                       <div class="select">
                                           <select class="form-control input-lg" name="address" id="js-address-list"></select>
                                       </div>
                                   </div>
                               </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Date <span class="hr-required">*</span></label>
                                    <input type="text" readonly="true" class="form-control invoice-fields datepicker input-lg" id="session_date" data-rule-required="true" value="<?=$session_date;?>">
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <label>Start Time <span class="hr-required">*</span></label>
                                    <input type="text" readonly="true" class="form-control invoice-fields input-lg" id="session_start_time" data-rule-required="true" value="<?=$session_start_time;?>">
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <label>End Time <span class="hr-required">*</span></label>
                                    <input type="text" readonly="true" class="form-control invoice-fields input-lg" id="session_end_time" data-rule-required="true"  value="<?=$session_end_time;?>">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <label>Assigned To Employees</label> <br />
                            <label class="control control--radio" style="margin-top:10px;">
                                All
                                <input class="employees_assigned_to" type="radio" id="employees_assigned_to_all" name="employees_assigned_to" value="all" <?=$allChecked;?> />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                Specific
                                <input class="employees_assigned_to" type="radio" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific" <?=$specificChecked;?>  />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                None
                                <input class="employees_assigned_to" type="radio" id="employees_assigned_to_none" name="employees_assigned_to" value="none" <?=$noneChecked;?>  />
                                <div class="control__indicator"></div>
                            </label>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <div class="">
                                <select data-rule-required="true" name="employees_assigned_sid[]" id="employees_assigned_sid" multiple="multiple"  data-placeholder="Select employee(s)">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($employees)) { ?>
                                        <?php foreach($employees as $employee) { ?>
                                            <option <?=isset($selected_employees[$employee['sid']]) ? 'selected="selected"' : '';?> value="<?=$employee['sid'];?>" ><?=remakeEmployeeName($employee);?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label id="js-non-employee-heading">Non Employee <span id="none_employee_title">Participant(s)</span></label>
                                </div>
                            </div>
                            <div class="row">
                                <div id="js-extra-interviewers-box" class="col-xs-12"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <label>Online Videos</label>
                            <div class="hr-select-dropdown">
                                <select name="online_video_sid[]" id="online_video_sid" multiple="multiple" data-placeholder="Select videos">
                                    <?php if(!empty($videos)) { ?>
                                        <?php foreach($videos as $video) { ?>
                                            <option <?=in_array($video['sid'], $selected_videos) ? 'selected="selected"' : '';?> value="<?php echo $video['sid']; ?>" ><?php echo $video['video_title']?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Allow Reminder:
                                            <input id="js-reminder-check" type="checkbox" <?=isset($training_session) && $training_session['duration_check'] == 1 ? 'checked="checked"' : '';?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight" id="js-reminder-box" <?=isset($training_session) && $training_session['duration_check'] == 1 ? '' : 'style="display: none;"';?>>
                                        <label>Reminder Duration <span class="hr-required">*</span></label>
                                        <select class="form-control input-lg" id="js-reminder-select">
                                            <option  <?=isset($training_session) && $training_session['duration'] == 15 ? 'selected="true"' : '';?> value="15">15 Minutes</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 30 ? 'selected="true"' : '';?> value="30">30 Minutes</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 45 ? 'selected="true"' : '';?> value="45">45 Minutes</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 60 ? 'selected="true"' : '';?> value="60">60 Minutes</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 90 ? 'selected="true"' : '';?> value="90">90 Minutes</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 120 ? 'selected="true"' : '';?> value="120">2 Hours</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 240 ? 'selected="true"' : '';?> value="240">4 Hours</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 360 ? 'selected="true"' : '';?> value="360">6 Hours</option>
                                            <option  <?=isset($training_session) && $training_session['duration'] == 480 ? 'selected="true"' : '';?> value="480">8 Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <?php if(isset($training_session)) { ?>
                        <!--  -->
                        <div class="form-group">
                            <!-- Reminder Emails -->
                            <div class="row" id="js-reminder-email-wrap">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Send Reminder Email:
                                            <input id="js-reminder-email-check" type="checkbox" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight" id="js-reminder-email-box" style="display: none;">
                                        <?php if(isset($training_session) && sizeof($training_session['employees'])) {
                                            foreach($training_session['employees'] as $key => $value) { ?>
                                                <div class="col-xs-6">
                                                    <label class="control control--checkbox">
                                                        <?=$value['full_name'];?>
                                                        <input data-value="<?=$value['full_name'];?>" data-id="<?=$value['user_sid'];?>" data-type="interviewer" value="<?=$value['email'];?>" name="reminder_emails[]" checked="checked" type="checkbox" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                        <?php }} ?>
                                        <div class="col-sm-12">
                                            <br />
                                            <button type="button" class="btn btn-success pull-right js-reminder-email-btn">Send Reminder Email</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                <button class="btn btn-success" type="submit"><?=isset($training_session) ? 'Update' : 'Save';?></button>
                                <?php if(isset($training_session)) { ?>
                                <input type="hidden" id="js-session-id" value="<?=$training_session['sid'];?>">
                                <?php } ?>
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
    $(function(){
        var address_list_array = [],
        addressXHR = null,
        employee_list = <?=@json_encode($employees);?>,
        email_regex = new RegExp(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/),
        changeClass = '#online_video_sid, #session_date, #session_start_time, #session_location, #session_description, #session_topic'
        ;

        CKEDITOR.replace('session_description');

        $('#js-extra-interviewers-box').html(get_extra_interviewer_row());

        $('#js-training-session').validate({ ignore: '[disabled=disabled]' });

        $('.employees_assigned_to').on('click', function () {

            //
            $('#employees_assigned_sid').prop('disabled', $(this).val() == 'specific' ? false : true);

            // $('#js-training-session').valid();
        });

        $('input[type=radio]:checked').trigger('click');

        $('#employees_assigned_sid').select2({ closeOnSelect: false });

        $('#online_video_sid').chosen({ placeholder: "Select videos", closeOnSelect: false});

        $('.datepicker').datepicker({ dateFormat: 'mm-dd-yy' });

        $('#session_start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?=$calendar_opt['time_duration'];?>,
            onChangeDateTime: function (dp, $input) {
                $('#session_end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('#session_end_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?=$calendar_opt['time_duration'];?>,
            onChangeDateTime: function (dp, $input) {
                $('#session_start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });

        // Reminder check toggle
        $(document).on('click', '#js-reminder-check', function() {
            $('#js-reminder-box').hide(0);
            if($(this).prop('checked')) $('#js-reminder-box').show(0);
        });

        // Reminder email check toggle
        $(document).on('click', '#js-reminder-email-check', function() {
            $('#js-reminder-email-box').hide(0);
            if($(this).prop('checked')) $('#js-reminder-email-box').show(0);
        });

        // Address check toggle
        $('.js-address-type').on('click', function() {
            $('#js-address-select-box, #js-address-input-box').hide(0);
            if($(this).val() == 'new') $('#js-address-input-box').show(0);
            else $('#js-address-select-box').show(0);
        });

        // Get adress event
        $('#js-address-select').click(get_address);

        // Add extra interviewer event
        $(document).on('click', '#btn_add_participant', function () {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_participant_0').clone();
            //
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).attr('id', 'external_participant_' + random_id);
            $(new_row).addClass('external_participants');
            $(new_row).attr('data-id', random_id);
            $(new_row).find('input.external_participants_name').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-email', true);
            $(new_row).find('input').each(function () {
                var name = $(this).attr('name').toString();
                var id = $(this).attr('id').toString();
                name = name.split('0').join(random_id);
                id = id.split('0').join(random_id);
                $(this).attr('name', name);
                $(this).attr('id', id);
            });
            $('#js-extra-interviewers-box').append(new_row);
        });

        // Remove extra interviewer event
        $(document).on('click', '.btn_remove_participant', function () { $($(this).closest('.external_participants').get()).remove(); });

        //
        $('#js-training-session').submit(function(e) {
            e.preventDefault();
            var obj = request_template();
            $('#js-address-text-error').remove();
            $('#js-address-text').removeClass('error');
            $('#session_topic-error').remove();
            $('#session_topic').removeClass('error');
            //
//            if(obj.address == ''){
//                $('#js-address-text').addClass('error').after('<label id="js-address-text-error" class="error" for="js-address-text">This field is required.</label>');
//                return false;
//            }
            if(obj.title == ''){
                $('#session_topic').addClass('error').after('<label id="session_topic-error" class="error" for="session_topic">This field is required.</label>');
                return false;
            }

            var ep = $.parseJSON(func_get_external_users_data());
            if(ep.length > 1){
                var is_error = false;
                $.each(ep, function(i, v) {
                    email_regex.lastIndex = 0;
                    if(v.name.trim() != '' || v.email.trim() != ''){
                        if(v.name.trim() == ''){
                            alertify.alert("Name is missing for non-employee participants.( "+(++i)+" row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if(v.email.trim() == ''){
                            alertify.alert("Email is missing for non-employee participants.( "+(++i)+" row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if(!email_regex.test(v.email.trim())){
                            alertify.alert("Invalid email is provided for non-employee participants.( "+(++i)+" row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                    }
                });

                if(is_error) return false;
            }else if( ep.length == 1){
                if(ep[0].name.trim() != '' || ep[0].email.trim() != ''){
                    if(ep[0].name.trim() == ''){
                        alertify.alert("Name is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if(ep[0].email.trim() == ''){
                        alertify.alert("Email is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if(!email_regex.test(ep[0].email.trim())){
                        alertify.alert("Invalid email is provided for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                }
            }
            console.log(obj);
            //
            obj.training_session_type = '';
            //
            if(obj.interviewer_type == 'all'){
                var tmp = [];
                employee_list.map(function(v){ tmp.push(v.sid); });
                obj.interviewer = tmp.join(',');
            }else if(obj.interviewer_type == 'none'){
                obj.interviewer = [];
                obj.interviewer_type = 'specific';
                obj.training_session_type = 'none';
            }else{
                obj.interviewer = obj.interviewer.join(',');
                obj.interviewer_type = 'specific';
            }

            loader(true);

            $.post("<?=base_url('calendar/event-handler');?>",
                obj,
                function( resp ) {
                if(resp.Status === false && resp.Redirect === true) window.location.reload();
                if(resp.Status === false) {
                    alertify.alert(resp.Response, flush_alertify_cb); return false;
                }
                alertify.alert(resp.Response, function(){
                    window.location.href = "<?=base_url('learning_center/training_sessions');?>";
                });

            });
        });

        <?php if(isset($training_session)) { ?>
        // Send reminder emails
        // to interviewers, non-employee interviewers
        // applicant/employee
        $(document).on('click', '.js-reminder-email-btn', function(e) {
            e.preventDefault();
            var reminder_emails = [];
            $('input[name="reminder_emails[]"]:checked').map(function(){
                var obj = {
                    type: $(this).data('type'),
                    id: $(this).data('id'),
                    value: $(this).data('value'),
                    email_address: $(this).val().trim()
                };
                reminder_emails.push(obj);
            });
            // Check for empty array
            if(reminder_emails.length === 0){
                alertify.alert("Error! Please, select atleast one email to send reminder.", flush_alertify_cb); return false;
            }
            //
            loader(true);

            $.post("<?=base_url();?>calendar/event-handler", {
                emails: reminder_emails,
                event_id: <?=$training_session['event_sid'];?>,
                action: 'send_reminder_emails'
            }, function(resp) {
                if(resp.Status === false && resp.Redirect === true) window.location.reload();
                loader();
                alertify.alert(resp.Response, flush_alertify_cb);
            });
        });
        <?php } ?>

        loader();

        function request_template(){
            var obj =  {
                action: 'save_event',
                title: $('#session_topic').val().trim(),
                description: $.trim(CKEDITOR.instances.session_description.getData()),
                address: $('#js-address-select').prop('checked') !== true ? $('#js-address-text').val() : $('#js-address-list').val(),
                date: $('#session_date').val(),
                eventstarttime: $('#session_start_time').val(),
                eventendtime: $('#session_end_time').val(),
                interviewer: $('#employees_assigned_sid').val(),
                interviewer_show_email: '',
                interviewer_type: $('#employees_assigned_to_all').prop('checked') === true ? 'all' : $('#employees_assigned_to_specific').prop('checked') === true ? 'specific' : 'none',
                external_participants: func_get_external_users_data(),
                video_ids: $('#online_video_sid').val() != null ? $('#online_video_sid').val().join(',') : '',
                category: 'training-session',
                applicant_sid: <?=$employer_sid;?>,
                employee_sid: <?=$employer_sid;?>,
                users_type: 'employee',
                users_phone: '',
                applicant_jobs_list: '',
                duration: $('#js-reminder-select').val() == '' ? 15 : $('#js-reminder-select').val(),
                reminder_flag: $('#js-reminder-check').prop('checked') === true ? 1 : 0,
                messageCheck: 0,
                recur: JSON.stringify({})
            };
            <?php if(isset($training_session)) { ?>
                obj.lcts = <?=$training_session['sid'];?>;
                obj.action = 'update_event';
                obj.sid = <?=$training_session['event_sid'];?>;
            <?php } ?>
            return obj;
        }

        // Get company addresses via ajax
        function get_address(){
            if(address_list_array.length > 0) {
                $('#js-address-list').html(address_list_array.map(function(v){
                    return '<option value="'+ v +'">'+ v +'</option>';
                }));
                return true;
            }

            loader(true);
            $('.btn').addClass('disabled');
            $('.btn').prop('disabled', true);
            //
            if(addressXHR !== null) addressXHR.abort();

            addressXHR = $.get("<?=base_url();?>calendar/get-address/", function(resp){
                address_list_array = resp.Address;
                $('#js-address-list').html(resp.Address.map(function(v){
                    return '<option value="'+ v +'">'+ v +'</option>';
                }));

                loader();
                $('.btn').removeClass('disabled');
                $('.btn').prop('disabled', false);

                addressXHR = null;
            });
        }

        // Get added external partipents
        function func_get_external_users_data() {
            var names = $('.external_participants_name'),
            emails = $('.external_participants_email'),
            show_emails = $('.external_participants_show_email'),
            data = [];

            for (iCount = 0; iCount < names.length; iCount++) {
                var person_data = {
                    'name': $(names[iCount]).val().trim(),
                    'email': $(emails[iCount]).val().trim(),
                    'show_email': $(show_emails[iCount]).prop('checked') == true ? 1 : 0
                };

                data.push(person_data);
            }

            return JSON.stringify(data);
        }

        // Create extra interviewer
        // row
        function get_extra_interviewer_row(){
            var row = '';
            row += '<div id="external_participant_0" class="row external_participants">';
            row += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label>Name</label>';
            row += '            <input name="ext_participants[0][name]" id="ext_participants_0_name" type="text" class="form-control input-lg external_participants_name" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label>Email</label>';
            row += '            <input name="ext_participants[0][email]" id="ext_participants_0_email" type="email" class="form-control input-lg external_participants_email" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label class="control control--checkbox" style="margin-top: 37px;">';
            row += '                Show Email';
            row += '                <input name="ext_participants[0][show_email]" id="ext_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
            row += '                <div class="control__indicator"></div>';
            row += '            </label>';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
            row += '        <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            row += '    </div>';
            row += '</div>';
            return row;
        }

        //
        function flush_alertify_cb(){ return; }

        //
        function loader(show){ if(show === undefined) $('#my_loader').fadeOut(150); else $('#my_loader').show(0); }

        // Loads reminder email rows
        // @param a
        // Interviewers
        // @param b
        // Non-employee Interviewers
        // @param c
        // Applicant or Employee
        function generate_reminder_email_rows(a,b,c){
            var rows = '';
            rows += '<div class="row">';
            if(a.length){
                rows += '<div class="col-xs-12"><h4>Participant(s)</h4></div>';
                $.each(a, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows +=         v.value;
                    rows += '       <input data-value="'+v.value.replace(/ *\([^)]*\) */g, '')+'" data-id="'+v.id+'" data-type="interviewer" value="'+v.email_address+'" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }
            if(b.length){
                rows += '<div class="col-xs-12"><h4>Non Employee Participant(s)</h4></div>';
                $.each(b, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows +=         v.name;
                    rows += '       <input data-value="'+v.name+'" data-id="0" data-type="non-employee interviewer" value="'+v.email+'" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }

            if(c !== undefined){
                rows += '<div class="col-xs-12"><h4>'+( c.type == 'applicant' ? 'Applicant' : 'Employee' )+'</h4></div>';
                rows += '<div class="col-xs-6">';
                rows += '   <label class="control control--checkbox">';
                rows +=         c.value;
                rows += '       <input data-value="'+c.value.replace(/ *\([^)]*\) */g, '')+'" data-id="'+c.id+'" data-type="'+c.type+'" value="'+c.email_address+'" name="reminder_emails[]" class="js-reminder-input" checked="checked" type="checkbox" />';
                rows += '       <div class="control__indicator"></div>';
                rows += '   </label>';
                rows += '</div>';

            }
            rows += '    <div class="col-sm-12">';
            rows += '    <br />';
            rows += '       <button class="btn btn-success pull-right js-reminder-email-btn">Send Reminder Email</button>';
            rows += '    </div>';
            rows += '</div>';

            $('#js-reminder-email-box').html(rows);
        }

        <?php if(isset($training_session) && sizeof($training_session['external_employees'])) { ?>
        generate_extra_interviewers(<?=@json_encode($training_session['external_employees']);?>);
        // Create rows for selected extra interviewers
        // Occurs only on update event
        // @param extra_interviewers
        // Holds the data of extra interviewers
        function generate_extra_interviewers(extra_interviewers){
            var rows = '';
            //
            $.each(extra_interviewers, function(i, v) {
                var rand_id = Math.floor((Math.random() * 1000) + 1);
                rows += '<div id="external_participant_'+i+'" class="row external_participants">';
                rows += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Name</label>';
                rows += '            <input name="ext_participants['+i+'][name]" id="ext_participants_'+i+'_name" type="text" class="form-control external_participants_name" value="'+v.txt_name+'" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="ext_participants['+i+'][email]" id="ext_participants_'+i+'_email" type="email" class="form-control external_participants_email" value="'+v.email_address+'" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="ext_participants['+i+'][show_email]" '+( v.show_email == 1 ? 'checked="checked"' : '' )+' id="ext_participants_'+i+'_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                if(i == 0)
                    rows += '<button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_participant btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="'+rand_id+'"></i></button>';
                rows += '    </div>';
                rows += '</div>';
            });
            $('#js-extra-interviewers-box').html(rows);
        }
        <?php } ?>
    })
</script>

<style>
    .hr-required{ color: #ff0000; }
    .form-group{ margin-bottom: 20px !important; }
</style>
