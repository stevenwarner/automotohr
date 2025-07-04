<div id="popup1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-header-green">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Event Management</h4>
            </div>
            <div class="form-wrp">
                <form class="date_form" id="event_form" method="post">
                    <input type="hidden" id="event_id" name="event_id" value="" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <label>Event For:</label>
                            </div>

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Applicant
                                    <input class="users_type" type="radio" id="users_type_applicant" name="users_type" value="applicant"/>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Employee
                                    <input class="users_type" type="radio" id="users_type_employee" name="users_type" value="employee" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <hr />

                        <div id="applicant_select" class="row">
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <div class="form-group  autoheight">
                                    <label>Applicant</label>
                                    <select class="form-control" id="applicant_sid" name="applicant_sid">
                                        <option value="">Select an Applicant</option>
                                        <?php foreach ($applicants as $applicant) { ?>
                                            <option data-applicant-sid="<?php echo $applicant['sid'];?>"
                                                    data-applicant-phone="<?php echo $applicant['phone_number'];?>"
                                                    data-job-list-sid="<?php echo $applicant['portal_applicant_jobs_list_sid'];?>"
                                                    value="<?php echo $applicant['sid']; ?>">
                                                <strong><?php echo $applicant['first_name']; ?> <?php echo $applicant['last_name']; ?></strong>
                                                (<?php echo $applicant['email']; ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label class="hidden-xs"></label>
                                    <!--<a id="applicant_profile_link" href="#" class="btn btn-success btn-equalizer btn-block" target="_blank">Applicant Profile</a>-->
                                    <button onclick="func_goto_applicant_profile()" id="applicant_profile_link" type="button" class="btn btn-success btn-equalizer btn-block">Applicant Profile</button>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="show_jobs_main_container" class="row">
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label>Applicant Jobs List</label>
                                    </div>
                                    <div class="row">
                                    <?php   foreach ($applicant_jobs as $applicant_job) {
                                                $applicant_sid =  $applicant_job['sid'];
                                                $applicant_list_sid = $applicant_job['list_sid'];
                                                $job_sid =  $applicant_job['job_sid'];
                                                $job_title = $applicant_job['job_title']; 
                                                $desired_job_title = $applicant_job['desired_job_title']; 
                                                
                                                if (!empty($job_title)) {
                                                    $title = $job_title;
                                                } else if (!empty($desired_job_title)) {
                                                    $title = $desired_job_title;
                                                } else {
                                                    continue;
                                                } ?>

                                                <div class="col-xs-6 show_jobs_col show_jobs_container_<?php echo $applicant_sid; ?>">
                                                    <label class="control control--checkbox">
                                                        <?php echo ucwords($title); ?>
                                                        <input id="show_jobs_<?php echo $applicant_list_sid; ?>" class="show_jobs_<?php echo $applicant_sid; ?> enable_disable_jobs" value="<?php echo $applicant_list_sid; ?>" name="show_jobs[]" type="checkbox" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                    <?php   } ?>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div id="employee_select" class="row">
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <div class="form-group autoheight">
                                    <label>Employee</label>
                                    <select class="form-control" id="employee_sid" name="employee_sid">
                                        <option value="">Select an Employee</option>
                                        <?php foreach ($company_accounts as $account) { ?>
                                            <option class="emp"
                                                    id="emp_<?php echo $account['sid']; ?>"
                                                    data-employee-sid="<?php echo $account['sid']; ?>"
                                                    data-employee-phone="<?php echo $account['PhoneNumber']; ?>"
                                                    value="<?php echo $account["sid"] ?>">
                                                <?php if ($employer_id == $account["sid"]) { ?>
                                                    You
                                                <?php } else {
                                                    if($account["is_executive_admin"] == 1) {
                                                        $employee_type = "Executive Admin";
                                                    } else {
                                                        $employee_type = $account["access_level"];
                                                    }
                                                    echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                } ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label class="hidden-xs"></label>
                                    <!--<a id="employee_profile_link" href="#" class="btn btn-success btn-equalizer btn-block" target="_blank">Employee Profile</a>-->
                                    <button onclick="func_goto_employee_profile()" type="button" id="employee_profile_link" class="btn btn-success btn-equalizer btn-block">Employee Profile</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <label>Phone Number</label>
                                    <input name="users_phone" id="users_phone" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"></div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Event Title</label>
                                    <input type="text" id="title" name="title" placeholder="Enter title here" class="form-control eventtitle">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" id="add_event_category" name="category" value="call" />
                                    <div class="dropdown event-category-dropdown">
                                        <label>Categories</label>
                                        <button class="btn btn-block form-control dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span id="add_event_selected_category">Call</span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'call');" class="btn btn-default btn-block dropdown-btn btn-event-call training-session-tile">Call</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'email');" class="btn btn-default btn-block dropdown-btn btn-event-email training-session-tile">Email</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'meeting');" class="btn btn-default btn-block dropdown-btn btn-event-meeting training-session-tile">Meeting</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview');" class="btn btn-default btn-block dropdown-btn btn-event-interview training-session-tile">In-Person Interview</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview-phone');" class="btn btn-default btn-block dropdown-btn btn-event-interview-phone training-session-tile">Phone Interview</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'interview-voip');" class="btn btn-default btn-block dropdown-btn btn-event-interview-voip training-session-tile">Voip Interview</button></li>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'personal');" class="btn btn-default btn-block dropdown-btn btn-event-personal training-session-tile">Personal</button></li>
                                            
                                            <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){ ?>
                                                <li id="training-session-tile"><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'training-session');" class="btn btn-default btn-block dropdown-btn btn-primary">Training Session</button></li>
                                            <?php } ?>
                                            <li><button type="button" onclick="func_set_category('add_event_category', 'add_event_selected_category', 'other');" class="btn btn-default btn-block dropdown-btn btn-event-other training-session-tile">Other</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Event Date</label>
                                    <input name="eventdate" id="eventdate" type="text" class="form-control datepicker" required="">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input name="eventstarttime" id="eventstarttime" readonly="readonly" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input name="eventendtime" id="eventendtime" readonly="readonly" type="text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label id="attendees_label">Participants</label>
                                    <select id="interviewer" class="form-control" multiple
                                            name="interviewer[]" required="required">
                                        <?php foreach ($company_accounts as $account) { ?>
                                            <option class="int" id="int_<?php echo $account['sid']; ?>" value="<?= $account["sid"] ?>">
                                                <?php if ($employer_id == $account["sid"]) { ?>
                                                    You
                                                <?php } else {
                                                    if($account["is_executive_admin"] == 1) {
                                                        $employee_type = "Executive Admin";
                                                    } else {
                                                        $employee_type = $account["access_level"];
                                                    }
                                                    echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                } ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="show_email_main_container" class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Show Email Address of The Following Users in Event Email:</label>
                                </div>
                                <div class="row">
                                    <?php foreach ($company_accounts as $account) { ?>
                                        <div id="show_email_container_<?php echo $account['sid']; ?>" class="col-xs-6 show_email_col">
                                            <label class="control control--checkbox">
                                                <?php echo ucwords($account["first_name"] . ' ' .$account["last_name"]) .' ( '. ($account["is_executive_admin"] == 1 ?  'Executive Admin' : ucwords($account["access_level"])) .' )' ?>
                                                <input id="show_email_<?php echo $account['sid']; ?>" class="show_email" value="<?php echo $account['sid']; ?>" name="show_email[]" type="checkbox" />
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
                                    <div id="external_participants_container" class="col-xs-12 external-participants-container">
                                        <div id="external_participant_0" class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input name="ext_participants[0][name]" id="ext_participants_0_name" type="text" class="form-control external_participants_name" />
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input name="ext_participants[0][email]" id="ext_participants_0_email" type="email" class="form-control external_participants_email" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control control--checkbox margin-top-20">
                                                        Show Email
                                                        <input name="ext_participants[0][show_email]" id="ext_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>
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
                                        Comment for <span id="comment_for">Interviewer(s)</span>
                                        <input id="interviewer_comment" class="interviewer_comment" value="1" name="commentCheck" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row comment-div">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Comment:</label>
                                    <textarea id="interviewerComment" name="comment" class="form-control textarea"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Message To <span id="message_to_label">Candidate</span>
                                        <input class="message-to-candidate" id="candidate_msg" name="messageCheck" value="1" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row message-div">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Subject:</label>
                                    <input class="message-subject form-control" id="applicantSubject" name="subject" type="text" value="" placeholder="Enter Subject (required)" />
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Message:</label>
                                    <textarea id="applicantMessage" name="message" class="form-control textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Attachment:</label>
                                    <input type="file" id="messageFile" name="messageFile" />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row mb-2">
                            <div class="col-xs-12">
                                <label>Address:</label>
                                <span id="current_saved_address"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-xs-12">
                                <label class="control control--radio">
                                    <span id="label_address_type">Address</span>
                                    <input id="address_type_new" class="address_type" value="new" name="address_type" type="radio" />
                                    <div class="control__indicator"></div>
                                </label>
                                &nbsp;
                                &nbsp;
                                <label class="control control--radio ">
                                    Company Addresses
                                    <input id="address_type_saved" class="address_type" value="saved" name="address_type" type="radio" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div id="address_select" class="row">
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
                        <div id="address_input" class="row">
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
                                        Meeting Call Details:
                                        <input id="goto_meeting" class="goto_meeting" value="1" name="goToMeetingCheck" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Allow Reminder:
                                        <input id="allow-reminder" class="allow-reminder" value="0" name="allow-reminder" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <div class="form-group  autoheight">
                                    <label>Reminder Duration</label>
                                    <select class="form-control" id="reminder-duration" name="reminder-duration">
                                        <option value="15">15 Minutes</option>
                                        <option value="30">30 Minutes</option>
                                        <option value="45">45 Minutes</option>
                                        <option value="60">60 Minutes</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row meeting-div">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Meeting Call In #: </label>
                                    <input class="form-control" id="meetingCallNumber" name="meetingCallNumber" type="text" value="" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Meeting ID #: </label>
                                    <input class="form-control" id="meetingId" name="meetingId" type="text" value="" />
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Webinar/Meeting Login URL: </label>
                                    <input class="form-control" id="meetingURL" name="meetingURL" type="text" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 id="cancelled_message" style="display: none;" class="text-danger text-center">Event Cancelled!</h3>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="">
                        <div class="row">
                            <div class="col-md-2 text-left">
                                <div id="loader" class="loader" style="display: none;">
                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <?php if(check_access_permissions_for_view($security_details, 'reschedule_event')){?>
                                    <input class="btn btn-success" name="event_submit" type="button" style="display: none;" value="Re Schedule Event" id="reschedule" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'update_event')){?>
                                    <input class="btn btn-success training-session-btns" name="event_submit" type="button" style="display: none;" value="Update" id="update" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'create_event')){?>
                                    <input class="btn btn-success" name="event_submit" style="display: none; margin-top: 1px !important;" type="button" value="Save" id="save" />
                                <?php } ?>
                                    <a id="close_btn" href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
                                <?php if(check_access_permissions_for_view($security_details, 'delete_event')){?>
                                    <input class="btn btn-danger training-session-btns" name="event_submit" type="button" style="display: none;" value="Delete" id="delete" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'cancel_event')){?>
                                    <input class="btn btn-warning training-session-btns" name="event_submit" type="button" style="display: none;" value="Cancel Event" id="cancel" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>