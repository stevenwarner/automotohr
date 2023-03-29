<div class="event_create" style="display: none">
    <div class="row">
        <div class="col-xs-12">
            <div class="hr-box">
                <div class="hr-box-header">
                    <h1 class="hr-registered">Schedule Event (<?= $employer_timezone ?>)</h1>
                </div>
                <div class="hr-innerpadding form-wrp">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group autoheight">
                                        <label>Event Title <span class="required">*</span></label>
                                        <input placeholder="Event Title" name="title" id="js-event-title" class="form-control" data-rule-required="true" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group autoheight">
                                        <?php $uri_segment = $this->uri->segment(1);
                                        $users_phone = '';

                                        if ($uri_segment == 'employee_profile') {
                                            $users_type = 'Employee';

                                            if (isset($employer['PhoneNumber'])) {
                                                $users_phone = $employer['PhoneNumber'];
                                            }
                                        } else if ($uri_segment == 'applicant_profile') {
                                            $users_type = 'Applicant';

                                            if (isset($applicant_info['phone_number'])) {
                                                $users_phone = $applicant_info['phone_number'];
                                            }
                                        } ?>

                                        <label><?php echo $users_type; ?> Phone Number </label>
                                        <input name="users_phone" id="users_phone" class="form-control" data-rule-required="true" value="<?= $users_phone; ?>" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Category <span class="required">*</span></label>
                                        <input type="hidden" id="js-event-selected-type-value" name="category" value="interview" />
                                        <div class="dropdown event-category-dropdown">
                                            <button class="btn btn-default btn-block dropdown-toggle form-control" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="js-event-selected-type-area">
                                                <span id="js-event-selected-type-text"></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" id="js-event-type-area"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group  autoheight">
                                        <label>Event Timezone <span class="cs-required">*</span></label>
                                        <?= timezone_dropdown(
                                            $employer_timezone,
                                            array(
                                                'class' => 'form-control',
                                                'id' => 'event_timezone',
                                                'name' => 'event_timezone'
                                            ),
                                            'north_america'
                                        ); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Event Date <span class="required">*</span></label>
                                        <input type="text" readonly="" placeholder="Event date" name="date" class="form-control" data-rule-required="true" required="required" id="js-date" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Start Time <span class="required">*</span></label>
                                        <input name="eventstarttime" id="js-start-time" readonly="readonly" type="text" class="start_time form-control" data-rule-required="true" required="required" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>End Time <span class="required">*</span></label>
                                        <input name="eventendtime" id="js-end-time" readonly="readonly" type="text" class="end_time form-control" data-rule-required="true" required="required" />
                                    </div>
                                </div>
                            </div>

                            <!-- Interviewers -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label id="attendees_label"><span class="attendees_label"><?= $this->uri->segment(1) == 'applicant_profile' ? 'Interviewer(s)' : 'Participant(s)'; ?></span> <span class="required">*</span></label>
                                        <select data-add="1" class=" form-control" multiple id="js-interviewer" data-rule-required="true" name="interviewer[]">
                                            <?php foreach ($company_accounts as $account) { ?>
                                                <?php if ($id != $account['sid']) { ?>
                                                    <option value="<?= $account['sid'] ?>">
                                                        <?php
                                                        $timezone = !empty($account['timezone']) ? $account['timezone'] : $company_timezone;
                                                        if ($employer_id == $account['sid']) {
                                                            echo "You" . ' (' . $timezone . ')';
                                                        } else {
                                                            if ($account['is_executive_admin'] == 1) {
                                                                $employee_type = 'Executive Admin';
                                                            } else {
                                                                $employee_type = $account['access_level'];
                                                            }
                                                            echo $account['first_name'] . '&nbsp;' . $account['last_name'] . ' (' . $timezone . ')' . ' (' . $employee_type . ')';
                                                        } ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php if ($users_type == 'Applicant') { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group autoheight">
                                            <label><span class="attendees_label">Applicant Jobs List</span></label>
                                        </div>
                                        <?php if (sizeof($applicant_jobs) > 0) {
                                            // echo "<pre>"; print_r($applicant_jobs); echo '</pre>';
                                            $i = 0;
                                            foreach ($applicant_jobs as $applicant_job) {
                                                $applicant_list_sid = $applicant_job['sid'];
                                                $job_sid =  $applicant_job['job_sid'];
                                                $job_title = $applicant_job['Title'];
                                                $desired_job_title = $applicant_job['job_title'];
                                                $i++;

                                                if (!empty($job_title)) {
                                                    $title = $job_title;
                                                } else if (!empty($desired_job_title)) {
                                                    if ($desired_job_title == 'Job Not Applied') {
                                                        continue;
                                                    }
                                                    $title = $desired_job_title;
                                                } else {
                                                    continue;
                                                } ?>

                                                <div class="col-xs-6">
                                                    <label class="control control--checkbox">
                                                        <?php echo ucwords($title); ?>
                                                        <input name="applicant_jobs_list[]" class="js-applicant-job" value="<?php echo $applicant_list_sid; ?>" type="checkbox" <?php if ($i == 1) {
                                                                                                                                                                                    echo 'checked="checked"';
                                                                                                                                                                                } ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                        <?php         }
                                        } ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div id="js-interviewers-list" class="row show_email_main_container">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label>Show Email Address of The Following Users in Event Email:</label>
                                    </div>
                                    <div class="row">
                                        <?php foreach ($company_accounts as $account) {
                                            $timezone = !empty($account['timezone']) ? $account['timezone'] : $company_timezone;
                                        ?>
                                            <div id="show_email_container_0_<?php echo $account['sid']; ?>" class="col-xs-6 show_email_col">
                                                <label class="control control--checkbox">
                                                    <?php echo ucwords($account["first_name"] . ' ' . $account["last_name"]) . ' (' . $timezone . ')' . ' ( ' . ($account["is_executive_admin"] == 1 ?  'Executive Admin' : ucwords($account["access_level"])) . ' )' ?>
                                                    <input id="show_email_0_<?php echo $account['sid']; ?>" class="show_email" value="<?php echo $account['sid']; ?>" name="show_email[]" type="checkbox" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label id="js-non-employee-heading">Non Employee <span id="none_employee_title">Participant(s)</span></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="external_participants_container_0" class="col-xs-12">
                                            <div id="external_participant_0" class="row">
                                                <div class="col-xs-4">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input name="external_participants[0][name]" id="external_participants_0_name" type="text" class="form-control external_participants_name" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-5">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input name="external_participants[0][email]" id="external_participants_0_email" type="email" class="form-control external_participants_email" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-2">
                                                    <div class="form-group">
                                                        <label class="control control--checkbox margin-top-20">
                                                            Show Email
                                                            <input name="external_participants[0][show_email]" id="external_participants_0_show_email" class="external_participants_show_email" type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xs-1">
                                                    <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20 btn_add_participant"><i class="fa fa-fw fa-plus-square"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <?php if ($users_type == 'Employee' && $show_online_videos == 1) { ?>
                                <!-- Videos row -->
                                <div class="row" id="js-video-wrap" style="display: none;">
                                    <div class="col-xs-12">
                                        <div class="form-group autoheight">
                                            <label>Online Videos</label>
                                            <select class="form-control" multiple="true"></select>
                                        </div>
                                    </div>
                                </div>
                                <hr class="js-video-hr" />
                            <?php } ?>

                            <!-- Comment -->
                            <div class="row js-comment-wrap">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Comment for <span class="comment_for"><?= $this->uri->segment(1) == 'applicant_profile' ? 'Interviewer(s)' : 'Participant(s)'; ?></span>
                                            <input class="comment_check" data-event_id="0" id="js-comment-check" value="1" name="commentCheck" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="interviewer_comment_0" style="display: none">
                                        <div class="form-group autoheight">
                                            <textarea rows="5" class="form-control textarea" id="js-comment-msg" name="comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="js-comment-hr" />

                            <!-- Message -->
                            <div class="row js-message-wrap">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Message to <span class=""><?= $this->uri->segment(1) == 'applicant_profile' ? 'Applicant' : 'Employee'; ?></span>
                                            <input id="js-message-check" class="message_check" data-event_id="0" name="messageCheck" value="1" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="message_0" style="display: none">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Subject <span class="required">*</span></label>
                                                    <input class="form-control" id="js-message-subject" name="subject" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Message <span class="required">*</span></label>
                                                    <textarea rows="5" id="js-message-body" class="form-control textarea" name="message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <input type="file" id="js-message-file" name="messageFile" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="js-message-hr" />

                            <!-- Address -->
                            <div class="row">
                                <div class="col-xs-12 mb-2">
                                    <label class="control control--radio" id="lbl_new_address_0">
                                        Address
                                        <input data-event_sid="0" id="address_type_new_0" class="address_type" value="new" name="address_type" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;
                                    &nbsp;
                                    <label class="control control--radio " id="lbl_saved_address_0">
                                        Company Addresses
                                        <input data-event_sid="0" id="address_type_saved_0" class="address_type" value="saved" name="address_type" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div id="address_select_0" class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <div class="select">
                                            <select class="form-control" name="address" id="address_saved">
                                                <?php foreach ($addresses as $address) { ?>
                                                    <option value="<?php echo $address ?>"><?php echo $address ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="address_input_0" class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <input class="form-control" name="address" id="address_new" type="text" value="" />
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <!-- Meeting -->
                            <div class="row js-meeting-wrap">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Meeting Call In Details
                                            <input id="js-meeting-check" class="goto_meeting_check" data-event_id="0" value="1" name="goToMeetingCheck" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="meeting_details_0" style="display: none">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Meeting Call In Number <span class="required">*</span></label>
                                                    <input type="text" name="meetingCallNumber" id="js-meeting-call" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Meeting ID Number <span class="required">*</span></label>
                                                    <input type="text" name="meetingId" id="js-meeting-id" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Webinar/Meeting log in URL <span class="required">*</span></label>
                                                    <input type="text" name="meetingURL" id="js-meeting-url" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reminder -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Allow Reminder:
                                            <input id="js-reminder-check" type="checkbox" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight" id="js-reminder-box" style="display: none;">
                                        <label>Reminder Duration <span class="required">*</span></label>
                                        <select class="form-control" id="js-reminder-select">
                                            <option value="15">15 Minutes</option>
                                            <option value="30">30 Minutes</option>
                                            <option value="45">45 Minutes</option>
                                            <option value="60">60 Minutes</option>
                                            <option value="90">90 Minutes</option>
                                            <option value="120">2 Hours</option>
                                            <option value="240">4 Hours</option>
                                            <option value="360">6 Hours</option>
                                            <option value="480">8 Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Reoccuring events -->
                            <!-- TODO -->
                            <div class="row" id="js-reoccur-wrap">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Recuring Event
                                            <input id="js-reoccur-check" type="checkbox" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <!--  -->
                                <div id="js-reoccur-box">
                                    <!-- Type row -->
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-sm-2 autoheight">
                                            <label class="control control--radio">
                                                Daily
                                                <input type="radio" name="txt_reoccur_type" class="js-recurr-type" id="js-recurr-daily-check" value="Daily" checked="checked" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 autoheight" id="js-reoccur-box">
                                            <label class="control control--radio">
                                                Weekly
                                                <input type="radio" name="txt_reoccur_type" class="js-recurr-type" id="js-recurr-weekly-check" value="Weekly" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 autoheight" id="js-reoccur-box">
                                            <label class="control control--radio">
                                                Monthly
                                                <input type="radio" name="txt_reoccur_type" class="js-recurr-type" id="js-recurr-monthly-check" value="Monthly" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 autoheight" id="js-reoccur-box">
                                            <label class="control control--radio">
                                                Yearly
                                                <input type="radio" name="txt_reoccur_type" class="js-recurr-type" id="js-recurr-yearly-check" value="Yearly" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Event row -->
                                    <!-- TODO -->
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="cs-row-view"></div>
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

<style>
    .cs-row {
        padding-top: 30px;
    }

    .cs-row select {
        padding: 5px;
        width: 60px;
        height: 40px;
    }

    .cs-row p {
        padding-top: 7px;
    }

    .cs-row span {
        margin-left: 10px;
    }

    .cs-row span.cs-day {
        display: inline-block;
        width: 40px;
        height: 40px;
        border: 2px solid #81b431;
        text-align: center;
        line-height: 36px;
        font-weight: bold;
        font-size: 24px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        cursor: pointer;
        margin-right: 0px;
        color: #81b431;
    }

    .cs-row span.cs-day:hover,
    .cs-row span.cs-day.cs-active-day {
        background-color: #81b431;
        color: #ffffff;
    }

    .cs-row input[type="text"] {
        height: 40px !important;
        padding: 5px;
    }

    .cs-required {
        color: #cc1100;
    }
</style>

<script>
    $(document).ready(function() {
        // Set interviewers default text
        var default_interviewer_text = 'Participant(s)',
            // Set interviewers default text
            default_einterviewer_text = 'Non-employee Participant(s)',
            employee_list = <?= @json_encode($company_accounts); ?>,
            current_event_type = '',
            site_date_format = 'MM-DD-YYYY';
        $('#js-date').datepicker({
            dateFormat: 'mm-dd-yy DD',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        // $('#event_form').validate();

        $('body').on('click', '.btn_add_participant', function() {

            var random_id = Math.floor((Math.random() * 1000) + 1);

            var new_row = $('#external_participant_0').clone();



            var event_id = $('#event_id').val();

            event_id = event_id == '' || event_id == undefined || event_id == null ? 0 : event_id;


            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');

            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').removeClass('btn_add_participant').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);

            $(new_row).find('input').val('');



            $(new_row).attr('id', 'external_participant_' + random_id);

            $(new_row).addClass('external_participants');

            $(new_row).attr('data-id', random_id);



            $(new_row).find('input.external_participants_name').attr('data-rule-required', true);

            $(new_row).find('input.external_participants_email').attr('data-rule-required', true);

            $(new_row).find('input.external_participants_email').attr('data-rule-email', true);



            $(new_row).find('input').each(function() {

                var name = $(this).attr('name').toString();

                var id = $(this).attr('id').toString();

                name = name.split('0').join(random_id);

                id = id.split('0').join(random_id);

                $(this).attr('name', name);

                $(this).attr('id', id);

            });



            $('#external_participants_container_' + event_id).append(new_row);
        });

        $('body').on('click', '.btn_remove_participant', function() {
            $($(this).closest('.external_participants').get()).remove();
        });

        $('.loader').hide();
        $('#tab_loader').hide();


        $('body').on('click', '.address_type', function() {

            var event_sid = $(this).attr('data-event_sid');

            if ($(this).prop('checked') == true) {

                if ($(this).val() == 'saved') {

                    $('#address_select_' + event_sid).show();

                    $('#address_select_' + event_sid + ' select').prop('disabled', false);



                    $('#address_input_' + event_sid).hide();

                    $('#address_input_' + event_sid + ' input').prop('disabled', true);

                } else if ($(this).val() == 'new') {

                    $('#address_select_' + event_sid).hide();

                    $('#address_select_' + event_sid + ' select').prop('disabled', true);



                    $('#address_input_' + event_sid).show();

                    $('#address_input_' + event_sid + ' input').prop('disabled', false);

                }

            }
        });

        $('#address_type_new_0').trigger('click');
        $('#address_input_0').show();
        $('#address_input_0 select').prop('disabled', false);
        $('#address_select_0').hide();
        $('#address_select_0 input').prop('disabled', true);

        $(document).on('click', '.comment_check', function() {

            var checked = $(this).prop('checked');

            var event_id = $(this).attr('data-event_id');



            if (checked) {

                $('#interviewer_comment_' + event_id).show();

            } else {

                $('#interviewer_comment_' + event_id).hide();

            }
        });

        $(document).on('click', '.message_check', function() {

            var checked = $(this).prop('checked');

            var event_id = $(this).attr('data-event_id');



            if (checked) {

                $('#message_' + event_id).show();

            } else {

                $('#message_' + event_id).hide();

            }

        });

        $(document).on('click', '.goto_meeting_check', function() {

            var checked = $(this).prop('checked');

            var event_id = $(this).attr('data-event_id');



            if (checked) {

                $('#meeting_details_' + event_id).show();

            } else {

                $('#meeting_details_' + event_id).hide();

            }

        });

        $(document).on('click', '#js-reminder-check', function() {
            $('#js-reminder-box').toggle();
        });

        $('.show_email_main_container').hide();
        $('.show_email_col').hide();

        $('#js-date').val("<?= date('m-d-Y l'); ?>");
        $('#js-start-time').val(default_start_time);
        $('#js-end-time').val(default_end_time);

        load_categories(user_type, '#js-event-type-area');

        func_make_time_pickers();
        func_make_date_picker();
        //
        $('.js-recurr-type').prop('checked', false);
        $('#js-reoccur-check').prop('checked', false);
        $('#js-recurr-daily-check').prop('checked', true);
        $('#js-reoccur-box').hide(0);
        // TODO
        $('#js-interviewer').select2();
        $('#js-interviewer').trigger('change');

        // Added on: 24-04-2019
        // Loads categories
        // @param type
        // The user type
        // @param target_OBJ
        // Target el array
        function load_categories(type, target_OBJ) {
            var target = $(target_OBJ),
                rows = '',
                selected = event_type.default_applicant,
                arr = event_type.applicant;
            target.html('');
            //
            if (type == 'employee') {
                selected = event_type.default_employee;
                arr = event_type.employee;
            }
            // Sort array by asc
            arr = _.sortBy(arr);
            var cls = 'js-btn-category';
            if (edit_mode != '') cls += ' js-edit-btn-category';
            $.each(arr, function(i, v) {
                if (v == 'training-session') {
                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                        rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                    <?php } ?>
                } else {
                    rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                }
            });

            $('#js' + (edit_mode) + '-event-selected-type-value').val(selected);
            $('#js' + (edit_mode) + '-event-selected-type-text').text(event_obj[selected]);
            $('#js' + (edit_mode) + '-event-selected-type-area').css({
                'background-color': event_color_obj[selected],
                'color': '#ffffff'
            });
            target.html(rows);
        }

        // Set category on click 
        $(document).on('click', '.js-btn-category', function() {
            current_event_type = $(this).data('id');
            if (user_type === 'employee') {
                $('#js-interviewers-list').show(0);

                $('label#attendees_label').text(default_interviewer_text);
                $('#js-non-employee-heading').text(default_einterviewer_text);
                $('.js-comment-wrap, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').show(0);
                $('.js-message-hr').show(0);
                $('.js-comment-hr').show(0);

                if ($('#js-message-check').prop('checked') === false)
                    $('#js-message-box').hide(0);
                if ($('#js-meeting-check').prop('checked') === false)
                    $('#js-meeting-box').hide(0);

                $('#js-video-wrap, .js-video-hr').hide(0);

                remove_all_from_interviewers();

                // For training session
                if ($(this).data('id') == 'training-session') {
                    $('#js-interviewers-list').hide(0);

                    $('label#attendees_label').text('Assigned Attendees');
                    $('#js-non-employee-heading').text('Assigned non-employee Attendees');
                    $('.js-comment-wrap, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').hide(0);
                    $('.js-message-hr').hide(0);
                    $('.js-comment-hr').hide(0);
                    $('#js-video-wrap, .js-video-hr').show(0);

                    append_all_to_interviewers();

                }
            }
            //
            $('#js-event-selected-type-value').val($(this).data('id'));
            $('#js-event-selected-type-text').text(event_obj[$(this).data('id')]);
            $('#js-event-selected-type-area').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
        });

        if (show_recur_btn == 0) $('#js-reoccur-wrap').hide(0);

        $('#add_event_form').submit(function(e) {
            e.preventDefault();
            if (form_check()) {
                func_show_loader();
                //
                var eventOBJ = get_event_obj_template();

                eventOBJ.applicant_sid = <?= $id; ?>;
                eventOBJ.employee_sid = <?= $main_employer_id; ?>;

                eventOBJ.title = $('#js-event-title').val().trim();
                eventOBJ.users_phone = $('#users_phone').val().trim();
                eventOBJ.category = $('#js-event-selected-type-value').val().trim();
                eventOBJ.date = moment($('#js-date').val().trim(), site_date_format + " dddd").format(site_date_format);
                eventOBJ.eventstarttime = $('#js-start-time').val().trim();
                eventOBJ.eventendtime = $('#js-end-time').val().trim();

                eventOBJ.commentCheck = $('#js-comment-check').prop('checked') ? 1 : 0;
                if (eventOBJ.commentCheck === 1)
                    eventOBJ.comment = $('#js-comment-msg').val().trim();

                eventOBJ.reminder_flag = $('#js-reminder-check').prop('checked') ? 1 : 0;
                if (eventOBJ.reminder_flag === 1)
                    eventOBJ.duration = $('#js-reminder-select').val().trim();

                eventOBJ.messageCheck = $('#js-message-check').prop('checked') ? 1 : 0;
                if (eventOBJ.messageCheck === 1) {
                    eventOBJ.message = $('#js-message-body').val().trim();
                    eventOBJ.subject = $('#js-message-subject').val().trim();
                }

                eventOBJ.goToMeetingCheck = $('#js-meeting-check').prop('checked') ? 1 : 0;
                if (eventOBJ.goToMeetingCheck === 1) {
                    eventOBJ.meetingId = $('#js-meeting-id').val().trim();
                    eventOBJ.meetingURL = $('#js-meeting-url').val().trim();
                    eventOBJ.meetingCallNumber = $('#js-meeting-call').val().trim();
                }

                // Interviewers
                eventOBJ.interviewer = $('#js-interviewer').val();
                var tmp = [];
                $('#js-interviewers-list').find('input:checked').each(function() {
                    tmp.push($(this).val());
                });
                eventOBJ.interviewer_show_email = tmp.join(',');

                // Applicant job list
                tmp = [];
                $('input.js-applicant-job:checked').each(function() {
                    tmp.push($(this).val());
                });
                eventOBJ.applicant_jobs_list = tmp.join(',');
                // show_email_sids.join(',');

                var address_type = $('.address_type:checked').val();
                var address_saved = $('#address_saved').val();
                var address_new = $('#address_new').val();

                eventOBJ.address = address_type == 'new' ? address_new.trim() : address_saved;

                // TODO
                // Recurring event
                var recur_obj = {};
                if ($('#js-reoccur-check').prop('checked') === true && show_recur_btn == 1) {
                    recur_obj.recur_type = $('.js-recurr-type:checked').val();
                    recur_obj.recur_start_date = moment(eventOBJ.date + ' 23:59:59').format('YYYY-MM-DD');
                    recur_obj.recur_end_date = $('.js-infinite').prop('checked') === false ? moment($('.js-recurr-datepicker').val() + ' 23:59:59').format('YYYY-MM-DD') : null;
                    recur_obj.list = {};
                    switch (recur_obj.recur_type) {
                        case 'Daily':
                            recur_obj.list.Days = get_selected_days();
                            if (recur_obj.list.Days.length === 0) {
                                alertify.alert("Please, select atleast one day", flush_alertify_cb);
                                return false;
                            }
                            break;

                        case 'Weekly':
                            recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
                            recur_obj.list.Days = get_selected_days();
                            if (recur_obj.list.Days.length === 0) {
                                alertify.alert("Please, select atleast one day", flush_alertify_cb);
                                return false;
                            }
                            break;

                        case 'Monthly':
                            recur_obj.list.Months = get_selected_months();
                            if (recur_obj.list.Months.length === 0) {
                                alertify.alert("Please, select atleast one month", flush_alertify_cb);
                                return false;
                            }
                            recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
                            recur_obj.list.Days = get_selected_days();
                            if (recur_obj.list.Days.length === 0) {
                                alertify.alert("Please, select atleast one day", flush_alertify_cb);
                                return false;
                            }
                            break;

                        case 'Yearly':
                            recur_obj.list.Years = $('#js-recurr-year-input').val().trim();
                            recur_obj.list.Months = get_selected_months();
                            if (recur_obj.list.Months.length === 0) {
                                alertify.alert("Please, select atleast one month", flush_alertify_cb);
                                return false;
                            }
                            recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
                            recur_obj.list.Days = get_selected_days();
                            if (recur_obj.list.Days.length === 0) {
                                alertify.alert("Please, select atleast one day", flush_alertify_cb);
                                return false;
                            }
                            break;
                    }
                }

                eventOBJ.recur = JSON.stringify(recur_obj);
                //
                // Set video 
                eventOBJ.video_ids = $('#js-video-wrap select').val();

                //
                if (eventOBJ.interviewer == 'all') {
                    var tmp = [];
                    employee_list.map(function(v) {
                        tmp.push(v.employer_id);
                    });
                    eventOBJ.interviewer = tmp.join(',');
                    eventOBJ.interviewer_show_email = '';
                    eventOBJ.interviewer_type = 'all';
                } else eventOBJ.interviewer_type = 'specific';

                var form_data = new FormData();

                var file_data = $('#js-message-file').prop('files')[0];
                form_data.append('messageFile', file_data);
                form_data.append('event_timezone', $("#event_timezone option:selected").val());

                //
                // eventOBJ.users_phone = eventOBJ.users_phone.replace(/\D/, '');
                // eventOBJ.users_phone = '+1'+eventOBJ.users_phone;

                for (myKey in eventOBJ) {
                    form_data.append(myKey, eventOBJ[myKey]);
                }

                $.ajax({
                    url: "<?php echo base_url('calendar/event-handler'); ?>",
                    type: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        //
                        alertify.alert(res.Response, flush_alertify_cb);
                        if (res.Status !== false)
                            window.location.reload();
                    }
                });

            }
        });

        // Validate calendar
        function form_check() {
            url_regex.lastIndex = digit_regex.lastIndex = email_regex.lastIndex = phone_regex.lastIndex = 0;
            alertify.defaults.glossary.title = 'Event Management!';
            //
            var users_phone = $('#users_phone').val().trim();
            if (users_phone != '') {
                // Check for phone
                if (!phone_regex.test(users_phone)) {
                    alertify.alert("Please, enter a proper phone number.", flush_alertify_cb);
                    return false;
                }
            }


            var ep = $.parseJSON(func_get_external_users_data());
            if (ep.length > 1) {
                var is_error = false;
                $.each(ep, function(i, v) {
                    email_regex.lastIndex = 0;
                    if (v.name.trim() != '' || v.email.trim() != '') {
                        if (v.name.trim() == '') {
                            alertify.alert("Name is missing for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if (v.email.trim() == '') {
                            alertify.alert("Email is missing for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if (!email_regex.test(v.email.trim())) {
                            alertify.alert("Invalid email is provided for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                    }
                });

                if (is_error) return false;
            } else if (ep.length == 1) {
                if (ep[0].name.trim() != '' || ep[0].email.trim() != '') {
                    if (ep[0].name.trim() == '') {
                        alertify.alert("Name is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if (ep[0].email.trim() == '') {
                        alertify.alert("Email is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if (!email_regex.test(ep[0].email.trim())) {
                        alertify.alert("Invalid email is provided for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                }
            }

            // Event title
            if ($('#js-event-title').val().trim() == '') {
                alertify.alert('An event title is missing.', flush_alertify_cb);
                return false;
            }

            // Check for comment
            if ($('#js-comment-check').prop('checked') === true && $('#js-comment-msg').val().trim() == '') {
                alertify.alert('A comment is missing. If you don\'t want to add a comment please uncheck "Comment" check.', flush_alertify_cb);
                return false;
            }

            // Check for message
            if ($('#js-message-check').prop('checked') === true) {
                if ($('#js-message-subject').val().trim() == '') {
                    alertify.alert('The Subject field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
                if ($('#js-message-body').val().trim() == '') {
                    alertify.alert('Message field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
            }

            // Check for meeting
            if ($('#js-meeting-check').prop('checked') === true) {

                if ($('#js-meeting-call').val().trim() == '') {
                    alertify.alert('The call field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                if (!digit_regex.test($('#js-meeting-call').val().trim())) {
                    alertify.alert('The call field can only hold numeric values. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                // Reset
                digit_regex.lastIndex = 0;

                if ($('#js-meeting-id').val().trim() == '') {
                    alertify.alert('The ID field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }

                if ($('#js-meeting-url').val().trim() == '') {
                    alertify.alert('The URL field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }

                if (!url_regex.test($('#js-meeting-url').val().trim())) {
                    alertify.alert('The URL is invalid. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
            }


            // set default interviewer to employer
            if ($('#js-interviewer').val() == null || $('#js-interviewer').val() <= 0 || $('#js-interviewer').val() == ' ') {
                $('#js-interviewer').val(<?= $employer_id; ?>);
            }

            return true;
        }

        // Overwrite Alertify callback
        function flush_alertify_cb() {
            console.clear();
        }


        function get_event_obj_template() {
            return {
                action: 'save_event',
                applicant_sid: 0,
                employee_sid: 0,
                title: '',
                category: '',
                date: '',
                eventstarttime: '',
                eventendtime: '',
                interviewer: '',
                address: '',
                users_type: user_type,
                users_phone: '',
                interviewer_show_email: '',
                external_participants: func_get_external_users_data(),
                applicant_jobs_list: [],
                reminder_flag: 0,
                duration: 15,
                commentCheck: 0,
                comment: '',
                messageCheck: 0,
                subject: '',
                message: '',
                goToMeetingCheck: 0,
                meetingId: 0,
                meetingCallNumber: 0,
                meetingURL: '',
                recur: ''
            };
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
                    'show_email': $(show_emails[iCount]).prop('checked') === true ? 1 : 0
                };

                data.push(person_data);
            }

            return JSON.stringify(data);
        }

        $(document).on('change', '#js-interviewer', function() {
            var event_sid = $(this).attr('data-event_sid');
            event_sid = event_sid == null || event_sid == undefined || event_sid == '' || event_sid == 0 ? 0 : event_sid;
            var selected = $(this).val();

            if (selected !== null && selected.length > 0 && current_event_type != 'training-session') {
                $('#js-interviewers-list').show();
                $('#show_email_main_container_' + event_sid).show();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', false);
                $('.show_email_col').hide();

                for (var i = 0; i < selected.length; i++) {
                    var emp_sid = selected[i];
                    $('#show_email_container_' + event_sid + '_' + emp_sid).show();
                    $('#show_email_' + event_sid + '_' + emp_sid).prop('disabled', false);
                }
            } else {
                $('#js-interviewers-list').hide();
                $('#show_email_main_container_' + event_sid).hide();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', true);
            }
        });

        if (user_type === 'employee') {
            // Triggers when interviewer is un-selected
            $('#js-interviewer').on('select2:select', function() {
                handle_all_check($('#js-interviewer').val());
            });
            $('#js-interviewer').on('select2:unselect', function() {
                handle_all_check($('#js-interviewer').val());
            });
        }
        // TODO
        // Check for reoccur events
        // @param e
        // Holds the current render event
        function check_for_reoccur_event(e) {
            if (e.is_recur == 0) return true;
            // 
            // if(e.event_id != '1799') return false;

            var return_value = false,
                recur_obj = {},
                list = JSON.parse(e.recur_list);
            recur_obj.start_date = moment(e.recur_start_date);
            recur_obj.end_date = null;
            if (e.recur_end_date != null)
                recur_obj.end_date = moment(e.recur_end_date);

            // Check for reoccur type
            switch (e.recur_type) {
                case 'Yearly':
                    recur_obj.years = list.Years;
                    // Check for years
                    return_value = check_recur_years(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.months = reset_array_for_recur(list.Months, 12);
                    // Check for months
                    return_value = check_recur_months(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.weeks = list.Weeks;
                    // Check for weeks
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Monthly':
                    recur_obj.months = reset_array_for_recur(list.Months, 12);
                    // Check for months
                    return_value = check_recur_months(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.weeks = list.Weeks;
                    // Check for weeks
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Weekly':
                    recur_obj.weeks = list.Weeks;
                    recur_obj.days = reset_array_for_recur(list.Days, 7);

                    // Check for weeks
                    // Don't check the end time if
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    // Check for timeframe
                    // Don't check the end time if
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Daily':
                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    // Don't check the end time if
                    return_value = check_recur_days(e, recur_obj);
                    break;
            }

            return return_value;
        }

        // Check recur days check
        function check_recur_days(e, recur_obj) {

            var is_return = false,
                is_return_2 = false;
            // it's null
            if (recur_obj.end_date == null)
                // 2019-05-08 >= 2019-05-08
                is_return = e.start.isAfter(recur_obj.start_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY');
            // is_return = e.start.isAfter(recur_obj.start_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY');
            // 2019-05-08 <= 2019-09-04 && 2019-05-08 >= 2019-05-08
            else if (recur_obj.end_date != null) {
                is_return =
                    (e.start.isBefore(recur_obj.end_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY')) &&
                    (e.end.isAfter(recur_obj.start_date) || e.end.format('MM-DD-YYYY') == recur_obj.end_date.format('MM-DD-YYYY'));
            }

            is_return_2 = (recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty(e.start.day()));

            // console.log(
            // e.start.format('MM-DD-YYYY'),
            // e.end.format('MM-DD-YYYY'),
            // recur_obj.start_date.format('MM-DD-YYYY'),
            // recur_obj.end_date.format('MM-DD-YYYY')

            // recur_obj
            // );

            // console.log(recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty( e.start.day() ), e.start.day(), recur_obj.days, e.start.format('MM-DD-YYYY'), is_return, is_return_2);
            // console.log( e.start.day(), recur_obj.days, e.start.format('MM-DD-YYYY') );

            return is_return && is_return_2;
        }

        // Check recur weeks check
        function check_recur_weeks(e, recur_obj) {
            return moment(e.start).week() % recur_obj.weeks === 0 ? true : false
        }

        // Check recur months check
        function check_recur_months(e, recur_obj) {
            if (recur_obj.months.hasOwnProperty('is_all'))
                return true;
            return recur_obj.months.hasOwnProperty(e.start.month() + 1);
        }

        // Check recur years check
        function check_recur_years(e, recur_obj) {
            return moment(e.start).year() % recur_obj.years === 0 ? true : false
        }

        // Generates HTML for recurr view
        // @param type
        // @param selected
        function get_recurr_html(type, selected) {
            var rows = '';
            //
            switch (type) {
                case 'days_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>On Days</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    $.each(recurr_days, function(i, v) {
                        var key = i++,
                            is_class = (selected !== undefined && (selected.hasOwnProperty(key) || selected.hasOwnProperty('is_all'))) ? 'cs-active-day' : '';
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-day-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
                    });
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'end_date_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Ends On</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-4">';
                    rows += '                    <input type="text" readonly="true" class="js-recurr-datepicker" ' + (selected === undefined ? '' : 'value="' + selected + '"') + ' />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-4">';
                    rows += '                    <label class="control control--checkbox">';
                    rows += '                        Infinite';
                    rows += '                        <input type="checkbox" class="js-infinite"/>';
                    rows += '                        <div class="control__indicator"></div>';
                    rows += '                    </label>';
                    rows += '                </div>';
                    rows += '            </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'summary_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Summary</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <p class="js-summary-row"></p>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
                    // 
                case 'week_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat Every</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-2">';
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-recurr-week-input" />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-8">';
                    rows += '                    <span>Weeks</span>';
                    rows += '                </div>';
                    rows += '             </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
                    // 
                case 'month_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>On Months</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    $.each(recurr_months, function(i, v) {
                        var key = i++,
                            is_class = (selected !== undefined && (selected.hasOwnProperty(key) || selected.hasOwnProperty('is_all'))) ? 'cs-active-day' : '';
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-month-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
                    });
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'year_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat Every</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-2">';
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-recurr-year-input" />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-8">';
                    rows += '                    <span>Years</span>';
                    rows += '                </div>';
                    rows += '             </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'every_day_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <label class="control control--checkbox">';
                    rows += '                  Every Day';
                    rows += '                  <input type="checkbox" />';
                    rows += '                  <div class="control__indicator"></div>';
                    rows += '            </label>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
            }
            return rows;
        }

        // Load recurr view
        // @param type
        // Recur type
        // @param data
        // Holds data for edit mode
        function load_recurr_view(type, data) {
            var row = '',
                is_load = false;
            switch (type) {
                case 'daily':
                    row +=
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'daily';
                    break;
                case 'weekly':
                    row +=
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'weekly';
                    break;
                case 'monthly':
                    row +=
                        get_recurr_html('month_row', data === undefined ? default_months : data.months) +
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'monthly';
                    break;
                case 'yearly':
                    row +=
                        get_recurr_html('year_row', data === undefined ? 2 : data.years) +
                        get_recurr_html('month_row', data === undefined ? default_months : data.months) +
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'yearly';
                    break;
            }
            $('.cs' + edit_mode + '-row-view').html(row);
            // Load datepikcer for
            // recurr event
            $(".js-recurr-datepicker").datepicker({
                dateFormat: 'mm-dd-yy',
                minDate: 0,
                onSelect: function() {
                    set_summary();
                }
            });
            // Trigger set summary if is_load
            // check is active
            if (is_load) {
                $('.js-recurr-type').prop('checked', false);
                $('#js-recurr-' + is_load + '-check').prop('checked', true);
                set_summary();
            }
        }

        // Set summary for recurr event
        function set_summary() {
            var rows = '',
                number_of = null,
                type = 'week';

            // For monthly
            if ($('#js-recurr-monthly-check').prop('checked') === true ||
                $('#js-recurr-yearly-check').prop('checked') === true) {
                var month_length = $('.js-month-box.cs-active-day').length;
                if (month_length !== 0) {
                    rows += ' for month' + (month_length === 1 ? ' ' : 's ');
                    // Loop through selected
                    $.each($('.js-month-box.cs-active-day'), function(i, v) {
                        if (i == 0)
                            rows += recurr_months[$(this).data('key')];
                        else if (++i == month_length)
                            rows += ' and ' + recurr_months[$(this).data('key')];
                        else
                            rows += ', ' + recurr_months[$(this).data('key')];
                    });
                }
                // rows += ' ';
                number_of = $('#js-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For daily view
            if ($('#js-recurr-daily-check').prop('checked') === true ||
                $('#js-recurr-weekly-check').prop('checked') === true ||
                $('#js-recurr-monthly-check').prop('checked') === true ||
                $('#js-recurr-yearly-check').prop('checked') === true) {
                var day_length = $('.js-day-box.cs-active-day').length;
                rows += ' on ';
                // Loop through selected
                $.each($('.js-day-box.cs-active-day'), function(i, v) {
                    if (i == 0)
                        rows += recurr_days[$(this).data('key')];
                    else if (++i == day_length)
                        rows += ' and ' + recurr_days[$(this).data('key')];
                    else
                        rows += ', ' + recurr_days[$(this).data('key')];
                });
            }

            // For weekly
            if ($('#js-recurr-weekly-check').prop('checked') === true) {
                number_of = $('#js-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For yearly
            if ($('#js-recurr-yearly-check').prop('checked') === true) {
                number_of = $('#js-recurr-year-input').val();
                type = 'year' + (number_of == 1 ? '' : 's');
            }

            // Check if nothing was selected
            if (rows == '') {
                $('.js-summary-row').text('');
                return false;
            }
            // Load end time if defined
            if ($('.js-infinite').prop('checked') === false)
                rows += ' until ' + moment($('.js-recurr-datepicker').val()).format('MMMM Do, YYYY');
            // Add prefix
            rows = 'Repeats ' + (number_of === null ? '' : 'every ' + number_of + ' ' + type + ' ') + ' ' + rows;
            // Load summary to DOM
            $('.js-summary-row').html(rows);
        }

        // Convert array to object
        // @param arr
        // Array to be converted in Object
        // @param check_length
        // Length
        function reset_array_for_recur(arr, check_length) {
            //
            if (arr == '*') return {
                is_all: true
            };
            //
            if (arr.length === check_length) return {
                is_all: true
            };
            //
            var tmp = {};
            $.each(arr, function(i, v) {
                tmp[v] = true;
            });
            return tmp;
        }

        // Load recurr view
        $(document).on('click', '.js-recurr-type', function() {
            load_recurr_view($(this).val().toLowerCase());
        });

        // Days on, off
        $(document).on('click', '.js-day-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Months on, off
        $(document).on('click', '.js-month-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Triggeres when infinite check
        // is changed
        $(document).on('click', '.js-infinite', function() {
            set_summary();
        });

        // Triggers when
        // week is entered 
        $(document).on('keyup', '#js-recurr-week-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers when 
        // year is entered
        $(document).on('keyup', '#js-recurr-year-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers on
        // reccur check
        $(document).on('click', '#js-reoccur-check', function() {
            $('#js-reoccur-box').hide();
            if ($(this).prop('checked') === true) {
                $('#js-reoccur-box').show();
                $('#js-recurr-daily-check').prop('checked', true);
                load_recurr_view('daily');
            }
        });

        // Get selected days
        function get_selected_days() {
            var tmp = [];
            $('.js-day-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        // Get selected months
        function get_selected_months() {
            var tmp = [];
            $('.js-month-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        $('#add_event').click(function() {
            var rows = '';
            rows += '<div id="external_participant_0" class="row external_participants">';
            rows += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
            rows += '        <div class="form-group">';
            rows += '            <label>Name</label>';
            rows += '            <input name="ext_participants[0][name]" id="ext_participants_0_name" type="text" class="form-control external_participants_name" />';
            rows += '        </div>';
            rows += '    </div>';
            rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
            rows += '        <div class="form-group">';
            rows += '            <label>Email</label>';
            rows += '            <input name="ext_participants[0][email]" id="ext_participants_0_email" type="email" class="form-control external_participants_email" />';
            rows += '        </div>';
            rows += '    </div>';
            rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
            rows += '        <div class="form-group">';
            rows += '            <label class="control control--checkbox margin-top-20">';
            rows += '                Show Email';
            rows += '                <input name="ext_participants[0][show_email]" id="ext_participants_0_show_email" class="external_participants_show_email"  type="checkbox" />';
            rows += '                <div class="control__indicator"></div>';
            rows += '            </label>';
            rows += '        </div>';
            rows += '    </div>';
            rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
            rows += '<button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            rows += '    </div>';
            rows += '</div>';
            $('#js-extra-interviewers-box').html(rows);
        });

        func_hide_loader();

        function func_hide_loader() {
            $('#my_loader').hide();
        }

        function func_show_loader() {
            $('#my_loader').show();
        }


        // File plugin on message file button
        $('#js-message-file').filestyle({
            text: 'Add Attachment',
            btnClass: 'btn-success',
            placeholder: "No file selected"
        });

        // Added on: 09-06-2019
        if (user_type === 'employee') {
            // Added on: 09-06-2019
            // Remove 'all' from interviewers
            // list
            function remove_all_from_interviewers() {
                var vals = $('#js-interviewer').val();
                if (vals == null) return;
                vals.remove('all');
                $('#js-interviewer option[value="all"]').remove();
                $('#js-interviewer').select2('val', <?= $employer_id; ?>);
                var obj = get_ie_obj(<?= $employer_id; ?>);
            }

            // Add 'all' in interviewers
            // list
            function append_all_to_interviewers(default_select) {
                $('#js-interviewer').append('<option value="all">All</option>');
                if (default_select === undefined) {
                    $('#js-interviewer').select2('val', '');
                    $('#js-interviewer').select2('val', 'all');
                }
            }

            // Remove 'all' when employee is selected
            // Add 'all' when no employee is selected
            function handle_all_check(selected_interviewers) {
                //
                if (selected_interviewers == null) {
                    $('#js-interviewer').select2('val', 'all');
                    return;
                }
                //
                if (selected_interviewers.length > 1) {
                    selected_interviewers.remove('all');
                    $('#js-interviewer').select2('val', selected_interviewers);
                }
            }

            // Fetch online videos 
            function fetch_online_videos() {
                $.get("<?= base_url('calendar/fetch-online-videos'); ?>", function(resp) {
                    if (resp.Status === false && resp.Redirect === true) window.reload();
                    if (resp.Status === false) return;
                    //
                    $.each(resp.Videos, function(i, v) {
                        $('#js-video-wrap select').append('<option value="' + v.id + '">' + v.name + '</option>');
                    });
                });
            }

            // Get employee index
            function get_ie_obj(employer_id) {
                var return_value = false;
                $.each(employee_list, function(i, v) {
                    if (employer_id == v.employer_id) {
                        return_value = v;
                        return false;
                    }
                });

                return return_value;
            }

            fetch_online_videos();
            $('#js-video-wrap select').select2();
        }

    });
</script>
<style>
    #my_loader {
        z-index: 9999 !important;
    }
</style>


<!-- <script>
    $('#users_phone').keyup(function(event) {
        var val = fpn($(this).val().trim());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition($(this), val.cur);
        }else $(this).val(val);
    });
</script> -->