<form class="date_form" id="edit_event_form" action="<?php echo base_url('calendar/tasks'); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="employee_sid" value="<?php echo $event['applicant_job_sid'] ?>" />
    <input type="hidden" name="applicant_sid" value="<?php echo $event['applicant_job_sid'] ?>" />
    <input type="hidden" name="employers_sid" value="<?php echo $event['employers_sid'] ?>" />
    <input type="hidden" name="users_type" value="<?php echo $event['users_type'] ?>" />
    <input type="hidden" name="action" value="update_event" />
    <input type="hidden" name="sid" id="event_sid" value="<?php echo $event["sid"]; ?>" />
    <input type="hidden" id="event_id" value="<?php echo $event["sid"]; ?>" />
    <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">
    <input type="hidden" name="redirect_to_user_sid" value="<?php echo $redirect_to_user_sid; ?>">
    <input type="hidden" name="redirect_to_job_list_sid" value="<?php echo $redirect_to_job_list_sid; ?>">
    <?php   $applicant_jobs_list = $event['applicant_jobs_list'];
//    echo '<pre>'; print_r($event); exit;
            if($applicant_jobs_list != '' && $applicant_jobs_list != null) {
                $applicant_jobs_array = explode(',', $applicant_jobs_list);
            }
//    echo '<pre>'; print_r($applicant_jobs_array); echo '</pre>'; ?>
    <div class="form-wrp modal-form">
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label>Title: <i class="fa fa-asterisk text-danger"></i></label>
                            <input id="eventtitle<?= $event["sid"] ?>"
                                   type="text"
                                   name="title"
                                   placeholder='Enter title here'
                                   value="<?= $event["title"] ?>"
                                   data-rule-required="true"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label><?php echo ucwords($event['users_type']); ?> Phone Number: <i class="fa fa-asterisk text-danger"></i></label>
                            <input id="users_phone<?= $event["sid"] ?>"
                                   type="text"
                                   name="users_phone"
                                   value="<?= $event["users_phone"] ?>"
                                   data-rule-required="true"
                                   class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Category: <i class="fa fa-asterisk text-danger"></i></label>
                            <input type="hidden" id="edit_event_category" name="category" value="<?php echo $event['category']; ?>" />
                            <div class="dropdown event-category-dropdown">
                                <button class="btn btn-default btn-block dropdown-toggle form-control" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span id="edit_event_selected_category">
                                        <?php if($event['category'] == 'call') { ?>
                                            Call
                                        <?php } else if($event['category'] == 'email') { ?>
                                            Email
                                        <?php } else if($event['category'] == 'meeting') { ?>
                                            Meeting
                                        <?php } else if($event['category'] == 'interview') { ?>
                                            In-Person Interview
                                        <?php } else if($event['category'] == 'interview-phone') { ?>
                                            Phone Interview
                                        <?php } else if($event['category'] == 'interview-voip') { ?>
                                            VOIP Interview
                                        <?php } else if($event['category'] == 'personal') { ?>
                                            Personal Meeting
                                        <?php } else if($event['category'] == 'other') { ?>
                                            Other
                                        <?php } ?>
                                    </span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'call');" class="btn btn-default btn-block dropdown-btn btn-event-call">Call</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'email');" class="btn btn-default btn-block dropdown-btn btn-event-email">Email</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'meeting');" class="btn btn-default btn-block dropdown-btn btn-event-meeting">Meeting</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'interview');" class="btn btn-default btn-block dropdown-btn btn-event-interview">In-Person Interview</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'interview-phone');" class="btn btn-default btn-block dropdown-btn btn-event-interview-phone">Phone Interview</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'interview-voip');" class="btn btn-default btn-block dropdown-btn btn-event-interview-voip">Voip Interview</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'personal');" class="btn btn-default btn-block dropdown-btn btn-event-personal">Personal</button></li>
                                    <li><button type="button" onclick="func_set_category('edit_event_category', 'edit_event_selected_category', 'other');" class="btn btn-default btn-block dropdown-btn btn-event-other">Other</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Event Date: <i class="fa fa-asterisk text-danger"></i></label>
                            <input class="form-control datepicker"
                                   name="date"
                                   type="text"
                                   value="<?php echo date('m-d-Y', strtotime($event["date"])); ?>"
                                   id="datepicker101<?= $event['sid'] ?>"
                                   readonly="readonly"
                                   data-rule-required="true"
                                   required="">
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Start Time: <i class="fa fa-asterisk text-danger"></i></label>
                            <input id="eventstarttime"
                                   name="eventstarttime"
                                   type="text"
                                   value="<?= $event["eventstarttime"] ?>"
                                   data-rule-required="true"
                                   class="start_time form-control">
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>End Time: <i class="fa fa-asterisk text-danger"></i></label>
                            <input id="eventendtime"
                                   name="eventendtime"
                                   type="text"
                                   value="<?= $event["eventendtime"] ?>"
                                   data-rule-required="true"
                                   class="end_time form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>
                                <span class="attendees_label">
                                    <?php if($event['category'] == 'call' || $event['category'] == 'email' || $event['category'] == 'meeting' || $event['category'] == 'personal' || $event['category'] == 'other' ) { ?>
                                        Participant(s)
                                    <?php } else if($event['category'] == 'interview' || $event['category'] == 'interview-phone' || $event['category'] == 'interview-voip') { ?>
                                        Interviewer(s)
                                    <?php } ?>
                                </span> <i class="fa fa-asterisk text-danger"></i>
                            </label>
                            <div class="fields-wrapper">
                                <select data-event_sid="<?php echo $event['sid']; ?>" class="interviewer form-control" id="interviewer" multiple name="interviewer[]">
                                    <?php $event['interviewer'] = explode(',', $event['interviewer']); ?>
                                    <?php foreach ($employees as $employee) { ?>
                                        <?php if ($id != $employee['sid']) { ?>
                                            <option value="<?= $employee['sid'] ?>" <?php echo in_array($employee['sid'], $event['interviewer']) ? 'selected="selected"' : '' ?>>
                                                <?php if ($employer_sid == $employee['sid']) { ?>
                                                    You
                                                <?php } else { ?>
                                                    <?php echo $employee['is_executive_admin'] == 1 ?  $employee['first_name'].'&nbsp;'.$employee['last_name'].' ( Executive Admin )' : $employee['first_name'].'&nbsp;'.$employee['last_name'].' ('.$employee['access_level'].')'?>
                                                <?php } ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group autoheight">
                            <label><span class="attendees_label">Applicant Jobs List</span></label>
                        </div>
                        <?php if (sizeof($applicant_jobs) > 0) { 
                            foreach ($applicant_jobs as $applicant_job) {
                                    $applicant_list_sid = $applicant_job['sid'];
                                    $job_sid =  $applicant_job['job_sid'];
                                    $job_title = $applicant_job['Title']; 
                                    $desired_job_title = $applicant_job['job_title'];

                                    if (!empty($job_title)) {
                                        $title = $job_title;
                                    } else if (!empty($desired_job_title)) {
                                        if($desired_job_title == 'Job Not Applied') {
                                            continue;
                                        }
                                        $title = $desired_job_title;
                                    } else {
                                        continue;
                                    } 
                                    
                                    $is_selected = '';
                                    if(in_array($applicant_list_sid, $applicant_jobs_array)) {
                                        $is_selected = 'checked="checked"';
                                    } ?>

                                    <div class="col-xs-6">
                                        <label class="control control--checkbox">
                                            <?php echo ucwords($title); ?>
                                            <input name="applicant_jobs_list[]" class="external_participants_show_email" value="<?php echo $applicant_list_sid; ?>" type="checkbox" <?php echo $is_selected; ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                <?php         }
                        } ?>
                    </div>
                </div>

                <hr />
                <div id="show_email_main_container_<?php echo $event['sid']; ?>" class="row show_email_main_container">
                    <div class="col-xs-12">
                        <div> <!--class="form-group"-->
                            <label>Show Email Address of The Following Users in Event Email:</label>
                        </div>
                        <div class="row">
                        <?php   foreach ($employees as $employee) { ?>
                                    <div id="show_email_container_<?php echo $event['sid'] . '_' . $employee['sid']; ?>" class="col-xs-6 show_email_col">
                                        <label class="control control--checkbox">
                                            <?php echo ucwords($employee["first_name"] . ' ' .$employee["last_name"]) .' ( '. ($employee["is_executive_admin"] == 1 ?  'Executive Admin' : ucwords($employee["access_level"])) .' )' ?>
                                            <input <?php echo in_array($employee['sid'], explode(',', $event['interviewer_show_email'])) ? 'checked="checked"' : ''; ?> id="show_email_<?php echo $event['sid'] . '_' . $employee['sid']; ?>" class="show_email" value="<?php echo $employee['sid']; ?>" name="show_email[]" type="checkbox" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                        <?php   } ?>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Non Employee
                                    <span id="none_employee_title">
                                        <?php if($event['category'] == 'call' || $event['category'] == 'email' || $event['category'] == 'meeting' || $event['category'] == 'personal' || $event['category'] == 'other' ) { ?>
                                            Participant(s)
                                        <?php } else if($event['category'] == 'interview' || $event['category'] == 'interview-phone' || $event['category'] == 'interview-voip') { ?>
                                            Interviewer(s)
                                        <?php } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div id="external_participants_container_<?php echo $event['sid']; ?>" class="col-xs-12">
                                <?php $this->load->view('calendar/external_participants_partial'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />

                <div class="row">
                    <div class="col-xs-12">
                        <label class="control control--checkbox">
                            Comment for <span class="comment_for">
                                <?php if($event['category'] == 'call' || $event['category'] == 'email' || $event['category'] == 'meeting' || $event['category'] == 'personal' || $event['category'] == 'other' ) { ?>
                                    Participant(s)
                                <?php } else if($event['category'] == 'interview' || $event['category'] == 'interview-phone' || $event['category'] == 'interview-voip') { ?>
                                    Interviewer(s)
                                <?php } ?>
                            </span>
                            <input <?php echo $event['commentCheck'] == 1 ? 'checked="checked"' : ''; ?>
                                    class="comment_check" data-event_id="<?php echo $event['sid']; ?>"
                                    id="comment_check_<?php echo $event['sid']; ?>"
                                    value="1"
                                    name="commentCheck"
                                    type="checkbox" />
                            <div class="control__indicator"></div>
                        </label>
                        <div id="interviewer_comment_<?php echo $event['sid']; ?>" style="display: none">
                            <div class="form-group">
                                <textarea rows="5" class="form-control auto-height" id="interviewerComment" name="comment"><?php echo $event['comment']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-xs-12">
                        <label class="control control--checkbox">
                            Message to <span class=""><?php echo $this->uri->segment(1) == 'applicant_profile' ? 'Applicant' : 'Employee'; ?></span>:
                            <input <?php echo $event['messageCheck'] == 1 ? 'checked="checked"' : ''; ?>
                                    id="message_check_<?php echo $event['sid']; ?>"
                                    class="message_check"
                                    data-event_id="<?php echo $event['sid']; ?>"
                                    name="messageCheck"
                                    value="1"
                                    type="checkbox" />
                            <div class="control__indicator"></div>
                        </label>

                        <div id="message_<?php echo $event['sid']; ?>" style="display: none">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input class="form-control" name="subject" type="text" value="<?php echo $event['subject']; ?>" />
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group auto-height">
                                        <label>Message</label>
                                        <textarea rows="5" class="form-control autoheight" name="message"><?php echo $event['message']; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group auto-height">
                                        <label>&nbsp;</label>
                                        <input type="file" id="add_attachment" name="messageFile" />
                                    </div>
                                    <div class="current-attachments bg-success col-lg-12">
                                        <label class="pull-left">Current Attachment: </label>
                                        <div class="pull-right">
                                            <?php if($event['messageFile'] != '') { ?>
                                                <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>"><i class="fa fa-paperclip"></i> <span><?php echo $event['messageFile']; ?></span></a>
                                            <?php } else { ?>
                                                <a href="javascript:;"><i class="fa fa-paperclip"></i> <span>No file attached</span></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />
                <div class="row">
                    <div class="col-xs-12 mb-2">
                        <label class="control control--radio" id="lbl_new_address_<?php echo $event['sid']; ?>">
                            Address
                            <input data-event_sid="<?php echo $event['sid']; ?>" id="address_type_new_<?php echo $event['sid']; ?>" class="address_type" value="new" name="address_type" type="radio" />
                            <div class="control__indicator"></div>
                        </label>
                        &nbsp;
                        &nbsp;
                        <label class="control control--radio " id="lbl_saved_address_<?php echo $event['sid']; ?>">
                            Company Addresses
                            <input data-event_sid="<?php echo $event['sid']; ?>" id="address_type_saved_<?php echo $event['sid']; ?>" class="address_type" value="saved" name="address_type" type="radio" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div id="address_select_<?php echo $event['sid']; ?>" class="row">
                    <div class="col-xs-12">
                        <div class="form-group autoheight">
                            <div class="select">
                                <select class="form-control" name="address" id="address_saved_<?php echo $event['sid']; ?>">
                                    <?php foreach($addresses as $address) { ?>
                                        <option <?php echo $address == $event['address'] ? 'selected="selected"' : ''; ?> value="<?php echo $address?>"><?php echo $address?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="address_input_<?php echo $event['sid']; ?>" class="row">
                    <div class="col-xs-12">
                        <div class="form-group autoheight">
                            <input class="form-control" name="address" id="address_new_<?php echo $event['sid']; ?>" type="text" value="<?php echo $event['address']; ?>" />
                        </div>
                    </div>
                </div>
                <hr />

                <div class="row mb-2">
                    <div class="col-xs-12">
                        <label class="control control--checkbox">
                            Meeting Call In Details
                            <input <?php echo $event['goToMeetingCheck'] == 1 ? 'checked="checked"' : ''; ?>
                                    id="goto_meeting_check_<?php echo $event['sid']; ?>"
                                    class="goto_meeting_check"
                                    data-event_id="<?php echo $event['sid']; ?>"
                                    value="1"
                                    name="goToMeetingCheck"
                                    type="checkbox" />
                            <div class="control__indicator"></div>
                        </label>

                        <div id="meeting_details_<?php echo $event['sid']; ?>" style="display: none">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Meeting Call In Number</label>
                                        <input type="text"
                                               name="meetingCallNumber"
                                               id="meetingCallNumber"
                                               value="<?php echo $event["meetingCallNumber"] ?>"
                                               class="form-control" />
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Meeting ID Number</label>
                                        <input type="text"
                                               name="meetingId"
                                               id="meetingId"
                                               value="<?php echo $event["meetingId"] ?>"
                                               class="form-control" />
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Webinar/Meeting log in URL</label>
                                        <input type="text"
                                               name="meetingURL"
                                               id="meetingURL"
                                               value="<?php echo $event["meetingURL"] ?>"
                                               class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-xs-12">
                <input class="btn btn-success" type="submit" value="Save" id="save_event_edit_<?= $event['sid'] ?>">
                <a href="javascript:;" class="btn btn-default" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>


</form>