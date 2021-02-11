<div class="event_create" style="display: none">
    <div class="row">
        <div class="col-xs-12">
            <div class="hr-box">
                <div class="hr-box-header">
                    <h1 class="hr-registered">Schedule Event</h1>
                </div>
                <div class="hr-innerpadding form-wrp">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="form-group autoheight">
                                        <label>Event Title <span class="required">*</span></label>
                                        <input placeholder="Event Title"
                                               name="title"
                                               id="title"
                                               class="form-control"
                                               data-rule-required="true"
                                               type="text">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group autoheight">
                                <?php   $uri_segment = $this->uri->segment(1);
                                        $users_phone = '';

                                        if($uri_segment == 'employee_profile'){
                                            $users_type = 'Employee';

                                            if(isset($employer['PhoneNumber'])) {
                                                $users_phone = $employer['PhoneNumber'];
                                            }

                                        } else if($uri_segment == 'applicant_profile'){
                                            $users_type = 'Applicant';

                                            if(isset($applicant_info['phone_number'])) {
                                                $users_phone = $applicant_info['phone_number'];
                                            }
                                        } ?>

                                        <label><?php echo $users_type; ?> Phone Number</label>
                                        <input name="users_phone"
                                               id="users_phone"
                                               class="form-control"
                                               data-rule-required="true"
                                               value="<?php echo $users_phone; ?>"
                                               type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Category <span class="required">*</span></label>
                                        <input type="hidden" id="add_event_category" name="category" value="interview" />
                                        <div class="dropdown event-category-dropdown">
                                            <button class="btn btn-default btn-block dropdown-toggle form-control" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <span id="add_event_selected_category">In-Person Interview</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'call');" class="btn btn-default btn-block dropdown-btn btn-event-call">Call</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'email');" class="btn btn-default btn-block dropdown-btn btn-event-email">Email</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'meeting');" class="btn btn-default btn-block dropdown-btn btn-event-meeting">Meeting</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview');" class="btn btn-default btn-block dropdown-btn btn-event-interview">In-Person Interview</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview-phone');" class="btn btn-default btn-block dropdown-btn btn-event-interview-phone">Phone Interview</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview-voip');" class="btn btn-default btn-block dropdown-btn btn-event-interview-voip">Voip Interview</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'personal');" class="btn btn-default btn-block dropdown-btn btn-event-personal">Personal</button></li>
                                                <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'other');" class="btn btn-default btn-block dropdown-btn btn-event-other">Other</button></li>
                                            </ul>
                                        </div>
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
                                               id="eventdate" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Start Time <span class="required">*</span></label>
                                        <input name="eventstarttime"
                                               id="eventstarttime"
                                               value="12:00AM"
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
                                               id="eventendtime"
                                               readonly="readonly"
                                               value="12:00PM"
                                               type="text"
                                               class="end_time form-control"
                                               data-rule-required="true"
                                               required="required" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label><span class="attendees_label">Participant(s)</span> <span class="required">*</span></label>
                                        <select data-add="1"
                                                class="contact_id form-control"
                                                multiple
                                                id="interviewer"
                                                data-rule-required="true"
                                                name="interviewer[]">
                                            <?php foreach ($company_accounts as $account) { ?>
                                                <?php if ($id != $account['sid']) { ?>
                                                    <option
                                                        value="<?= $account['sid'] ?>">
                                                        <?php if ($employer_id == $account['sid']) { ?>
                                                            You
                                                        <?php } else {
                                                            if($account['is_executive_admin'] == 1) {
                                                                $employee_type = 'Executive Admin';
                                                            } else {
                                                                $employee_type = $account['access_level'];
                                                            }
                                                            echo $account['first_name'].'&nbsp;'.$account['last_name'].' ('.$employee_type.')';
                                                        } ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label><span class="attendees_label">Applicant Jobs List</span></label>
                                    </div>
                                    <?php if (sizeof($applicant_jobs) > 0) { 
//                                        echo "<pre>"; print_r($applicant_jobs); echo '</pre>';
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
                                                        <input name="applicant_jobs_list[]" class="external_participants_show_email" value="<?php echo $applicant_list_sid; ?>" type="checkbox" <?php if($i==1) { echo 'checked="checked"'; } ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                            <?php         }
                                    } ?>
                                </div>
                            </div>

                            <div id="show_email_main_container_0" class="row show_email_main_container">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label>Show Email Address of The Following Users in Event Email:</label>
                                    </div>
                                    <div class="row">
                                        <?php foreach ($company_accounts as $account) { ?>
                                            <div id="show_email_container_0_<?php echo $account['sid']; ?>" class="col-xs-6 show_email_col">
                                                <label class="control control--checkbox">
                                                    <?php echo ucwords($account["first_name"] . ' ' .$account["last_name"]) .' ( '. ($account["is_executive_admin"] == 1 ?  'Executive Admin' : ucwords($account["access_level"])) .' )' ?>
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
                                            <label>Non Employee <span id="none_employee_title">Participant(s)</span></label>
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
                                                            <input name="external_participants[0][show_email]" id="external_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />
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

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Comment for <span class="comment_for">Interviewer(s)</span>
                                            <input class="comment_check" data-event_id="0" id="comment_check_0" value="1" name="commentCheck" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="interviewer_comment_0" style="display: none">
                                        <div class="form-group autoheight">
                                            <textarea rows="5" class="form-control textarea" id="interviewerComment" name="comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Message to <span class=""><?php echo $this->uri->segment(1) == 'applicant_profile' ? 'Applicant' : 'Employee'; ?></span>:
                                            <input id="message_check_0" class="message_check" data-event_id="0"  name="messageCheck" value="1" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="message_0" style="display: none">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Subject</label>
                                                    <input class="form-control" name="subject" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Message</label>
                                                    <textarea rows="5" class="form-control textarea" name="message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <input type="file" id="add_attachment" name="messageFile" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
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
                                                <?php foreach($addresses as $address) { ?>
                                                    <option value="<?php echo $address?>"><?php echo $address?></option>
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
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Meeting Call In Details
                                            <input id="goto_meeting_check_0" class="goto_meeting_check" data-event_id="0" value="1" name="goToMeetingCheck" type="checkbox">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div id="meeting_details_0" style="display: none">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Meeting Call In Number</label>
                                                    <input type="text" name="meetingCallNumber" id="meetingCallNumber" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Meeting ID Number</label>
                                                    <input type="text" name="meetingId" id="meetingId" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group autoheight">
                                                    <label>Webinar/Meeting log in URL</label>
                                                    <input type="text" name="meetingURL" id="meetingURL"  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <input class="btn btn-success" type="submit" onclick="validate_form()" value="Save" />
                                    <input class="btn black-btn" onclick="cancel_event()" type="button" value="Cancel" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>