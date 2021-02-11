<?php 
    $participant_email_rows = $admin_rows = $participant_rows = '';
    if(sizeof($super_admins)) {
        foreach ($super_admins as $k0 => $v0) {
            $full_name =  $admin_id == $v0['admin_id'] ? 'You' : ucwords($v0['full_name']);
            $participant_rows .= '<option value="'.$v0['admin_id'].'">'.($full_name).'</option>';
            if($admin_id != $v0['admin_id']) $admin_rows = $participant_rows;
            $participant_email_rows .= '
            <div class="col-xs-6 js_ps_row js_ps_row_'.($v0['admin_id']).'">
                <label class="control control--checkbox">'.( $full_name ).'
                    <input value="'.($v0['admin_id']).'" name="participants_input[]" checked="checked" type="checkbox">       
                    <div class="control__indicator"></div>   
                </label>
            </div>';
        }
    }

    $user_rows = '';
    if(sizeof($users)) {
        foreach ($users as $k0 => $v0) {
            $full_name =  strtolower( $v0['email_address'] );
            // $full_name =  ucwords( $v0['full_name'] == '' ? $v0['company_name'] : $v0['full_name'] );
            $user_type = 'demo';
            if(strtolower($v0['type']) == 'referred affiliate')
                $user_type = 'affiliate';
            else if(strtolower($v0['type']) == 'referred affiliate client')
                $user_type = 'affiliate client';
            
            $user_rows .= '<option value="'.$v0['id'].'-'.$user_type.'">'.($full_name).' ('.$v0['type'].')</option>';
        }
    }
?>
<div id="js-event-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-header-green">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title js-event-head-title">Event Management</h4>
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

                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Super Admin
                                    <input class="js-users-type" type="radio" id="js-user-type-super-admin" name="users_type" value="super admin" />
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

                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                                <label class="control control--radio">
                                    Demo
                                    <input class="js-users-type" type="radio" id="js-user-type-demo" name="users_type" value="demo" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>

                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 js-extra-btns"></div>
                        </div>
                        <hr />

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
                                        <input type="text" placeholder="Enter title here" id="js-event-title-p" class="form-control" autocomplete="off" />
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
                                        <!-- <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="input-group-text">+1</span>
                                            </div> -->
                                            <input type="text" class="form-control">
                                        <!-- </div> -->
                                    </div>
                                </div>
                                <div class="col-sm-12 js-person-show-email-check">
                                    <div class="form-group autoheight">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="0" name="notification_input" class="js-person-send-notification-input">
                                                Send notification
                                            </label>
                                        </div>
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
                                <div class="col-sm-4 js-p-date">
                                    <div class="form-group autoHeight">
                                        <label>Event Date <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" value="<?=date('m-d-Y');?>" class="form-control js-datepicker">
                                    </div>
                                </div>
                                <div class="col-sm-4 js-p-start-time">
                                    <div class="form-group autoHeight">
                                        <label>Event Start Time <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" class="form-control js-clone-start-time">
                                    </div>
                                </div>
                                <div class="col-sm-4 js-p-end-time">
                                    <div class="form-group autoHeight">
                                        <label>Event End Time <span class="cs-required">*</span></label>
                                        <input type="text" readonly="true" class="form-control js-clone-end-time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Personal section wrap -->
                                             
                        <!-- Employee info box -->
                        <div id="js-superadmin-box" class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Admin(s)</label>
                                    <select  class="chosen-select" id="js-superadmin-select" multiple="multiple">
                                        <?=$admin_rows;?>
                                    </select>
                                </div>
                            </div>

                            <hr />

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 js-external-admin-wrap">
                                <div class="form-group autoheight">
                                    <label>External Admin(s)</label>
                                    <div id="js-external-admins"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Demo box -->
                        <div id="js-demo-box" class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>System User(s)</label>
                                    <select class="form-control cs-select" multiple="true" id="js-user-select" type="text">
                                        <?=$user_rows;?>
                                    </select>
                                </div>
                            </div>

                            <hr />

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>External User(s)</label>
                                    <div id="js-external-users"></div>
                                </div>
                            </div>
                        </div>
                        <hr class="js-superadmin-hr"/>

                        <!-- Event title bar -->
                        <div class="row js-event-title-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Event Title <span class="cs-required">*</span></label>
                                    <input type="text" placeholder="Enter title here" id="js-event-title" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <!-- Event details row -->
                        <div class="row js-event-detail-wrap">
                            <!-- Categories row -->
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6 js-event-category-wrap">
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

                            <!-- Demo Category row -->
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6 js-demo-category-wrap">
                                <div class="form-group">
                                    <input type="hidden" class="js-selected-demo-category"/>
                                    <div class="dropdown event-category-dropdown">
                                        <label>Categories <span class="cs-required">*</span></label>
                                        <button class="btn btn-block form-control dropdown-toggle js-demo-category-dropdown" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="js-demo-active-category"></span>
                                        </button>
                                        <ul class="dropdown-menu js-demo-category-list" aria-labelledby="js-demo-category-dropdown"></ul>
                                    </div>
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

                        <hr />

                        <!-- Meeting row -->
                        <div class="row js-meeting-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <strong>Meeting Call Details:</strong>
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
                        <hr class="js-meeting-hr" />

                        <!-- Interviewers row -->
                        <div class="row js-participants-wrap">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label id="attendees_label">Participants(s)</label>
                                    <select id="js-participants-select" multiple="true">
                                        <?=$participant_rows;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="js-participants-box" class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label>Show Email Address of The Following Admins in Event Email:</label>
                                </div>
                                <div class="row" id="js-participants-list">
                                    <?=$participant_email_rows;?>
                                </div>
                            </div>
                        </div>
                        <hr class="js-participants-hr" />

                        <!-- Extra participants row -->
                        <div class="row js-extra-participants-wrap">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label id="js-non-employee-heading">Non Employee <span id="none_employee_title">Participants(s)</span></label>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div id="js-extra-participants-box" class="col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                        <hr class="js-extra-participants-hr" />
                       
                        <!-- Address bar -->
                        <div class="row js-hide mb-2 js-address-text-row">
                            <div class="col-xs-12">
                                <label>Address:</label>
                                <span id="current_saved_address"></span>
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

                            
                        <!-- Comment row -->
                        <div class="row js-comment-box">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox js-comment-text">
                                        <span>Comment for Participant(s)</span>
                                        <input id="js-comment-check" type="checkbox" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12" id="js-comment-msg-box">
                                <div class="form-group autoheight">
                                    <label>Comment: <span class="cs-required">*</span></label>
                                    <textarea id="js-comment-msg" class="form-control textarea" rows="5" style="max-width: 100%; min-width: 100%; height: auto;"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Reminder -->
                        <div class="row js-reminder-box">
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
                                        Recurring Event
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
                                <input class="js-modal-btn btn btn-success" name="event_submit" type="button" style="display: none;" value="Re Schedule Event" id="js-reschedule" />
                                <input class="js-modal-btn btn btn-success training-session-btns" name="event_submit" type="button" style="display: none;" value="Update" id="js-update" />
                                <input class="js-modal-btn btn btn-success js-save-btn" name="event_submit" style="display: none; margin-top: 1px !important;" type="button" value="Save" id="js-save" />
                                <a id="close_btn" href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
                                <input class="js-modal-btn btn btn-danger training-session-btns" name="event_submit" type="button" style="display: none;" value="Delete" id="js-delete" />
                                <input class="js-modal-btn btn btn-warning training-session-btns" name="event_submit" type="button" style="display: none;" value="Cancel Event" id="js-cancel" />
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
                            <table class="table table-bordered table-striped">
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
    .select2-container-multi{ display: block !important; }
    .ajs-header{ background-color: #81b431 !important; color: #ffffff; }
    body .alertify .ajs-commands button.ajs-close{ background-color: #fff; border-radius: 100%; }
    .alertify .ajs-footer .ajs-buttons .ajs-cancel{ background-color: #252524 !important; border-radius: 5px; cursor: pointer; color: #fff !important; }
    .alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok{ color: #fff !important; background-color: #81b431; cursor: pointer; border-radius: 5px; padding: 0 10px; }
</style>

<script>
    $('#js-applicant-popover').popover({trigger: 'hover'});

    var admin_list = <?=@json_encode(sizeof($super_admins) ? $super_admins : array());?>,
    user_list = <?=@json_encode(sizeof($users) ? $users : array());?>,
    admin_id = <?=$admin_id;?>;

</script>
