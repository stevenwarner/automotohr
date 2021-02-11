<div class="event_edit">
    <div class="row">
        <div class="col-xs-12">
            <div class="hr-innerpadding form-wrp">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label>Event Title <span class="required">*</span></label>
                                    <input placeholder="Event Title"
                                           name="title"
                                           id="js-edit-event-title"
                                           class="form-control"
                                           data-rule-required="true"
                                           type="text">
                                    <input type="hidden" id="js-edit-event-id">
                                    <input type="hidden" id="js-lcts-id">
                                    <input type="hidden" id="js-edit-event-type">
                                    <input type="hidden" id="js-edit-applicant-id">
                                    <input type="hidden" id="js-edit-employee-id">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                          

                                    <label>Phone Number</label>
                                        <input name="users_phone"
                                               id="js-edit-users-phone"
                                               class="form-control"
                                               data-rule-required="true"
                                               value=""
                                               type="text">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Category <span class="required">*</span></label>
                                    <input type="hidden" id="js-edit-event-selected-type-value" name="category" value="interview" />
                                    <div class="dropdown event-category-dropdown">
                                        <button class="btn btn-default btn-block dropdown-toggle form-control" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="js-edit-event-selected-type-area">
                                            <span id="js-edit-event-selected-type-text"></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" id="js-edit-event-type-area"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group  autoheight">
                                    <label>Event Timezone <span class="cs-required">*</span></label>
                                    <?=timezone_dropdown(
                                        $employer_timezone, 
                                        array(
                                            'class' => 'form-control',
                                            'id' => 'edit_event_timezone',
                                            'name' => 'edit_event_timezone'
                                        ),
                                        'north_america'
                                    );?>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Event Date <span class="required">*</span></label>
                                    <input type="text"
                                           readonly=""
                                           placeholder="Event date"
                                           name="date"
                                           class="form-control"
                                           data-rule-required="true"
                                           required="required"
                                           id="js-edit-date" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Start Time <span class="required">*</span></label>
                                    <input name="eventstarttime"
                                           id="js-edit-start-time"
                                           readonly="readonly"
                                           type="text"
                                           class="start_time form-control"
                                           data-rule-required="true"
                                           required="required" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>End Time <span class="required">*</span></label>
                                    <input name="eventendtime"
                                           id="js-edit-end-time"
                                           readonly="readonly"
                                           type="text"
                                           class="end_time form-control"
                                           data-rule-required="true"
                                           required="required" />
                                </div>
                            </div>
                        </div>

                        <!-- Interviewers -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label id="js-edit-attendees-label"><span class="attendees_label"><?=$users_type == 'Employee' ? 'Participant(s)' : 'Interviewer(s)';?></span> <span class="required">*</span></label>
                                    <select data-add="1"
                                            class=" form-control"
                                            multiple
                                            id="js-edit-interviewer"
                                            data-rule-required="true"
                                            name="interviewer[]">
                                        <?php foreach ($company_accounts as $account) { ?>
                                            <?php //if ($id != $account['sid']) { ?>
                                                <option
                                                    value="<?= $account['sid'] ?>">
                                                    <?php 
                                                        $timezone = !empty($account['timezone']) ? $account['timezone'] : $company_timezone;
                                        
                                                        if ((isset($main_employer_id) ? $main_employer_id : $employer_id) == $account['sid']) {
                                                            echo "You".' ('.$timezone.')';
                                                        } else {
                                                        if($account['is_executive_admin'] == 1) {
                                                            $employee_type = 'Executive Admin';
                                                        } else {
                                                            $employee_type = $account['access_level'];
                                                        }
                                                        
                                                        echo $account['first_name'].'&nbsp;'.$account['last_name'].' ('.$timezone.')'.' ('.$employee_type.')';
                                                    } ?>
                                                </option>
                                            <?php //} ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 js-edit-applicant-box">
                                <div class="form-group autoheight">
                                    <label><span class="attendees_label">Applicant Jobs List</span></label>
                                </div>
                                <?php if (isset($applicant_jobs) && sizeof($applicant_jobs) > 0) { 
                                   // echo "<pre>"; print_r($applicant_jobs); echo '</pre>';
                                    $i=0;
                                    foreach ($applicant_jobs as $applicant_job) {
                                            $applicant_list_sid = $applicant_job['sid'];
                                            $job_sid =  $applicant_job['job_sid'];
                                            $job_title = $applicant_job['Title']; 
                                            $desired_job_title = $applicant_job['job_title'];
                                            $i++;
                                            
                                            if (!empty($job_title)) {
                                                $title = $job_title;
                                            } else if (!empty($desired_job_title)) {
                                                if($desired_job_title == 'Job Not Applied') {
                                                    continue;
                                                }
                                                $title = $desired_job_title;
                                            } else {
                                                continue;
                                            } ?>

                                            <div class="col-xs-6">
                                                <label class="control control--checkbox">
                                                    <?php echo ucwords($title); ?>
                                                    <input name="applicant_jobs_list[]" class="external_participants_show_email js-edit-applicant-job js-edit-applicant-job-<?=$applicant_list_sid;?>" value="<?php echo $applicant_list_sid; ?>" type="checkbox" <?php if($i==1) { echo 'checked="checked"'; } ?> />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                        <?php         }
                                } ?>
                            </div>
                        </div>

                        <div id="js-edit-interviewers-list" class="row show_email_main_container">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Show Email Address of The Following Users in Event Email:</label>
                                </div>
                                <div class="row">
                                    <?php foreach ($company_accounts as $account) { 
                                        $timezone = !empty($account['timezone']) ? $account['timezone'] : $company_timezone;
                                        ?>
                                        <div id="js_edit_show_email_container_0_<?php echo $account['sid']; ?>" class="col-xs-6 js_edit_show_email_col">
                                            <label class="control control--checkbox">
                                                <?php echo ucwords($account["first_name"] . ' ' .$account["last_name"]) .' ('.$timezone.') ( '. ($account["is_executive_admin"] == 1 ?  'Executive Admin' : ucwords($account["access_level"])) .' )' ?>
                                                <input id="js_edit_show_email_0_<?php echo $account['sid']; ?>" class="show_email" value="<?php echo $account['sid']; ?>" name="show_email[]" type="checkbox" />
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
                                    <div id="js-edit-ep-box" class="col-xs-12">
                                        <div id="js-edit-ep-0" class="row">
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input name="external_participants[0][name]" id="js-edit-ep-0-name" type="text" class="form-control external_participants_name" />
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input name="external_participants[0][email]" id="js-edit-ep-0-email" type="email" class="form-control external_participants_email" />
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control control--checkbox margin-top-20">
                                                        Show Email
                                                        <input name="external_participants[0][show_email]" id="js-edit-ep-0-show-email" class="external_participants_show_email" type="checkbox" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xs-1">
                                                <button id="js-edit-btn-add-participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20 btn_add_participant"><i class="fa fa-fw fa-plus-square"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />

                        <?php if($users_type == 'Employee' && $show_online_videos == 1) { ?>
                            <!-- Videos row -->
                            <div class="row" id="js-edit-video-wrap" style="display: none;">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label>Online Videos</label>
                                        <select class="form-control" multiple="true"></select>
                                    </div>
                                </div>
                            </div>
                            <hr class="js-video-hr"/>
                        <?php } ?>

                        <!-- Comment -->
                        <div class="row js-edit-comment-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Comment for <span class="comment_for"><?=$users_type == 'Employee' ? 'Participant(s)' : 'Interviewer(s)';?></span>
                                        <input class="comment_check" data-event_id="0" id="js-edit-comment-check" value="1" name="commentCheck" type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div id="js-edit-comment-box" style="display: none">
                                    <div class="form-group autoheight">
                                        <textarea rows="5" class="form-control textarea" id="js-edit-comment-msg" name="comment"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="js-edit-comment-hr" />

                        <!-- Message -->
                        <div class="row js-edit-message-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Message to <span class=""><?=$users_type;?></span>
                                        <input id="js-edit-message-check" class="message_check" data-event_id="0"  name="messageCheck" value="1" type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div id="js-edit-message-box" style="display: none">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Subject <span class="required">*</span></label>
                                                <input class="form-control" id="js-edit-message-subject" name="subject" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Message <span class="required">*</span></label>
                                                <textarea rows="5" id="js-edit-message-body" class="form-control textarea" name="message"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <input type="file" id="js-edit-message-file" name="messageFile" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="js-edit-message-hr" />

                        <!-- Address -->
                        <div class="row">
                            <div class="col-xs-12 mb-2">
                                <label class="control control--radio" id="lbl_new_address_0">
                                    Address
                                    <input data-event_sid="0" id="address_type_new_0" class="js-edit-address-type" value="new" name="address_type" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label>
                                &nbsp;
                                &nbsp;
                                <label class="control control--radio " id="lbl_saved_address_0">
                                    Company Addresses
                                    <input data-event_sid="0" id="address_type_saved_0" class="js-edit-address-type" value="saved" name="address_type" type="radio" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div id="js-edit-address-select-box" class="row" style="display: none;">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <div class="select">
                                        <select class="form-control" name="address" id="js-edit-address-saved">
                                            <?php foreach($addresses as $address) { ?>
                                                <option value="<?php echo $address?>"><?php echo $address?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="js-edit-address-input-box" class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <input class="form-control" name="address" id="js-edit-address-new" type="text" />
                                </div>
                            </div>
                        </div>
                        <hr />

                        <!-- Meeting -->
                        <div class="row js-edit-meeting-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Meeting Call In Details
                                        <input id="js-edit-meeting-check" class="goto_meeting_check" data-event_id="0" value="1" name="goToMeetingCheck" type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div id="js-edit-meeting-box" style="display: none">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group autoheight">
                                                <label>Meeting Call In Number <span class="required">*</span></label>
                                                <input type="text" name="meetingCallNumber" id="js-edit-meeting-call" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group autoheight">
                                                <label>Meeting ID Number <span class="required">*</span></label>
                                                <input type="text" name="meetingId" id="js-edit-meeting-id" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Webinar/Meeting log in URL <span class="required">*</span></label>
                                                <input type="text" name="meetingURL" id="js-edit-meeting-url"  class="form-control">
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
                                        <input id="js-edit-reminder-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight" id="js-edit-reminder-box" style="display: none;">
                                    <label>Reminder Duration <span class="required">*</span></label>
                                    <select class="form-control" id="js-edit-reminder-select">
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
                        <div class="row" id="js-edit-reoccur-wrap">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Recuring Event
                                        <input id="js-edit-reoccur-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <!--  -->
                            <div id="js-edit-reoccur-box">
                                <!-- Type row -->
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="col-sm-2 autoheight">
                                        <label class="control control--radio">
                                            Daily
                                            <input type="radio" name="txt_reoccur_type" class="js-edit-recurr-type" id="js-edit-recurr-daily-check" value="Daily" checked="checked" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-sm-2 autoheight" id="js-edit-reoccur-box">
                                        <label class="control control--radio">
                                            Weekly
                                            <input type="radio" name="txt_reoccur_type" class="js-edit-recurr-type" id="js-edit-recurr-weekly-check" value="Weekly" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-sm-2 autoheight" id="js-edit-reoccur-box">
                                        <label class="control control--radio">
                                            Monthly
                                            <input type="radio" name="txt_reoccur_type" class="js-edit-recurr-type" id="js-edit-recurr-monthly-check" value="Monthly" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-sm-2 autoheight" id="js-edit-reoccur-box">
                                        <label class="control control--radio">
                                            Yearly
                                            <input type="radio" name="txt_reoccur_type" class="js-edit-recurr-type" id="js-edit-recurr-yearly-check" value="Yearly" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Event row -->
                                <!-- TODO -->
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="js-edit-row-view"></div>
                                </div>
                            </div>
                        </div>   

                        <!-- Reminder Emails -->
                        <div class="row" id="js-edit-reminder-email-wrap">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Send Reminder Email:
                                        <input id="js-edit-reminder-email-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight" id="js-edit-reminder-email-box"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <h3 id="js-edit-cancelled-message" style="display: none;" class="text-danger text-center">Event Cancelled!</h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .cs-row{ padding-top: 30px; }
    .cs-row select{ padding: 5px; width: 60px; height: 40px; }
    .cs-row p{ padding-top: 7px; }
    .cs-row span{ margin-left: 10px; }
    .cs-row span.cs-day{ display: inline-block; width: 40px; height: 40px; border: 2px solid #81b431; text-align: center; line-height: 36px; font-weight: bold; font-size: 24px; border-radius: 50%; -webkit-border-radius: 50%; -moz-border-radius: 50%; cursor: pointer; margin-right: 0px; color: #81b431; }
    .cs-row span.cs-day:hover, .cs-row span.cs-day.cs-active-day{ background-color: #81b431; color: #ffffff; }
    .cs-row input[type="text"]{ height: 40px !important; padding: 5px; }
    .cs-required{ color: #cc1100; }
</style>

