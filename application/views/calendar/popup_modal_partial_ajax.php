<?php 
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    // if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
    //     $is_regex = 1;
    //     $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
    //     $input_group_end   = '</div>';
    // }
?>
<div id="js-event-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-header-green">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>

                <div class="modal-title">
                    <h4 class="modal-title js-event-head-title" style="display: initial;">Event Management</h4>
                    <b style="margin-left:20px;">(<?= $employer_timezone ?>)</b>
                </div>

            </div>
            <div class="form-wrp js-main-page">
                <!-- Form for applicant and employee -->
                <form class="date_form" id="event_form" method="post">
                    <input type="hidden" id="js-event-id" name="event_id" />
                    <input type="hidden" id="js-lcts-id" name="learning_center_training_session_id" />
                    <div class="modal-body">
                        <!-- Event type row  -->
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <label>Event For: <span class="cs-required">*</span></label>
                            </div>

                            <?php if(strtolower($access_level) != 'employee') {?>

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Applicant
                                    <input class="js-users-type" type="radio" id="js-user-type-applicant" name="users_type" value="applicant"/>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <?php } ?>

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Employee
                                    <input class="js-users-type" type="radio" id="js-user-type-employee" name="users_type" value="employee" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Personal
                                    <input class="js-users-type" type="radio" id="js-user-type-personal" name="users_type" value="personal" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-3 js-extra-btns"></div>
                        </div>
                        
                        <?php if(strtolower($access_level) != 'employee') {?>
                        <!-- Search bar  -->
                        <div class="row">
                            <br />
                            <div class="col-sm-12">
                                <div class="cs-search-input-applicant js-search-input-applicant">
                                    <!--
                                    <label>Search Applicant <span 
                                        class="fa fa-question-circle" 
                                        id="js-applicant-popover" 
                                        data-container="body" 
                                        data-toggle="popover" 
                                        data-placement="right" 
                                        data-content="Search the applicant; Type in the name or email and wait for the list to show and then select the applicant from the list."></span></label>
                                    -->
                                    <input type="text" class="form-control" id="js-applicant-search-bar" placeholder="Autocomplete: Search applicant by name or email. e.g. John Doe" tabindex="-1" autocomplete="off" />
                                    <i class="fa fa-spin fa-spinner js-search-loader-applicant"></i>
                                    <!-- For ajax -->
                                    <!-- <input type="text" class="form-control" id="js-employee-search-bar" placeholder="Search employee" /> -->
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <hr/>

                        <!-- Personal section wrap -->
                        <div class="js-personal-type-wrap">
                            <div class="row js-personal-box">
                                <div class="col-sm-12">
                                    <div class="form-group autoHeight">
                                        <input type="hidden" class="js-selected-event-category-p"/>
                                        <div class="dropdown event-category-dropdown">
                                            <label>Categories <span class="cs-required">*</span></label>
                                            <button class="btn btn-block form-control dropdown-toggle js-category-dropdown-p" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <span class="js-active-category-p"></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu js-category-list-p" aria-labelledby="js-category-dropdown"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row js-personal-box">
                                <div class="col-sm-12">
                                    <div class="form-group autoHeight">
                                        <label>Event Title <span class="cs-required">*</span></label>
                                        <input type="text" placeholder="Enter title here" id="js-event-title-p" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="row js-personal-box">
                                <div class="col-sm-12 js-person-name">
                                    <div class="form-group autoheight">
                                        <label>Person Name <span class="cs-required">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12 js-person-phone">
                                    <div class="form-group autoheight">
                                        <label>Person Phone <span class="cs-required">*</span></label>
                                            <?=$input_group_start;?>
                                            <input type="text" class="form-control">
                                            <?=$input_group_end;?>
                                    </div>
                                </div>
                                <div class="col-sm-12 js-person-email-check">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            <span>Send notification</span>
                                            <input type="checkbox" value="0" class="js-person-email-check-input" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12 js-person-email">
                                    <div class="form-group autoheight">
                                        <label>Person Email <span class="cs-required">*</span></label>
                                        <input type="email" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row js-personal-box">
                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">
                                    <div class="form-group  autoheight">
                                        <label>Event Timezone <span class="cs-required">*</span></label>
                                        <?=timezone_dropdown(
                                            $employer_timezone, 
                                            array(
                                                'class' => 'form-control js-timezone ',
                                                'id' => 'event_timezone-personal',
                                                'name' => 'event_timezone'
                                            ),
                                            'north_america'
                                        );?>
                                    </div>
                                </div>
                                <div class="col-sm-3 js-p-date">
                                    <div class="form-group autoHeight">
                                        <label>Event Date <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" value="<?=date('m-d-Y');?>" class="form-control js-datepicker">
                                    </div>
                                </div>
                                <div class="col-sm-2 js-p-start-time">
                                    <div class="form-group autoHeight">
                                        <label>Start Time <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" class="form-control js-clone-start-time">
                                    </div>
                                </div>
                                <div class="col-sm-2 js-p-end-time">
                                    <div class="form-group autoHeight">
                                        <label>End Time <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" class="form-control js-clone-end-time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Personal section wrap -->
                        <?php if(strtolower($access_level) != 'employee') {?>
                        <!-- Applicant info box -->
                        <div id="js-applicant-box" class="row">
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <div class="form-group  autoheight">
                                    <label>Applicant <span class="cs-required">*</span></label>
                                    <input type="text" class="form-control" id="js-applicant-input" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label class="hidden-xs"></label>
                                    <button onclick="func_goto_applicant_profile()" id="js-applicant-profile-link" type="button" class="btn btn-success btn-equalizer btn-block">Applicant Profile</button>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <label>Phone Number <span class="cs-required">*</span></label>
                                        <?=$input_group_start;?>
                                        <input id="js-applicant-phone" type="text" class="form-control">
                                        <?=$input_group_end;?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="js-applicant-job-box" class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group autoheight">
                                            <label>Applicant Jobs List</label>
                                        </div>
                                        <div class="row" id="js-applicant-job-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <!-- Employee info box -->
                        <div id="js-employee-box" class="row">
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <div class="form-group autoheight">
                                    <label>Employee <span class="cs-required">*</span></label>
                                    <select class="form-control" id="js-employee-select" type="text">
                                        <option value="null">[Select an employee]</option>
                                        <?php if(strtolower($access_level) == 'employee') { ?>
                                            <option value="<?=$employer_id;?>">You</option>
                                        <?php } ?>
                                        <?php if(sizeof($employees)) { ?>
                                        <?php   foreach($employees as $k0 => $v0) { ?>
                                        <?php       if($v0['employer_id'] == $employer_id) continue; ?>
                                        <option value="<?=$v0['employer_id'];?>"><?=$v0['full_name'];?></option>
                                        <?php   } ?>
                                        <?php } ?>
                                    </select>
                                    <!-- For ajax -->
                                    <!-- <input class="form-control" id="js-employee-input" type="text" readonly="readonly" /> -->
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                <div class="form-group autoheight">
                                    <label class="hidden-xs"></label>
                                    <button onclick="func_goto_employee_profile()" type="button" id="js-employee-profile-link" class="btn btn-success btn-equalizer btn-block">Employee Profile</button>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <label>Phone Number</label>
                                    <?=$input_group_start;?>
                                        <input id="js-employee-phone" type="text" class="form-control">
                                    <?=$input_group_end;?>
                                </div>
                            </div>
                        </div>
                        <hr class="js-employee-hr"/>

                        <!-- Event title bar -->
                        <div class="row js-event-title-wrap">
                            <div class="col-sm-6">
                                <div class="form-group autoheight">
                                    <label>Event Title <span class="cs-required">*</span></label>
                                    <input type="text" placeholder="Enter title here" id="js-event-title" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" class="js-selected-event-category"/>
                                    <div class="dropdown event-category-dropdown">
                                        <label>Categories <span class="cs-required">*</span></label>
                                        <button class="btn btn-block form-control dropdown-toggle js-category-dropdown" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="js-active-category" ></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu js-category-list" aria-labelledby="js-category-dropdown"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event details row -->
                        <div class="row js-event-detail-wrap">
                            <!-- Categories row -->
                            
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">
                                <div class="form-group  autoheight">
                                    <label>Event Timezone <span class="cs-required">*</span></label>
                                    <?=timezone_dropdown(
                                        $employer_timezone, 
                                        array(
                                            'class' => 'form-control js-timezone ',
                                            'id' => 'event_timezone',
                                            'name' => 'event_timezone'
                                        ),
                                        'north_america'
                                    );?>
                                </div>
                            </div>
                            <!-- Event date -->
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Event Date <span class="cs-required">*</span></label>
                                    <input readonly="readonly" type="text" class="form-control datepicker" id="js-event-date" required="required">
                                </div>
                            </div>
                            <!-- Event start time -->
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Start Time <span class="cs-required">*</span></label>
                                    <input readonly="readonly" type="text" class="form-control" id="js-event-start-time" required="required">
                                </div>
                            </div>
                            <!-- Event end time -->
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>End Time <span class="cs-required">*</span></label>
                                    <input readonly="readonly" type="text"  class="form-control" id="js-event-end-time" required="required">
                                </div>
                            </div>
                        </div>

                        <!-- Interviewers row -->
                        <div class="row js-interviewers-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label id="attendees_label">Interviewer(s)</label>
                                    <!-- For AJAX -->
                                    <!-- <input id="js-interviewers" class="form-control"> -->

                                    <select id="js-interviewers-select" multiple>
                                        <?php if(sizeof($interviewers)) { ?>
                                        <?php   foreach($interviewers as $k0 => $v0) { 
                                            $timezone = !empty($v0['timezone']) ? $v0['timezone'] : $company_timezone;
                                            ?>
                                        <option <?=($v0['employer_id'] == $employer_id ? 'selected="selected"' : '');?> value="<?=$v0['employer_id'];?>"><?=($v0['employer_id'] == $employer_id ? 'You ('.$timezone.')' : $v0['full_name']);?></option>
                                        <?php   } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="js-interviewers-box" class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Show Email Address of The Following Users in Event Email:</label>
                                </div>
                                <div class="row" id="js-interviewers-list"></div>
                            </div>
                        </div>
                        <hr class="js-interviewers-hr" />

                        <!-- Extra interviewers row -->
                        <div class="row js-extra-interviewers-wrap">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label id="js-non-employee-heading">Non Employee <span id="none_employee_title">Interviewers(s)</span></label>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div id="js-extra-interviewers-box" class="col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                        <hr class="js-extra-interviewers-hr" />
                        
                        <!-- Videos row -->
                        <div class="row" id="js-video-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Online Videos</label>
                                    <select class="form-control" multiple="true"></select>
                                </div>
                            </div>
                        </div>
                        <hr class="js-video-hr"/>

                        <!-- Comment row -->
                        <div class="row js-comment-box">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox js-comment-text">
                                        <span>Comment for Interviewer(s)</span>
                                        <input id="js-comment-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12" id="js-comment-msg-box">
                                <div class="form-group autoheight">
                                    <label>Comment: <span class="cs-required">*</span></label>
                                    <textarea id="js-comment-msg" class="form-control textarea"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr class="js-comment-hr"/>
                        <!-- Message row -->
                        <div class="row js-message-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Message To <span id="message_to_label">Candidate</span>
                                        <input id="js-message-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="js-message-box">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Subject: <span class="cs-required">*</span></label>
                                    <input class="form-control" id="js-message-subject" type="text" placeholder="Enter Subject (required)" />
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Message: <span class="cs-required">*</span></label>
                                    <textarea id="js-message-body" class="form-control textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Attachment:</label>
                                    <input type="file" id="js-message-file" name="messageFile" />
                                </div>
                            </div>
                        </div>
                        <hr class="js-message-hr" />

                        <!-- Address bar -->
                        <div class="row js-hide mb-2">
                            <div class="col-xs-12">
                                <label>Address:</label>
                                <span id="current_saved_address"></span>
                            </div>
                        </div>
                        <div class="row js-address-wrap mb-2">
                            <div class="col-xs-12">
                                <label class="control control--radio">
                                    <span id="label_address_type">Address</span>
                                    <input id="js-address-input" value="new" class="js-address-type" name="address_type" type="radio" />
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
                        <div id="js-address-select-box" class="row js-hide">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <div class="select">
                                        <select class="form-control" name="address" id="js-address-list"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="js-address-input-box" class="row js-hide">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <input class="form-control" id="js-address-text" type="text" />
                                </div>
                            </div>
                        </div>
                        <hr class="js-address-hr"/>

                        <!-- Meeting row -->
                        <div class="row js-meeting-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Meeting Call Details:
                                        <input id="js-meeting-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="js-meeting-box">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Meeting Call In #:  <span class="cs-required">*</span></label>
                                    <input class="form-control" id="js-meeting-call" type="text" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Meeting ID #:  <span class="cs-required">*</span></label>
                                    <input class="form-control" id="js-meeting-id" type="text" />
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Webinar/Meeting Login URL:  <span class="cs-required">*</span></label>
                                    <input class="form-control" id="js-meeting-url" type="text" />
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
                                <div class="form-group autoheight" id="js-reminder-box">
                                    <label>Reminder Duration <span class="cs-required">*</span></label>
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
                                <div class="form-group autoheight" id="js-reminder-email-box"></div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 id="cancelled_message" style="display: none;" class="text-danger text-center">Event Cancelled!</h3>
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
                                    <input class="js-modal-btn btn btn-success" name="event_submit" type="button" style="display: none;" value="Re Schedule Event" id="js-reschedule" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'update_event')){?>
                                    <input class="js-modal-btn btn btn-success training-session-btns" name="event_submit" type="button" style="display: none;" value="Update" id="js-update" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'create_event')){?>
                                    <input class="js-modal-btn btn btn-success js-save-btn" name="event_submit" style="display: none; margin-top: 1px !important;" type="button" value="Save" id="js-save" />
                                <?php } ?>
                                    <a id="close_btn" href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
                                <?php if(check_access_permissions_for_view($security_details, 'delete_event')){?>
                                    <input class="js-modal-btn btn btn-danger training-session-btns" name="event_submit" type="button" style="display: none;" value="Delete" id="js-delete" />
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'cancel_event')){?>
                                    <input class="js-modal-btn btn btn-warning training-session-btns" name="event_submit" type="button" style="display: none;" value="Cancel Event" id="js-cancel" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Request list for event status button -->
            <div class="js-request-page" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User name</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="js-pagination-area pull-right"></div>
                    </div>
                </div>
            </div>

            <!-- Reminder emails list -->
            <div class="js-reminder-email-history-page" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>User Email</th>
                                        <th>User Type</th>
                                        <!-- <th>Sent Date</th> -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="js-pagination-area pull-right"></div>
                    </div>
                </div>
            </div>
            <!-- Event change history list -->
            <div class="js-change-history-page" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Before change event details</th>
                                        <th>After change event details</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="js-pagination-area pull-right"></div>
                    </div>
                </div>
            </div>                        
            <!-- Expired Reschedule box -->
            <div class="js-reschedule-page" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="javascript:void(0)" id="js-reschedule-form">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Event Date</label>
                                        <input type="text" class="form-control" readonly="true" id="js-reschedule-event-date"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Event Start Time</label>
                                        <input type="text" class="form-control" readonly="true"  id="js-reschedule-event-start-time"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Event End Time</label>
                                        <input type="text" class="form-control" readonly="true"  id="js-reschedule-event-end-time"/>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="form-group  pull-right">
                                <input type="submit" class="btn btn-success" value="Reschedule" readonly="true" />
                                <input type="button" class="btn btn-default js-reschedule-cancel" value="Cancel" readonly="true" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recur -->
            <!-- TODO -->
            <!-- You are editing a series of shifts -->
            <div class="js-recur-page" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12">
                        <br />
                        <br />
                        <label class="control control--checkbox">
                            Make changes to only the current shift
                            <input type="checkbox" class="js-infinite"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-sm-12">
                        <label class="control control--checkbox">
                            Make changes to all shifts in the future too
                            <input type="checkbox" class="js-infinite"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <br />
                        <br />
                        <button class="btn btn-success pull-right">Save</button>
                        <button class="btn btn-default pull-right" style="margin-right: 10px;">Close</button>
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
    /**/
    .cs-search-input-applicant i{ position: absolute; top: 50%; right: 30px; font-size: 20px; margin-top: -10px; color: #81b431; }
    table tr.cs-odd{ background-color: #f9f9f9; }
</style>

<script>
    $('#js-applicant-popover').popover({trigger: 'hover'})
</script>